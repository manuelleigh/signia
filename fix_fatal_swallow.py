import re

with open("app/Http/Controllers/Api/DocumentController.php", "r", encoding="utf-8") as f:
    content = f.read()

old_block = """            } catch (\Exception $engineEx) {
                // Si falla (timeout, error de SUNAT), lo mandamos a la cola para que se siga reintentando
                \Illuminate\Support\Facades\Log::warning("Proceso sncrono fall para doc {$document->id}, enviando a cola. Error: " . $engineEx->getMessage());
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);"""

# fixing encoding artifacts
old_block = old_block.replace("sncrono", "síncrono").replace("fall", "falló")

new_block = """            } catch (\Throwable $engineEx) {
                // AUDITORIA PROFUNDA: Si el error es de c??digo (ej. falta una llave en el array del JSON y Blade crashea), 
                // NO DEBEMOS ENCOLARLO. Debemos decirle al cliente inmediatamente que su JSON est?? mal.
                if ($engineEx instanceof \ErrorException || $engineEx instanceof \TypeError || $engineEx instanceof \InvalidArgumentException || str_contains(get_class($engineEx), 'ViewException')) {
                    $document->update(['status' => 'rejected']);
                    if (!$isDemo) {
                        $agency->increment($balanceField);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Error cr??tico construyendo el comprobante. Revisa que no te falte ning??n campo obligatorio en tu JSON. Detalles t??cnicos: ' . $engineEx->getMessage(),
                    ], 422);
                }

                // Si es un error gen??rico de red o de SUNAT, lo mandamos a la cola para que se siga reintentando
                \Illuminate\Support\Facades\Log::warning("Proceso s??ncrono fall?? para doc {$document->id}, enviando a cola. Error: " . $engineEx->getMessage());
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);"""

# Replace trying both exact match and regex
if old_block in content:
    content = content.replace(old_block, new_block)
else:
    # Try regex
    pattern = re.compile(r'\} catch \(\\Exception \$engineEx\) \{.*?ProcessDocumentJob::dispatch\(\$document->id, \$request->all\(\), \$company->id, \$agency->id\);', re.DOTALL)
    content = pattern.sub(new_block, content)

with open("app/Http/Controllers/Api/DocumentController.php", "w", encoding="utf-8") as f:
    f.write(content)

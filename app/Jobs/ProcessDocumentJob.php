<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\Company;
use App\Models\Agency;
use App\Services\Signia\EngineRouter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $documentId;
    protected $payload;
    protected $companyId;
    protected $agencyId;

    public function __construct(int $documentId, array $payload, int $companyId, int $agencyId)
    {
        $this->documentId = $documentId;
        $this->payload = $payload;
        $this->companyId = $companyId;
        $this->agencyId = $agencyId;
    }

    public function handle(EngineRouter $engineRouter): void
    {
        $document = Document::find($this->documentId);
        $company = Company::find($this->companyId);
        $agency = Agency::find($this->agencyId);

        if (!$document || !$company || !$agency) {
            Log::error("ProcessDocumentJob: Datos incompletos (Doc: {$this->documentId})");
            return;
        }

        try {
            $engine = $engineRouter->resolve($company);
            $result = $engine->process($company, $this->payload);

            if ((isset($result['status']) && $result['status'] === 'exception' && empty($result['success'])) || (isset($result['success']) && $result['success'] === false)) {
                
                // Forzamos un throw para que la cola lo intente de nuevo
                throw new Exception($result['message'] ?? 'Error desconocido del motor');
            }

            // Éxito o Aceptado con Observaciones o Rechazado (pero que fue procesado)
            $ruc = $this->payload['company']['ruc'];
            $docType = $this->payload['document']['document_type_id'];
            $series = $this->payload['document']['series'];
            $number = $this->payload['document']['number'];

            $fileNameBase = "{$ruc}-{$docType}-{$series}-{$number}";
            $pathPrefix = "documents/{$ruc}/" . date('Y/m');

            $xmlPath = null;
            if (!empty($result['xml_base64'])) {
                $xmlPath = "{$pathPrefix}/{$fileNameBase}.xml";
                Storage::disk('public')->put($xmlPath, base64_decode($result['xml_base64']));
            }

            $cdrPath = null;
            if (!empty($result['cdr_base64'])) {
                $cdrPath = "{$pathPrefix}/R-{$fileNameBase}.zip";
                Storage::disk('public')->put($cdrPath, base64_decode($result['cdr_base64']));
            }

            $pdfPath = null;
            if (!empty($result['pdf_base64'])) {
                $pdfPath = "{$pathPrefix}/{$fileNameBase}.pdf";
                Storage::disk('public')->put($pdfPath, base64_decode($result['pdf_base64']));
            }

            $document->update([
                'status' => $result['status'] ?? 'accepted', 
                'ticket' => $result['ticket'] ?? $document->ticket,
                'xml_path' => $xmlPath ?? $document->xml_path,
                'cdr_path' => $cdrPath ?? $document->cdr_path,
                'pdf_path' => $pdfPath ?? $document->pdf_path,
            ]);

        } catch (Exception $e) {
            Log::error("ProcessDocumentJob Exception: " . $e->getMessage());
            $document->update(['status' => 'exception']);
            throw $e; // Re-lanza para que Laravel cuente el intento (tries = 3)
        }
    }

    /**
     * Handle a job failure.
     * Solo se ejecuta cuando se agotaron todos los intentos (tries = 3).
     */
    public function failed(?Exception $exception): void
    {
        $company = Company::find($this->companyId);
        $agency = Agency::find($this->agencyId);
        $document = Document::find($this->documentId);

        if ($company && $agency && $company->environment !== 'demo') {
            $balanceField = $company->engine_type === 'qpse' ? 'balance_qpse' : 'balance_native';
            $agency->increment($balanceField); // Devolvemos el saldo solo al final
        }

        if ($document) {
            $document->update([
                'status' => 'exception',
            ]);
        }
        
        Log::critical("ProcessDocumentJob falló permanentemente para doc {$this->documentId}. Saldo devuelto.");
    }
}

import re

with open("app/Http/Controllers/Api/DocumentController.php", "r", encoding="utf-8") as f:
    content = f.read()

old_block = """            try {
                $engine = $this->engineRouter->resolve($company);
                $result = $engine->process($company, $request->all());

                if ((isset($result['status']) && $result['status'] === 'exception' && empty($result['success'])) || (isset($result['success']) && $result['success'] === false)) {
                    throw new Exception($result['message'] ?? 'Error del motor');
                }"""

new_block = """            try {
                $engine = $this->engineRouter->resolve($company);
                $result = $engine->process($company, $request->all());

                if ((isset($result['status']) && $result['status'] === 'exception' && empty($result['success'])) || (isset($result['success']) && $result['success'] === false)) {
                    // Si el motor devolvi?? errores de validaci??n estructurales (QpseEngine env??a un array 'errors'), 
                    // no lo encolamos como intermitencia. Lo rechazamos de inmediato.
                    if (isset($result['errors'])) {
                        $document->update(['status' => 'rejected']);
                        if (!$isDemo) {
                            $agency->increment($balanceField);
                        }
                        return response()->json([
                            'success' => false,
                            'message' => $result['message'] ?? 'Error de validaci??n estructural.',
                            'errors' => $result['errors']
                        ], 422);
                    }
                    throw new Exception($result['message'] ?? 'Error del motor');
                }"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/Api/DocumentController.php", "w", encoding="utf-8") as f:
    f.write(content)

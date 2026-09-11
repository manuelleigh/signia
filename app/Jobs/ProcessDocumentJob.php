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

    /**
     * Create a new job instance.
     */
    public function __construct(int $documentId, array $payload, int $companyId, int $agencyId)
    {
        $this->documentId = $documentId;
        $this->payload = $payload;
        $this->companyId = $companyId;
        $this->agencyId = $agencyId;
    }

    /**
     * Execute the job.
     */
    public function handle(EngineRouter $engineRouter): void
    {
        $document = Document::find($this->documentId);
        $company = Company::find($this->companyId);
        $agency = Agency::find($this->agencyId);

        if (!$document || !$company || !$agency) {
            Log::error("ProcessDocumentJob: Datos incompletos (Doc: {$this->documentId}, Comp: {$this->companyId}, Agency: {$this->agencyId})");
            return;
        }

        try {
            $engine = $engineRouter->resolve($company);
            $result = $engine->process($company, $this->payload);

            if ((isset($result['status']) && $result['status'] === 'exception' && empty($result['success'])) || (isset($result['success']) && $result['success'] === false)) {
                // Revertir saldo solo si NO es demo
                if ($company->environment !== 'demo') {
                    $balanceField = $company->engine_type === 'qpse' ? 'balance_qpse' : 'balance_native';
                    $agency->increment($balanceField);
                }

                $document->update([
                    'status' => 'exception',
                ]);
                Log::error("ProcessDocumentJob Error procesando doc {$this->documentId}: " . ($result['message'] ?? 'Error desconocido'));
                return;
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
                'status' => $result['status'] ?? 'accepted', // accepted, rejected, accepted_with_observations, in_process
                'ticket' => $result['ticket'] ?? $document->ticket, // mantener ticket anterior si no devuelve uno nuevo (caso de error)
                'xml_path' => $xmlPath ?? $document->xml_path,
                'cdr_path' => $cdrPath ?? $document->cdr_path,
                'pdf_path' => $pdfPath ?? $document->pdf_path,
            ]);

        } catch (Exception $e) {
            // Revertir saldo solo si NO es demo
            if ($company->environment !== 'demo') {
                $balanceField = $company->engine_type === 'qpse' ? 'balance_qpse' : 'balance_native';
                $agency->increment($balanceField);
            }

            $document->update([
                'status' => 'exception',
            ]);
            Log::error("ProcessDocumentJob Exception: " . $e->getMessage());
            
            // Re-throw para que Laravel intente de nuevo según $tries
            throw $e;
        }
    }
}

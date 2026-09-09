<?php

namespace App\Services\Signia;

use App\Contracts\EngineContract;
use App\Models\Company;
use App\Services\Signia\Engines\QpseEngine;
use App\Services\Signia\Engines\NativeSunatEngine;
use Exception;

class EngineRouter
{
    /**
     * Resolve the appropriate engine based on the company's settings.
     *
     * @param Company $company
     * @return EngineContract
     * @throws Exception
     */
    public function resolve(Company $company): EngineContract
    {
        return match ($company->engine_type) {
            'qpse' => new QpseEngine(),
            'native' => new NativeSunatEngine(),
            default => throw new Exception("Engine type '{$company->engine_type}' is not supported."),
        };
    }
}

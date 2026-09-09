<?php

namespace App\Contracts;

use App\Models\Company;

interface EngineContract
{
    /**
     * Process and sign the given payload, then send it.
     *
     * @param Company $company
     * @param array $payload
     * @return array
     */
    public function process(Company $company, array $payload): array;
}

<?php

namespace App\Services\Billing;

use App\Models\Agency;
use Illuminate\Support\Facades\DB;
use Exception;

class BalanceService
{
    /**
     * Deduct balance safely using pessimistic locking.
     *
     * @param int $agencyId
     * @param string $engineType
     * @param int $amount
     * @return bool
     * @throws Exception
     */
    public function deductBalance(int $agencyId, string $engineType, int $amount = 1): bool
    {
        return DB::transaction(function () use ($agencyId, $engineType, $amount) {
            // lockForUpdate() prevents other requests from modifying this agency's balance 
            // until the current transaction completes.
            $agency = Agency::where('id', $agencyId)->lockForUpdate()->first();

            if (!$agency) {
                throw new Exception("Agency not found.");
            }

            $balanceField = $engineType === 'qpse' ? 'balance_qpse' : 'balance_native';

            if ($agency->{$balanceField} < $amount) {
                throw new Exception("Insufficient balance for engine: " . $engineType);
            }

            $agency->{$balanceField} -= $amount;
            $agency->save();

            return true;
        });
    }
}

<?php

namespace App\Services;

use App\Models\AffiliateAgent;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    private const COMMISSION_AMOUNT = 40.00; // 40 GHS per successful registration

    /**
     * Process commission for affiliate agent
     */
    public function processCommission(StudentRegistration $registration): bool
    {
        if (!$registration->affiliate_agent_id) {
            return false;
        }

        $agent = AffiliateAgent::find($registration->affiliate_agent_id);

        if (!$agent) {
            Log::warning('Affiliate agent not found', [
                'agent_id' => $registration->affiliate_agent_id,
            ]);
            return false;
        }

        try {
            DB::transaction(function () use ($agent) {
                $agent->increment('total_earnings', self::COMMISSION_AMOUNT);
                $agent->increment('wallet_balance', self::COMMISSION_AMOUNT);
            });

            Log::info('Commission processed successfully', [
                'agent_id' => $agent->id,
                'amount' => self::COMMISSION_AMOUNT,
                'registration_id' => $registration->id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to process commission', [
                'error' => $e->getMessage(),
                'agent_id' => $agent->id,
                'registration_id' => $registration->id,
            ]);

            return false;
        }
    }

    /**
     * Get commission amount
     */
    public static function getCommissionAmount(): float
    {
        return self::COMMISSION_AMOUNT;
    }
}


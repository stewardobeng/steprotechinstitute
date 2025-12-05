<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending');
        }

        $withdrawals = $agent->withdrawals()->latest()->paginate(20);

        return view('affiliate.withdrawals.index', compact('withdrawals', 'agent'));
    }

    public function request(Request $request)
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:200',
        ]);

        if (!$agent->canWithdraw($validated['amount'])) {
            return back()->withErrors([
                'amount' => 'Insufficient balance or amount is less than minimum withdrawal of 200 GHS.',
            ]);
        }

        $withdrawal = Withdrawal::create([
            'affiliate_agent_id' => $agent->id,
            'amount' => $validated['amount'],
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // Send notification to admin
        $notificationService = new \App\Services\NotificationService();
        $notificationService->notifyWithdrawalRequested($withdrawal);

        return redirect()->route('affiliate.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully.');
    }

    public function analytics()
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending');
        }

        return view('affiliate.analytics');
    }
}

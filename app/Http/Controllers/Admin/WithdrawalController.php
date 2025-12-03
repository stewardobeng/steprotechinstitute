<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with(['affiliateAgent.user', 'approver'])
            ->latest()
            ->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['affiliateAgent.user', 'approver']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        // Additional authorization check
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Verify withdrawal is in pending status
        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawals.index')
                ->with('error', 'Withdrawal is not in pending status.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Withdrawal approved successfully.');
    }

    public function markAsPaid(Request $request, Withdrawal $withdrawal)
    {
        // Additional authorization check
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Verify withdrawal is approved before marking as paid
        if ($withdrawal->status !== 'approved') {
            return redirect()->route('admin.withdrawals.index')
                ->with('error', 'Withdrawal must be approved before marking as paid.');
        }

        $validated = $request->validate([
            'payment_proof' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        \DB::transaction(function () use ($withdrawal, $validated) {
            $withdrawal->update([
                'status' => 'paid',
                'payment_proof' => $validated['payment_proof'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $affiliateAgent = $withdrawal->affiliateAgent;
            $affiliateAgent->increment('total_withdrawn', $withdrawal->amount);
            $affiliateAgent->decrement('wallet_balance', $withdrawal->amount);
        });

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Withdrawal marked as paid successfully.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        // Additional authorization check
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Verify withdrawal is in pending or approved status
        if (!in_array($withdrawal->status, ['pending', 'approved'])) {
            return redirect()->route('admin.withdrawals.index')
                ->with('error', 'Withdrawal cannot be rejected in its current status.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $withdrawal->update([
            'status' => 'rejected',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Withdrawal rejected.');
    }
}

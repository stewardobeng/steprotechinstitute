<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateAgent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending')
                ->with('error', 'Affiliate agent profile not found.');
        }

        $stats = [
            'total_earnings' => $agent->total_earnings,
            'total_withdrawn' => $agent->total_withdrawn,
            'wallet_balance' => $agent->wallet_balance,
            'total_students' => $agent->studentRegistrations()->where('payment_status', 'paid')->count(),
            'pending_withdrawals' => $agent->withdrawals()->where('status', 'pending')->count(),
        ];

        $recentStudents = $agent->studentRegistrations()
            ->with('user')
            ->where('payment_status', 'paid')
            ->latest()
            ->limit(10)
            ->get();

        // Prepare chart data - Last 12 months
        $chartData = [];
        $commissionAmount = 40.00;
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $chartData['labels'][] = $monthStart->format('M Y');
            
            // Earnings from paid registrations in that month
            $paidRegistrations = $agent->studentRegistrations()
                ->where('payment_status', 'paid')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->count();
            $chartData['earnings'][] = $paidRegistrations * $commissionAmount;
            
            // Students registered in that month
            $chartData['students'][] = $agent->studentRegistrations()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            // Paid students in that month
            $chartData['paid_students'][] = $agent->studentRegistrations()
                ->where('payment_status', 'paid')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->count();
        }

        // Payment status distribution
        $chartData['payment_status'] = [
            'paid' => $agent->studentRegistrations()->where('payment_status', 'paid')->count(),
            'pending' => $agent->studentRegistrations()->where('payment_status', 'pending')->count(),
            'failed' => $agent->studentRegistrations()->where('payment_status', 'failed')->count(),
        ];

        return view('affiliate.dashboard', compact('stats', 'recentStudents', 'agent', 'chartData'));
    }

    public function pending()
    {
        $agent = auth()->user()->affiliateAgent;

        if ($agent && $agent->registration_approved) {
            return redirect()->route('affiliate.dashboard');
        }

        return view('affiliate.pending');
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $user->update(['status' => 'inactive']);

        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Your account has been deactivated. Administrators can still access it.');
    }
}

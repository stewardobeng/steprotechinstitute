<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateAgent;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Current stats
        $totalStudents = StudentRegistration::count();
        $paidStudents = StudentRegistration::where('payment_status', 'paid')->count();
        $totalCommissions = AffiliateAgent::sum('total_earnings');
        $newAffiliateSignups = AffiliateAgent::where('created_at', '>=', now()->subDays(30))->count();
        $pendingPayouts = Withdrawal::where('status', 'pending')->sum('amount');
        
        // Previous period stats (30 days ago)
        $previousTotalStudents = StudentRegistration::where('created_at', '<', now()->subDays(30))->count();
        $previousPaidStudents = StudentRegistration::where('payment_status', 'paid')
            ->where('payment_date', '<', now()->subDays(30))
            ->count();
        $previousCommissions = AffiliateAgent::where('created_at', '<', now()->subDays(30))
            ->sum('total_earnings');
        $previousNewSignups = AffiliateAgent::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $previousPayouts = Withdrawal::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(30))
            ->sum('amount');

        // Calculate percentage changes (comparing last 30 days to previous 30 days)
        $currentPeriodStudents = StudentRegistration::where('created_at', '>=', now()->subDays(30))->count();
        $previousPeriodStudents = StudentRegistration::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $studentsChange = $previousPeriodStudents > 0 
            ? (($currentPeriodStudents - $previousPeriodStudents) / $previousPeriodStudents) * 100 
            : ($currentPeriodStudents > 0 ? 100 : 0);

        $currentPeriodCommissions = AffiliateAgent::where('created_at', '>=', now()->subDays(30))->sum('total_earnings');
        $previousPeriodCommissions = AffiliateAgent::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->sum('total_earnings');
        $commissionsChange = $previousPeriodCommissions > 0 
            ? (($currentPeriodCommissions - $previousPeriodCommissions) / $previousPeriodCommissions) * 100 
            : ($currentPeriodCommissions > 0 ? 100 : 0);

        $signupsChange = $previousNewSignups > 0 
            ? (($newAffiliateSignups - $previousNewSignups) / $previousNewSignups) * 100 
            : ($newAffiliateSignups > 0 ? 100 : 0);

        $currentPeriodPayouts = Withdrawal::where('status', 'pending')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('amount');
        $previousPeriodPayouts = Withdrawal::where('status', 'pending')
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('amount');
        $payoutsChange = $previousPeriodPayouts > 0 
            ? (($currentPeriodPayouts - $previousPeriodPayouts) / $previousPeriodPayouts) * 100 
            : ($currentPeriodPayouts > 0 ? 100 : 0);

        // Calculate Total Income (from paid registrations)
        $totalIncome = StudentRegistration::where('payment_status', 'paid')->sum('registration_fee');
        $currentPeriodIncome = StudentRegistration::where('payment_status', 'paid')
            ->where('payment_date', '>=', now()->subDays(30))
            ->sum('registration_fee');
        $previousPeriodIncome = StudentRegistration::where('payment_status', 'paid')
            ->whereBetween('payment_date', [now()->subDays(60), now()->subDays(30)])
            ->sum('registration_fee');
        $incomeChange = $previousPeriodIncome > 0 
            ? (($currentPeriodIncome - $previousPeriodIncome) / $previousPeriodIncome) * 100 
            : ($currentPeriodIncome > 0 ? 100 : 0);

        // Calculate Profit (Total Income - Total Commissions)
        $profit = $totalIncome - $totalCommissions;
        $currentPeriodProfit = $currentPeriodIncome - $currentPeriodCommissions;
        $previousPeriodProfit = $previousPeriodIncome - $previousPeriodCommissions;
        $profitChange = $previousPeriodProfit > 0 
            ? (($currentPeriodProfit - $previousPeriodProfit) / $previousPeriodProfit) * 100 
            : ($currentPeriodProfit > 0 ? 100 : 0);

        $stats = [
            'total_students' => $totalStudents,
            'paid_students' => $paidStudents,
            'pending_students' => StudentRegistration::where('payment_status', 'pending')->count(),
            'total_agents' => AffiliateAgent::count(),
            'approved_agents' => AffiliateAgent::where('registration_approved', true)->count(),
            'pending_agents' => AffiliateAgent::where('registration_approved', false)->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'total_earnings' => $totalIncome,
            'total_income' => $totalIncome,
            'total_commissions' => $totalCommissions,
            'profit' => $profit,
            'total_withdrawn' => AffiliateAgent::sum('total_withdrawn'),
            'pending_payouts' => $pendingPayouts,
            'new_affiliate_signups' => $newAffiliateSignups,
            'students_change' => round($studentsChange, 1),
            'commissions_change' => round($commissionsChange, 1),
            'signups_change' => round($signupsChange, 1),
            'payouts_change' => round($payoutsChange, 1),
            'income_change' => round($incomeChange, 1),
            'profit_change' => round($profitChange, 1),
        ];

        // Prepare chart data - Last 12 months
        $chartData = [];
        $commissionAmount = 40.00; // Commission per paid registration
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $chartData['labels'][] = $monthStart->format('M Y');
            $chartData['revenue'][] = StudentRegistration::where('payment_status', 'paid')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->sum('registration_fee');
            // Calculate commissions based on paid registrations with affiliate agents in that month
            $paidRegistrationsWithAgents = StudentRegistration::where('payment_status', 'paid')
                ->whereNotNull('affiliate_agent_id')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->count();
            $chartData['commissions'][] = $paidRegistrationsWithAgents * $commissionAmount;
            $chartData['students'][] = StudentRegistration::whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
        }

        // Payment status distribution
        $chartData['payment_status'] = [
            'paid' => StudentRegistration::where('payment_status', 'paid')->count(),
            'pending' => StudentRegistration::where('payment_status', 'pending')->count(),
            'failed' => StudentRegistration::where('payment_status', 'failed')->count(),
        ];

        $recentRegistrations = StudentRegistration::with(['user', 'affiliateAgent'])
            ->latest()
            ->limit(10)
            ->get();

        $recentWithdrawals = Withdrawal::with(['affiliateAgent.user'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingAffiliates = AffiliateAgent::with('user')
            ->where('registration_approved', false)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'recentWithdrawals', 'pendingAffiliates', 'chartData'));
    }
}

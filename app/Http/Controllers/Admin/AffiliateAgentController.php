<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateAgent;
use App\Models\User;
use Illuminate\Http\Request;

class AffiliateAgentController extends Controller
{
    public function index(Request $request)
    {
        $query = AffiliateAgent::with(['user', 'approver']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('referral_link', 'like', "%{$search}%");
        }

        // Filter by approval status
        if ($request->has('approval_status') && $request->approval_status !== '') {
            $query->where('registration_approved', $request->approval_status == '1');
        }

        // Filter by user status
        if ($request->has('user_status') && $request->user_status) {
            $query->whereHas('user', function($userQuery) use ($request) {
                $userQuery->where('status', $request->user_status);
            });
        }

        $agents = $query->latest()->paginate(20)->withQueryString();

        // Stats for dashboard
        $stats = [
            'total' => AffiliateAgent::count(),
            'approved' => AffiliateAgent::where('registration_approved', true)->count(),
            'pending' => AffiliateAgent::where('registration_approved', false)->count(),
            'active' => AffiliateAgent::whereHas('user', function($q) {
                $q->where('status', 'active');
            })->count(),
            'total_earnings' => AffiliateAgent::sum('total_earnings'),
        ];

        return view('admin.affiliate-agents.index', compact('agents', 'stats'));
    }

    public function show(AffiliateAgent $affiliateAgent)
    {
        $affiliateAgent->load(['user', 'studentRegistrations.user', 'withdrawals']);

        if (request()->wantsJson()) {
            return response()->json($affiliateAgent);
        }

        return view('admin.affiliate-agents.show', compact('affiliateAgent'));
    }

    public function approve(AffiliateAgent $affiliateAgent)
    {
        $affiliateAgent->update([
            'registration_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent approved successfully.');
    }

    public function reject(AffiliateAgent $affiliateAgent)
    {
        $affiliateAgent->update([
            'registration_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent registration rejected.');
    }

    public function deactivate(AffiliateAgent $affiliateAgent)
    {
        $affiliateAgent->user->update(['status' => 'inactive']);

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent deactivated successfully.');
    }

    public function activate(AffiliateAgent $affiliateAgent)
    {
        $affiliateAgent->user->update(['status' => 'active']);

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent activated successfully.');
    }

    public function update(Request $request, AffiliateAgent $affiliateAgent)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'registration_approved' => 'sometimes|boolean',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['phone'])) {
            $affiliateAgent->user->update(array_filter([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]));
        }

        if (isset($validated['registration_approved'])) {
            $affiliateAgent->update([
                'registration_approved' => $validated['registration_approved'],
                'approved_by' => $validated['registration_approved'] ? auth()->id() : null,
                'approved_at' => $validated['registration_approved'] ? now() : null,
            ]);
        }

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent updated successfully.');
    }

    public function destroy(AffiliateAgent $affiliateAgent)
    {
        $user = $affiliateAgent->user;
        $affiliateAgent->delete();
        
        // Optionally delete user if they have no other roles/relations
        if ($user && $user->affiliateAgent === null) {
            $user->delete();
        }

        return redirect()->route('admin.affiliate-agents.index')
            ->with('success', 'Affiliate agent deleted successfully.');
    }
}

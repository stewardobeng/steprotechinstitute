<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAffiliateApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role !== 'affiliate_agent') {
            return $next($request);
        }

        $affiliateAgent = $user->affiliateAgent;

        if (!$affiliateAgent || !$affiliateAgent->registration_approved) {
            return redirect()->route('affiliate.pending')
                ->with('error', 'Your registration is pending approval. Please wait for administrator approval.');
        }

        return $next($request);
    }
}

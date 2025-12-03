<?php

namespace App\Http\Controllers;

use App\Models\AffiliateAgent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // If there's a referral parameter, store it in session
        if ($request->has('ref')) {
            $ref = $request->query('ref');
            // Validate that the referral link exists
            $affiliateAgent = AffiliateAgent::where('referral_link', $ref)->first();
            if ($affiliateAgent) {
                $request->session()->put('referral_ref', $ref);
            }
        }

        // Also handle invite code parameter
        if ($request->has('code')) {
            $code = $request->query('code');
            $request->session()->put('referral_code', $code);
        }

        return view('welcome');
    }
}


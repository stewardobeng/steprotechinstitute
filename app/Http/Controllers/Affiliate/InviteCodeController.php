<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\InviteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteCodeController extends Controller
{
    public function generate()
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending');
        }

        // Generate 6 capital letters
        $code = strtoupper(Str::random(6));

        // Ensure it's only letters
        while (!ctype_alpha($code)) {
            $code = strtoupper(Str::random(6));
        }

        // Check if code already exists
        while (InviteCode::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(6));
            while (!ctype_alpha($code)) {
                $code = strtoupper(Str::random(6));
            }
        }

        InviteCode::create([
            'code' => $code,
            'type' => 'agent_generated',
            'generated_by' => auth()->id(),
            'max_uses' => 999999, // Unlimited uses for agent codes
            'current_uses' => 0,
            'status' => 'active',
        ]);

        return redirect()->back()
            ->with('success', "Invite code generated: {$code}");
    }
}

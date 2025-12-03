<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InviteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InviteCodeController extends Controller
{
    public function index()
    {
        $inviteCodes = InviteCode::with('generator')
            ->where('type', 'admin_generated')
            ->latest()
            ->paginate(20);

        return view('admin.invite-codes.index', compact('inviteCodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:invite_codes,code',
            'max_uses' => 'required|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $code = $validated['code'] ?? strtoupper(Str::random(8));

        $inviteCode = InviteCode::create([
            'code' => $code,
            'type' => 'admin_generated',
            'generated_by' => auth()->id(),
            'max_uses' => $validated['max_uses'],
            'current_uses' => 0,
            'status' => 'active',
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        // If coming from dashboard, redirect back with generated code
        if (request()->header('Referer') && str_contains(request()->header('Referer'), 'dashboard')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Invite code generated successfully.')
                ->with('generated_code', $inviteCode->code);
        }

        return redirect()->route('admin.invite-codes.index')
            ->with('success', 'Invite code generated successfully.');
    }

    public function validateCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $inviteCode = InviteCode::where('code', $validated['code'])->first();

        if (!$inviteCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Invite code does not exist.',
            ]);
        }

        if (!$inviteCode->canBeUsed()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invite code is not valid or has expired.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'code' => $inviteCode->code,
            'max_uses' => $inviteCode->max_uses,
            'current_uses' => $inviteCode->current_uses,
            'remaining_uses' => $inviteCode->max_uses - $inviteCode->current_uses,
        ]);
    }

    public function destroy(InviteCode $inviteCode)
    {
        $inviteCode->update(['status' => 'inactive']);

        return redirect()->route('admin.invite-codes.index')
            ->with('success', 'Invite code deactivated successfully.');
    }
}

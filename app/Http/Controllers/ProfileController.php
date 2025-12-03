<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            
            // Additional security checks
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif'];
            
            $mimeType = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Verify MIME type and extension
            if (!in_array($mimeType, $allowedMimes) || !in_array($extension, $allowedExtensions)) {
                return back()->withErrors([
                    'profile_image' => 'Invalid file type. Only JPEG, PNG, JPG, and GIF images are allowed.'
                ])->withInput();
            }
            
            // Verify file size (2MB max)
            if ($file->getSize() > 2048000) {
                return back()->withErrors([
                    'profile_image' => 'File size exceeds 2MB limit.'
                ])->withInput();
            }
            
            // Verify it's actually an image by checking dimensions
            try {
                $imageInfo = getimagesize($file->getRealPath());
                if ($imageInfo === false) {
                    return back()->withErrors([
                        'profile_image' => 'Invalid image file.'
                    ])->withInput();
                }
            } catch (\Exception $e) {
                return back()->withErrors([
                    'profile_image' => 'Unable to process image file.'
                ])->withInput();
            }
            
            // Generate secure filename to prevent path traversal
            $filename = uniqid('profile_', true) . '.' . $extension;
            
            // Delete old image if exists
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image with secure filename
            $path = $file->storeAs('profile-images', $filename, 'public');
            $validated['profile_image'] = $path;
        } else {
            // Remove profile_image from validated if not uploaded
            unset($validated['profile_image']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Prepare 2FA setup (show QR code) - Returns modal content
     */
    public function prepare2FA(Request $request)
    {
        $user = $request->user();
        $twoFactorService = app(TwoFactorService::class);
        
        $result = $twoFactorService->prepareSetup($user);

        if (!$result['success']) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to prepare two-factor authentication.',
                ], 400);
            }
            return Redirect::route('profile.edit')
                ->with('error', $result['message'] ?? 'Failed to prepare two-factor authentication.');
        }

        $view = view('profile.partials.setup-2fa-modal', [
            'user' => $user,
            'qrCodeSvg' => $result['qr_code_svg'],
            'secret' => $result['secret'],
        ])->render();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => $view,
                'qrCodeSvg' => $result['qr_code_svg'],
                'secret' => $result['secret'],
            ]);
        }

        return view('profile.setup-2fa', [
            'user' => $user,
            'qrCodeSvg' => $result['qr_code_svg'],
            'secret' => $result['secret'],
        ]);
    }

    /**
     * Enable two-factor authentication.
     */
    public function enable2FA(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        $twoFactorService = app(TwoFactorService::class);
        
        $result = $twoFactorService->enableTwoFactor($user, $request->code);

        if ($result['success']) {
            return Redirect::route('profile.edit')
                ->with('status', '2fa-enabled')
                ->with('backup_codes', $result['backup_codes']);
        }

        return Redirect::route('profile.setup-2fa')
            ->with('error', $result['message'] ?? 'Failed to enable two-factor authentication.');
    }

    /**
     * Disable two-factor authentication.
     */
    public function disable2FA(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $twoFactorService = app(TwoFactorService::class);
        
        $result = $twoFactorService->disableTwoFactor($user);

        if ($result) {
            return Redirect::route('profile.edit')
                ->with('status', '2fa-disabled');
        }

        return Redirect::route('profile.edit')
            ->with('error', 'Failed to disable two-factor authentication.');
    }

    /**
     * Regenerate backup codes.
     */
    public function regenerateBackupCodes(Request $request): RedirectResponse
    {
        $user = $request->user();
        $twoFactorService = app(TwoFactorService::class);
        
        $result = $twoFactorService->regenerateBackupCodes($user);

        if ($result['success']) {
            return Redirect::route('profile.edit')
                ->with('status', 'backup-codes-regenerated')
                ->with('backup_codes', $result['backup_codes'])
                ->with('warning', 'Your old backup codes are no longer valid. Please save the new codes.');
        }

        return Redirect::route('profile.edit')
            ->with('error', $result['message'] ?? 'Failed to regenerate backup codes.');
    }
}

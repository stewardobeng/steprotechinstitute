<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MessageTemplate;
use App\Mail\CustomMessageMail;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Setting;

class MessageController extends Controller
{
    public function index()
    {
        // Get users for dropdown (for individual selection)
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        // Get message templates grouped by category
        $templates = MessageTemplate::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('admin.messages.index', compact('users', 'templates'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:individual,students,affiliates,admins,all',
            'user_id' => 'required_if:recipient_type,individual|nullable|exists:users,id',
            'template_id' => 'nullable|exists:message_templates,id',
            'subject' => 'required_without:template_id|string|max:255',
            'message' => 'required_without:template_id|string|min:10',
            'send_email' => 'boolean',
            'send_notification' => 'boolean',
        ], [
            'recipient_type.required' => 'Please select a recipient type.',
            'user_id.required_if' => 'Please select a user when sending to individual.',
            'subject.required_without' => 'Subject is required when not using a template.',
            'message.required_without' => 'Message content is required when not using a template.',
            'message.min' => 'Message must be at least 10 characters.',
        ]);

        // If template is selected, load it and use its subject/message
        $subject = $validated['subject'] ?? null;
        $message = $validated['message'] ?? null;

        if ($request->has('template_id') && $request->template_id) {
            $template = MessageTemplate::findOrFail($request->template_id);
            $subject = $template->subject;
            $message = $template->message;
        }

        // Ensure at least one delivery method is selected
        if (!($request->has('send_email') || $request->has('send_notification'))) {
            return back()->withErrors(['delivery' => 'Please select at least one delivery method (Email or In-App Notification).'])->withInput();
        }

        // Validate that we have subject and message (either from template or manual input)
        if (!$subject || !$message) {
            return back()->withErrors(['template' => 'Please select a template or enter subject and message manually.'])->withInput();
        }

        try {
            // Get recipients based on type
            $recipients = $this->getRecipients($validated['recipient_type'], $validated['user_id'] ?? null);

            if ($recipients->isEmpty()) {
                return back()->withErrors(['recipients' => 'No recipients found for the selected criteria.'])->withInput();
            }

            $sentCount = 0;
            $failedCount = 0;
            $notificationService = app(NotificationService::class);

            foreach ($recipients as $user) {
                try {
                    $sendEmail = $request->has('send_email');
                    $sendNotification = $request->has('send_notification');

                    // Replace variables in subject and message with user data
                    $userSubject = $this->replaceVariables($subject, $user);
                    $userMessage = $this->replaceVariables($message, $user);

                    // Send branded email if requested (uses CustomMessageMail with professional template)
                    if ($sendEmail) {
                        $this->sendEmail($user, $userSubject, $userMessage);
                    }

                    // Send in-app notification if requested
                    // Always use sendNotificationOnly to avoid duplicate emails
                    // (Email is handled separately above with the branded template)
                    if ($sendNotification) {
                        $notificationService->sendNotificationOnly(
                            $user,
                            'custom_message',
                            $userSubject,
                            $userMessage,
                            ['action_url' => $this->getActionUrl($user)]
                        );
                    }

                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error('Failed to send message to user', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $message = "Message sent successfully to {$sentCount} recipient(s).";
            if ($failedCount > 0) {
                $message .= " {$failedCount} failed.";
            }

            return redirect()->route('admin.messages.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to send messages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Failed to send messages. Please try again.'])->withInput();
        }
    }

    protected function getRecipients(string $type, ?int $userId = null)
    {
        $query = User::where('status', 'active');

        switch ($type) {
            case 'individual':
                if ($userId) {
                    $query->where('id', $userId);
                }
                break;
            case 'students':
                $query->where('role', 'student');
                break;
            case 'affiliates':
                $query->where('role', 'affiliate_agent');
                break;
            case 'admins':
                $query->where('role', 'admin');
                break;
            case 'all':
                // No filter needed
                break;
        }

        return $query->get();
    }

    protected function sendEmail(User $user, string $subject, string $message)
    {
        // Update mail config from settings
        $this->updateMailConfig();

        // Check if SMTP is enabled
        $smtpEnabled = Setting::getValue('smtp_enabled', false);
        $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
        
        if (!$smtpEnabled) {
            Log::info('SMTP is disabled. Email skipped.', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return;
        }

        Mail::to($user->email, $user->name)
            ->send(new CustomMessageMail($subject, $message, $user->name));

        Log::info('Custom message email sent', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    protected function getActionUrl(User $user): string
    {
        if ($user->isStudent()) {
            return route('student.dashboard');
        } elseif ($user->isAffiliateAgent()) {
            return route('affiliate.dashboard');
        } elseif ($user->isAdmin()) {
            return route('admin.dashboard');
        }
        return route('dashboard');
    }

    protected function updateMailConfig(): void
    {
        try {
            // Check if SMTP is enabled
            $smtpEnabled = Setting::getValue('smtp_enabled', false);
            $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
            
            if (!$smtpEnabled) {
                Config::set('mail.default', 'log');
                app()->forgetInstance('mailer');
                return;
            }

            $mailHost = Setting::getValue('smtp_host', env('MAIL_HOST', '127.0.0.1'));
            $mailPort = Setting::getValue('smtp_port', env('MAIL_PORT', 2525));
            $mailUsername = Setting::getValue('smtp_username', env('MAIL_USERNAME'));
            $mailPassword = Setting::getValue('smtp_password', env('MAIL_PASSWORD'));
            $mailEncryption = Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
            $mailFromAddress = Setting::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
            $mailFromName = Setting::getValue('smtp_from_name', env('MAIL_FROM_NAME', 'StepProClass'));

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $mailHost,
                'port' => (int) $mailPort,
                'encryption' => $mailEncryption ?: null,
                'username' => $mailUsername,
                'password' => $mailPassword,
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            ]);

            \Config::set('mail.from', [
                'address' => $mailFromAddress,
                'name' => $mailFromName,
            ]);

            app()->forgetInstance('mailer');
        } catch (\Exception $e) {
            Log::error('Failed to update mail config in MessageController', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Replace variables in message/subject with user data
     */
    protected function replaceVariables(string $text, User $user): string
    {
        $replacements = [
            '{{name}}' => $user->name,
            '{{email}}' => $user->email,
        ];

        // Add student-specific variables if user is a student
        if ($user->isStudent() && $user->studentRegistration) {
            $replacements['{{student_id}}'] = $user->studentRegistration->student_id;
        }

        // Add affiliate-specific variables if user is an affiliate
        if ($user->isAffiliateAgent() && $user->affiliateAgent) {
            $replacements['{{balance}}'] = number_format($user->affiliateAgent->wallet_balance, 2);
        }

        foreach ($replacements as $key => $value) {
            $text = str_replace($key, $value, $text);
        }

        return $text;
    }
}


<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class NotificationService
{
    /**
     * Send notification (both email and in-app)
     */
    public function send(User $user, string $type, string $title, string $message, array $data = []): void
    {
        // Create in-app notification
        $this->createInAppNotification($user, $type, $title, $message, $data);

        // Send email notification
        $this->sendEmailNotification($user, $type, $title, $message, $data);
    }

    /**
     * Create in-app notification
     */
    protected function createInAppNotification(User $user, string $type, string $title, string $message, array $data = []): void
    {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'read' => false,
            ]);
            
            Log::info('In-app notification created', [
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create in-app notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification(User $user, string $type, string $title, string $message, array $data = []): void
    {
        try {
            // Update mail config from settings
            $this->updateMailConfig();

            // Check if SMTP is enabled
            $smtpEnabled = Setting::getValue('smtp_enabled', false);
            $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
            if (!$smtpEnabled) {
                Log::info('SMTP is disabled. Email notification skipped.', [
                    'user_id' => $user->id,
                    'type' => $type,
                ]);
                return;
            }

            // Send email
            Mail::raw($message, function ($mail) use ($user, $title, $type) {
                $mail->to($user->email, $user->name)
                    ->subject($title);
            });

            Log::info('Email notification sent', [
                'user_id' => $user->id,
                'type' => $type,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }
    }

    /**
     * Update mail configuration from settings
     */
    protected function updateMailConfig(): void
    {
        $mailer = Setting::getValue('smtp_mailer', env('MAIL_MAILER', 'smtp'));
        $host = Setting::getValue('smtp_host', env('MAIL_HOST', 'smtp.mailtrap.io'));
        $port = Setting::getValue('smtp_port', env('MAIL_PORT', 2525));
        $username = Setting::getValue('smtp_username', env('MAIL_USERNAME'));
        $password = Setting::getValue('smtp_password', env('MAIL_PASSWORD'));
        $encryption = Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
        $fromAddress = Setting::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
        $fromName = Setting::getValue('smtp_from_name', env('MAIL_FROM_NAME', 'StepProClass'));

        Config::set('mail.default', $mailer);
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $host,
            'port' => $port,
            'encryption' => $encryption ?: null,
            'username' => $username,
            'password' => $password,
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ]);

        Config::set('mail.from', [
            'address' => $fromAddress,
            'name' => $fromName,
        ]);
    }

    /**
     * Notify affiliate agent when account is approved
     */
    public function notifyAffiliateApproved(User $user): void
    {
        $this->send(
            $user,
            'account_approved',
            'Account Approved',
            "Hello {$user->name},\n\nYour affiliate agent account has been approved. You can now start referring students and earning commissions.\n\nThank you for joining us!",
            ['action_url' => route('affiliate.dashboard')]
        );
    }

    /**
     * Notify admin when withdrawal is requested
     */
    public function notifyWithdrawalRequested($withdrawal): void
    {
        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->send(
                $admin,
                'withdrawal_requested',
                'New Withdrawal Request',
                "A withdrawal request of GHS {$withdrawal->amount} has been submitted by {$withdrawal->affiliateAgent->user->name}.",
                ['withdrawal_id' => $withdrawal->id, 'action_url' => route('admin.withdrawals.show', $withdrawal)]
            );
        }
    }

    /**
     * Notify affiliate agent when withdrawal is approved
     */
    public function notifyWithdrawalApproved(User $user, $withdrawal): void
    {
        $this->send(
            $user,
            'withdrawal_approved',
            'Withdrawal Approved',
            "Hello {$user->name},\n\nYour withdrawal request of GHS {$withdrawal->amount} has been approved and will be processed shortly.\n\nThank you!",
            ['withdrawal_id' => $withdrawal->id, 'amount' => $withdrawal->amount]
        );
    }

    /**
     * Notify when student is added
     */
    public function notifyStudentAdded(User $student, $affiliateAgent = null): void
    {
        // Notify student
        $this->send(
            $student,
            'student_added',
            'Welcome to StepProClass',
            "Hello {$student->name},\n\nYour student account has been created successfully. You can now access your dashboard and start your learning journey.\n\nWelcome aboard!",
            ['action_url' => route('student.dashboard')]
        );

        // Notify affiliate agent if student was referred
        if ($affiliateAgent && $affiliateAgent->user) {
            $this->send(
                $affiliateAgent->user,
                'student_referred',
                'New Student Referred',
                "Great news! A new student ({$student->name}) has been registered through your referral link. You will earn commission once they complete payment.",
                ['student_id' => $student->id, 'action_url' => route('affiliate.students.index')]
            );
        }

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->send(
                $admin,
                'student_added',
                'New Student Registered',
                "A new student ({$student->name}) has been registered." . ($affiliateAgent ? " Referred by: {$affiliateAgent->user->name}" : ""),
                ['student_id' => $student->id, 'action_url' => route('admin.students.index')]
            );
        }
    }

    /**
     * Notify affiliate agent when commission is earned
     */
    public function notifyCommissionEarned(User $affiliateAgent, $registration, $commissionAmount): void
    {
        $this->send(
            $affiliateAgent,
            'commission_earned',
            'Commission Earned',
            "Congratulations! You've earned GHS {$commissionAmount} commission from student {$registration->user->name} completing their payment.",
            ['registration_id' => $registration->id, 'commission_amount' => $commissionAmount, 'action_url' => route('affiliate.students.index')]
        );
    }

    /**
     * Notify when payment is completed
     */
    public function notifyPaymentCompleted(User $user, $payment): void
    {
        $this->send(
            $user,
            'payment_completed',
            'Payment Received',
            "Hello {$user->name},\n\nYour payment of GHS {$payment->amount} has been received and confirmed.\n\nThank you for your payment!",
            ['payment_id' => $payment->id, 'amount' => $payment->amount]
        );
    }
}


<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\RegistrationController as StudentRegistrationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ResourcesController as StudentResourcesController;
use App\Http\Controllers\Affiliate\DashboardController as AffiliateDashboardController;
use App\Http\Controllers\Affiliate\StudentController as AffiliateStudentController;
use App\Http\Controllers\Affiliate\WithdrawalController as AffiliateWithdrawalController;
use App\Http\Controllers\Affiliate\InviteCodeController as AffiliateInviteCodeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InviteCodeController as AdminInviteCodeController;
use App\Http\Controllers\Admin\AffiliateAgentController as AdminAffiliateAgentController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ClassroomController as AdminClassroomController;
use App\Http\Controllers\Student\ClassroomController as StudentClassroomController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Student Registration (Public)
Route::get('/register', [StudentRegistrationController::class, 'show'])->name('register');
Route::post('/register', [StudentRegistrationController::class, 'store'])->name('register.store');
Route::get('/register/payment/{registration}', [StudentRegistrationController::class, 'payment'])->name('register.payment')->middleware('auth');

// Payment Routes
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/2fa/setup', [ProfileController::class, 'prepare2FA'])->name('profile.setup-2fa');
    Route::post('/profile/2fa/enable', [ProfileController::class, 'enable2FA'])->name('profile.2fa.enable');
    Route::delete('/profile/2fa/disable', [ProfileController::class, 'disable2FA'])->name('profile.2fa.disable');
    Route::post('/profile/2fa/regenerate-codes', [ProfileController::class, 'regenerateBackupCodes'])->name('profile.2fa.regenerate-codes');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Student Routes
    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/payment', [StudentDashboardController::class, 'payment'])->name('payment');
        Route::get('/resources/recordings', [StudentResourcesController::class, 'recordings'])->name('resources.recordings');
        Route::get('/resources/downloads', [StudentResourcesController::class, 'downloads'])->name('resources.downloads');
        Route::get('/classroom', [StudentClassroomController::class, 'index'])->name('classroom.index');
    });

    // Affiliate Agent Routes
    Route::prefix('affiliate')->name('affiliate.')->middleware('role:affiliate_agent')->group(function () {
        // Routes accessible to unapproved agents
        Route::get('/pending', [AffiliateDashboardController::class, 'pending'])->name('pending');
        Route::get('/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
        Route::get('/referral-link', [AffiliateDashboardController::class, 'index'])->name('referral-link');
        Route::delete('/account', [AffiliateDashboardController::class, 'deleteAccount'])->name('account.delete');
        
        // Routes that require approval
        Route::middleware('affiliate.approved')->group(function () {
            Route::get('/students', [AffiliateStudentController::class, 'index'])->name('students.index');
            Route::get('/analytics', [AffiliateWithdrawalController::class, 'analytics'])->name('analytics');
            Route::get('/withdrawals', [AffiliateWithdrawalController::class, 'index'])->name('withdrawals.index');
            Route::post('/withdrawal/request', [AffiliateWithdrawalController::class, 'request'])->name('withdrawal.request');
            Route::post('/invite-codes/generate', [AffiliateInviteCodeController::class, 'generate'])->name('invite-codes.generate');
        });
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Invite Codes
        Route::get('/invite-codes', [AdminInviteCodeController::class, 'index'])->name('invite-codes.index');
        Route::post('/invite-codes/generate', [AdminInviteCodeController::class, 'store'])->name('invite-codes.store');
        Route::post('/invite-codes/validate', [AdminInviteCodeController::class, 'validateCode'])->name('invite-codes.validate');
        Route::delete('/invite-codes/{inviteCode}', [AdminInviteCodeController::class, 'destroy'])->name('invite-codes.destroy');
        
        // Affiliate Agents
        Route::get('/affiliate-agents', [AdminAffiliateAgentController::class, 'index'])->name('affiliate-agents.index');
        Route::get('/affiliate-agents/{affiliateAgent}', [AdminAffiliateAgentController::class, 'show'])->name('affiliate-agents.show');
        Route::put('/affiliate-agents/{affiliateAgent}', [AdminAffiliateAgentController::class, 'update'])->name('affiliate-agents.update');
        Route::delete('/affiliate-agents/{affiliateAgent}', [AdminAffiliateAgentController::class, 'destroy'])->name('affiliate-agents.destroy');
        Route::post('/affiliate-agents/{affiliateAgent}/approve', [AdminAffiliateAgentController::class, 'approve'])->name('affiliate-agents.approve');
        Route::post('/affiliate-agents/{affiliateAgent}/reject', [AdminAffiliateAgentController::class, 'reject'])->name('affiliate-agents.reject');
        Route::post('/affiliate-agents/{affiliateAgent}/activate', [AdminAffiliateAgentController::class, 'activate'])->name('affiliate-agents.activate');
        Route::post('/affiliate-agents/{affiliateAgent}/deactivate', [AdminAffiliateAgentController::class, 'deactivate'])->name('affiliate-agents.deactivate');
        
        // Students
        Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
        Route::post('/students/verify', [AdminStudentController::class, 'verify'])->name('students.verify');
        Route::post('/students/mark-added', [AdminStudentController::class, 'markAsAdded'])->name('students.mark-added');
        Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
        
        // Withdrawals
        Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/mark-paid', [AdminWithdrawalController::class, 'markAsPaid'])->name('withdrawals.mark-paid');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
        
        // Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-mail', [AdminSettingsController::class, 'testMail'])->name('settings.test-mail');
        
        // Classroom
        Route::get('/classroom', [AdminClassroomController::class, 'index'])->name('classroom.index');
        Route::post('/classroom/update-id', [AdminClassroomController::class, 'updateClassroomId'])->name('classroom.update-id');
        Route::post('/classroom/students/{student}/toggle-approval', [AdminClassroomController::class, 'toggleStudentApproval'])->name('classroom.students.toggle-approval');
        
        // Messages
        Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages/send', [\App\Http\Controllers\Admin\MessageController::class, 'send'])->name('messages.send');
        
        // Message Templates
        Route::get('/message-templates', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'index'])->name('message-templates.index');
        Route::get('/message-templates/create', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'create'])->name('message-templates.create');
        Route::post('/message-templates', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'store'])->name('message-templates.store');
        Route::get('/message-templates/{messageTemplate}/edit', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'edit'])->name('message-templates.edit');
        Route::put('/message-templates/{messageTemplate}', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'update'])->name('message-templates.update');
        Route::delete('/message-templates/{messageTemplate}', [\App\Http\Controllers\Admin\MessageTemplateController::class, 'destroy'])->name('message-templates.destroy');
    });
});

// Default dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isAffiliateAgent()) {
        return redirect()->route('affiliate.dashboard');
    } elseif ($user->isStudent()) {
        return redirect()->route('student.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

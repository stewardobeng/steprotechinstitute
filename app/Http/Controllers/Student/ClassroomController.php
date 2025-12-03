<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $registration = $user->studentRegistration;

        if (!$registration) {
            return redirect()->route('register');
        }

        // Check if payment is completed
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('student.dashboard')
                ->with('error', 'Please complete your payment to access classroom information.');
        }

        // Get classroom ID from settings
        $classroomId = \App\Models\Setting::getValue('classroom_id', '');

        return view('student.classroom.index', compact('registration', 'classroomId'));
    }
}


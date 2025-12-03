<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    public function recordings()
    {
        $user = auth()->user();
        $registration = $user->studentRegistration;

        if (!$registration) {
            return redirect()->route('register');
        }

        // Check if payment is completed
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('student.dashboard')
                ->with('error', 'Please complete your payment to access class recordings.');
        }

        // TODO: Fetch actual recordings from database when implemented
        $recordings = [];

        return view('student.resources.recordings', compact('recordings'));
    }

    public function downloads()
    {
        $user = auth()->user();
        $registration = $user->studentRegistration;

        if (!$registration) {
            return redirect()->route('register');
        }

        // Check if payment is completed
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('student.dashboard')
                ->with('error', 'Please complete your payment to access downloadable resources.');
        }

        // TODO: Fetch actual resources from database when implemented
        $resources = [];

        return view('student.resources.downloads', compact('resources'));
    }
}


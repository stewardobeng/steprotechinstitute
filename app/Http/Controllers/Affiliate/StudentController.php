<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $agent = auth()->user()->affiliateAgent;

        if (!$agent) {
            return redirect()->route('affiliate.pending');
        }

        $students = $agent->studentRegistrations()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('affiliate.students.index', compact('students'));
    }
}

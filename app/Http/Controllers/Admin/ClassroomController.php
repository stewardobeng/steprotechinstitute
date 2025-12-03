<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        // Get classroom ID from settings
        $classroomId = Setting::getValue('classroom_id', '');

        // Get all students who have paid
        $students = StudentRegistration::with('user')
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.classroom.index', compact('classroomId', 'students'));
    }

    public function updateClassroomId(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|string|max:255',
        ]);

        Setting::updateOrCreate(
            ['key' => 'classroom_id'],
            [
                'value' => $request->classroom_id,
                'type' => 'string',
            ]
        );

        return redirect()->route('admin.classroom.index')
            ->with('success', 'Classroom ID updated successfully.');
    }

    public function toggleStudentApproval(Request $request, StudentRegistration $student)
    {
        // Additional authorization check - ensure user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Verify student has paid before allowing approval
        if ($student->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Student must complete payment before approval.'
            ], 400);
        }

        $student->classroom_approved = !$student->classroom_approved;
        $student->save();

        return response()->json([
            'success' => true,
            'message' => $student->classroom_approved 
                ? 'Student classroom access approved.' 
                : 'Student classroom access revoked.',
            'approved' => $student->classroom_approved,
        ]);
    }
}

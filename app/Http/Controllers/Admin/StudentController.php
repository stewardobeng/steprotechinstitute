<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRegistration::with(['user', 'affiliateAgent.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by WhatsApp status
        if ($request->has('whatsapp_status') && $request->whatsapp_status !== '') {
            $query->where('added_to_whatsapp', $request->whatsapp_status == '1');
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        // Stats for dashboard
        $stats = [
            'total' => StudentRegistration::count(),
            'paid' => StudentRegistration::where('payment_status', 'paid')->count(),
            'pending' => StudentRegistration::where('payment_status', 'pending')->count(),
            'whatsapp_added' => StudentRegistration::where('added_to_whatsapp', true)->count(),
        ];

        return view('admin.students.index', compact('students', 'stats'));
    }

    public function show(StudentRegistration $student)
    {
        $student->load(['user', 'affiliateAgent.user']);
        return response()->json($student);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
        ]);

        $student = StudentRegistration::with(['user', 'affiliateAgent.user', 'payment'])
            ->where('student_id', $validated['student_id'])
            ->first();

        if (!$student) {
            return response()->json([
                'exists' => false,
                'message' => 'Student does not exist.',
            ]);
        }

        return response()->json([
            'exists' => true,
            'student' => [
                'student_id' => $student->student_id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'registration_date' => $student->created_at->format('Y-m-d H:i:s'),
                'payment_status' => $student->payment_status,
                'registration_fee' => $student->registration_fee,
                'payment_date' => $student->payment_date ? $student->payment_date->format('Y-m-d H:i:s') : null,
                'added_to_whatsapp' => $student->added_to_whatsapp,
                'affiliate_agent' => $student->affiliateAgent ? $student->affiliateAgent->user->name : null,
            ],
        ]);
    }

    public function markAsAdded(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|exists:student_registrations,student_id',
        ]);

        $student = StudentRegistration::where('student_id', $validated['student_id'])->first();

        if ($student->added_to_whatsapp) {
            return response()->json([
                'success' => false,
                'message' => 'This student has already been added to WhatsApp group.',
            ]);
        }

        $student->update([
            'added_to_whatsapp' => true,
            'added_by' => auth()->id(),
            'added_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student marked as added to WhatsApp group.',
        ]);
    }

    public function destroy(StudentRegistration $student)
    {
        // Delete associated user if needed
        $user = $student->user;
        $student->delete();
        
        // Optionally delete user if they have no other registrations
        if ($user && $user->studentRegistration === null) {
            $user->delete();
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function update(Request $request, StudentRegistration $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'payment_status' => 'sometimes|in:pending,paid,failed',
            'added_to_whatsapp' => 'sometimes|boolean',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['phone'])) {
            $student->user->update(array_filter([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]));
        }

        $student->update(array_filter([
            'payment_status' => $validated['payment_status'] ?? null,
            'added_to_whatsapp' => $validated['added_to_whatsapp'] ?? null,
        ]));

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }
}

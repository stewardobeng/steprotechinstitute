<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $categories = MessageTemplate::distinct('category')
            ->pluck('category')
            ->sort()
            ->values();

        return view('admin.message-templates.index', compact('templates', 'categories'));
    }

    public function create()
    {
        $categories = MessageTemplate::distinct('category')
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();

        // Add common categories if not present
        $defaultCategories = [
            'Welcome & Registration',
            'Class & Sessions',
            'Completion & Certification',
            'General Announcements',
            'Payment & Financial',
            'Affiliate Messages',
            'Reminders',
            'Support & Help',
        ];

        $allCategories = array_unique(array_merge($defaultCategories, $categories));

        return view('admin.message-templates.create', compact('allCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'description' => 'nullable|string|max:500',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Parse variables if provided
        $variables = null;
        if ($request->has('variables') && !empty($request->variables)) {
            $variables = array_map('trim', explode(',', $request->variables));
            $variables = array_filter($variables);
        }

        MessageTemplate::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'description' => $validated['description'] ?? null,
            'variables' => $variables ?: null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.message-templates.index')
            ->with('success', 'Message template created successfully!');
    }

    public function edit(MessageTemplate $messageTemplate)
    {
        $categories = MessageTemplate::distinct('category')
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();

        $defaultCategories = [
            'Welcome & Registration',
            'Class & Sessions',
            'Completion & Certification',
            'General Announcements',
            'Payment & Financial',
            'Affiliate Messages',
            'Reminders',
            'Support & Help',
        ];

        $allCategories = array_unique(array_merge($defaultCategories, $categories));

        return view('admin.message-templates.edit', compact('messageTemplate', 'allCategories'));
    }

    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'description' => 'nullable|string|max:500',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Parse variables if provided
        $variables = null;
        if ($request->has('variables') && !empty($request->variables)) {
            $variables = array_map('trim', explode(',', $request->variables));
            $variables = array_filter($variables);
        }

        $messageTemplate->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'description' => $validated['description'] ?? null,
            'variables' => $variables ?: null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.message-templates.index')
            ->with('success', 'Message template updated successfully!');
    }

    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();

        return redirect()->route('admin.message-templates.index')
            ->with('success', 'Message template deleted successfully!');
    }
}


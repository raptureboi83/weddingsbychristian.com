<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'partner_name' => ['nullable', 'string', 'max:255'],
            'wedding_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'budget_range' => ['nullable', 'string', 'max:255'],
            'services_requested' => ['nullable', 'array'],
            'services_requested.*' => ['string', 'max:255'],
            'how_did_you_hear' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $validated['services_requested'] = $validated['services_requested'] ?? [];
        $validated['status'] = 'new';
        $validated['submitted_at'] = now();

        ContactSubmission::create($validated);

        return redirect()
            ->back()
            ->with('status', 'Thank you — your inquiry has been sent.');
    }
}
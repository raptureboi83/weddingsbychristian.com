<?php

namespace App\Http\Controllers;

use App\Models\ContactSection;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index', [
            'siteSettings' => SiteSetting::current(),
            'contactSection' => ContactSection::query()
                ->where('is_published', true)
                ->first(),
        ]);
    }

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
            'message' => ['nullable', 'string'],
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
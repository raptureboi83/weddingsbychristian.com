<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionMail;
use App\Models\ContactSection;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
            'wedding_date' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('recaptcha.secret_key'),
            'response' => $validated['g-recaptcha-response'],
            'remoteip' => $request->ip(),
        ]);

        $body = $response->json();

        if (!($body['success'] ?? false)) {
            return redirect()
                ->back()
                ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        if (($body['score'] ?? 1) < config('recaptcha.minimum_score', 0.5)) {
            return redirect()
                ->back()
                ->withErrors(['g-recaptcha-response' => 'Your request could not be verified. Please try again.'])
                ->withInput();
        }

        unset($validated['g-recaptcha-response']);

        if (!empty($validated['wedding_date'])) {
            $validated['wedding_date'] = $this->parseDate($validated['wedding_date']);
        }

        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/[^0-9]/', '', $validated['phone']);
        }

        $validated['status'] = 'new';
        $validated['submitted_at'] = now();

        $submission = ContactSubmission::create($validated);

        $recipient = SiteSetting::current()->contact_form_recipient_email
            ?? SiteSetting::current()->contact_email
            ?? config('mail.from.address');

        if ($recipient) {
            Mail::to($recipient)->send(new ContactSubmissionMail($submission));
        }

        return redirect()
            ->back()
            ->with('status', 'Thank you for contacting us. Your message has been sent and we will get back to you as soon as possible.');
    }

    private function parseDate(string $value): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $value);

        if (strlen($digits) === 8) {
            $parsed = Carbon::createFromFormat('Ymd', $digits);
            if ($parsed) {
                return $parsed->format('Y-m-d');
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
}

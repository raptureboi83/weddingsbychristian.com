@if (session('status'))
    <div class="contact-form-alert contact-form-alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="contact-form-alert contact-form-alert-error">
        Please fix the highlighted fields and try again.
    </div>
@endif

<form method="POST" action="{{ route('contact.store') }}" class="contact-form-shell">
    @csrf

    <div class="contact-form-grid">
        <div>
            <label for="name" class="contact-form-label">Your Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Jane & Alex"
                required
                class="contact-form-input"
            >
            @error('name')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email" class="contact-form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="you@email.com"
                required
                class="contact-form-input"
            >
            @error('email')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="contact-form-grid">
        <div>
            <label for="phone" class="contact-form-label">Phone</label>
            <input
                id="phone"
                type="text"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="(555) 555-5555"
                class="contact-form-input"
            >
            @error('phone')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="partner_name" class="contact-form-label">Partner Name</label>
            <input
                id="partner_name"
                type="text"
                name="partner_name"
                value="{{ old('partner_name') }}"
                placeholder="Partner name"
                class="contact-form-input"
            >
            @error('partner_name')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="contact-form-grid">
        <div>
            <label for="wedding_date" class="contact-form-label">Wedding Date</label>
            <input
                id="wedding_date"
                type="date"
                name="wedding_date"
                value="{{ old('wedding_date') }}"
                class="contact-form-input"
            >
            @error('wedding_date')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="budget_range" class="contact-form-label">Budget Range</label>
            <input
                id="budget_range"
                type="text"
                name="budget_range"
                value="{{ old('budget_range') }}"
                placeholder="Your approximate range"
                class="contact-form-input"
            >
            @error('budget_range')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="contact-form-grid">
        <div>
            <label for="location" class="contact-form-label">Location</label>
            <input
                id="location"
                type="text"
                name="location"
                value="{{ old('location') }}"
                placeholder="City or region"
                class="contact-form-input"
            >
            @error('location')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="venue" class="contact-form-label">Venue</label>
            <input
                id="venue"
                type="text"
                name="venue"
                value="{{ old('venue') }}"
                placeholder="Venue name"
                class="contact-form-input"
            >
            @error('venue')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @php
        $selectedServices = old('services_requested', []);
        $serviceOptions = [
            'Wedding Film',
            'Teaser Film',
            'Speeches',
            'Drone Coverage',
            'Full Ceremony Edit',
            'Additional Coverage',
        ];
    @endphp

    <div>
        <div class="contact-form-label contact-form-label-block">Services Requested</div>

        <div class="contact-checkbox-grid">
            @foreach ($serviceOptions as $service)
                <label class="contact-checkbox-card">
                    <input
                        type="checkbox"
                        name="services_requested[]"
                        value="{{ $service }}"
                        {{ in_array($service, $selectedServices, true) ? 'checked' : '' }}
                    >
                    <span>{{ $service }}</span>
                </label>
            @endforeach
        </div>

        @error('services_requested')
            <div class="contact-form-error">{{ $message }}</div>
        @enderror

        @error('services_requested.*')
            <div class="contact-form-error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="how_did_you_hear" class="contact-form-label">How Did You Hear About Me?</label>
        <input
            id="how_did_you_hear"
            type="text"
            name="how_did_you_hear"
            value="{{ old('how_did_you_hear') }}"
            placeholder="Referral, Instagram, venue, etc."
            class="contact-form-input"
        >
        @error('how_did_you_hear')
            <div class="contact-form-error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="message" class="contact-form-label">Anything Else You'd Like To Share</label>
        <textarea
            id="message"
            name="message"
            rows="6"
            placeholder="Guest count, vibe, timing, or anything that matters to you..."
            required
            class="contact-form-textarea"
        >{{ old('message') }}</textarea>
        @error('message')
            <div class="contact-form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="contact-form-submit-wrap">
        <button type="submit" class="button-primary contact-form-submit">Send Details</button>
    </div>
</form>
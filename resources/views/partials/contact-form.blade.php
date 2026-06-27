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
            <label for="phone" class="contact-form-label">Phone (Optional)</label>
            <input
                id="phone"
                type="tel"
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
            <label for="wedding_date" class="contact-form-label">Wedding Date</label>
            <input
                id="wedding_date"
                type="text"
                name="wedding_date"
                value="{{ old('wedding_date') }}"
                placeholder="yyyy-mm-dd"
                class="contact-form-input"
            >
            @error('wedding_date')
                <div class="contact-form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div>
        <label for="venue" class="contact-form-label">Venue / Location</label>
        <input
            id="venue"
            type="text"
            name="venue"
            value="{{ old('venue') }}"
            placeholder="Venue name, city"
            class="contact-form-input"
        >
        @error('venue')
            <div class="contact-form-error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="message" class="contact-form-label">Anything else you'd like to share</label>
        <textarea
            id="message"
            name="message"
            rows="6"
            placeholder="Guest count, vibe, anything that matters to you..."
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
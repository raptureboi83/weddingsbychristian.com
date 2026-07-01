@push('styles')
<style>
.contact-success-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    padding: 24px;
}

.contact-success-overlay.is-hidden {
    display: none;
}

.contact-success-modal {
    background: var(--bg-card, #1a1a1e);
    border: 1px solid var(--border-soft, rgba(255, 255, 255, 0.1));
    border-radius: 8px;
    padding: 48px 40px;
    max-width: 480px;
    width: 100%;
    text-align: center;
}

.contact-success-icon {
    color: var(--accent, #d4af37);
    margin-bottom: 20px;
}

.contact-success-text {
    font-size: 16px;
    line-height: 26px;
    color: var(--text-color, #e0e0e0);
    margin: 0 0 28px;
}
</style>
@endpush

@if (session('status'))
    <div class="contact-success-overlay" id="contactSuccessModal">
        <div class="contact-success-modal">
            <div class="contact-success-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <p class="contact-success-text">{{ session('status') }}</p>
            <button type="button" class="button-primary" onclick="document.getElementById('contactSuccessModal').classList.add('is-hidden'); window.scrollTo({ top: 0, behavior: 'smooth' })">Close</button>
        </div>
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

    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

    <div class="contact-form-submit-wrap">
        <button type="submit" class="button-primary contact-form-submit">Send Details</button>
    </div>
</form>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('contactSuccessModal');
        if (modal) {
            modal.classList.remove('is-hidden');
        }

        function formatPhone(value) {
            var digits = value.replace(/\D/g, '').slice(0, 10);
            if (digits.length === 0) return '';
            if (digits.length <= 3) return '(' + digits;
            if (digits.length <= 6) return '(' + digits.slice(0, 3) + ') ' + digits.slice(3);
            return '(' + digits.slice(0, 3) + ') ' + digits.slice(3, 6) + '-' + digits.slice(6);
        }

        function formatDate(value) {
            var digits = value.replace(/\D/g, '').slice(0, 8);
            if (digits.length === 0) return '';
            if (digits.length <= 4) return digits;
            if (digits.length <= 6) return digits.slice(0, 4) + '-' + digits.slice(4);
            return digits.slice(0, 4) + '-' + digits.slice(4, 6) + '-' + digits.slice(6);
        }

        var phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.value = formatPhone(phoneInput.value);
            phoneInput.addEventListener('blur', function() {
                this.value = formatPhone(this.value);
            });
        }

        var dateInput = document.getElementById('wedding_date');
        if (dateInput) {
            dateInput.value = formatDate(dateInput.value);
            dateInput.addEventListener('blur', function() {
                this.value = formatDate(this.value);
            });
        }

        var form = document.querySelector('.contact-form-shell');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'contact_form'}).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                });
            });
        });
    });
</script>
@endpush
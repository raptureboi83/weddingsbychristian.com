<section id="contact" class="section">
    <div class="container container-narrow">
        @php
            $isContactFormOpen = $errors->any() || filled(session('status')) || !empty(old());
        @endphp

        <div class="contact-wrap contact-wrap-spaced">
            <div>
                <div class="eyebrow">{{ $contactSection?->eyebrow ?: 'Contact' }}</div>
                <h2 class="section-title">{{ $contactSection?->title ?: 'Ready to talk about your wedding film?' }}</h2>
                <div class="section-copy">
                    {{ $contactSection?->description ?: "Tell me a bit about your day, and I'll let you know my availability, pricing options, and what I can suggest based on your plans. No pressure, no hard sell." }}
                </div>
            </div>

            <div class="contact-card">
                <div class="contact-meta">
                    <div>
                        <div class="contact-meta-label">Email</div>
                        <div class="contact-meta-value">
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        </div>
                    </div>

                    <div>
                        <div class="contact-meta-label">Phone</div>
                        <div class="contact-meta-value">
                            <a href="tel:{{ preg_replace('/\D+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                        </div>
                    </div>

                    <div>
                        <div class="contact-meta-label">Based In</div>
                        <div class="contact-meta-value">{{ $contactBasedIn }}</div>
                    </div>
                </div>

                <button
                    type="button"
                    class="contact-email-cta contact-form-toggle"
                    data-contact-form-toggle
                    aria-controls="contact-form-panel"
                    aria-expanded="{{ $isContactFormOpen ? 'true' : 'false' }}"
                >
                    Email About Your Date
                </button>
                <p class="contact-email-note">
                    Include your wedding date, venue, and anything you already know about timing.
                    You can also email directly if you prefer.
                </p>

                <div
                    id="contact-form-panel"
                    class="contact-form-wrap{{ $isContactFormOpen ? '' : ' is-collapsed' }}"
                    data-contact-form-panel
                >
                    @include('partials.contact-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var toggle = document.querySelector('[data-contact-form-toggle]');
            var panel = document.querySelector('[data-contact-form-panel]');

            if (!toggle || !panel || toggle.dataset.bound === '1') {
                return;
            }

            toggle.dataset.bound = '1';

            toggle.addEventListener('click', function () {
                var isCollapsed = panel.classList.contains('is-collapsed');
                panel.classList.toggle('is-collapsed', !isCollapsed);
                toggle.setAttribute('aria-expanded', isCollapsed ? 'true' : 'false');
            });
        })();
    </script>
</section>
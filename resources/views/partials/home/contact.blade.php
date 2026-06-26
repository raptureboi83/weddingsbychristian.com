<section id="contact" class="section">
    <div class="container container-narrow">
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

                @include('partials.contact-form')
            </div>
        </div>
    </div>
</section>
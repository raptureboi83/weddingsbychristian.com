<section id="testimonials" class="section section-alt-2">
    <div class="container">
        <div class="section-heading section-heading-centered section-heading-large">
            <div class="eyebrow">{{ $testimonialsSection?->eyebrow ?: 'Kind Words' }}</div>
            <h2 class="section-title">{{ $testimonialsSection?->title ?: 'What Clients Are Saying' }}</h2>
            @if ($testimonialsSection?->description)
                <div class="section-copy section-copy-centered">{{ $testimonialsSection->description }}</div>
            @endif
        </div>

        <div class="testimonial-grid">
            @foreach ($testimonials as $testimonial)
                <article class="testimonial-card">
                    <blockquote>&ldquo;{{ $testimonial->quote }}&rdquo;</blockquote>

                    <div class="testimonial-author">
                        <strong>{{ $testimonial->couple_names }}</strong>
                        @if ($testimonial->source_label)
                            <span>{{ $testimonial->source_label }}</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="center-link">
            <a href="{{ route('testimonials.index') }}">
                <span>See More Testimonials</span>
                <i></i>
            </a>
        </div>
    </div>
</section>
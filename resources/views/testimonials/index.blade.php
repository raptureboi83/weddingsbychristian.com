@extends('layouts.site')

@section('title', 'Testimonials — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('content')
<main class="page-top-spacing">
    <section class="page-hero">
        <div class="page-hero-glow"></div>
        <div class="container">
            <div class="page-hero-grid">
                <div>
                    <div class="eyebrow">Kind Words</div>
                    <h1 class="page-title">What Clients Are Saying</h1>
                </div>
                <div class="side-card">
                    <div class="side-card-title">Work with me</div>
                    <div class="side-card-copy">
                        If you want a wedding film that feels personal and beautifully put together,
                        I'd love to hear from you.
                    </div>
                    <div class="side-card-actions">
                        <a href="{{ route('home') }}" class="button-primary">Back to home</a>
                        <a href="{{ route('contact.index') }}" class="button-secondary">Reach out</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="testimonial-grid">
                @forelse ($testimonials as $testimonial)
                    <article class="testimonial-card">
                        <blockquote>&ldquo;{{ $testimonial->quote }}&rdquo;</blockquote>
                        <div class="testimonial-author">
                            <strong>{{ $testimonial->couple_names }}</strong>
                            @if ($testimonial->source_label)
                                <span>{{ $testimonial->source_label }}</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <p>No testimonials yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection

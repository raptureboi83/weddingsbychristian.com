@extends('layouts.site')

@section('title', 'Testimonials — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('page_css', asset('css/home.css') . '?v=' . @filemtime(public_path('css/home.css')))

@section('content')
<main>
    <section id="testimonials" class="section section-alt-2">
        <div class="container container-narrow">
            <div class="section-heading section-heading-centered section-heading-spaced">
                <div class="eyebrow">Kind Words</div>
                <h1 class="section-title">What Clients Are Saying</h1>
            </div>

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

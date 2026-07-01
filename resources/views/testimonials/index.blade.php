@extends('layouts.site')

@section('title', 'Testimonials — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('page_css', asset('css/home.css') . '?v=' . @filemtime(public_path('css/home.css')))

@push('styles')
<style>
    .page-testimonials .testimonial-grid-full {
        display: grid;
        grid-template-columns: 1fr;
        gap: 40px;
    }

    @media (min-width: 1024px) {
        .page-testimonials .testimonial-grid-full {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 32px;
        }
    }

    .page-testimonials .testimonial-card-horizontal blockquote {
        max-height: 180px;
        overflow-y: auto;
        padding-right: 8px;
        scrollbar-width: thin;
        scrollbar-color: var(--border-soft) transparent;
        font-size: 15px;
        line-height: 22px;
    }

    .page-testimonials .testimonial-card-horizontal blockquote::-webkit-scrollbar {
        width: 4px;
    }

    .page-testimonials .testimonial-card-horizontal blockquote::-webkit-scrollbar-thumb {
        background: var(--border-soft);
        border-radius: 2px;
    }

    .page-testimonials .testimonial-card-horizontal .testimonial-author {
        margin-top: 24px;
        padding-top: 16px;
    }
</style>
@endpush

@section('content')
<main>
    <section id="testimonials" class="section section-alt-2 page-testimonials">
        <div class="container container-narrow">
            <div class="section-heading section-heading-centered section-heading-spaced">
                <div class="eyebrow">{{ $testimonialsSection?->eyebrow ?: 'Kind Words' }}</div>
                <h1 class="section-title">{{ $testimonialsSection?->title ?: 'What Clients Are Saying' }}</h1>
            </div>

            @if ($testimonialsSection?->description)
                <div class="section-copy section-copy-centered">{{ $testimonialsSection->description }}</div>
            @endif

            <div class="testimonial-grid testimonial-grid-full">
                @forelse ($testimonials as $testimonial)
                    <article class="testimonial-card testimonial-card-horizontal">
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

@extends('layouts.site')

@section('title', 'Films — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))
@section('page_css', asset('css/films-index.css') . '?v=' . @filemtime(public_path('css/films-index.css')))

@section('content')
@php
    $useCmsSection = (bool) ($filmsSection->is_published ?? true);



    $heroEyebrow = $useCmsSection && filled($filmsSection->eyebrow)
        ? $filmsSection->eyebrow
        : 'SELECTED WORK';

    $heroTitle = $useCmsSection && filled($filmsSection->title)
        ? $filmsSection->title
        : 'Featured Films';

    $heroCopy = $useCmsSection && filled($filmsSection->description)
        ? $filmsSection->description
        : 'These are real wedding stories, captured quietly and edited with intention.
Some are emotional, some are joyful, some are both, but all of them are meant
to feel honest and easy to come back to.';

    $ctaTitle = $useCmsSection && filled($filmsSection->cta_title)
        ? $filmsSection->cta_title
        : 'Looking for your own film?';

    $ctaCopy = $useCmsSection && filled($filmsSection->cta_copy)
        ? $filmsSection->cta_copy
        : 'If you want a wedding film that feels personal, calm, and beautifully put together,
this is the best place to start.';

    $ctaPrimaryLabel = $useCmsSection && filled($filmsSection->cta_primary_label)
        ? $filmsSection->cta_primary_label
        : 'Back to home';

    $ctaPrimaryUrl = $useCmsSection && filled($filmsSection->cta_primary_url)
        ? $filmsSection->cta_primary_url
        : route('home');

    $ctaSecondaryLabel = $useCmsSection && filled($filmsSection->cta_secondary_label)
        ? $filmsSection->cta_secondary_label
        : 'Reach out';

    $ctaSecondaryUrl = $useCmsSection && filled($filmsSection->cta_secondary_url)
        ? $filmsSection->cta_secondary_url
        : route('contact.index');
@endphp

<main class="page-top-spacing">
    <section class="page-hero">
        <div class="page-hero-glow"></div>

        <div class="container">
            <div class="page-hero-grid">
                <div>
                    <div class="eyebrow">{{ $heroEyebrow }}</div>
                    <h1 class="page-title">{{ $heroTitle }}</h1>
                    <div class="page-copy">{!! nl2br(e($heroCopy)) !!}</div>
                </div>

                <div class="side-card">
                    <div class="side-card-title">{{ $ctaTitle }}</div>

                    <div class="side-card-copy">{!! nl2br(e($ctaCopy)) !!}</div>

                    <div class="side-card-actions">
                        <a href="{{ $ctaPrimaryUrl }}" class="button-primary">{{ $ctaPrimaryLabel }}</a>
                        <a href="{{ $ctaSecondaryUrl }}" class="button-secondary">{{ $ctaSecondaryLabel }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="films-section">
        <div class="container">

            @php
                $allFilms = $featuredFilms->concat($archiveFilms)->unique('id')->values();
            @endphp

            <div class="films-grid">
                @foreach ($allFilms as $film)
                    <a href="{{ route('films.show', $film) }}" class="films-card-link">
                        <div class="film-card-image">
                            @if ($film->thumbnail_path)
                                <img
                                    src="{{ asset('storage/' . $film->thumbnail_path) }}"
                                    alt="{{ $film->thumbnail_alt ?: $film->title }}"
                                >
                            @endif
                        </div>

                        <div class="film-card-body">
                            <h3 class="film-card-title">{{ $film->title }}</h3>

                            @if ($film->wedding_date || $film->venue || $film->location)
                                <div class="film-meta">
                                    {{ collect([
                                        optional($film->wedding_date)->format('F j, Y'),
                                        $film->venue,
                                        $film->location,
                                    ])->filter()->implode(' • ') }}
                                </div>
                            @endif

                            @if ($film->description)
                                <div class="film-blurb">{{ $film->description }}</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection

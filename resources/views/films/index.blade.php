@extends('layouts.site')

@section('title', 'Films — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))
@section('page_css', asset('css/films-index.css') . '?v=' . @filemtime(public_path('css/films-index.css')))

@section('content')
<main class="page-top-spacing">
    <section class="page-hero">
        <div class="page-hero-glow"></div>

        <div class="container">
            <div class="page-hero-grid">
                <div>
                    <div class="eyebrow">Films</div>
                    <h1 class="page-title">Wedding films that feel true to the people in them</h1>
                    <div class="page-copy">
                        These are real wedding stories, captured quietly and edited with intention.
                        Some are emotional, some are joyful, some are both, but all of them are meant
                        to feel honest and easy to come back to.
                    </div>
                </div>

                <div class="side-card">
                    <div class="side-card-title">Looking for your own film?</div>

                    <div class="side-card-copy">
                        If you want a wedding film that feels personal, calm, and beautifully put together,
                        this is the best place to start.
                    </div>

                    <div class="side-card-actions">
                        <a href="{{ route('home') }}" class="button-primary">Back to home</a>
                        <a href="{{ route('contact.index') }}" class="button-secondary">Reach out</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="films-section">
        <div class="container">
            <div class="films-heading">
                <div class="eyebrow">Cinematography</div>
                <h2 class="section-title">All Films</h2>
            </div>

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
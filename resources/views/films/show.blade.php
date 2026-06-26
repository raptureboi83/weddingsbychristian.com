@extends('layouts.site')

@section('title', $film->title . ' — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))
@section('page_css', asset('css/films-show.css'))

@section('content')
<main class="film-detail-wrap">
    <div class="container film-detail-container">
        <a href="{{ route('films.index') }}" class="back-link">Back to films</a>

        <div class="film-detail-grid">
            <div class="film-detail-main">
                <div class="film-player">
                    <video
                        src="{{ asset('storage/' . $film->video_path) }}"
                        @if ($film->thumbnail_path) poster="{{ asset('storage/' . $film->thumbnail_path) }}" @endif
                        controls
                        playsinline
                    ></video>
                </div>
            </div>

            <aside>
                <div class="film-detail-label">Wedding Film</div>
                <h1 class="film-detail-title">{{ $film->title }}</h1>

                @if ($film->wedding_date || $film->location)
                    <div class="film-detail-meta">
                        {{ collect([
                            optional($film->wedding_date)->format('F j, Y'),
                            $film->location,
                        ])->filter()->implode(' • ') }}
                    </div>
                @endif

                @if ($film->description)
                    <div class="film-detail-copy">{{ $film->description }}</div>
                @endif
            </aside>
        </div>
    </div>
</main>
@endsection
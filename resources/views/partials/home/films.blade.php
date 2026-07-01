<section id="films" class="section section-alt-3">
    <div class="container">
        <div class="section-heading section-heading-spaced">
            <div class="eyebrow">{{ $filmsSection?->eyebrow ?: 'Selected Work' }}</div>
            <h2 class="section-title">{{ $filmsSection?->title ?: 'Featured Films' }}</h2>
            @if ($filmsSection?->description)
                <div class="section-copy">{{ $filmsSection->description }}</div>
            @endif
        </div>

        <div class="film-grid">
            @foreach ($films as $film)
                <a href="{{ route('films.show', $film) }}" class="film-card-link">
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

                        @if ($film->wedding_date || $film->location)
                            <div class="film-card-meta">
                                {{ collect([
                                    optional($film->wedding_date)->format('F j, Y'),
                                    $film->location,
                                ])->filter()->implode(' • ') }}
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="center-link">
            <a href="{{ route('films.index') }}">
                <span>View All Films</span>
                <i></i>
            </a>
        </div>
    </div>
</section>
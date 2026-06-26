<section id="hero" class="hero-section">
    @if ($heroMedia)
        <div class="hero-media">
            @if (\Illuminate\Support\Str::endsWith(strtolower($heroMedia), ['.mp4', '.webm', '.ogg']))
                <video autoplay muted loop playsinline>
                    <source src="{{ $heroMedia }}">
                </video>
            @else
                <img src="{{ $heroMedia }}" alt="{{ $heroTitle }}">
            @endif
        </div>
    @endif

    <div class="hero-overlay"></div>

    <div class="container hero-content">
        <div class="hero-grid">
            <div class="hero-text">
                <div class="hero-label">{{ $heroDescription }}</div>
                <h1 class="hero-title">{{ $heroTitle }}</h1>
            </div>

            <a href="{{ route('films.index') }}" class="hero-cta">
                <span class="hero-cta-text">See the films</span>
                <span class="hero-cta-icon">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                        <path d="M3 2L9 6L3 10V2Z" fill="currentColor" />
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>
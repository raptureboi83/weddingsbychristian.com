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
        <div class="hero-text">
            @if (filled($heroEyebrow ?? null))
                <div class="hero-label">{{ $heroEyebrow }}</div>
            @endif

            <h1 class="hero-title">{{ $heroTitle }}</h1>

            @if (filled($heroDescription ?? null))
                <p class="hero-copy">{{ $heroDescription }}</p>
            @endif
        </div>
    </div>
</section>
<section id="hero" class="hero-section">
    @if ($heroMedia)
        <div class="hero-media">
            @if (\Illuminate\Support\Str::endsWith(strtolower($heroMedia), ['.mp4', '.webm', '.ogg']))
                <video autoplay muted loop playsinline data-hero-video @if ($heroFallback) data-hero-fallback-src="{{ $heroFallback }}" @endif>
                    <source src="{{ $heroMedia }}">
                </video>
            @else
                <img src="{{ $heroMedia }}" alt="{{ $heroTitle }}">
            @endif
        </div>
    @elseif ($heroFallback)
        <div class="hero-media">
            <img src="{{ $heroFallback }}" alt="{{ $heroTitle }}">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var video = document.querySelector('[data-hero-video]');
        if (!video) return;

        var fallbackSrc = video.getAttribute('data-hero-fallback-src');
        if (!fallbackSrc) return;

        video.addEventListener('playing', function () {
            video.removeAttribute('data-hero-fallback-src');
        });

        video.addEventListener('error', function () {
            var img = document.createElement('img');
            img.src = fallbackSrc;
            img.alt = '';
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            video.parentNode.insertBefore(img, video);
            video.style.display = 'none';
        });
    });
</script>
@endpush
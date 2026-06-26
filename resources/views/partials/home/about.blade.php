<section id="about" class="section section-alt">
    <div class="container two-col">
        <div>
            <div class="eyebrow">{{ $aboutSection?->eyebrow ?: 'About' }}</div>
            <h2 class="section-title">{{ $aboutTitle }}</h2>
            <div class="section-copy section-copy-preline">{{ $aboutDescription }}</div>
        </div>

        <div class="about-media">
            @if ($aboutSection?->media_type === 'video' && $aboutVideo)
                <video controls>
                    <source src="{{ $aboutVideo }}">
                </video>
            @elseif ($aboutImage)
                <img src="{{ $aboutImage }}" alt="{{ $aboutTitle }}">
            @else
                <div class="about-media-fallback" aria-hidden="true"></div>
            @endif
        </div>
    </div>
</section>
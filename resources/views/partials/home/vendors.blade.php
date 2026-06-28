<section id="vendors" class="section section-alt-3">
    <div class="container container-narrow">
        <div class="section-heading section-heading-centered section-heading-spaced">
            <div class="eyebrow">{{ $vendorsSection?->eyebrow ?: 'Partners' }}</div>
            <h2 class="section-title">{{ $vendorsSection?->title ?: 'Our Preferred Vendors' }}</h2>
            @if ($vendorsSection?->description)
                <div class="section-copy section-copy-centered">{{ $vendorsSection->description }}</div>
            @else
                <div class="section-copy section-copy-centered">
                    If you would like to connect with anyone, please click their name for a website.
                </div>
            @endif
        </div>

        @foreach ($vendorCategories as $category)
            @if ($category->vendors->isNotEmpty())
                <div class="vendor-category">
                    <h3 class="vendor-category-title">{{ $category->name }}</h3>

                    <div class="vendor-grid">
                        @foreach ($category->vendors as $vendor)
                            <div class="vendor-card">
                                <div class="vendor-name">
                                    @if ($vendor->website_url)
                                        <a href="{{ $vendor->website_url }}" target="_blank" rel="noopener noreferrer">
                                            {{ $vendor->name }}
                                        </a>
                                    @else
                                        {{ $vendor->name }}
                                    @endif
                                </div>

                                @if ($vendor->contact_phone)
                                    <div class="vendor-phone">{{ $vendor->contact_phone }}</div>
                                @endif

                                @if ($vendor->description)
                                    <div class="vendor-copy">{{ $vendor->description }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="center-link">
            <a href="{{ route('vendors.index') }}">
                <span>See More Vendors</span>
                <i></i>
            </a>
        </div>
    </div>
</section>
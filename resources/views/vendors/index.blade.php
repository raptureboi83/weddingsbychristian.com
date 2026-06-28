@extends('layouts.site')

@section('title', 'Vendors — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('content')
<main class="page-top-spacing">
    <section class="page-hero">
        <div class="page-hero-glow"></div>
        <div class="container">
            <div class="page-hero-grid">
                <div>
                    <div class="eyebrow">Partners</div>
                    <h1 class="page-title">Our Preferred Vendors</h1>
                    <div class="page-copy">
                        If you would like to connect with anyone, please click their name for a website.
                    </div>
                </div>
                <div class="side-card">
                    <div class="side-card-title">Looking for vendors?</div>
                    <div class="side-card-copy">
                        These are vendors we trust and love working with.
                    </div>
                    <div class="side-card-actions">
                        <a href="{{ route('home') }}" class="button-primary">Back to home</a>
                        <a href="{{ route('contact.index') }}" class="button-secondary">Reach out</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container container-narrow">
            @forelse ($vendorCategories as $category)
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
            @empty
                <p>No vendors listed yet.</p>
            @endforelse
        </div>
    </section>
</main>
@endsection

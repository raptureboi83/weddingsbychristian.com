@extends('layouts.site')

@section('title', 'Vendors — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('content')
<main>
    <section id="vendors" class="section section-alt-3">
        <div class="container container-narrow">
            <div class="section-heading section-heading-centered section-heading-spaced">
                <div class="eyebrow">Partners</div>
                <h1 class="section-title">Our Preferred Vendors</h1>
                <div class="section-copy section-copy-centered">
                    If you would like to connect with anyone, please click their name for a website.
                </div>
            </div>

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

@extends('layouts.site')

@section('title', 'Vendors — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))

@section('page_css', asset('css/home.css') . '?v=' . @filemtime(public_path('css/home.css')))

@push('styles')
<style>
    .page-vendors .vendor-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 48px;
        align-items: start;
    }

    .page-vendors .vendor-sidebar {
        position: sticky;
        top: calc(var(--nav-offset) + 32px);
    }

    .page-vendors .vendor-filter-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .page-vendors .vendor-filter-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 10px 16px;
        border: 0;
        border-radius: 4px;
        background: transparent;
        color: var(--text-muted);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        line-height: 18px;
        letter-spacing: 0.01em;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .page-vendors .vendor-filter-btn:hover {
        background: rgba(255, 255, 255, 0.04);
        color: var(--warm-beige);
    }

    .page-vendors .vendor-filter-btn.is-active {
        background: rgba(255, 255, 255, 0.06);
        color: var(--warm-beige);
        font-weight: 500;
    }

    .page-vendors .vendor-category {
        margin-top: 0;
        display: block;
    }

    .page-vendors .vendor-category.is-hidden {
        display: none;
    }

    .page-vendors .vendor-category:not(.is-hidden) ~ .vendor-category:not(.is-hidden) {
        margin-top: 56px;
    }

    @media (max-width: 768px) {
        .page-vendors .vendor-layout {
            grid-template-columns: 1fr;
            gap: 32px;
        }

        .page-vendors .vendor-sidebar {
            position: static;
        }

        .page-vendors .vendor-filter-list {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 6px;
        }

        .page-vendors .vendor-filter-btn {
            width: auto;
            padding: 8px 14px;
            font-size: 12px;
            border: 1px solid var(--border-soft);
            border-radius: 4px;
        }

    .page-vendors .vendor-filter-btn.is-active {
        border-color: rgba(246, 245, 241, 0.3);
    }

    .page-vendors .vendor-card-header {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .page-vendors .vendor-logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        border-radius: 4px;
        flex-shrink: 0;
        background: rgba(255,255,255,0.04);
    }

    .page-vendors .vendor-card-header-text {
        flex: 1;
        min-width: 0;
    }

    .page-vendors .vendor-location {
        margin-top: 4px;
        font-size: 12px;
        color: var(--cool-gray);
        letter-spacing: 0.01em;
    }

    .page-vendors .vendor-email {
        margin-top: 4px;
        font-size: 12px;
        color: var(--cool-gray);
    }

    .page-vendors .vendor-email a {
        color: var(--cream);
    }

    .page-vendors .vendor-email a:hover {
        text-decoration: underline;
    }

    .page-vendors .vendor-socials {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--border-softer);
        display: flex;
        gap: 12px;
    }

    .page-vendors .vendor-social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        color: var(--text-faint);
        transition: color 0.2s ease, background 0.2s ease;
    }

    .page-vendors .vendor-social-link:hover {
        color: var(--warm-beige);
        background: rgba(255,255,255,0.04);
    }

    .page-vendors .vendor-social-link svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
    }

    .page-vendors .vendor-card .vendor-copy {
        margin-top: 16px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('[data-vendor-filter]');
        const categories = document.querySelectorAll('[data-vendor-category]');

        if (!buttons.length || !categories.length) return;

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = this.getAttribute('data-vendor-filter');

                buttons.forEach(function (b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');

                if (target === 'all') {
                    categories.forEach(function (c) { c.classList.remove('is-hidden'); });
                } else {
                    categories.forEach(function (c) {
                        if (c.getAttribute('data-vendor-category') === target) {
                            c.classList.remove('is-hidden');
                        } else {
                            c.classList.add('is-hidden');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

@section('content')
<main>
    <section id="vendors" class="section section-alt-3 page-vendors">
        <div class="container">
            <div class="section-heading section-heading-centered section-heading-spaced">
                <div class="eyebrow">Partners</div>
                <h1 class="section-title">Our Preferred Vendors</h1>
                <div class="section-copy section-copy-centered">
                    If you would like to connect with anyone, please click their name for a website.
                </div>
            </div>

            <div class="vendor-layout">
                <aside class="vendor-sidebar">
                    <nav class="vendor-filter-list">
                        <button class="vendor-filter-btn is-active" data-vendor-filter="all">All Vendors</button>
                        @foreach ($vendorCategories as $category)
                            @if ($category->vendors->isNotEmpty())
                                <button class="vendor-filter-btn" data-vendor-filter="{{ $category->id }}">{{ $category->name }}</button>
                            @endif
                        @endforeach
                    </nav>
                </aside>

                <div class="vendor-content">
                    @forelse ($vendorCategories as $category)
                        @if ($category->vendors->isNotEmpty())
                            <div class="vendor-category" data-vendor-category="{{ $category->id }}">
                                <h3 class="vendor-category-title">{{ $category->name }}</h3>
                                <div class="vendor-grid">
                                    @foreach ($category->vendors as $vendor)
                                        <div class="vendor-card">
                                            @php
                                                $logoUrl = null;
                                                if (filled($vendor->logo_path)) {
                                                    if (\Illuminate\Support\Str::startsWith($vendor->logo_path, ['http://', 'https://', '//', 'data:'])) {
                                                        $logoUrl = $vendor->logo_path;
                                                    } elseif (\Illuminate\Support\Str::startsWith($vendor->logo_path, ['/'])) {
                                                        $logoUrl = $vendor->logo_path;
                                                    } else {
                                                        $logoUrl = asset('storage/' . ltrim($vendor->logo_path, '/'));
                                                    }
                                                }
                                            @endphp

                                            <div class="vendor-card-header">
                                                @if ($logoUrl)
                                                    <img class="vendor-logo" src="{{ $logoUrl }}" alt="{{ $vendor->name }}">
                                                @endif
                                                <div class="vendor-card-header-text">
                                                    <div class="vendor-name">
                                                        @if ($vendor->website_url)
                                                            <a href="{{ $vendor->website_url }}" target="_blank" rel="noopener noreferrer">
                                                                {{ $vendor->name }}
                                                            </a>
                                                        @else
                                                            {{ $vendor->name }}
                                                        @endif
                                                    </div>
                                                    @if ($vendor->location)
                                                        <div class="vendor-location">{{ $vendor->location }}</div>
                                                    @endif
                                                    @if ($vendor->contact_email)
                                                        <div class="vendor-email">{{ $vendor->contact_email }}</div>
                                                    @endif
                                                    @if ($vendor->contact_phone)
                                                        <div class="vendor-phone">{{ $vendor->contact_phone }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($vendor->description)
                                                <div class="vendor-copy">{{ $vendor->description }}</div>
                                            @endif

                                            @php
                                                $socials = [];
                                                if ($vendor->facebook_url) $socials['facebook'] = $vendor->facebook_url;
                                                if ($vendor->instagram_url) $socials['instagram'] = $vendor->instagram_url;
                                                if ($vendor->tiktok_url) $socials['tiktok'] = $vendor->tiktok_url;
                                                if ($vendor->youtube_url) $socials['youtube'] = $vendor->youtube_url;
                                                $socialPaths = [
                                                    'facebook' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
                                                    'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z',
                                                    'tiktok' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
                                                    'youtube' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
                                                ];
                                            @endphp

                                            @if ($socials)
                                                <div class="vendor-socials">
                                                    @foreach ($socials as $network => $url)
                                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="vendor-social-link" aria-label="{{ ucfirst($network) }}">
                                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="{{ $socialPaths[$network] }}"></path>
                                                            </svg>
                                                        </a>
                                                    @endforeach
                                                </div>
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
            </div>
        </div>
    </section>
</main>
@endsection

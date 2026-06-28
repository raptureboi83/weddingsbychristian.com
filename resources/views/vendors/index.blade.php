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
        display: block;
    }

    .page-vendors .vendor-category.is-hidden {
        display: none;
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
            </div>
        </div>
    </section>
</main>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $siteSettings->seo_meta_title ?: ($siteSettings->site_name ?: config('app.name', 'Laravel')) }}</title>

    @if ($siteSettings->seo_meta_description)
        <meta name="description" content="{{ $siteSettings->seo_meta_description }}">
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <div class="min-h-screen">
        <header class="border-b border-stone-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                <div>
                    <a href="/" class="text-lg font-semibold tracking-wide">
                        {{ $siteSettings->logo_text ?: $siteSettings->site_name ?: config('app.name', 'Studio') }}
                    </a>

                    @if ($siteSettings->site_tagline)
                        <p class="mt-1 text-sm text-stone-500">
                            {{ $siteSettings->site_tagline }}
                        </p>
                    @endif
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-md border border-stone-300 px-4 py-2 text-sm font-medium hover:bg-stone-100"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-md border border-stone-300 px-4 py-2 text-sm font-medium hover:bg-stone-100"
                            >
                                Log in
                            </a>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main>
            <section class="border-b border-stone-200 bg-white">
                <div class="mx-auto grid max-w-7xl gap-10 px-6 py-20 lg:grid-cols-12 lg:items-end">
                    <div class="lg:col-span-8">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">
                            {{ $heroSection?->eyebrow ?: ($siteSettings->contact_based_in ?: 'Wedding Films') }}
                        </p>

                        <h1 class="max-w-4xl text-4xl font-semibold tracking-tight text-stone-900 sm:text-5xl lg:text-6xl">
                            {{ $heroSection?->title ?: ($siteSettings->site_name ?: 'Wedding filmmaker') }}
                        </h1>

                        @if ($heroSection?->description || $siteSettings->site_tagline)
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-600">
                                {{ $heroSection?->description ?: $siteSettings->site_tagline }}
                            </p>
                        @endif
                    </div>

                    @if ($heroSection?->background_media_path)
                        <div class="lg:col-span-4">
                            <div class="overflow-hidden rounded-2xl border border-stone-200 bg-stone-100 shadow-sm">
                                <img
                                    src="{{ asset('storage/' . $heroSection->background_media_path) }}"
                                    alt="{{ $heroSection->title ?: 'Hero image' }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            @if ($aboutSection)
                <section class="border-b border-stone-200 bg-stone-50">
                    <div class="mx-auto max-w-7xl px-6 py-20">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div>
                                @if ($aboutSection->eyebrow)
                                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">
                                        {{ $aboutSection->eyebrow }}
                                    </p>
                                @endif

                                <h2 class="max-w-3xl text-4xl font-semibold tracking-tight text-stone-900 sm:text-5xl">
                                    {{ $aboutSection->title ?: 'About' }}
                                </h2>

                                @if ($aboutSection->description)
                                    <div class="mt-6 max-w-2xl whitespace-pre-line text-base leading-7 text-stone-700">
                                        {{ $aboutSection->description }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                @if ($aboutSection->media_type === 'video' && $aboutSection->video_path)
                                    <div class="overflow-hidden rounded-2xl border border-stone-200 bg-black shadow-sm">
                                        <video controls class="h-full w-full">
                                            <source src="{{ asset('storage/' . $aboutSection->video_path) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @elseif ($aboutSection->image_path)
                                    <div class="overflow-hidden rounded-2xl border border-stone-200 bg-stone-100 shadow-sm">
                                        <img
                                            src="{{ asset('storage/' . $aboutSection->image_path) }}"
                                            alt="{{ $aboutSection->title ?: 'About image' }}"
                                            class="h-full w-full object-cover"
                                        >
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($films->isNotEmpty())
                <section class="border-b border-stone-200 bg-stone-50">
                    <div class="mx-auto max-w-7xl px-6 py-16">
                        <div class="mb-10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">Films</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900">
                                Featured stories
                            </h2>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-3">
                            @foreach ($films as $film)
                                <article class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
                                    <div class="aspect-[4/3] bg-stone-200">
                                        @if ($film->thumbnail_path)
                                            <img
                                                src="{{ asset('storage/' . $film->thumbnail_path) }}"
                                                alt="{{ $film->thumbnail_alt ?: $film->title }}"
                                                class="h-full w-full object-cover"
                                            >
                                        @endif
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-stone-900">
                                            {{ $film->title }}
                                        </h3>

                                        @if ($film->location || $film->venue)
                                            <p class="mt-2 text-sm text-stone-500">
                                                {{ collect([$film->location, $film->venue])->filter()->implode(' · ') }}
                                            </p>
                                        @endif

                                        @if ($film->description)
                                            <p class="mt-4 text-sm leading-6 text-stone-700">
                                                {{ $film->description }}
                                            </p>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <section class="border-b border-stone-200 bg-white">
                <div class="mx-auto max-w-7xl px-6 py-20">
                    @if ($packagesSection)
                        @if ($packagesSection->eyebrow)
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">
                                {{ $packagesSection->eyebrow }}
                            </p>
                        @endif

                        <h2 class="max-w-3xl text-4xl font-semibold tracking-tight text-stone-900 sm:text-5xl">
                            {{ $packagesSection->title ?: 'Packages' }}
                        </h2>

                        @if ($packagesSection->description)
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-600">
                                {{ $packagesSection->description }}
                            </p>
                        @endif

                        @if ($packagesSection->intro)
                            <div class="mt-8 max-w-3xl whitespace-pre-line text-base leading-7 text-stone-700">
                                {{ $packagesSection->intro }}
                            </div>
                        @endif
                    @else
                        <h2 class="text-4xl font-semibold tracking-tight text-stone-900 sm:text-5xl">
                            Packages
                        </h2>
                    @endif
                </div>
            </section>

            <section class="bg-stone-50">
                <div class="mx-auto max-w-7xl px-6 py-16">
                    @if ($packages->isNotEmpty())
                        <div class="grid gap-8 lg:grid-cols-3">
                            @foreach ($packages as $package)
                                <article class="flex h-full flex-col rounded-2xl border border-stone-200 bg-white p-8 shadow-sm">
                                    <div class="mb-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <h3 class="text-2xl font-semibold text-stone-900">
                                                {{ $package->name }}
                                            </h3>

                                            @if ($package->is_featured)
                                                <span class="rounded-full bg-stone-900 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                                                    Featured
                                                </span>
                                            @endif
                                        </div>

                                        @if ($package->short_description)
                                            <p class="mt-4 text-sm leading-6 text-stone-600">
                                                {{ $package->short_description }}
                                            </p>
                                        @endif
                                    </div>

                                    @if ($package->starting_price || $package->price_label || $package->duration_label)
                                        <div class="mb-6 border-t border-stone-200 pt-6">
                                            @if (! is_null($package->starting_price))
                                                <p class="text-3xl font-semibold text-stone-900">
                                                    ${{ number_format((float) $package->starting_price, 2) }}
                                                </p>
                                            @endif

                                            @if ($package->price_label)
                                                <p class="mt-1 text-sm text-stone-500">
                                                    {{ $package->price_label }}
                                                </p>
                                            @endif

                                            @if ($package->duration_label)
                                                <p class="mt-2 text-sm text-stone-600">
                                                    {{ $package->duration_label }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($package->description)
                                        <p class="mb-6 text-sm leading-6 text-stone-700">
                                            {{ $package->description }}
                                        </p>
                                    @endif

                                    @if ($package->items->isNotEmpty())
                                        <ul class="mb-8 space-y-3 border-t border-stone-200 pt-6">
                                            @foreach ($package->items as $item)
                                                <li class="flex gap-3">
                                                    <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-stone-900"></span>
                                                    <div>
                                                        <p class="text-sm font-medium text-stone-900">
                                                            {{ $item->label }}
                                                        </p>

                                                        @if ($item->description)
                                                            <p class="mt-1 text-sm leading-6 text-stone-600">
                                                                {{ $item->description }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="mt-auto">
                                        <a
                                            href="{{ ($packagesSection && $packagesSection->cta_url) ? $packagesSection->cta_url : '#contact' }}"
                                            class="inline-flex rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-900 hover:bg-stone-100"
                                        >
                                            {{ ($packagesSection && $packagesSection->cta_label) ? $packagesSection->cta_label : 'Inquire now' }}
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-10 text-center">
                            <h3 class="text-xl font-semibold text-stone-900">No packages published yet</h3>
                            <p class="mt-3 text-sm text-stone-600">
                                Add active packages in Filament and they will appear here.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            @if ($testimonials->isNotEmpty())
                <section class="border-t border-stone-200 bg-white">
                    <div class="mx-auto max-w-7xl px-6 py-16">
                        <div class="mb-10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">Testimonials</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900">
                                Kind words from couples
                            </h2>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-3">
                            @foreach ($testimonials as $testimonial)
                                <article class="rounded-2xl border border-stone-200 bg-stone-50 p-8">
                                    <p class="text-base leading-7 text-stone-700">
                                        “{{ $testimonial->quote }}”
                                    </p>

                                    <div class="mt-6">
                                        <p class="text-sm font-semibold text-stone-900">
                                            {{ $testimonial->couple_names }}
                                        </p>

                                        @if ($testimonial->source_label)
                                            <p class="mt-1 text-sm text-stone-500">
                                                {{ $testimonial->source_label }}
                                            </p>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($vendorCategories->isNotEmpty())
                <section class="border-t border-stone-200 bg-stone-50">
                    <div class="mx-auto max-w-7xl px-6 py-16">
                        <div class="mb-10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">Vendors</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900">
                                Recommended partners
                            </h2>
                        </div>

                        <div class="space-y-10">
                            @foreach ($vendorCategories as $category)
                                @if ($category->vendors->isNotEmpty())
                                    <div>
                                        <div class="mb-5">
                                            <h3 class="text-2xl font-semibold text-stone-900">
                                                {{ $category->name }}
                                            </h3>

                                            @if ($category->description)
                                                <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                            @foreach ($category->vendors as $vendor)
                                                <article class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div>
                                                            <h4 class="text-lg font-semibold text-stone-900">
                                                                {{ $vendor->name }}
                                                            </h4>

                                                            @if ($vendor->location)
                                                                <p class="mt-1 text-sm text-stone-500">
                                                                    {{ $vendor->location }}
                                                                </p>
                                                            @endif
                                                        </div>

                                                        @if ($vendor->is_featured)
                                                            <span class="rounded-full bg-stone-900 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                                                                Featured
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if ($vendor->description)
                                                        <p class="mt-4 text-sm leading-6 text-stone-700">
                                                            {{ $vendor->description }}
                                                        </p>
                                                    @endif

                                                    <div class="mt-6 flex flex-wrap gap-3">
                                                        @if ($vendor->website_url)
                                                            <a
                                                                href="{{ $vendor->website_url }}"
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                class="inline-flex rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-900 hover:bg-stone-100"
                                                            >
                                                                Website
                                                            </a>
                                                        @endif

                                                        @if ($vendor->instagram_url)
                                                            <a
                                                                href="{{ $vendor->instagram_url }}"
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                class="inline-flex rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-900 hover:bg-stone-100"
                                                            >
                                                                Instagram
                                                            </a>
                                                        @endif
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($packageSharedBlocks->isNotEmpty())
                <section class="border-t border-stone-200 bg-white">
                    <div class="mx-auto max-w-5xl space-y-8 px-6 py-16">
                        @foreach ($packageSharedBlocks as $block)
                            <div class="rounded-2xl border border-stone-200 p-8">
                                @if ($block->title)
                                    <h3 class="text-2xl font-semibold text-stone-900">
                                        {{ $block->title }}
                                    </h3>
                                @endif

                                @if ($block->content)
                                    <div class="mt-4 whitespace-pre-line text-base leading-7 text-stone-700">
                                        {{ $block->content }}
                                    </div>
                                @endif

                                @if ($block->cta_label && $block->cta_url)
                                    <div class="mt-6">
                                        <a
                                            href="{{ $block->cta_url }}"
                                            class="inline-flex rounded-md bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700"
                                        >
                                            {{ $block->cta_label }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($contactSection)
                <section id="contact" class="border-t border-stone-200 bg-stone-100">
                    <div class="mx-auto max-w-5xl px-6 py-16">
                        @if ($contactSection->eyebrow)
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">
                                {{ $contactSection->eyebrow }}
                            </p>
                        @endif

                        <h2 class="text-3xl font-semibold tracking-tight text-stone-900">
                            {{ $contactSection->title ?: 'Contact' }}
                        </h2>

                        @if ($contactSection->description)
                            <p class="mt-4 max-w-2xl text-base leading-7 text-stone-700">
                                {{ $contactSection->description }}
                            </p>
                        @endif

                        <div class="mt-10 grid gap-8 lg:grid-cols-2">
                            <div class="rounded-2xl border border-stone-200 bg-white p-8">
                                <h3 class="text-xl font-semibold text-stone-900">
                                    {{ $contactSection->form_heading ?: 'Start the conversation' }}
                                </h3>

                                @if ($contactSection->form_description)
                                    <p class="mt-3 text-sm leading-6 text-stone-600">
                                        {{ $contactSection->form_description }}
                                    </p>
                                @endif

                                @if (session('status'))
                                    <div class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        Please fix the highlighted fields and try again.
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                                    @csrf

                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-stone-900">Name *</label>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            value="{{ old('name') }}"
                                            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            required
                                        >
                                        @error('name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="mb-2 block text-sm font-medium text-stone-900">Email *</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value="{{ old('email') }}"
                                            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            required
                                        >
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid gap-5 md:grid-cols-2">
                                        <div>
                                            <label for="phone" class="mb-2 block text-sm font-medium text-stone-900">Phone</label>
                                            <input
                                                id="phone"
                                                name="phone"
                                                type="text"
                                                value="{{ old('phone') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                            @error('phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="partner_name" class="mb-2 block text-sm font-medium text-stone-900">Partner name</label>
                                            <input
                                                id="partner_name"
                                                name="partner_name"
                                                type="text"
                                                value="{{ old('partner_name') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                            @error('partner_name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid gap-5 md:grid-cols-2">
                                        <div>
                                            <label for="wedding_date" class="mb-2 block text-sm font-medium text-stone-900">Wedding date</label>
                                            <input
                                                id="wedding_date"
                                                name="wedding_date"
                                                type="date"
                                                value="{{ old('wedding_date') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                            @error('wedding_date')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="budget_range" class="mb-2 block text-sm font-medium text-stone-900">Budget range</label>
                                            <input
                                                id="budget_range"
                                                name="budget_range"
                                                type="text"
                                                value="{{ old('budget_range') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                        @error('budget_range')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        </div>
                                    </div>

                                    <div class="grid gap-5 md:grid-cols-2">
                                        <div>
                                            <label for="location" class="mb-2 block text-sm font-medium text-stone-900">Location</label>
                                            <input
                                                id="location"
                                                name="location"
                                                type="text"
                                                value="{{ old('location') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                            @error('location')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="venue" class="mb-2 block text-sm font-medium text-stone-900">Venue</label>
                                            <input
                                                id="venue"
                                                name="venue"
                                                type="text"
                                                value="{{ old('venue') }}"
                                                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            >
                                            @error('venue')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <p class="mb-3 block text-sm font-medium text-stone-900">Services requested</p>

                                        @php
                                            $selectedServices = old('services_requested', []);
                                        @endphp

                                        <div class="grid gap-3 sm:grid-cols-2">
                                            @foreach (['Wedding Film', 'Teaser Film', 'Speeches', 'Drone Coverage', 'Full Ceremony Edit', 'Additional Coverage'] as $service)
                                                <label class="flex items-center gap-3 rounded-md border border-stone-200 px-4 py-3 text-sm text-stone-700">
                                                    <input
                                                        type="checkbox"
                                                        name="services_requested[]"
                                                        value="{{ $service }}"
                                                        @checked(in_array($service, $selectedServices))
                                                        class="rounded border-stone-300 text-stone-900 focus:ring-stone-900"
                                                    >
                                                    <span>{{ $service }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        @error('services_requested')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror

                                        @error('services_requested.*')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="how_did_you_hear" class="mb-2 block text-sm font-medium text-stone-900">How did you hear about me?</label>
                                        <input
                                            id="how_did_you_hear"
                                            name="how_did_you_hear"
                                            type="text"
                                            value="{{ old('how_did_you_hear') }}"
                                            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                        >
                                        @error('how_did_you_hear')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="message" class="mb-2 block text-sm font-medium text-stone-900">Message *</label>
                                        <textarea
                                            id="message"
                                            name="message"
                                            rows="6"
                                            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
                                            required
                                        >{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <button
                                            type="submit"
                                            class="inline-flex rounded-md bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700"
                                        >
                                            Send inquiry
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="rounded-2xl border border-stone-200 bg-white p-8">
                                <h3 class="text-xl font-semibold text-stone-900">
                                    {{ $contactSection->info_heading ?: 'Details' }}
                                </h3>

                                @if ($contactSection->info_description)
                                    <p class="mt-3 text-sm leading-6 text-stone-600">
                                        {{ $contactSection->info_description }}
                                    </p>
                                @endif

                                <div class="mt-6 space-y-3 text-sm text-stone-700">
                                    @if ($siteSettings->contact_email)
                                        <p>Email: {{ $siteSettings->contact_email }}</p>
                                    @endif

                                    @if ($siteSettings->contact_phone)
                                        <p>Phone: {{ $siteSettings->contact_phone }}</p>
                                    @endif

                                    @if ($siteSettings->contact_based_in)
                                        <p>Based in: {{ $siteSettings->contact_based_in }}</p>
                                    @endif
                                </div>

                                @if ($contactSection->cta_label && $contactSection->cta_url)
                                    <div class="mt-6">
                                        <a
                                            href="{{ $contactSection->cta_url }}"
                                            class="inline-flex rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-900 hover:bg-stone-100"
                                        >
                                            {{ $contactSection->cta_label }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </main>

        <footer class="border-t border-stone-200 bg-white">
            <div class="mx-auto max-w-7xl px-6 py-8">
                <p class="text-sm text-stone-500">
                    {{ $siteSettings->footer_copyright_text ?: 'All rights reserved.' }}
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
bash-5.1$
</query>
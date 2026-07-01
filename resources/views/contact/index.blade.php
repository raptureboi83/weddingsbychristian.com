@extends('layouts.site')

@section('title', 'Contact — ' . ($siteSettings->site_name ?: 'Weddings By Christian'))
@section('page_css', asset('css/contact-page.css'))

@php
    $title = $contactSection?->title ?: 'Let’s talk about your wedding film';
    $description = $contactSection?->description ?: 'Share a few details about your day and I’ll let you know availability, pricing, and what I’d suggest based on your plans.';
    $contactEmail = $siteSettings->contact_email ?: 'better2b@rogers.com';
    $contactPhone = $siteSettings->contact_phone ?: '(705) 734-9971';
    $contactBasedIn = $siteSettings->contact_based_in ?: 'Barrie, Ontario, Canada';
@endphp

@section('content')
<main class="page-top-spacing contact-page-main">
    <section class="contact-page-hero">
        <div class="contact-page-glow"></div>

        <div class="container contact-page-container">
            <div class="contact-page-intro">
                <div class="eyebrow">{{ $contactSection?->eyebrow ?: 'Contact' }}</div>
                <h1 class="page-title contact-page-title">{{ $title }}</h1>
                <div class="page-copy contact-page-copy">
                    {{ $description }}
                </div>
            </div>
        </div>
    </section>

    <section class="contact-page-wrap">
        <div class="container contact-page-container">
            <div class="contact-page-card">
                <div class="contact-info-grid">
                    <div>
                        <div class="contact-info-label">Email</div>
                        <div class="contact-info-value">
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        </div>
                    </div>

                    <div>
                        <div class="contact-info-label">Phone</div>
                        <div class="contact-info-value">
                            <a href="tel:{{ preg_replace('/\D+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                        </div>
                    </div>

                    <div>
                        <div class="contact-info-label">Based In</div>
                        <div class="contact-info-value">{{ $contactBasedIn }}</div>
                    </div>
                </div>

                @include('partials.contact-form')
            </div>
        </div>
    </section>
</main>
@endsection
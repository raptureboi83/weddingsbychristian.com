<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', $siteSettings->seo_meta_title ?: ($siteSettings->site_name ?: 'Weddings By Christian'))</title>

    @if ($siteSettings->seo_meta_description)
        <meta name="description" content="{{ $siteSettings->seo_meta_description }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    />

    <style>
        :root {
            --cream: #e3dacb;
            --warm-beige: #f6f5f1;
            --cool-gray: #6e7381;
            --dark-bg: #0a090e;
            --elevated-bg: #131218;
            --border-soft: rgba(255, 255, 255, 0.10);
            --border-softer: rgba(255, 255, 255, 0.05);
            --text-muted: #8a8a8a;
            --text-faint: #8a8a8a;
            --success-bg: rgba(34, 197, 94, 0.10);
            --success-border: rgba(34, 197, 94, 0.30);
            --error-text: #fca5a5;
            --nav-offset: 80px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: var(--nav-offset);
        }

        body {
            margin: 0;
            background: var(--dark-bg);
            color: var(--warm-beige);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body.mobile-menu-open {
            overflow: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            margin: 0;
        }

        p {
            margin: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        img,
        video {
            display: block;
            max-width: 100%;
        }

        #hero,
        #about,
        #films,
        #packages,
        #testimonials,
        #vendors,
        #contact {
            scroll-margin-top: var(--nav-offset);
        }

        .site-shell {
            min-height: 100vh;
            background: var(--dark-bg);
            color: var(--warm-beige);
            overflow-x: hidden;
        }

        .container {
            width: min(100%, 1280px);
            margin: 0 auto;
            padding-left: 24px;
            padding-right: 24px;
        }

        .section {
            padding-top: 96px;
            padding-bottom: 96px;
        }

        .section-alt {
            background: #111111;
        }

        .section-alt-2 {
            background: #181818;
        }

        .section-alt-3 {
            background: #202020;
        }

        .eyebrow {
            margin-bottom: 16px;
            font-size: 12px;
            line-height: 16px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--cool-gray);
        }

        .section-title {
            font-size: 36px;
            line-height: 40px;
            letter-spacing: normal;
        }

        .section-copy {
            margin-top: 24px;
            max-width: 720px;
            color: var(--text-muted);
            font-size: 16px;
            line-height: 24px;
        }

        .site-nav {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 1000;
            min-height: var(--nav-offset);
            background: rgba(10, 9, 14, 0.82);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .site-nav-inner {
            min-height: var(--nav-offset);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .site-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
        }

        .site-brand-text {
            font-size: 16px;
            font-weight: 400;
            letter-spacing: normal;
            text-transform: none;
            color: var(--warm-beige);
        }

        .site-brand img {
            max-height: 56px;
            width: auto;
            object-fit: contain;
        }

        .site-nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .site-nav-links a {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 16px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--text-faint);
            transition: color 0.25s ease;
        }

        .site-nav-links a:hover,
        .site-nav-links a.is-active {
            color: var(--warm-beige);
        }

        .site-nav-socials {
            padding-left: 20px;
            border-left: 1px solid rgba(255, 255, 255, 0.10);
        }

        .social-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-faint);
            transition: color 0.25s ease;
        }

        .social-links a:hover {
            color: var(--warm-beige);
        }

        .social-links svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        .site-nav-toggle {
            display: none;
            position: relative;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--warm-beige);
        }

        .site-nav-toggle span {
            position: absolute;
            left: 10px;
            width: 24px;
            height: 1px;
            background: currentColor;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        .site-nav-toggle span:nth-child(1) {
            top: 14px;
        }

        .site-nav-toggle span:nth-child(2) {
            top: 21px;
        }

        .site-nav-toggle span:nth-child(3) {
            top: 28px;
        }

        .site-mobile-menu {
            display: none;
            position: fixed;
            top: var(--nav-offset);
            left: 0;
            right: 0;
            z-index: 999;
            padding: 24px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
            background: rgba(10, 9, 14, 0.96);
            backdrop-filter: blur(12px);
        }

        .site-mobile-menu[hidden] {
            display: none !important;
        }

        .site-mobile-menu-links {
            display: grid;
            gap: 20px;
        }

        .site-mobile-menu-links a {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 16px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--warm-beige);
        }

        .site-mobile-socials {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
        }

        @media (max-width: 900px) {
            .site-nav-links {
                display: none;
            }

            .site-nav-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .site-mobile-menu:not([hidden]) {
                display: block;
            }
        }

        @media (min-width: 901px) {
            .site-mobile-menu {
                display: none !important;
            }
        }
    </style>

    @if (trim($__env->yieldContent('page_css')))
        <link rel="stylesheet" href="@yield('page_css')">
    @endif
</head>
<body>
    <div class="site-shell">
        @include('partials.site-header', ['siteSettings' => $siteSettings])

        <main>
            @yield('content')
        </main>
    </div>

    @if (trim($__env->yieldContent('page_js')))
        @yield('page_js')
    @endif
</body>
</html>
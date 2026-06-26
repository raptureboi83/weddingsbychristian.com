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
            --text-muted: rgba(246, 245, 241, 0.72);
            --text-faint: rgba(246, 245, 241, 0.45);
            --success-bg: rgba(34, 197, 94, 0.10);
            --success-border: rgba(34, 197, 94, 0.30);
            --error-text: #fca5a5;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 96px;
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
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--cool-gray);
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 4.5rem);
            line-height: 0.98;
            letter-spacing: 0.02em;
        }

        .section-copy {
            margin-top: 24px;
            max-width: 720px;
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .site-nav {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 1000;
            min-height: 80px;
            background: rgba(10, 9, 14, 0.82);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .site-nav-inner {
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .site-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
        }

        .site-brand-text {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
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
            gap: 32px;
        }

        .site-nav-links a {
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-faint);
            transition: color 0.25s ease;
        }

        .site-nav-links a:hover,
        .site-nav-links a.is-active {
            color: var(--warm-beige);
        }

        .site-nav-socials {
            padding-left: 16px;
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
            width: 18px;
            height: 18px;
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
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(10, 9, 14, 0.97);
            backdrop-filter: blur(12px);
        }

        .site-mobile-menu[hidden] {
            display: none;
        }

        .site-mobile-menu-links {
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding: 24px 0 20px;
        }

        .site-mobile-menu-links a {
            font-size: 14px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--warm-beige);
        }

        .site-mobile-socials {
            padding-top: 16px;
            padding-bottom: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .card {
            border: 1px solid var(--border-soft);
            background: rgba(19, 18, 24, 0.6);
        }

        .button-primary,
        .button-secondary,
        .button-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 24px;
            font-size: 14px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: all 0.25s ease;
        }

        .button-primary {
            background: var(--warm-beige);
            color: var(--dark-bg);
        }

        .button-primary:hover {
            background: var(--cream);
        }

        .button-secondary {
            border: 1px solid var(--border-soft);
            color: var(--warm-beige);
        }

        .button-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .button-ghost {
            color: var(--warm-beige);
            padding-left: 0;
            padding-right: 0;
        }

        .page-top-spacing {
            padding-top: 80px;
        }

        .footer-bar {
            border-top: 1px solid var(--border-soft);
            padding-top: 48px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            align-items: center;
        }

        .footer-grid > div:nth-child(2) {
            text-align: center;
        }

        .footer-grid > div:nth-child(3) {
            justify-self: start;
        }

        @media (min-width: 900px) {
            .container {
                padding-left: 40px;
                padding-right: 40px;
            }

            .section {
                padding-top: 128px;
                padding-bottom: 128px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr 1fr;
            }

            .footer-grid > div:nth-child(1) {
                text-align: left;
            }

            .footer-grid > div:nth-child(3) {
                justify-self: end;
            }
        }

        @media (max-width: 899px) {
            .site-nav-links {
                display: none;
            }

            .site-nav-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>

    @hasSection('page_css')
        <link rel="stylesheet" href="@yield('page_css')">
    @endif

    @stack('styles')
</head>
<body>
    <div class="site-shell">
        @include('partials.site-header', ['siteSettings' => $siteSettings])
        @yield('content')
    </div>
</body>
</html>
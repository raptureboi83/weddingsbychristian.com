<section>
    <div class="container container-narrow">
        <div class="footer-bar">
            <div class="footer-grid">
                <div>
                    <div class="footer-note">
                        {{ $siteSettings->footer_copyright_text ?: '© 2026 Weddings By Christian' }}
                    </div>
                </div>

                <div>
                    <a href="#hero" class="site-brand-text">{{ $siteName }}</a>
                </div>

                <div>
                    @if (!empty($footerLinks))
                        <div class="social-links">
                            @foreach ($footerLinks as $link)
                                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($link['network']) }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="{{ $iconPaths[$link['network']] }}"></path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
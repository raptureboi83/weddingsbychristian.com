<section class="footer-section">
    <div class="container container-narrow">
        <div class="footer-bar">
            <div class="footer-grid">
                <div class="footer-col footer-col-left">
                    <div class="footer-note footer-copyright">
                        {{ $siteSettings->footer_copyright_text ?: '© 2026 Weddings By Christian' }}
                    </div>
                </div>

                <div class="footer-col footer-col-center">
                    <a href="#hero" class="footer-brand-link">{{ $siteName }}</a>
                </div>

                <div class="footer-col footer-col-right">
                    @if (!empty($footerLinks))
                        <div class="social-links footer-socials">
                            @foreach ($footerLinks as $link)
                                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($link['network']) }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="{{ $link['icon'] }}"></path>
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
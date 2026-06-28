<section class="footer-section">
    <div class="container container-narrow">
        <div class="footer-bar">
            <div class="footer-grid">
                <div class="footer-col footer-col-left">
                    <div class="footer-note footer-copyright">
                        {!! str_replace('©', '<a href="/admin" style="text-decoration:none;color:inherit">&copy;</a>', e($siteSettings->footer_copyright_text ?: '© 2026 Weddings By Christian')) !!}
                    </div>
                </div>

                <div class="footer-col footer-col-center">
                    <a href="#hero" class="footer-brand-link">{{ $siteSettings->footer_title ?: $siteName }}</a>
                </div>

                <div class="footer-col footer-col-right">
                    @if (!empty($footerLinks))
                        <div class="social-links footer-socials">
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
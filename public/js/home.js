(function () {
    'use strict';

    // --- Scroll Animations ---
    var animateSection = function (section) {
        var heading = section.querySelector('.section-heading') || section.querySelector('.hero-text') || section.querySelector('.contact-wrap > div:first-child');
        var grids = section.querySelectorAll('.film-grid, .package-grid, .testimonial-grid, .vendor-grid, .two-col, .contact-wrap > div:last-child');

        if (heading) {
            heading.style.opacity = '0';
            heading.style.transform = 'translateY(40px)';
        }

        grids.forEach(function (grid) {
            grid.style.opacity = '0';
            grid.style.transform = 'translateY(40px)';
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                observer.unobserve(entry.target);

                var el = entry.target;
                var delay = entry.target.dataset.aniDelay || 0;

                if (el === heading) {
                    setTimeout(function () {
                        el.style.transition = 'opacity 1.2s cubic-bezier(0.22, 1, 0.36, 1), transform 1.2s cubic-bezier(0.22, 1, 0.36, 1)';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, delay);
                } else {
                    var cards = el.children;
                    Array.from(cards).forEach(function (card, i) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(40px)';
                        setTimeout(function () {
                            card.style.transition = 'opacity 1s cubic-bezier(0.22, 1, 0.36, 1), transform 1s cubic-bezier(0.22, 1, 0.36, 1)';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 100 + i * 120);
                    });

                    setTimeout(function () {
                        el.style.transition = 'opacity 1.2s cubic-bezier(0.22, 1, 0.36, 1), transform 1.2s cubic-bezier(0.22, 1, 0.36, 1)';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, delay);
                }
            });
        }, { threshold: 0.15 });

        if (heading) observer.observe(heading);
        grids.forEach(function (grid) { observer.observe(grid); });
    };

    var sections = document.querySelectorAll('#hero, #about, #films, #packages, #testimonials, #vendors, #contact');
    sections.forEach(animateSection);


})();

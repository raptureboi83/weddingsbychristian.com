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

    // --- Testimonials Progressive Reveal ---
    var testimonialGrid = document.querySelector('.testimonial-grid');
    var testimonialSection = document.getElementById('testimonials');

    if (testimonialGrid && testimonialSection) {
        var allTestimonialCards = Array.from(testimonialGrid.querySelectorAll('.testimonial-card'));
        if (allTestimonialCards.length > 3) {
            allTestimonialCards.forEach(function (card, i) {
                if (i >= 3) {
                    card.style.display = 'none';
                }
            });

            var seeMoreBtn = document.createElement('div');
            seeMoreBtn.className = 'center-link testimonial-see-more';
            seeMoreBtn.style.marginTop = '72px';
            seeMoreBtn.innerHTML = '<button type="button" class="group flex flex-col items-center gap-4 text-warm-beige text-xs tracking-[0.2em] uppercase font-[\'Inter\']" style="cursor:pointer;background:transparent;border:0;padding:0;font-family:\'Inter\',sans-serif;font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:var(--warm-beige);display:inline-flex;flex-direction:column;align-items:center;gap:16px"><span style="border-bottom:1px solid rgba(246,245,241,0.3);padding-bottom:4px">See More Kind Words</span><span style="width:1px;height:48px;display:block;background:linear-gradient(to bottom,rgba(246,245,241,0.5),transparent)"></span></button>';

            var container = testimonialGrid.closest('.container') || testimonialSection.querySelector('.container');
            if (container) {
                container.appendChild(seeMoreBtn);
            }

            seeMoreBtn.querySelector('button').addEventListener('click', function () {
                allTestimonialCards.forEach(function (card) {
                    card.style.display = '';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    requestAnimationFrame(function () {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                });
                seeMoreBtn.style.display = 'none';
            });
        }
    }

    // --- Vendors Progressive Reveal ---
    var vendorsSection = document.getElementById('vendors');
    if (vendorsSection) {
        var vendorCategories = Array.from(vendorsSection.querySelectorAll('.vendor-category'));
        if (vendorCategories.length > 1) {
            vendorCategories.forEach(function (cat, i) {
                if (i >= 1) {
                    cat.style.display = 'none';
                }
            });

            var seeMoreVendorsBtn = document.createElement('div');
            seeMoreVendorsBtn.className = 'center-link vendors-see-more';
            seeMoreVendorsBtn.style.marginTop = '72px';
            seeMoreVendorsBtn.innerHTML = '<button type="button" class="group flex flex-col items-center gap-4 text-warm-beige text-xs tracking-[0.2em] uppercase font-[\'Inter\']" style="cursor:pointer;background:transparent;border:0;padding:0;font-family:\'Inter\',sans-serif;font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:var(--warm-beige);display:inline-flex;flex-direction:column;align-items:center;gap:16px"><span style="border-bottom:1px solid rgba(246,245,241,0.3);padding-bottom:4px">See More Vendors</span><span style="width:1px;height:48px;display:block;background:linear-gradient(to bottom,rgba(246,245,241,0.5),transparent)"></span></button>';

            var vendorsContainer = vendorsSection.querySelector('.container') || vendorsSection;
            vendorsContainer.appendChild(seeMoreVendorsBtn);

            var currentCategoryIdx = 1;

            seeMoreVendorsBtn.querySelector('button').addEventListener('click', function () {
                var cat = vendorCategories[currentCategoryIdx];
                if (cat) {
                    cat.style.display = '';
                    cat.style.opacity = '0';
                    cat.style.transform = 'translateY(20px)';
                    requestAnimationFrame(function () {
                        cat.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        cat.style.opacity = '1';
                        cat.style.transform = 'translateY(0)';
                    });
                    currentCategoryIdx++;
                }

                if (currentCategoryIdx >= vendorCategories.length) {
                    seeMoreVendorsBtn.style.display = 'none';
                }
            });
        }
    }
})();

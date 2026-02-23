import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/carousel';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();

    const startNonCritical = () => {
        initTooltipsAndPopovers();
        initOptionalGsapAnimations();
    };

    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(startNonCritical, { timeout: 600 });
    } else {
        window.setTimeout(startNonCritical, 0);
    }
});

async function initTooltipsAndPopovers() {
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const popoverEls = document.querySelectorAll('[data-bs-toggle="popover"]');

    if (!tooltipEls.length && !popoverEls.length) {
        return;
    }

    if (tooltipEls.length) {
        const { default: Tooltip } = await import('bootstrap/js/dist/tooltip');
        tooltipEls.forEach((el) => {
            new Tooltip(el);
        });
    }

    if (popoverEls.length) {
        const { default: Popover } = await import('bootstrap/js/dist/popover');
        popoverEls.forEach((el) => {
            new Popover(el);
        });
    }
}

function initScrollReveal() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealSelector = [
        '.hero-ready .container > .row > [class*="col-"]',
        'main section',
        'main .card',
        'main .event-card',
        'main .dish-tile',
        'main .menu-card',
        'main .map-shell',
        'main .highlight-card',
    ].join(',');

    const targets = Array.from(document.querySelectorAll(revealSelector))
        .filter((el) => !el.dataset.noReveal);

    if (!targets.length) {
        return;
    }

    document.body.classList.add('reveal-ready');

    targets.forEach((el, index) => {
        el.classList.add('reveal-on-scroll');
        el.style.setProperty('--reveal-delay', `${Math.min((index % 6) * 45, 225)}ms`);
    });

    if (reduceMotion || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    targets.forEach((el) => observer.observe(el));
}

async function initOptionalGsapAnimations() {
    if (document.body.dataset.gsap === 'off') {
        return;
    }

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) {
        return;
    }

    const heroHeadline = document.querySelector('.js-hero-headline');
    const staggerSections = Array.from(document.querySelectorAll('[data-gsap-stagger]'));
    const parallaxTargets = Array.from(document.querySelectorAll('[data-gsap-parallax]'));
    const hasTargets = heroHeadline || staggerSections.length > 0 || parallaxTargets.length > 0;

    if (!hasTargets) {
        return;
    }

    try {
        const [{ gsap }, { ScrollTrigger }] = await Promise.all([
            import('gsap'),
            import('gsap/ScrollTrigger'),
        ]);

        gsap.registerPlugin(ScrollTrigger);

        if (heroHeadline) {
            gsap.from(heroHeadline, {
                autoAlpha: 0,
                y: 22,
                duration: 0.55,
                ease: 'power2.out',
            });
        }

        staggerSections.forEach((section) => {
            const items = section.querySelectorAll('[data-gsap-item]');
            if (!items.length) {
                return;
            }

            items.forEach((item) => {
                item.classList.remove('reveal-on-scroll');
                item.classList.add('is-revealed');
            });

            gsap.from(items, {
                autoAlpha: 0,
                y: 16,
                duration: 0.42,
                stagger: 0.07,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 82%',
                    once: true,
                },
            });
        });

        parallaxTargets.forEach((target) => {
            const factor = Number.parseFloat(target.dataset.parallaxFactor || '0.1');
            if (!Number.isFinite(factor)) {
                return;
            }

            gsap.to(target, {
                yPercent: factor * -30,
                ease: 'none',
                scrollTrigger: {
                    trigger: target.closest('[data-dish-parallax]') || target,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 0.8,
                },
            });
        });
    } catch (error) {
        // Optional enhancement: skip GSAP animations if import fails.
        console.warn('GSAP animations were skipped.', error);
    }
}

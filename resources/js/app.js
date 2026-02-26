import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/carousel';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';

document.addEventListener('DOMContentLoaded', () => {
    const hasMenuExperience = Boolean(document.querySelector('[data-menu-experience]'));
    const hasHomeExperience = Boolean(document.querySelector('[data-home-experience]')) || document.body.classList.contains('home-menu-theme');
    const motionProfile = getMotionProfile();

    document.body.dataset.performanceMode = motionProfile.prefersReduced
        ? 'reduced'
        : (motionProfile.isLite ? 'lite' : 'full');

    if (!hasMenuExperience && !hasHomeExperience) {
        initScrollReveal();
    }

    const startNonCritical = () => {
        initTooltipsAndPopovers();
        initMenuExperience();
        initHomeExperience();
        initOptionalGsapAnimations();
    };

    if (hasMenuExperience || hasHomeExperience) {
        startNonCritical();
    } else if ('requestIdleCallback' in window) {
        window.requestIdleCallback(startNonCritical, { timeout: 600 });
    } else {
        window.setTimeout(startNonCritical, 0);
    }
});

function getMotionProfile() {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const coarsePointer = window.matchMedia('(pointer: coarse)').matches;
    const smallViewport = window.matchMedia('(max-width: 991.98px)').matches;
    const saveData = navigator.connection?.saveData === true;
    const effectiveType = navigator.connection?.effectiveType || '';
    const lowBandwidth = /(2g|3g)/i.test(effectiveType);
    const cpuCores = Number(navigator.hardwareConcurrency || 8);
    const deviceMemory = Number(navigator.deviceMemory || 8);
    const lowPowerDevice = cpuCores <= 4 || deviceMemory <= 4;
    const isLite = !prefersReduced && (saveData || lowBandwidth || (coarsePointer && smallViewport) || lowPowerDevice);

    return {
        prefersReduced,
        isLite,
        coarsePointer,
        smallViewport,
    };
}

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
    if (document.querySelector('[data-menu-experience]') || document.querySelector('[data-home-experience]')) {
        return;
    }

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

function attachLenisToGsap({ Lenis, gsap, ScrollTrigger, config = {} }) {
    const lenis = new Lenis({
        duration: 1.1,
        smoothWheel: true,
        syncTouch: true,
        syncTouchLerp: 0.08,
        touchMultiplier: 1,
        wheelMultiplier: 0.9,
        ...config,
    });

    let rafId = 0;

    const raf = (time) => {
        lenis.raf(time);
        rafId = window.requestAnimationFrame(raf);
    };

    let scrollTriggerUpdateQueued = false;
    lenis.on('scroll', () => {
        if (scrollTriggerUpdateQueued) {
            return;
        }

        scrollTriggerUpdateQueued = true;
        window.requestAnimationFrame(() => {
            scrollTriggerUpdateQueued = false;
            ScrollTrigger.update();
        });
    });

    rafId = window.requestAnimationFrame(raf);
    gsap.ticker.lagSmoothing(0);

    const refresh = () => ScrollTrigger.refresh();
    window.setTimeout(refresh, 120);
    window.addEventListener('load', refresh, { once: true });

    return {
        lenis,
        destroy() {
            if (rafId) {
                window.cancelAnimationFrame(rafId);
            }
            lenis.destroy();
        },
    };
}

async function initMenuExperience() {
    const root = document.querySelector('[data-menu-experience]');
    if (!root) {
        return;
    }

    const chipButtons = Array.from(root.querySelectorAll('[data-menu-chip]'));
    const navChips = Array.from(root.querySelectorAll('.menu-chip-rail [data-menu-chip]'));
    const sections = Array.from(root.querySelectorAll('[data-menu-section]'));
    const progressBar = root.querySelector('[data-menu-progress]');
    const heroVisual = root.querySelector('[data-menu-hero-visual]');
    const heroGlow = root.querySelector('[data-menu-hero-glow]');
    const heroImage = heroVisual?.querySelector('img') ?? null;
    const flameBlobs = Array.from(root.querySelectorAll('[data-menu-flame]'));
    const embers = Array.from(root.querySelectorAll('.menu-ember'));
    const menuCards = Array.from(root.querySelectorAll('[data-menu-card]'));
    const chipShell = root.querySelector('.menu-chip-shell');
    const motionProfile = getMotionProfile();
    const prefersReduced = motionProfile.prefersReduced;
    const isLiteMotion = motionProfile.isLite;
    const hasFinePointer = window.matchMedia('(pointer: fine)').matches;
    let lenis = null;
    let pulseActiveChip = null;

    const setActiveChip = (slug) => {
        chipButtons.forEach((button) => {
            const isActive = button.dataset.menuChip === slug;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-current', isActive ? 'true' : 'false');

            if (isActive && typeof pulseActiveChip === 'function' && button.classList.contains('menu-chip')) {
                pulseActiveChip(button);
            }
        });
    };

    const scrollToSection = (slug) => {
        const target = root.querySelector(`[data-menu-section="${slug}"]`);
        if (!target) {
            return;
        }

        if (lenis) {
            const navHeightVar = Number.parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 84;
            const chipHeight = chipShell?.getBoundingClientRect().height || 58;
            const offset = -1 * (navHeightVar + chipHeight + 10);
            lenis.scrollTo(target, { offset, duration: 1.15, easing: (t) => 1 - Math.pow(1 - t, 3) });
            return;
        }

        target.scrollIntoView({ behavior: prefersReduced ? 'auto' : 'smooth', block: 'start' });
    };

    chipButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const slug = button.dataset.menuChip;
            if (!slug) {
                return;
            }

            setActiveChip(slug);
            scrollToSection(slug);
        });
    });

    if ('IntersectionObserver' in window && sections.length) {
        const observer = new IntersectionObserver((entries) => {
            const visibleEntries = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

            if (visibleEntries[0]) {
                setActiveChip(visibleEntries[0].target.dataset.menuSection);
            }
        }, {
            rootMargin: '-25% 0px -55% 0px',
            threshold: [0.15, 0.3, 0.5, 0.75],
        });

        sections.forEach((section) => observer.observe(section));
    }

    if (prefersReduced || document.body.dataset.gsap === 'off') {
        return;
    }

    try {
        const [{ default: Lenis }, { gsap }, { ScrollTrigger }] = await Promise.all([
            import('lenis'),
            import('gsap'),
            import('gsap/ScrollTrigger'),
        ]);

        gsap.registerPlugin(ScrollTrigger);

        if (!isLiteMotion) {
            ({ lenis } = attachLenisToGsap({
                Lenis,
                gsap,
                ScrollTrigger,
                config: {
                    duration: 1.12,
                    wheelMultiplier: 0.85,
                },
            }));
        }

        const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
        heroTimeline
            .from('[data-menu-hero-badge]', { autoAlpha: 0, y: 20, duration: 0.45 })
            .from('[data-menu-hero-title] .line', { autoAlpha: 0, yPercent: 110, stagger: 0.08, duration: 0.65 }, '-=0.15')
            .from('[data-menu-hero-subtitle]', { autoAlpha: 0, y: 18, duration: 0.45 }, '-=0.35')
            .from('[data-menu-hero-cta]', { autoAlpha: 0, y: 14, stagger: 0.08, duration: 0.35 }, '-=0.2')
            .from('[data-menu-hero-visual]', { autoAlpha: 0, scale: 0.92, rotate: -2, duration: 0.65 }, '-=0.5');

        if (progressBar) {
            gsap.to(progressBar, {
                scaleX: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: root,
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: 0.18,
                },
            });
        }

        if (heroVisual && !isLiteMotion) {
            gsap.to(heroVisual, {
                y: -10,
                duration: 2.4,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });

            gsap.to(heroVisual, {
                rotate: 1.2,
                duration: 3.4,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });

            gsap.to(heroVisual, {
                yPercent: -5,
                ease: 'none',
                scrollTrigger: {
                    trigger: root.querySelector('.menu-hero') || heroVisual,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 0.5,
                },
            });
        } else if (heroVisual) {
            gsap.to(heroVisual, {
                yPercent: -3,
                ease: 'none',
                scrollTrigger: {
                    trigger: root.querySelector('.menu-hero') || heroVisual,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 0.25,
                },
            });
        }

        if (heroImage && !isLiteMotion) {
            gsap.to(heroImage, {
                scale: 1.035,
                duration: 2.7,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });
        }

        if (heroGlow && !isLiteMotion) {
            gsap.to(heroGlow, {
                opacity: 0.95,
                scale: 1.08,
                duration: 1.9,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });
        }

        if (flameBlobs.length && !isLiteMotion) {
            gsap.to(flameBlobs, {
                yPercent: -8,
                scale: 1.05,
                opacity: 0.92,
                duration: 2.1,
                ease: 'sine.inOut',
                stagger: {
                    each: 0.25,
                    from: 'random',
                },
                repeat: -1,
                yoyo: true,
            });
        }

        if (embers.length && !isLiteMotion) {
            embers.forEach((ember, index) => {
                const driftX = gsap.utils.random(-26, 26);
                const rise = gsap.utils.random(160, 360);

                gsap.set(ember, {
                    x: gsap.utils.random(-10, 10),
                    opacity: gsap.utils.random(0.15, 0.45),
                    scale: gsap.utils.random(0.7, 1.2),
                });

                gsap.to(ember, {
                    y: -rise,
                    x: `+=${driftX}`,
                    opacity: 0,
                    scale: 0.4,
                    duration: gsap.utils.random(2.8, 5.5),
                    ease: 'none',
                    delay: index * 0.18,
                    repeat: -1,
                    repeatDelay: gsap.utils.random(0.25, 1.2),
                });
            });
        }

        const chipRail = root.querySelector('[data-menu-chip-rail]');
        if (chipRail) {
            gsap.from(chipRail.children, {
                autoAlpha: 0,
                y: 14,
                stagger: 0.05,
                duration: 0.35,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: chipRail,
                    start: 'top 92%',
                    once: true,
                },
            });
        }

        pulseActiveChip = (button) => {
            gsap.fromTo(button, {
                boxShadow: '0 0 0 0 rgba(219, 29, 48, 0.35)',
            }, {
                boxShadow: '0 0 0 14px rgba(219, 29, 48, 0)',
                duration: 0.5,
                ease: 'power2.out',
                overwrite: true,
                clearProps: 'boxShadow',
            });
        };

        sections.forEach((section) => {
            const header = section.querySelector('[data-menu-section-header]');
            const cards = section.querySelectorAll('[data-menu-card]');
            const banner = section.querySelector('.menu-category-banner img');

            if (header) {
                gsap.from(header, {
                    autoAlpha: 0,
                    y: 24,
                    duration: 0.55,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 80%',
                        once: true,
                    },
                });
            }

            if (banner) {
                gsap.fromTo(banner, {
                    scale: 1.12,
                    autoAlpha: 0.72,
                }, {
                    scale: 1.02,
                    autoAlpha: 1,
                    duration: 0.9,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 78%',
                        once: true,
                    },
                });
            }

            if (cards.length) {
                gsap.from(cards, {
                    autoAlpha: 0,
                    y: 28,
                    rotateX: isLiteMotion ? 0 : 5,
                    transformOrigin: '50% 100%',
                    duration: isLiteMotion ? 0.42 : 0.55,
                    stagger: isLiteMotion ? 0.04 : 0.07,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 75%',
                        once: true,
                    },
                });
            }
        });

        if (!isLiteMotion) {
            navChips.forEach((chip) => {
                const badge = chip.querySelector('small');
                if (!badge) {
                    return;
                }

                gsap.to(badge, {
                    y: -2,
                    duration: 0.9,
                    ease: 'sine.inOut',
                    repeat: -1,
                    yoyo: true,
                    paused: false,
                    delay: Math.random() * 0.5,
                });
            });
        }

        if (!isLiteMotion) {
            root.querySelectorAll('[data-menu-parallax]').forEach((target) => {
                gsap.to(target, {
                    yPercent: -8,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: target.closest('[data-menu-card]') || target,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: 0.7,
                    },
                });
            });
        }

        if (hasFinePointer && !isLiteMotion) {
            const addTiltInteraction = (target, options = {}) => {
                const { maxX = 7, maxY = 8, isHero = false } = options;
                let frame = 0;
                let pending = null;

                const apply = () => {
                    frame = 0;
                    if (!pending) {
                        return;
                    }

                    const { rx, ry, gx, gy } = pending;
                    if (isHero) {
                        target.style.setProperty('--hero-tilt-x', `${rx}deg`);
                        target.style.setProperty('--hero-tilt-y', `${ry}deg`);
                        return;
                    }

                    target.style.setProperty('--tilt-x', `${rx}deg`);
                    target.style.setProperty('--tilt-y', `${ry}deg`);
                    target.style.setProperty('--glare-x', `${gx}%`);
                    target.style.setProperty('--glare-y', `${gy}%`);
                };

                const onMove = (event) => {
                    const rect = target.getBoundingClientRect();
                    if (!rect.width || !rect.height) {
                        return;
                    }

                    const px = (event.clientX - rect.left) / rect.width;
                    const py = (event.clientY - rect.top) / rect.height;
                    pending = {
                        rx: (0.5 - py) * maxX,
                        ry: (px - 0.5) * maxY,
                        gx: Math.round(px * 100),
                        gy: Math.round(py * 100),
                    };

                    if (!frame) {
                        frame = window.requestAnimationFrame(apply);
                    }
                };

                const onLeave = () => {
                    if (isHero) {
                        gsap.to(target, {
                            '--hero-tilt-x': '0deg',
                            '--hero-tilt-y': '0deg',
                            duration: 0.4,
                            ease: 'power3.out',
                        });
                        return;
                    }

                    gsap.to(target, {
                        '--tilt-x': '0deg',
                        '--tilt-y': '0deg',
                        '--glare-x': '50%',
                        '--glare-y': '50%',
                        duration: 0.35,
                        ease: 'power3.out',
                    });
                };

                target.addEventListener('pointermove', onMove);
                target.addEventListener('pointerleave', onLeave);
            };

            if (heroVisual) {
                addTiltInteraction(heroVisual, { maxX: 5, maxY: 6, isHero: true });
            }

            menuCards.forEach((card) => addTiltInteraction(card));
        }
    } catch (error) {
        console.warn('Menu animations were skipped.', error);
    }
}

async function initHomeExperience() {
    const root = document.querySelector('[data-home-experience]');
    if (!root) {
        return;
    }

    const motionProfile = getMotionProfile();
    const prefersReduced = motionProfile.prefersReduced;
    const isLiteMotion = motionProfile.isLite;
    const hero = document.querySelector('[data-home-hero]');
    const heroVideos = Array.from(document.querySelectorAll('[data-home-hero-video]'));
    const heroVeil = document.querySelector('[data-home-hero-veil]');
    const heroEmbers = Array.from(document.querySelectorAll('.hero-signature__embers span'));
    const heroVisualStack = document.querySelector('[data-home-hero-visual-stack]');
    const heroRotateDisc = document.querySelector('[data-home-rotate-disc]');
    const heroRotateDiscReverse = document.querySelector('[data-home-rotate-disc-reverse]');
    const heroPanel = document.querySelector('[data-home-hero-panel]');
    const heroFloatPills = Array.from(document.querySelectorAll('[data-home-float-pill]'));
    const storySection = document.querySelector('[data-home-story-section]');
    const storyVisual = document.querySelector('[data-home-story-visual]');
    const storyPanel = document.querySelector('[data-home-story-panel]');
    const storyDiscs = Array.from(document.querySelectorAll('[data-home-story-disc]'));
    const storyDiscsReverse = Array.from(document.querySelectorAll('[data-home-story-disc-rev]'));
    const featuredSliderSection = document.querySelector('[data-home-featured-slider-section]');
    const featuredSlider = document.querySelector('#homeFeaturedSelections');
    const featuredSlides = Array.from(document.querySelectorAll('[data-home-featured-slide]'));
    const featuredDiscs = Array.from(document.querySelectorAll('[data-home-featured-disc]'));
    const progressBar = root.querySelector('[data-home-progress]');

    if (prefersReduced || document.body.dataset.gsap === 'off') {
        return;
    }

    try {
        const [{ default: Lenis }, { gsap }, { ScrollTrigger }] = await Promise.all([
            import('lenis'),
            import('gsap'),
            import('gsap/ScrollTrigger'),
        ]);

        gsap.registerPlugin(ScrollTrigger);

        if (!isLiteMotion) {
            attachLenisToGsap({
                Lenis,
                gsap,
                ScrollTrigger,
                config: {
                    duration: 1.06,
                    wheelMultiplier: 0.88,
                },
            });
        }

        if (progressBar) {
            gsap.to(progressBar, {
                scaleX: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: document.body,
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: 0.15,
                },
            });
        }

        const animateFeaturedSlide = (slide) => {
            if (!slide) {
                return;
            }

            const copy = slide.querySelector('[data-home-featured-copy]');
            const visual = slide.querySelector('[data-home-featured-visual]');
            const disc = slide.querySelector('[data-home-featured-disc]');
            const chips = Array.from(slide.querySelectorAll('.home-featured-slider__visual-chip'));
            const copyTargets = copy ? Array.from(copy.children) : [];

            if (copyTargets.length) {
                gsap.fromTo(copyTargets, {
                    autoAlpha: 0,
                    y: 14,
                }, {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.42,
                    stagger: 0.05,
                    ease: 'power2.out',
                    overwrite: 'auto',
                });
            }

            if (visual) {
                gsap.fromTo(visual, {
                    autoAlpha: 0,
                    x: 16,
                    scale: 0.98,
                }, {
                    autoAlpha: 1,
                    x: 0,
                    scale: 1,
                    duration: 0.5,
                    ease: 'power3.out',
                    overwrite: 'auto',
                });
            }

            if (chips.length && !isLiteMotion) {
                gsap.fromTo(chips, {
                    autoAlpha: 0,
                    y: 8,
                }, {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.35,
                    stagger: 0.06,
                    ease: 'power2.out',
                    overwrite: 'auto',
                });
            }

            if (disc) {
                gsap.fromTo(disc, {
                    scale: 0.94,
                    rotate: '-=10',
                }, {
                    scale: 1,
                    rotate: '+=10',
                    duration: 0.65,
                    ease: 'power3.out',
                    overwrite: 'auto',
                });
            }
        };

        const homeHeroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
        homeHeroTimeline
            .from('[data-home-hero-kicker]', { autoAlpha: 0, y: 16, duration: 0.45 })
            .from('[data-home-hero-title]', { autoAlpha: 0, y: 30, duration: 0.7 }, '-=0.15')
            .from('[data-home-hero-copy]', { autoAlpha: 0, y: 20, duration: 0.5 }, '-=0.35')
            .from('[data-home-hero-actions] > *', { autoAlpha: 0, y: 14, stagger: 0.08, duration: 0.35 }, '-=0.25');

        if (heroPanel) {
            homeHeroTimeline.from(heroPanel, { autoAlpha: 0, x: 20, y: 14, duration: 0.65 }, '-=0.45');
        }

        if (hero) {
            gsap.fromTo(hero, { backgroundPositionY: '0%' }, {
                backgroundPositionY: '10%',
                ease: 'none',
                scrollTrigger: {
                    trigger: hero,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.2 : 0.6,
                },
            });
        }

        heroVideos.forEach((video, index) => {
            gsap.set(video, { willChange: 'transform' });
            gsap.fromTo(video, {
                scale: 1.06,
            }, {
                scale: 1.14,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || video,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.25 : 0.8,
                },
            });
        });

        if (heroVeil) {
            gsap.to(heroVeil, {
                backgroundPosition: '50% 56%',
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroVeil,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.2 : 0.7,
                },
            });
        }

        if (heroVisualStack) {
            gsap.from(heroVisualStack, {
                autoAlpha: 0,
                y: 18,
                scale: 0.97,
                duration: 0.7,
                ease: 'power3.out',
                delay: 0.15,
            });

            gsap.to(heroVisualStack, {
                yPercent: -4,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroVisualStack,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.18 : 0.5,
                },
            });
        }

        if (heroRotateDisc) {
            gsap.to(heroRotateDisc, {
                rotate: isLiteMotion ? '+=42' : '+=140',
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroRotateDisc,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.06 : 0.12,
                },
            });
        }

        if (heroRotateDiscReverse) {
            gsap.to(heroRotateDiscReverse, {
                rotate: isLiteMotion ? '+=20' : '+=70',
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroRotateDiscReverse,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.06 : 0.12,
                },
            });
        }

        if (!isLiteMotion) {
            heroFloatPills.forEach((pill, index) => {
                gsap.to(pill, {
                    y: index % 2 === 0 ? -8 : 7,
                    x: index % 2 === 0 ? -2 : 3,
                    duration: 2 + (index * 0.35),
                    ease: 'sine.inOut',
                    repeat: -1,
                    yoyo: true,
                });
            });

            heroEmbers.forEach((ember, index) => {
                gsap.set(ember, {
                    x: gsap.utils.random(-8, 8),
                    opacity: gsap.utils.random(0.12, 0.34),
                    scale: gsap.utils.random(0.7, 1.35),
                });

                gsap.to(ember, {
                    y: -1 * gsap.utils.random(140, 320),
                    x: `+=${gsap.utils.random(-18, 18)}`,
                    opacity: 0,
                    scale: 0.4,
                    duration: gsap.utils.random(2.5, 5.2),
                    ease: 'none',
                    delay: index * 0.18,
                    repeat: -1,
                    repeatDelay: gsap.utils.random(0.2, 1.1),
                });
            });
        }

        if (featuredSliderSection) {
            const featuredHeader = featuredSliderSection.querySelector('.home-featured-slider__top');
            const featuredCard = featuredSliderSection.querySelector('.home-featured-slider__card');
            const featuredReveal = gsap.timeline({
                scrollTrigger: {
                    trigger: featuredSliderSection,
                    start: 'top 84%',
                    once: true,
                },
            });

            if (featuredHeader) {
                featuredReveal.from(featuredHeader, {
                    autoAlpha: 0,
                    y: 18,
                    duration: 0.5,
                    ease: 'power2.out',
                });
            }

            if (featuredCard) {
                featuredReveal.from(featuredCard, {
                    autoAlpha: 0,
                    y: 20,
                    duration: 0.55,
                    ease: 'power3.out',
                }, featuredHeader ? '-=0.2' : 0);
            }

            const activeFeaturedSlide = featuredSliderSection.querySelector('.carousel-item.active');
            if (activeFeaturedSlide) {
                animateFeaturedSlide(activeFeaturedSlide);
            }
        }

        featuredDiscs.forEach((disc, index) => {
            gsap.to(disc, {
                rotate: isLiteMotion ? '+=16' : '+=38',
                ease: 'none',
                scrollTrigger: {
                    trigger: disc.closest('[data-home-featured-slide]') || disc,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.2,
                },
            });
        });

        document.querySelectorAll('[data-home-featured-visual]').forEach((visual) => {
            gsap.to(visual, {
                yPercent: -3,
                ease: 'none',
                scrollTrigger: {
                    trigger: visual.closest('[data-home-featured-slider-section], .carousel-item') || visual,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.12 : 0.28,
                },
            });
        });

        if (!isLiteMotion) {
            document.querySelectorAll('.home-featured-slider__visual-chip').forEach((chip, index) => {
                gsap.to(chip, {
                    y: index % 2 === 0 ? -7 : 6,
                    x: index % 2 === 0 ? 2 : -2,
                    duration: 1.8 + (index * 0.2),
                    ease: 'sine.inOut',
                    repeat: -1,
                    yoyo: true,
                });
            });
        }

        if (featuredSlider) {
            featuredSlider.addEventListener('slid.bs.carousel', (event) => {
                animateFeaturedSlide(event.relatedTarget || featuredSlider.querySelector('.carousel-item.active'));
            });
        }

        if (storySection) {
            const storyTl = gsap.timeline({
                scrollTrigger: {
                    trigger: storySection,
                    start: 'top 82%',
                    once: true,
                },
            });

            storyTl
                .from('.home-discovery-story__intro > *', {
                    autoAlpha: 0,
                    y: 16,
                    stagger: 0.08,
                    duration: 0.45,
                    ease: 'power2.out',
                })
                .from(storyVisual, {
                    autoAlpha: 0,
                    y: 20,
                    scale: 0.97,
                    duration: 0.55,
                    ease: 'power3.out',
                }, '-=0.2')
                .from(storyPanel, {
                    autoAlpha: 0,
                    y: 18,
                    duration: 0.5,
                    ease: 'power2.out',
                }, '-=0.2');
        }

        if (storyVisual) {
            gsap.to(storyVisual, {
                yPercent: -2,
                ease: 'none',
                scrollTrigger: {
                    trigger: storySection || storyVisual,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.12 : 0.35,
                },
            });
        }

        storyDiscs.forEach((disc, index) => {
            gsap.to(disc, {
                rotate: isLiteMotion ? '+=14' : '+=30',
                ease: 'none',
                scrollTrigger: {
                    trigger: storySection || disc,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.18,
                },
            });
        });

        storyDiscsReverse.forEach((disc) => {
            gsap.to(disc, {
                rotate: isLiteMotion ? '-=12' : '-=24',
                ease: 'none',
                scrollTrigger: {
                    trigger: storySection || disc,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.16,
                },
            });
        });

        const animatedSections = Array.from(document.querySelectorAll('main section'));
        animatedSections.forEach((section) => {
            const header = section.querySelector('.section-header');
            const cards = section.querySelectorAll('.highlight-card, .dish-tile, .event-card, blockquote, .chef-spotlight, .home-stat-card, .home-step-card, .home-feature-panel, .dishes-gallery__info-card, .dishes-gallery__service-strip');

            if (header) {
                gsap.from(header, {
                    autoAlpha: 0,
                    y: 22,
                    duration: 0.55,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 84%',
                        once: true,
                    },
                });
            }

            if (cards.length) {
                gsap.from(cards, {
                    autoAlpha: 0,
                    y: 22,
                    duration: 0.5,
                    stagger: 0.06,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 80%',
                        once: true,
                    },
                });
            }
        });

        document.querySelectorAll('.dish-visual img, .chef-spotlight__image').forEach((target) => {
            gsap.to(target, {
                yPercent: -7,
                ease: 'none',
                scrollTrigger: {
                    trigger: target.closest('section, .carousel-item, .dish-tile') || target,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.18 : 0.6,
                },
            });
        });

        const finePointer = window.matchMedia('(pointer: fine)').matches;
        if (finePointer && !isLiteMotion) {
            const hoverCards = Array.from(document.querySelectorAll('.highlight-card, .dish-tile, blockquote, .home-stat-card, .home-step-card, .home-feature-panel, .dishes-gallery__info-card, .dishes-gallery__service-strip'));
            hoverCards.forEach((card) => {
                let raf = 0;
                let pending = null;

                const apply = () => {
                    raf = 0;
                    if (!pending) {
                        return;
                    }
                    card.style.setProperty('--hx', `${pending.rx}deg`);
                    card.style.setProperty('--hy', `${pending.ry}deg`);
                };

                card.style.transformStyle = 'preserve-3d';
                card.style.willChange = 'transform';

                card.addEventListener('pointermove', (event) => {
                    const rect = card.getBoundingClientRect();
                    const px = (event.clientX - rect.left) / rect.width;
                    const py = (event.clientY - rect.top) / rect.height;
                    pending = {
                        rx: (0.5 - py) * 4,
                        ry: (px - 0.5) * 5,
                    };

                    if (!raf) {
                        raf = window.requestAnimationFrame(apply);
                    }
                });

                card.addEventListener('pointerleave', () => {
                    gsap.to(card, {
                        '--hx': '0deg',
                        '--hy': '0deg',
                        duration: 0.35,
                        ease: 'power3.out',
                    });
                });
            });
        }
    } catch (error) {
        console.warn('Home animations were skipped.', error);
    }
}

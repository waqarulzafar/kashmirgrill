import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/carousel';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';

let gsapRuntimePromise = null;
let globalScrollSmootherPromise = null;
let globalScrollSmoother = null;
const HOME_HERO_FRAME_CACHE_NAME = 'kgh-home-hero-frames-v1';
const homeHeroFrameCache = new Map();

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

    initCartUi();
    initCheckoutExperience();

    if (!motionProfile.prefersReduced && document.body.dataset.gsap !== 'off') {
        void initGlobalSmoothScroll({ isLiteMotion: motionProfile.isLite });
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

function buildHomeHeroFrameUrls(basePath, frameCount) {
    if (!basePath || frameCount <= 0) {
        return [];
    }

    return Array.from({ length: frameCount }, (_, index) => (
        `${basePath}/frame-${String(index + 1).padStart(3, '0')}.png`
    ));
}

function loadImageElement(src) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.decoding = 'async';
        img.onload = () => resolve(img);
        img.onerror = () => reject(new Error(`Failed to load image: ${src}`));
        img.src = src;
    });
}

async function warmHomeHeroFrameCacheStorage(frameUrls) {
    if (!('caches' in window) || !frameUrls.length) {
        return;
    }

    try {
        const cache = await caches.open(HOME_HERO_FRAME_CACHE_NAME);
        await Promise.all(frameUrls.map(async (url) => {
            const request = new Request(url, { credentials: 'same-origin' });
            const existing = await cache.match(request);
            if (existing) {
                return;
            }

            const response = await fetch(request);
            if (response.ok) {
                await cache.put(request, response.clone());
            }
        }));
    } catch (error) {
        // Cache storage can be unavailable in strict browser contexts; ignore and continue.
    }
}

function preloadHomeHeroFrames(basePath, frameCount) {
    const frameUrls = buildHomeHeroFrameUrls(basePath, frameCount);
    const cacheKey = `${basePath}|${frameCount}`;

    if (homeHeroFrameCache.has(cacheKey)) {
        return homeHeroFrameCache.get(cacheKey);
    }

    const preloadPromise = (async () => {
        if (!frameUrls.length) {
            return [];
        }

        // Persist frames in browser cache for subsequent visits while also decoding in-memory now.
        void warmHomeHeroFrameCacheStorage(frameUrls);

        const decoded = await Promise.all(frameUrls.map(async (src) => {
            try {
                return await loadImageElement(src);
            } catch (error) {
                return null;
            }
        }));

        return decoded;
    })();

    homeHeroFrameCache.set(cacheKey, preloadPromise);

    return preloadPromise;
}

function initAdaptiveHeroVideoSources(videos) {
    if (!videos?.length) {
        return;
    }

    const mobileBreakpoint = window.matchMedia('(max-width: 767.98px)');
    const connection = navigator.connection;
    const saveData = connection?.saveData === true;
    const lowBandwidth = /(2g|3g)/i.test(connection?.effectiveType || '');

    const applySource = () => {
        videos.forEach((video) => {
            const desktopSrc = video.dataset.homeHeroVideoDesktop;
            const mobileSrc = video.dataset.homeHeroVideoMobile || desktopSrc;
            if (!desktopSrc && !mobileSrc) {
                return;
            }

            // Prefer the lighter mobile encode on small screens and slower connections.
            const nextSrc = (mobileBreakpoint.matches || saveData || lowBandwidth) ? mobileSrc : desktopSrc;
            if (!nextSrc || video.dataset.activeHeroSrc === nextSrc) {
                return;
            }

            video.dataset.activeHeroSrc = nextSrc;
            video.pause();
            video.src = nextSrc;
            video.load();

            const tryPlay = () => {
                video.play().catch(() => {
                    // Autoplay may be blocked transiently; video remains ready with poster fallback.
                });
            };

            if (video.readyState >= 2) {
                tryPlay();
            } else {
                video.addEventListener('loadeddata', tryPlay, { once: true });
            }
        });
    };

    applySource();

    if (!window.__heroVideoAdaptiveBound) {
        const onViewportChange = () => applySource();
        if (typeof mobileBreakpoint.addEventListener === 'function') {
            mobileBreakpoint.addEventListener('change', onViewportChange);
        } else if (typeof mobileBreakpoint.addListener === 'function') {
            mobileBreakpoint.addListener(onViewportChange);
        }
        window.__heroVideoAdaptiveBound = true;
    }
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

function initCartUi() {
    const root = document.querySelector('[data-floating-cart]');
    if (!root) {
        return;
    }

    const drawer = root.querySelector('[data-cart-drawer]');
    const backdrop = root.querySelector('[data-cart-backdrop]');
    const toggle = root.querySelector('[data-cart-toggle]');
    const closeButton = root.querySelector('[data-cart-close]');
    const body = root.querySelector('[data-cart-body]');
    const countTargets = root.querySelectorAll('[data-cart-count]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const addUrl = root.dataset.cartAddUrl || '';
    const clearUrl = root.dataset.cartClearUrl || '';
    const updateTemplate = root.dataset.cartUpdateUrlTemplate || '';
    const removeTemplate = root.dataset.cartRemoveUrlTemplate || '';

    let pending = false;

    const setPending = (state) => {
        pending = state;
        root.classList.toggle('is-pending', state);
    };

    const openDrawer = () => {
        if (!drawer || !backdrop) {
            return;
        }
        drawer.hidden = false;
        drawer.setAttribute('aria-hidden', 'false');
        backdrop.hidden = false;
        document.body.classList.add('cart-drawer-open');
    };

    const closeDrawer = () => {
        if (!drawer || !backdrop) {
            return;
        }
        drawer.hidden = true;
        drawer.setAttribute('aria-hidden', 'true');
        backdrop.hidden = true;
        document.body.classList.remove('cart-drawer-open');
    };

    const updateCount = (count) => {
        countTargets.forEach((target) => {
            target.textContent = String(count ?? 0);
        });
    };

    const applyResponse = (payload) => {
        if (!payload || typeof payload !== 'object') {
            return;
        }

        if (payload.drawer_html && body) {
            body.innerHTML = payload.drawer_html;
        }

        if (payload.cart && typeof payload.cart === 'object') {
            updateCount(payload.cart.count ?? 0);
        }
    };

    const buildRequestError = async (response) => {
        const contentType = response.headers.get('content-type') || '';
        const responseText = await response.text();
        let message = `Request failed: ${response.status}`;

        if (contentType.includes('application/json')) {
            try {
                const payload = JSON.parse(responseText);
                message = payload.message || payload.error || message;
            } catch (error) {
                message = `${message} (invalid JSON response)`;
            }
        } else if (response.redirected) {
            message = `Request was redirected to ${response.url}.`;
        } else if (responseText.trim()) {
            message = `${message} (${responseText.slice(0, 160)})`;
        }

        return new Error(message);
    };

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                ...(options.headers || {}),
            },
            ...options,
        });

        if (!response.ok) {
            throw await buildRequestError(response);
        }

        const contentType = response.headers.get('content-type') || '';
        const responseText = await response.text();

        if (!contentType.includes('application/json')) {
            if (response.redirected) {
                throw new Error(`Expected JSON but received a redirect to ${response.url}.`);
            }

            throw new Error(`Expected JSON response but received ${contentType || 'an unknown content type'}.`);
        }

        return JSON.parse(responseText);
    };

    const updateQuantity = async (menuItemId, quantity) => {
        if (!updateTemplate) {
            return;
        }
        const url = updateTemplate.replace('__ID__', String(menuItemId));
        const formData = new FormData();
        formData.set('quantity', String(quantity));

        const payload = await requestJson(url, {
            method: 'PATCH',
            body: formData,
        });

        applyResponse(payload);
    };

    const removeItem = async (menuItemId) => {
        const url = removeTemplate.replace('__ID__', String(menuItemId));
        const payload = await requestJson(url, {
            method: 'DELETE',
        });

        applyResponse(payload);
    };

    toggle?.addEventListener('click', () => {
        if (drawer?.hidden) {
            openDrawer();
        } else {
            closeDrawer();
        }
    });
    closeButton?.addEventListener('click', closeDrawer);
    backdrop?.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && drawer && !drawer.hidden) {
            closeDrawer();
        }
    });

    document.querySelectorAll('[data-add-to-cart-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            if (form.dataset.cartNativeSubmit === 'true') {
                return;
            }

            if (!addUrl || pending) {
                return;
            }

            event.preventDefault();
            setPending(true);

            try {
                const payload = await requestJson(addUrl, {
                    method: 'POST',
                    body: new FormData(form),
                });

                applyResponse(payload);
                openDrawer();
            } catch (error) {
                console.error('Cart add failed.', error);
                form.dataset.cartNativeSubmit = 'true';
                HTMLFormElement.prototype.submit.call(form);
            } finally {
                setPending(false);
            }
        });
    });

    root.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-cart-action]');
        if (!button || pending) {
            return;
        }

        const action = button.dataset.cartAction;
        const menuItemId = Number.parseInt(button.dataset.menuItemId || '0', 10);
        const quantity = Number.parseInt(button.dataset.quantity || '0', 10);

        setPending(true);
        try {
            if (action === 'set-qty' && menuItemId > 0) {
                await updateQuantity(menuItemId, quantity);
            } else if (action === 'remove' && menuItemId > 0) {
                await removeItem(menuItemId);
            } else if (action === 'clear' && clearUrl) {
                const payload = await requestJson(clearUrl, { method: 'POST' });
                applyResponse(payload);
            }
        } catch (error) {
            console.error('Cart action failed.', error);
        } finally {
            setPending(false);
        }
    });
}

function initCheckoutExperience() {
    const root = document.querySelector('[data-checkout-page]');
    if (!root) {
        return;
    }

    const fulfillmentInputs = Array.from(root.querySelectorAll('[data-fulfillment-input]'));
    const deliverySection = root.querySelector('[data-fulfillment-section="delivery"]');
    const dineInSection = root.querySelector('[data-fulfillment-section="dine_in"]');
    const createAccountToggle = root.querySelector('[data-create-account-toggle]');
    const createAccountFields = root.querySelector('[data-create-account-fields]');

    const slotDateInput = root.querySelector('[data-slot-date]');
    const slotGuestsInput = root.querySelector('[data-slot-guests]');
    const slotSelect = root.querySelector('[data-slot-select]');
    const slotsUrl = root.dataset.slotsUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const updateFulfillmentSections = () => {
        const selected = fulfillmentInputs.find((input) => input.checked)?.value;
        if (deliverySection) {
            deliverySection.hidden = selected !== 'delivery';
        }
        if (dineInSection) {
            dineInSection.hidden = selected !== 'dine_in';
        }
    };

    const updateCreateAccountSection = () => {
        if (!createAccountToggle || !createAccountFields) {
            return;
        }
        createAccountFields.hidden = !createAccountToggle.checked;
    };

    const refreshSlots = async () => {
        if (!slotsUrl || !slotDateInput || !slotGuestsInput || !slotSelect) {
            return;
        }

        const date = slotDateInput.value;
        const guestCount = slotGuestsInput.value;
        if (!date) {
            return;
        }

        const selectedBefore = slotSelect.value;
        const params = new URLSearchParams({
            date,
            guest_count: guestCount || '2',
        });

        try {
            const response = await fetch(`${slotsUrl}?${params.toString()}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            const slots = Array.isArray(payload.slots) ? payload.slots : [];
            slotSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select a slot';
            slotSelect.appendChild(placeholder);

            slots.forEach((slot) => {
                const option = document.createElement('option');
                option.value = String(slot.id);
                option.textContent = `${slot.name} (${slot.time_range}) - ${slot.remaining} seats left`;
                option.disabled = slot.can_book !== true;
                if (option.value === selectedBefore) {
                    option.selected = true;
                }
                slotSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Unable to refresh reservation slots.', error);
        }
    };

    fulfillmentInputs.forEach((input) => {
        input.addEventListener('change', updateFulfillmentSections);
    });
    createAccountToggle?.addEventListener('change', updateCreateAccountSection);

    slotDateInput?.addEventListener('change', refreshSlots);
    slotGuestsInput?.addEventListener('change', refreshSlots);
    slotGuestsInput?.addEventListener('input', refreshSlots);

    updateFulfillmentSections();
    updateCreateAccountSection();
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

async function getGsapRuntime() {
    if (!gsapRuntimePromise) {
        gsapRuntimePromise = Promise.all([
            import('gsap'),
            import('gsap/ScrollTrigger'),
            import('gsap/ScrollSmoother'),
        ]).then(([{ gsap }, { ScrollTrigger }, { ScrollSmoother }]) => {
            gsap.registerPlugin(ScrollTrigger, ScrollSmoother);
            return {
                gsap,
                ScrollTrigger,
                ScrollSmoother,
            };
        });
    }

    return gsapRuntimePromise;
}

async function initGlobalSmoothScroll({ isLiteMotion = false } = {}) {
    if (globalScrollSmoother) {
        return globalScrollSmoother;
    }

    if (globalScrollSmootherPromise) {
        return globalScrollSmootherPromise;
    }

    globalScrollSmootherPromise = (async () => {
        if (document.body.dataset.gsap === 'off') {
            return null;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return null;
        }

        const wrapper = document.getElementById('smooth-wrapper');
        const content = document.getElementById('smooth-content');
        if (!wrapper || !content) {
            return null;
        }

        const { ScrollSmoother, ScrollTrigger } = await getGsapRuntime();

        globalScrollSmoother = ScrollSmoother.get() || ScrollSmoother.create({
            wrapper,
            content,
            effects: false,
            normalizeScroll: true,
            ignoreMobileResize: true,
            smooth: isLiteMotion ? 0.75 : 1.05,
            smoothTouch: isLiteMotion ? 0.08 : 0.12,
        });

        const refresh = () => ScrollTrigger.refresh();
        window.setTimeout(refresh, 120);
        window.addEventListener('load', refresh, { once: true });

        return globalScrollSmoother;
    })();

    return globalScrollSmootherPromise;
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
    let smoothScroller = null;
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

        if (smoothScroller) {
            const navHeightVar = Number.parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 84;
            const chipHeight = chipShell?.getBoundingClientRect().height || 58;
            const offset = navHeightVar + chipHeight + 10;
            smoothScroller.scrollTo(target, true, `top ${offset}px`);
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
        const { gsap, ScrollTrigger } = await getGsapRuntime();
        smoothScroller = await initGlobalSmoothScroll({ isLiteMotion });

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

        if (heroVisual) {
            gsap.to(heroVisual, {
                y: isLiteMotion ? -5 : -10,
                duration: isLiteMotion ? 2.8 : 2.4,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });

            gsap.to(heroVisual, {
                rotate: isLiteMotion ? 0.6 : 1.2,
                duration: isLiteMotion ? 3.8 : 3.4,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });

            gsap.to(heroVisual, {
                yPercent: isLiteMotion ? -3 : -5,
                ease: 'none',
                scrollTrigger: {
                    trigger: root.querySelector('.menu-hero') || heroVisual,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.25 : 0.5,
                },
            });
        }

        if (heroImage) {
            gsap.to(heroImage, {
                scale: isLiteMotion ? 1.02 : 1.035,
                duration: isLiteMotion ? 3.1 : 2.7,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });
        }

        if (heroGlow) {
            gsap.to(heroGlow, {
                opacity: isLiteMotion ? 0.78 : 0.95,
                scale: isLiteMotion ? 1.04 : 1.08,
                duration: isLiteMotion ? 2.2 : 1.9,
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true,
            });
        }

        if (flameBlobs.length) {
            gsap.to(flameBlobs, {
                yPercent: isLiteMotion ? -4 : -8,
                scale: isLiteMotion ? 1.02 : 1.05,
                opacity: isLiteMotion ? 0.82 : 0.92,
                duration: isLiteMotion ? 2.6 : 2.1,
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
    const heroPlatterShell = document.querySelector('[data-home-hero-platter-shell]');
    const heroPlatterStage = document.querySelector('[data-home-hero-platter-stage]');
    const heroPlatter = document.querySelector('[data-home-hero-platter]');
    const heroPlatterShadow = document.querySelector('[data-home-hero-platter-shadow]');
    const heroPlatterGlow = document.querySelector('[data-home-hero-platter-glow]');
    const heroPlatterFramesBase = heroPlatter?.dataset.homeHeroFramesBase || '';
    const heroPlatterFramesCount = Number.parseInt(heroPlatter?.dataset.homeHeroFramesCount || '0', 10);
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

    initAdaptiveHeroVideoSources(heroVideos);

    if (prefersReduced || document.body.dataset.gsap === 'off') {
        return;
    }

    try {
        const { gsap, ScrollTrigger } = await getGsapRuntime();
        await initGlobalSmoothScroll({ isLiteMotion });
        const heroFramePreloadPromise = preloadHomeHeroFrames(heroPlatterFramesBase, heroPlatterFramesCount);

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

        if (!isLiteMotion) {
            heroVideos.forEach((video) => {
                gsap.set(video, { willChange: 'transform' });
                gsap.fromTo(video, {
                    scale: 1.04,
                }, {
                    scale: 1.1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: hero || video,
                        start: 'top top',
                        end: 'bottom top',
                        scrub: 0.65,
                    },
                });
            });
        } else {
            heroVideos.forEach((video) => {
                gsap.set(video, { clearProps: 'transform,willChange' });
            });
        }

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
            const startScale = isLiteMotion ? 1.26 : 1.9;
            gsap.set(heroVisualStack, {
                autoAlpha: 0,
                y: 18,
                scale: startScale,
            });

            heroFramePreloadPromise.finally(() => {
                gsap.to(heroVisualStack, {
                    autoAlpha: 1,
                    y: 0,
                    scale: 1,
                    duration: isLiteMotion ? 0.75 : 1.15,
                    ease: 'power4.out',
                    delay: 0.08,
                });
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

        if (heroPlatterShell) {
            gsap.set(heroPlatterShell, {
                transformPerspective: 1400,
                transformOrigin: '50% 55%',
                transformStyle: 'preserve-3d',
            });

            homeHeroTimeline.from(heroPlatterShell, {
                autoAlpha: 0,
                x: 20,
                y: 28,
                duration: 0.58,
            }, '-=0.55');

            const platterMotion = gsap.timeline({
                scrollTrigger: {
                    trigger: hero || heroPlatterShell,
                    start: 'top top+=12',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.2 : 0.65,
                },
            });

            platterMotion
                .fromTo(heroPlatterShell, {
                    xPercent: 0,
                    yPercent: 0,
                    z: 0,
                    scale: 1,
                }, {
                    xPercent: 2,
                    yPercent: -3,
                    z: 32,
                    scale: 1.02,
                    ease: 'none',
                }, 0)
                .to(heroPlatterShell, {
                    xPercent: -2,
                    yPercent: -8,
                    z: isLiteMotion ? 20 : 64,
                    scale: isLiteMotion ? 1.03 : 1.08,
                    ease: 'none',
                }, 1);

        if (heroPlatterStage) {
            gsap.set(heroPlatterStage, {
                transformOrigin: '50% 52%',
                transformStyle: 'preserve-3d',
                force3D: true,
                    rotationX: 0,
                    rotationY: 0,
                    rotationZ: 0,
                });

            platterMotion
                .to(heroPlatterStage, {
                    yPercent: 0,
                    ease: 'none',
                }, 0)
                .to(heroPlatterStage, {
                    yPercent: -3,
                    ease: 'none',
                }, 0.35)
                .to(heroPlatterStage, {
                    yPercent: -7,
                    ease: 'none',
                }, 0.68)
                .to(heroPlatterStage, {
                    yPercent: isLiteMotion ? -5 : -8,
                    ease: 'none',
                }, 1);
        }

            if (heroPlatterShadow) {
                gsap.set(heroPlatterShadow, {
                    transformOrigin: '50% 50%',
                });

                platterMotion
                    .to(heroPlatterShadow, {
                        scaleX: 0.98,
                        scaleY: 0.92,
                        opacity: 0.42,
                        xPercent: 4,
                        ease: 'none',
                    }, 0)
                    .to(heroPlatterShadow, {
                        scaleX: 0.78,
                        scaleY: 0.72,
                        opacity: 0.28,
                        xPercent: -5,
                        ease: 'none',
                    }, 1);
            }

            if (heroPlatterGlow) {
                platterMotion
                    .to(heroPlatterGlow, {
                        scale: 1.06,
                        opacity: 1,
                        xPercent: 2,
                        yPercent: -2,
                        ease: 'none',
                    }, 0)
                    .to(heroPlatterGlow, {
                        scale: 1.12,
                        opacity: 0.82,
                        xPercent: -3,
                        yPercent: -6,
                        ease: 'none',
                    }, 1);
            }
        }

        if (heroPlatter) {
            const frameState = { index: 0 };
            const currentFrame = { index: -1 };
            const frameImages = new Array(heroPlatterFramesCount);
            const context = heroPlatter.getContext('2d', { alpha: true });
            const renderFrame = (index) => {
                const safeIndex = Math.max(0, Math.min(heroPlatterFramesCount - 1, index));
                if (safeIndex === currentFrame.index || !heroPlatterFramesBase || !heroPlatterFramesCount || !context) {
                    return;
                }

                const frame = frameImages[safeIndex];
                if (!frame) {
                    return;
                }

                currentFrame.index = safeIndex;
                context.clearRect(0, 0, heroPlatter.width, heroPlatter.height);
                context.drawImage(frame, 0, 0, heroPlatter.width, heroPlatter.height);
            };

            gsap.set(heroPlatter, {
                transformOrigin: '50% 50%',
                z: 28,
                force3D: true,
            });

            if (heroPlatterFramesBase && heroPlatterFramesCount) {
                heroFramePreloadPromise.then((cachedFrames) => {
                    cachedFrames.forEach((frame, index) => {
                        if (frame) {
                            frameImages[index] = frame;
                        }
                    });

                    renderFrame(0);

                    gsap.to(frameState, {
                        index: heroPlatterFramesCount - 1,
                        ease: 'none',
                        snap: { index: 1 },
                        onUpdate: () => {
                            renderFrame(Math.round(frameState.index));
                        },
                        scrollTrigger: {
                            trigger: hero || heroPlatter,
                            start: 'top top+=12',
                            end: 'bottom top',
                            scrub: isLiteMotion ? 0.06 : 0.12,
                        },
                    });
                });
            }
        }

        if (heroRotateDisc) {
            gsap.to(heroRotateDisc, {
                rotate: isLiteMotion ? '+=42' : '+=140',
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroRotateDisc,
                    start: 'top top+=84',
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
                    start: 'top top+=84',
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

        // Keep chips static in the featured slider to reduce jank while scrolling this section.
        document.querySelectorAll('.home-featured-slider__visual-chip').forEach((chip) => {
            gsap.set(chip, { clearProps: 'transform' });
        });

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

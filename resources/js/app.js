import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/carousel';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';

let gsapRuntimePromise = null;
let globalScrollSmootherPromise = null;
let globalScrollSmoother = null;

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
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let pending = false;
    let currentCount = Number.parseInt(toggle?.querySelector('[data-cart-count]')?.textContent || toggle?.textContent || '0', 10) || 0;
    let drawerHideTimer = null;

    const setPending = (state) => {
        pending = state;
        root.classList.toggle('is-pending', state);
    };

    const pulseCartToggle = () => {
        if (!toggle) {
            return;
        }

        toggle.classList.remove('is-pulsing');
        void toggle.offsetWidth;
        toggle.classList.add('is-pulsing');

        window.setTimeout(() => {
            toggle.classList.remove('is-pulsing');
        }, 520);
    };

    const flashCartItem = (menuItemId) => {
        if (!body || !menuItemId) {
            return;
        }

        const cartItem = body.querySelector(`[data-cart-item-id="${menuItemId}"]`);
        if (!cartItem) {
            return;
        }

        cartItem.classList.remove('is-just-added');
        void cartItem.offsetWidth;
        cartItem.classList.add('is-just-added');

        window.setTimeout(() => {
            cartItem.classList.remove('is-just-added');
        }, 900);
    };

    const createCartFlyer = (sourceImage) => {
        const flyer = document.createElement('div');
        flyer.className = 'floating-cart__flyer';

        const thumb = document.createElement('span');
        thumb.className = 'floating-cart__flyer-thumb';

        if (sourceImage?.currentSrc || sourceImage?.src) {
            thumb.style.backgroundImage = `url("${sourceImage.currentSrc || sourceImage.src}")`;
        } else {
            thumb.classList.add('is-fallback');
            thumb.textContent = '+';
        }

        const badge = document.createElement('span');
        badge.className = 'floating-cart__flyer-badge';
        badge.innerHTML = '<i class="fa-solid fa-cart-plus" aria-hidden="true"></i>';

        flyer.append(thumb, badge);

        return flyer;
    };

    const animateAddToCart = async (form) => {
        if (reducedMotion || !toggle) {
            pulseCartToggle();
            return;
        }

        const productCard = form.closest('[data-cart-product-card]');
        const sourceImage = productCard?.querySelector('[data-cart-product-image]');
        const sourceElement = sourceImage || form.querySelector('button[type="submit"]') || form;
        const sourceRect = sourceElement.getBoundingClientRect();
        const targetElement = toggle.querySelector('[data-cart-count]') || toggle;
        const targetRect = targetElement.getBoundingClientRect();
        const flyer = createCartFlyer(sourceImage);
        const flyerSize = sourceImage ? 54 : 46;
        const startLeft = sourceRect.left + (sourceRect.width / 2) - (flyerSize / 2);
        const startTop = sourceRect.top + Math.min(sourceRect.height * 0.24, 22);
        const endLeft = targetRect.left + (targetRect.width / 2) - (flyerSize / 2);
        const endTop = targetRect.top + (targetRect.height / 2) - (flyerSize / 2);
        const deltaX = endLeft - startLeft;
        const deltaY = endTop - startTop;
        const arcLift = Math.max(58, Math.min(110, Math.abs(deltaX) * 0.14 + 42));

        Object.assign(flyer.style, {
            width: `${flyerSize}px`,
            height: `${flyerSize}px`,
            left: `${startLeft}px`,
            top: `${startTop}px`,
        });

        document.body.appendChild(flyer);

        const animation = flyer.animate(
            [
                {
                    transform: 'translate3d(0, 0, 0) scale(0.9)',
                    opacity: 0,
                },
                {
                    transform: 'translate3d(0, 0, 0) scale(1)',
                    opacity: 1,
                    offset: 0.12,
                },
                {
                    transform: `translate3d(${deltaX * 0.52}px, ${(deltaY * 0.45) - arcLift}px, 0) scale(0.84)`,
                    opacity: 0.96,
                    offset: 0.62,
                },
                {
                    transform: `translate3d(${deltaX}px, ${deltaY}px, 0) scale(0.22)`,
                    opacity: 0.08,
                },
            ],
            {
                duration: 720,
                easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
                fill: 'forwards',
            },
        );

        try {
            await animation.finished;
        } catch (error) {
            // Ignore aborted animations from rapid interactions.
        } finally {
            flyer.remove();
        }
    };

    const openDrawer = () => {
        if (!drawer || !backdrop) {
            return;
        }

        window.clearTimeout(drawerHideTimer);
        drawer.hidden = false;
        backdrop.hidden = false;
        drawer.setAttribute('aria-hidden', 'false');

        requestAnimationFrame(() => {
            drawer.classList.add('is-open');
            backdrop.classList.add('is-open');
        });

        document.body.classList.add('cart-drawer-open');
    };

    const closeDrawer = () => {
        if (!drawer || !backdrop) {
            return;
        }

        drawer.setAttribute('aria-hidden', 'true');
        drawer.classList.remove('is-open');
        backdrop.classList.remove('is-open');

        drawerHideTimer = window.setTimeout(() => {
            if (drawer.getAttribute('aria-hidden') === 'true') {
                drawer.hidden = true;
                backdrop.hidden = true;
            }
        }, 320);

        document.body.classList.remove('cart-drawer-open');
    };

    const updateCount = (count) => {
        countTargets.forEach((target) => {
            target.textContent = String(count ?? 0);
        });
    };

    const applyResponse = (payload, options = {}) => {
        if (!payload || typeof payload !== 'object') {
            return;
        }

        if (payload.drawer_html && body) {
            body.innerHTML = payload.drawer_html;
        }

        if (payload.cart && typeof payload.cart === 'object') {
            const nextCount = Number(payload.cart.count ?? 0);
            updateCount(nextCount);

            if (nextCount !== currentCount) {
                pulseCartToggle();
            }

            currentCount = nextCount;
        }

        if (options.menuItemId) {
            flashCartItem(options.menuItemId);
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
        formData.set('_method', 'PATCH');
        formData.set('quantity', String(quantity));

        const payload = await requestJson(url, {
            method: 'POST',
            body: formData,
        });

        applyResponse(payload);
    };

    const removeItem = async (menuItemId) => {
        const url = removeTemplate.replace('__ID__', String(menuItemId));
        const formData = new FormData();
        formData.set('_method', 'DELETE');
        const payload = await requestJson(url, {
            method: 'POST',
            body: formData,
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
                const menuItemId = Number.parseInt(new FormData(form).get('menu_item_id') || '0', 10);
                const animationPromise = animateAddToCart(form);
                const payload = await requestJson(addUrl, {
                    method: 'POST',
                    body: new FormData(form),
                });

                await animationPromise;
                applyResponse(payload, { menuItemId });
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
                flashCartItem(menuItemId);
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
    const heroBackdrop = document.querySelector('[data-home-hero-backdrop]');
    const heroVeil = document.querySelector('[data-home-hero-veil]');
    const heroEmbers = Array.from(document.querySelectorAll('.hero-signature__embers span'));
    const heroVisualStack = document.querySelector('[data-home-hero-visual-stack]');
    const heroOrbitalShell = document.querySelector('[data-home-hero-orbital-shell]');
    const heroOrbitalGlow = heroOrbitalShell?.querySelector('.hero-signature__orbital-glow') ?? null;
    const heroImageShell = document.querySelector('[data-home-hero-image-shell]');
    const heroImage = document.querySelector('[data-home-hero-image]');
    const heroOrbits = Array.from(document.querySelectorAll('[data-home-hero-orbit]'));
    const heroPlanets = Array.from(document.querySelectorAll('[data-home-hero-planet]'));
    const heroPanel = document.querySelector('[data-home-hero-panel]');
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
        const { gsap, ScrollTrigger } = await getGsapRuntime();
        await initGlobalSmoothScroll({ isLiteMotion });

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

        if (heroBackdrop) {
            gsap.fromTo(heroBackdrop, {
                scale: 1,
                yPercent: 0,
            }, {
                scale: isLiteMotion ? 1.03 : 1.08,
                yPercent: -4,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroBackdrop,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.18 : 0.45,
                },
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
            gsap.set(heroVisualStack, {
                autoAlpha: 0,
                y: 18,
                scale: isLiteMotion ? 1.06 : 1.12,
            });

            gsap.to(heroVisualStack, {
                autoAlpha: 1,
                y: 0,
                scale: 1,
                duration: isLiteMotion ? 0.75 : 1,
                ease: 'power4.out',
                delay: 0.08,
            });

            gsap.to(heroVisualStack, {
                yPercent: -5,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroVisualStack,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.16 : 0.38,
                },
            });
        }

        if (heroOrbitalShell) {
            gsap.set(heroOrbitalShell, {
                transformPerspective: 1400,
                transformOrigin: '50% 50%',
                transformStyle: 'preserve-3d',
            });

            homeHeroTimeline.from(heroOrbitalShell, {
                autoAlpha: 0,
                x: 18,
                y: 24,
                scale: 0.94,
                duration: 0.65,
            }, '-=0.5');

            gsap.to(heroOrbitalShell, {
                yPercent: isLiteMotion ? -2 : -6,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroOrbitalShell,
                    start: 'top top+=12',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.16 : 0.42,
                },
            });
        }

        if (heroImageShell) {
            gsap.set(heroImageShell, {
                transformOrigin: '50% 50%',
                force3D: true,
            });

            gsap.to(heroImageShell, {
                rotate: isLiteMotion ? 26 : 72,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroImageShell,
                    start: 'top top+=24',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.16,
                },
            });

            gsap.to(heroImageShell, {
                scale: isLiteMotion ? 1.01 : 1.04,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroImageShell,
                    start: 'top top+=24',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.12 : 0.24,
                },
            });
        }

        if (heroImage) {
            gsap.to(heroImage, {
                rotate: isLiteMotion ? -8 : -18,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroImage,
                    start: 'top top+=24',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.18,
                },
            });
        }

        heroOrbits.forEach((orbit, index) => {
            const direction = orbit.dataset.orbitDirection === '-1' ? -1 : 1;
            const rotationAmount = isLiteMotion ? (18 + (index * 6)) : (52 + (index * 18));

            gsap.to(orbit, {
                rotate: direction * rotationAmount,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || orbit,
                    start: 'top top+=24',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.08 : 0.18,
                },
            });
        });

        if (heroOrbitalGlow) {
            gsap.to(heroOrbitalGlow, {
                scale: isLiteMotion ? 1.03 : 1.1,
                opacity: isLiteMotion ? 0.72 : 0.94,
                ease: 'none',
                scrollTrigger: {
                    trigger: hero || heroOrbitalGlow,
                    start: 'top top+=12',
                    end: 'bottom top',
                    scrub: isLiteMotion ? 0.12 : 0.28,
                },
            });
        }

        if (!isLiteMotion) {
            heroPlanets.forEach((planet, index) => {
                gsap.to(planet, {
                    y: index % 2 === 0 ? -6 : 6,
                    duration: 2.1 + (index * 0.35),
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

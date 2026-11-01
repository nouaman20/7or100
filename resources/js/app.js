import './storefront/product-detail.js';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const toast = document.querySelector('[data-toast]');
    let toastTimer;

    const showToast = (message) => {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('show');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2600);
    };

    document.querySelector('[data-menu-toggle]')?.addEventListener('click', () => sidebar?.classList.toggle('open'));
    document.querySelectorAll('[data-interaction]').forEach((button) => button.addEventListener('click', () => showToast(button.dataset.interaction)));
    window.addEventListener('load', () => window.lucide?.createIcons());
});

document.addEventListener('DOMContentLoaded', () => {
    const toast = document.querySelector('[data-store-toast]');
    let timer;

    const notify = (message) => {
        if (!toast) return;

        toast.textContent = message;
        toast.classList.add('show');
        window.clearTimeout(timer);
        timer = window.setTimeout(() => toast.classList.remove('show'), 2600);
    };

    document.querySelectorAll('[data-store-message]').forEach((button) => {
        button.addEventListener('click', () => notify(button.dataset.storeMessage));
    });

});

document.addEventListener('DOMContentLoaded', () => {
    const storefront = document.body;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!storefront.classList.contains('storefront') || reducedMotion || !('IntersectionObserver' in window)) return;

    const motionItems = document.querySelectorAll(
        '.storefront main > *, .storefront .store-footer > .newsletter-band, .storefront .store-footer > .footer-main, .storefront .store-footer > .footer-bottom, .storefront [data-product-card], .storefront .category-card, .storefront .blog-card, .storefront .testimonial'
    );

    if (!motionItems.length) return;

    storefront.classList.add('store-motion-ready');

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('is-visible');
            currentObserver.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -36px 0px',
        threshold: 0.08,
    });

    motionItems.forEach((item, index) => {
        const isCard = item.matches('[data-product-card], .category-card, .blog-card, .testimonial');
        const stagger = isCard ? index % 5 : Math.min(index, 5);

        item.dataset.motionReveal = '';
        item.style.setProperty('--motion-delay', `${stagger * (isCard ? 32 : 48)}ms`);
        observer.observe(item);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-cart-autopen]');
    const drawer = document.querySelector('#storeCart');
    const openers = document.querySelectorAll('[data-cart-open]');
    const closers = document.querySelectorAll('[data-cart-close]');
    let lastTrigger = null;

    if (!root || !drawer) return;

    const setOpen = (open) => {
        root.classList.toggle('is-open', open);
        drawer.setAttribute('aria-hidden', String(!open));
        drawer.inert = !open;
        document.body.classList.toggle('cart-drawer-visible', open);
        openers.forEach((button) => button.setAttribute('aria-expanded', String(open)));

        if (open) {
            drawer.querySelector('[data-cart-close]:not(.cart-drawer-overlay)')?.focus();
            window.lucide?.createIcons();
        } else {
            lastTrigger?.focus();
        }
    };

    openers.forEach((button) => button.addEventListener('click', () => {
        lastTrigger = button;
        setOpen(true);
    }));
    closers.forEach((button) => button.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && root.classList.contains('is-open')) setOpen(false);
    });

    if (root.dataset.cartAutopen === 'true') {
        lastTrigger = openers[0] || null;
        setOpen(true);
    }
});

// Start Sidebar Button Section
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('profileToggle');
    const dropdown = document.getElementById('profileDropdown');

    if (!toggle || !dropdown) return;

    function setDropdown(open) {
        dropdown.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
    }

    toggle.addEventListener('click', () => {
        setDropdown(dropdown.hidden);
    });

    document.addEventListener('click', (event) => {
        if (!toggle.parentElement.contains(event.target)) {
            setDropdown(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setDropdown(false);
            toggle.focus();
        }
    });
});
// End Sidebar Button Section

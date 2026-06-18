/**
 * Motion UI Animations System
 * Smooth page transitions, accordion tabs, swipe, scroll, and parallax effects.
 */

class MotionUI {
    constructor() {
        this.isTransitioning = false;
        this.touchStartX = 0;
        this.touchEndX = 0;
        this.overlay = null;
        this.init();
    }

    init() {
        this.setupPageTransitions();
        this.setupAccordion();
        this.setupSwipeTransitions();
        this.setupScrollAnimations();
        this.setupParallaxEffect();
        this.setupRevealAnimations();
        this.observeIntersection();
    }

    // ─── PAGE TRANSITIONS ─────────────────────────────────────────────────────

    setupPageTransitions() {
        // DOM is already ready here (we're called from DOMContentLoaded)
        // Add class immediately so the body fades in right away
        document.body.classList.add('page-loaded');

        // window load as a belt-and-suspenders backup
        window.addEventListener('load', () => {
            document.body.classList.add('page-loaded');
        });

        // Intercept nav link clicks for smooth transitions
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') ||
                    href.startsWith('tel:') || link.classList.contains('no-transition') ||
                    link.target === '_blank') {
                    return;
                }

                try {
                    const targetUrl = new URL(href, window.location.href);
                    const isSamePage =
                        targetUrl.pathname === window.location.pathname &&
                        targetUrl.search === window.location.search &&
                        !targetUrl.hash;

                    if (isSamePage || targetUrl.origin !== window.location.origin) return;

                    e.preventDefault();
                    this.transitionToPage(targetUrl.href);
                } catch (_) {
                    // relative URL parsing failed — let browser handle it
                }
            });
        });


    }

    transitionToPage(url) {
        if (this.isTransitioning) return;
        this.isTransitioning = true;
        window.location.href = url;
    }

    // ─── ACCORDION / TABS ─────────────────────────────────────────────────────

    setupAccordion() {
        const categories = document.querySelectorAll('.menu-category');
        if (!categories.length) return;

        categories.forEach((category, index) => {
            const btn = category.querySelector('.category-card');
            const content = category.querySelector('.category-content');
            if (!btn || !content) return;

            // Inject chevron icon into the button
            if (!btn.querySelector('.category-card-chevron')) {
                const chevron = document.createElement('span');
                chevron.className = 'category-card-chevron';
                chevron.setAttribute('aria-hidden', 'true');
                btn.appendChild(chevron);
            }

            // All categories start closed
            btn.setAttribute('aria-expanded', 'false');

            btn.addEventListener('click', () => {
                const isOpen = content.classList.contains('is-open');

                if (isOpen) {
                    // Close
                    content.classList.remove('is-open');
                    btn.classList.remove('is-open');
                    btn.setAttribute('aria-expanded', 'false');
                } else {
                    // Open (optionally close others for accordion behaviour)
                    content.classList.add('is-open');
                    btn.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    // ─── SWIPE TRANSITIONS ────────────────────────────────────────────────────

    setupSwipeTransitions() {
        document.addEventListener('touchstart', (e) => {
            this.touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        document.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        }, { passive: true });
    }

    handleSwipe() {
        const diff = this.touchStartX - this.touchEndX;
        if (Math.abs(diff) < 50) return;

        const currentNav = document.querySelector('.nav-links.active');
        if (!currentNav) return;

        if (diff > 0) {
            this.slideOutContent(currentNav, 'left');
        } else {
            this.slideInContent(currentNav, 'right');
        }
    }

    slideOutContent(element, direction) {
        element.classList.add(`swipe-out-${direction}`);
        setTimeout(() => {
            element.classList.remove(`swipe-out-${direction}`);
            if (direction === 'left') element.classList.remove('active');
        }, 300);
    }

    slideInContent(element, direction) {
        element.classList.add(`swipe-in-${direction}`);
        if (direction === 'right') element.classList.add('active');
        setTimeout(() => {
            element.classList.remove(`swipe-in-${direction}`);
        }, 300);
    }

    // ─── SCROLL ANIMATIONS ────────────────────────────────────────────────────

    setupScrollAnimations() {
        const scrollElements = document.querySelectorAll(
            '[data-scroll-animation], .fade-in, .slide-in, .reveal, .slide-up, .slide-down, .scale-in'
        );

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const animationType = entry.target.getAttribute('data-scroll-animation') || 'fade-in';
                entry.target.classList.add('scroll-visible', `scroll-${animationType}`);
                if (!entry.target.dataset.repeatAnimation) {
                    scrollObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

        scrollElements.forEach(el => scrollObserver.observe(el));
    }

    // ─── REVEAL ANIMATIONS ────────────────────────────────────────────────────

    setupRevealAnimations() {
        document.querySelectorAll('[data-reveal]').forEach(element => {
            const revealType = element.getAttribute('data-reveal') || 'text';
            if (revealType === 'text') this.revealText(element);
            else if (revealType === 'lines') this.revealLines(element);
        });
    }

    revealText(element) {
        const text = element.textContent;
        element.innerHTML = '';
        Array.from(text).forEach((char, index) => {
            const span = document.createElement('span');
            span.textContent = char;
            span.className = 'reveal-char';
            span.style.animationDelay = `${index * 0.02}s`;
            element.appendChild(span);
        });
        element.classList.add('reveal-text-active');
    }

    revealLines(element) {
        const lines = element.innerHTML.split('<br>');
        element.innerHTML = lines.map(line =>
            `<div class="reveal-line"><span>${line}</span></div>`
        ).join('');
        element.querySelectorAll('.reveal-line').forEach((line, index) => {
            line.style.animationDelay = `${index * 0.1}s`;
            line.classList.add('reveal-line-active');
        });
    }

    // ─── PARALLAX ─────────────────────────────────────────────────────────────

    setupParallaxEffect() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        if (!parallaxElements.length) return;

        window.addEventListener('scroll', () => {
            parallaxElements.forEach(element => {
                const speed = parseFloat(element.getAttribute('data-parallax')) || 0.5;
                element.style.transform = `translateY(${window.scrollY * speed}px)`;
            });
        }, { passive: true });
    }

    // ─── INTERSECTION OBSERVER ────────────────────────────────────────────────

    observeIntersection() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    entry.target.dispatchEvent(new CustomEvent('enterView'));
                } else {
                    entry.target.classList.remove('in-view');
                    entry.target.dispatchEvent(new CustomEvent('exitView'));
                }
            });
        }, { threshold: [0, 0.25, 0.5, 0.75, 1] });

        document.querySelectorAll('[data-observe]').forEach(el => observer.observe(el));
    }

    // ─── UTILITY ANIMATIONS ───────────────────────────────────────────────────

    fadeElement(element, duration = 300, fadeOut = true) {
        element.style.transition = `opacity ${duration}ms ease-in-out`;
        element.style.opacity = fadeOut ? '0' : '1';
        return new Promise(resolve => setTimeout(resolve, duration));
    }

    slideElement(element, direction = 'right', distance = 100, duration = 400) {
        const map = { left: [-distance, 0], right: [distance, 0], up: [0, -distance], down: [0, distance] };
        const [x, y] = map[direction];
        element.style.transition = `transform ${duration}ms ease-in-out`;
        element.style.transform = `translate(${x}px, ${y}px)`;
        return new Promise(resolve => {
            setTimeout(() => {
                element.style.transition = '';
                element.style.transform = '';
                resolve();
            }, duration);
        });
    }

    staggerAnimateElements(selector, animationClass, delayMs = 100) {
        document.querySelectorAll(selector).forEach((el, i) => {
            setTimeout(() => el.classList.add(animationClass), i * delayMs);
        });
    }

    bounceElement(element) {
        element.classList.add('bounce');
        setTimeout(() => element.classList.remove('bounce'), 600);
    }

    pulseElement(element, duration = 2000) {
        element.classList.add('pulse');
        setTimeout(() => element.classList.remove('pulse'), duration);
    }

    shakeElement(element) {
        element.classList.add('shake');
        setTimeout(() => element.classList.remove('shake'), 400);
    }

    flipElement(element, axis = 'Y', duration = 600) {
        element.style.transition = `transform ${duration}ms cubic-bezier(0.68, -0.55, 0.265, 1.55)`;
        element.style.transformStyle = 'preserve-3d';
        element.style.transform = `rotate${axis}(360deg)`;
        setTimeout(() => {
            element.style.transition = '';
            element.style.transform = '';
        }, duration);
    }

    glowElement(element, duration = 1000) {
        element.classList.add('glow');
        setTimeout(() => element.classList.remove('glow'), duration);
    }

    rotateElement(element, degrees = 360, duration = 1000) {
        element.style.transition = `transform ${duration}ms ease-in-out`;
        element.style.transform = `rotate(${degrees}deg)`;
        setTimeout(() => {
            element.style.transition = '';
            element.style.transform = '';
        }, duration);
    }
}

// ─── INIT ─────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    window.motionUI = new MotionUI();
});

if (typeof module !== 'undefined' && module.exports) {
    module.exports = MotionUI;
}

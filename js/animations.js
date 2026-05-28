/**
 * Motion UI Animations System
 * Comprehensive animation library for page transitions, swipes, scrolls, and parallax effects
 */

class MotionUI {
    constructor() {
        this.currentPage = null;
        this.isTransitioning = false;
        this.touchStartX = 0;
        this.touchEndX = 0;
        this.init();
    }

    /**
     * Initialize all animation systems
     */
    init() {
        this.setupPageTransitions();
        this.setupSwipeTransitions();
        this.setupScrollAnimations();
        this.setupParallaxEffect();
        this.setupRevealAnimations();
        this.observeIntersection();
    }

    /**
     * PAGE TRANSITION - Smooth fade/slide when switching pages
     */
    setupPageTransitions() {
        // Handle page navigation with transition animation
        document.querySelectorAll('a[href$=".php"]').forEach(link => {
            link.addEventListener('click', (e) => {
                // Skip if it's the current page or external link
                const href = link.getAttribute('href');
                if (href === window.location.pathname) return;
                
                e.preventDefault();
                this.transitionToPage(href);
            });
        });

        // Fade in on page load
        window.addEventListener('load', () => {
            document.body.classList.add('page-loaded');
            this.triggerPageEnterAnimation();
        });
    }

    /**
     * Execute page transition animation
     */
    transitionToPage(url) {
        if (this.isTransitioning) return;
        this.isTransitioning = true;

        const mainContent = document.querySelector('main') || document.body;
        
        // Fade out current content
        mainContent.classList.add('transition-fade-out');
        
        setTimeout(() => {
            window.location.href = url;
        }, 400);
    }

    /**
     * Trigger entrance animation for page
     */
    triggerPageEnterAnimation() {
        const mainContent = document.querySelector('main') || document.body;
        mainContent.classList.add('transition-fade-in');
        
        // Stagger animate child sections
        document.querySelectorAll('section').forEach((section, index) => {
            section.style.animationDelay = `${index * 0.1}s`;
            section.classList.add('slide-in-animation');
        });
    }

    /**
     * SWIPE TRANSITION - Mobile swipe to slide content
     */
    setupSwipeTransitions() {
        document.addEventListener('touchstart', (e) => {
            this.touchStartX = e.changedTouches[0].screenX;
        }, false);

        document.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        }, false);
    }

    /**
     * Process swipe gesture
     */
    handleSwipe() {
        const diff = this.touchStartX - this.touchEndX;
        const threshold = 50;

        if (Math.abs(diff) < threshold) return;

        const currentNav = document.querySelector('.nav-links.active');
        if (!currentNav) return;

        if (diff > 0) {
            // Swiped left - close menu with slide out animation
            this.slideOutContent(currentNav, 'left');
        } else {
            // Swiped right - open menu with slide in animation
            this.slideInContent(currentNav, 'right');
        }
    }

    /**
     * Slide out content animation
     */
    slideOutContent(element, direction) {
        element.classList.add(`swipe-out-${direction}`);
        setTimeout(() => {
            element.classList.remove(`swipe-out-${direction}`);
            if (direction === 'left') {
                element.classList.remove('active');
            }
        }, 300);
    }

    /**
     * Slide in content animation
     */
    slideInContent(element, direction) {
        element.classList.add(`swipe-in-${direction}`);
        if (direction === 'right') {
            element.classList.add('active');
        }
        setTimeout(() => {
            element.classList.remove(`swipe-in-${direction}`);
        }, 300);
    }

    /**
     * SCROLL ANIMATIONS - Trigger animations as elements come into view
     */
    setupScrollAnimations() {
        const scrollElements = document.querySelectorAll(
            '[data-scroll-animation], .fade-in, .slide-in, .reveal, .slide-up, .slide-down, .scale-in'
        );

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                const animationType = entry.target.getAttribute('data-scroll-animation') || 'fade-in';
                entry.target.classList.add('scroll-visible');
                entry.target.classList.add(`scroll-${animationType}`);
                
                // Optional: unobserve after animation
                if (!entry.target.dataset.repeatAnimation) {
                    scrollObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });

        scrollElements.forEach(element => {
            scrollObserver.observe(element);
        });
    }

    /**
     * REVEAL ANIMATION - Gradual content appearance
     */
    setupRevealAnimations() {
        const revealElements = document.querySelectorAll('[data-reveal]');

        revealElements.forEach(element => {
            const revealType = element.getAttribute('data-reveal') || 'text';
            
            if (revealType === 'text') {
                this.revealText(element);
            } else if (revealType === 'lines') {
                this.revealLines(element);
            }
        });
    }

    /**
     * Reveal text character by character
     */
    revealText(element) {
        const text = element.textContent;
        element.innerHTML = '';
        
        Array.from(text).forEach((char, index) => {
            const span = document.createElement('span');
            span.textContent = char;
            span.classList.add('reveal-char');
            span.style.animationDelay = `${index * 0.02}s`;
            element.appendChild(span);
        });

        element.classList.add('reveal-text-active');
    }

    /**
     * Reveal content line by line
     */
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

    /**
     * PARALLAX EFFECT - Background moves slower than foreground
     */
    setupParallaxEffect() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        if (parallaxElements.length === 0) return;

        window.addEventListener('scroll', () => {
            parallaxElements.forEach(element => {
                const speed = element.getAttribute('data-parallax') || 0.5;
                const scrollY = window.scrollY;
                element.style.transform = `translateY(${scrollY * speed}px)`;
            });
        }, { passive: true });
    }

    /**
     * FADE TRANSITION - Elements smoothly disappear and appear
     */
    fadeElement(element, duration = 300, fadeOut = true) {
        element.style.transition = `opacity ${duration}ms ease-in-out`;
        
        if (fadeOut) {
            element.style.opacity = '0';
        } else {
            element.style.opacity = '1';
        }

        return new Promise(resolve => {
            setTimeout(resolve, duration);
        });
    }

    /**
     * SLIDE TRANSITION - Content moves horizontally or vertically
     */
    slideElement(element, direction = 'right', distance = 100, duration = 400) {
        const directions = {
            'left': { x: -distance, y: 0 },
            'right': { x: distance, y: 0 },
            'up': { x: 0, y: -distance },
            'down': { x: 0, y: distance }
        };

        const { x, y } = directions[direction];
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

    /**
     * INTERSECTION OBSERVER - Enhanced element observation
     */
    observeIntersection() {
        const observerOptions = {
            threshold: [0, 0.25, 0.5, 0.75, 1],
            rootMargin: '0px'
        };

        const intersectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    entry.target.dispatchEvent(new CustomEvent('enterView'));
                } else {
                    entry.target.classList.remove('in-view');
                    entry.target.dispatchEvent(new CustomEvent('exitView'));
                }
            });
        }, observerOptions);

        document.querySelectorAll('[data-observe]').forEach(element => {
            intersectionObserver.observe(element);
        });
    }

    /**
     * STAGGER ANIMATION - Animate multiple elements with delay
     */
    staggerAnimateElements(selector, animationClass, delayMs = 100) {
        const elements = document.querySelectorAll(selector);
        
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add(animationClass);
            }, index * delayMs);
        });
    }

    /**
     * BOUNCE ANIMATION - Add bounce effect to element
     */
    bounceElement(element) {
        element.classList.add('bounce');
        setTimeout(() => element.classList.remove('bounce'), 600);
    }

    /**
     * PULSE ANIMATION - Add pulse effect to element
     */
    pulseElement(element, duration = 2000) {
        element.classList.add('pulse');
        setTimeout(() => element.classList.remove('pulse'), duration);
    }

    /**
     * SHAKE ANIMATION - Add shake effect
     */
    shakeElement(element) {
        element.classList.add('shake');
        setTimeout(() => element.classList.remove('shake'), 400);
    }

    /**
     * FLIP ANIMATION - Flip element on axis
     */
    flipElement(element, axis = 'Y', duration = 600) {
        element.style.transition = `transform ${duration}ms cubic-bezier(0.68, -0.55, 0.265, 1.55)`;
        element.style.transformStyle = 'preserve-3d';
        element.style.transform = `rotate${axis}(360deg)`;

        setTimeout(() => {
            element.style.transition = '';
            element.style.transform = '';
        }, duration);
    }

    /**
     * GLOW ANIMATION - Add and remove glow effect
     */
    glowElement(element, duration = 1000) {
        element.classList.add('glow');
        setTimeout(() => element.classList.remove('glow'), duration);
    }

    /**
     * ROTATE ANIMATION - Rotate element
     */
    rotateElement(element, degrees = 360, duration = 1000) {
        element.style.transition = `transform ${duration}ms ease-in-out`;
        element.style.transform = `rotate(${degrees}deg)`;

        setTimeout(() => {
            element.style.transition = '';
            element.style.transform = '';
        }, duration);
    }
}

/**
 * Initialize MotionUI on DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {
    window.motionUI = new MotionUI();
});

/**
 * Export for use in other scripts
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MotionUI;
}

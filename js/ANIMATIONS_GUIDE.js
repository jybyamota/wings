/**
 * MOTION UI ANIMATIONS - COMPLETE USAGE GUIDE
 * 
 * This guide covers all animation effects available in the Motion UI system.
 * Each animation can be applied via CSS classes or JavaScript API.
 */

/* ================================================================
   1. PAGE TRANSITIONS - Smooth animation when switching pages
   ================================================================ */

// HTML - Add fade-in class to main content:
// <main class="transition-fade-in">
//   <section class="slide-in-animation">Content</section>
// </main>

// CSS Classes:
// - transition-fade-in: Smooth fade-in when page loads
// - transition-fade-out: Fade-out when navigating away
// - slide-in-animation: Section slides in with stagger effect

// JavaScript Usage:
// window.motionUI.transitionToPage('about.php');
// window.motionUI.triggerPageEnterAnimation();


/* ================================================================
   2. SWIPE TRANSITIONS - Mobile swipe to close/open content
   ================================================================ */

// Automatically triggered on mobile swipe gestures
// Works on:
// - Navigation menus
// - Modal dialogs
// - Sidebars

// CSS Classes Generated:
// - swipe-out-left: Content exits to the left
// - swipe-out-right: Content exits to the right
// - swipe-in-left: Content enters from left
// - swipe-in-right: Content enters from right

// JavaScript Usage:
// window.motionUI.slideOutContent(element, 'left');
// window.motionUI.slideInContent(element, 'right');


/* ================================================================
   3. FADE TRANSITIONS - Smooth opacity changes
   ================================================================ */

// CSS Classes:
// - fade-transition-in: Fade in over 0.5s
// - fade-transition-out: Fade out over 0.4s
// - cross-fade: Cross-fade between two elements

// HTML Example:
// <div class="fade-transition-in">
//   <h2>Fading In Content</h2>
// </div>

// JavaScript Usage:
// window.motionUI.fadeElement(element, 300, true); // fade out
// window.motionUI.fadeElement(element, 300, false); // fade in


/* ================================================================
   4. SLIDE TRANSITIONS - Content moves horizontally or vertically
   ================================================================ */

// CSS Classes:
// - slide-transition-up: Slides in from bottom
// - slide-transition-down: Slides in from top
// - slide-transition-left: Slides in from right
// - slide-transition-right: Slides in from left

// HTML Example:
// <section class="slide-transition-up">
//   <p>This content slides up into view</p>
// </section>

// JavaScript Usage:
// window.motionUI.slideElement(element, 'up', 100, 400);
// window.motionUI.slideElement(element, 'left', 50, 600);


/* ================================================================
   5. REVEAL ANIMATIONS - Gradually reveal content
   ================================================================ */

// Text Character-by-Character Reveal:
// HTML:
// <p data-reveal="text">This text reveals one character at a time</p>

// JavaScript:
// window.motionUI.revealText(element);

// Line-by-Line Reveal:
// HTML:
// <div data-reveal="lines">
//   Line 1<br>
//   Line 2<br>
//   Line 3
// </div>

// JavaScript:
// window.motionUI.revealLines(element);

// Clip-Path Reveals (CSS Classes):
// - reveal-top: Reveal from top down
// - reveal-bottom: Reveal from bottom up
// - reveal-left: Reveal from left to right
// - reveal-right: Reveal from right to left

// HTML Example:
// <img class="reveal-left" src="image.jpg" alt="Reveals from left">


/* ================================================================
   6. SCROLL ANIMATIONS - Trigger when element enters viewport
   ================================================================ */

// Automatic Scroll Observer (applied to elements):
// HTML:
// <div class="fade-in">Content fades in on scroll</div>
// <div data-scroll-animation="fade-in">Explicit scroll animation</div>
// <div data-scroll-animation="slide-up">Slides up on scroll</div>

// Available Scroll Animations:
// - scroll-fade-in: Simple fade in
// - scroll-slide-up: Fade and slide up
// - scroll-slide-down: Fade and slide down
// - scroll-slide-left: Fade and slide left
// - scroll-slide-right: Fade and slide right
// - scroll-scale-in: Scale up while fading in
// - scroll-rotate-in: Rotate while scaling in

// Custom data attributes:
// data-scroll-animation="slide-up" — Set animation type
// data-repeat-animation — Repeat animation on re-enter

// JavaScript Usage:
// window.motionUI.staggerAnimateElements('.item', 'scroll-slide-up', 100);


/* ================================================================
   7. PARALLAX EFFECT - Background moves slower than foreground
   ================================================================ */

// HTML - Add data-parallax attribute with speed (0-1):
// <div data-parallax="0.3" style="background-image: url('bg.jpg');">
//   Slowly moving background
// </div>

// Speed Values:
// 0.3 = slow parallax (good for backgrounds)
// 0.5 = medium parallax
// 0.7 = fast parallax (good for foreground elements)

// Advanced Usage:
// CSS Helper Classes:
// [data-parallax="0.3"] — Slow
// [data-parallax="0.5"] — Medium
// [data-parallax="0.7"] — Fast

// The JavaScript automatically updates transform on scroll


/* ================================================================
   8. ADDITIONAL MOTION EFFECTS
   ================================================================ */

// BOUNCE - Pop-in with bounce effect
// HTML: <button class="bounce">Click Me</button>
// JavaScript: window.motionUI.bounceElement(element);

// PULSE - Continuous pulsing effect
// HTML: <div class="pulse">Pulsing content</div>
// JavaScript: window.motionUI.pulseElement(element, 2000);

// SHAKE - Alert/error emphasis
// HTML: <div class="shake">Error message</div>
// JavaScript: window.motionUI.shakeElement(element);

// FLIP - 3D flip animation
// HTML: <card class="flip">Card flips</card>
// Variants: .flip (Y-axis), .flip-x (X-axis)
// JavaScript: window.motionUI.flipElement(element, 'Y', 600);

// GLOW - Glowing highlight effect
// HTML: <button class="glow">Highlighted</button>
// JavaScript: window.motionUI.glowElement(element);

// ROTATE - Spinning animation
// HTML: <spinner class="rotate-360">Loading...</spinner>
// JavaScript: window.motionUI.rotateElement(element, 360, 1000);

// SCALE PULSE - Growing and shrinking
// HTML: <div class="scale-pulse">Scaling</div>

// BLUR IN - Fade in with blur effect
// HTML: <img class="blur-in" src="image.jpg">

// SKEW - Skew animation
// HTML: <div class="skew-in">Skewing content</div>

// WAVE - Waving effect
// HTML: <element class="wave">👋</element>

// SPOTLIGHT - Glowing box-shadow effect
// HTML: <div class="spotlight">Featured item</div>

// GRADIENT SHIFT - Animated gradient background
// HTML: <div class="gradient-shift" style="background: linear-gradient(...)">
// </div>

// POP IN - Scale from 0 to 1 with bounce
// HTML: <modal class="pop-in">Modal pops in</modal>

// SWING - Pendulum effect
// HTML: <element class="swing">Swinging</element>

// WOBBLE - Wobbly side-to-side
// HTML: <element class="wobble">Wobbles</element>

// ATTENTION PULSE - Scale pulse for attention
// HTML: <notification class="attention-pulse">New message</notification>


/* ================================================================
   9. INTERSECTION OBSERVER - Custom element observation
   ================================================================ */

// HTML - Elements to observe:
// <div data-observe>Element in view fires enterView/exitView events</div>

// JavaScript - Listen to events:
// element.addEventListener('enterView', () => {
//   console.log('Element entered viewport');
//   element.classList.add('animate-fade-in');
// });

// element.addEventListener('exitView', () => {
//   console.log('Element exited viewport');
// });


/* ================================================================
   10. COMPLETE PRACTICAL EXAMPLES
   ================================================================ */

/* Example 1: Hero Section with Multiple Animations */
// HTML:
// <section id="hero">
//   <div class="hero-content transition-fade-in">
//     <h1 class="slide-transition-up">Welcome</h1>
//     <p class="slide-transition-up" style="animation-delay: 0.2s">Amazing content</p>
//     <button class="pop-in" style="animation-delay: 0.4s">Get Started</button>
//   </div>
//   <div data-parallax="0.3" class="hero-background"></div>
// </section>

/* Example 2: Card Grid with Staggered Animation */
// HTML:
// <div class="card-grid animate-stagger">
//   <article class="card">Card 1</article>
//   <article class="card">Card 2</article>
//   <article class="card">Card 3</article>
// </div>

/* Example 3: Reveal Text on Scroll */
// HTML:
// <h2 data-reveal="text">Revealing this text character by character</h2>
// <p data-observe class="scroll-slide-up">This slides up when visible</p>

/* Example 4: Image Gallery with Parallax */
// HTML:
// <div class="gallery">
//   <img data-parallax="0.2" src="photo1.jpg" alt="Gallery">
//   <img data-parallax="0.4" src="photo2.jpg" alt="Gallery">
//   <img data-parallax="0.6" src="photo3.jpg" alt="Gallery">
// </div>

/* Example 5: Form with Entrance Animations */
// HTML:
// <form>
//   <input class="slide-transition-left" type="text" placeholder="Name">
//   <input class="slide-transition-left" type="email" placeholder="Email" 
//          style="animation-delay: 0.1s">
//   <button class="pop-in" style="animation-delay: 0.2s">Submit</button>
// </form>


/* ================================================================
   11. PERFORMANCE TIPS
   ================================================================ */

// Use will-change CSS for better performance:
// .animated-element {
//   will-change: transform, opacity;
// }

// For complex animations, use requestAnimationFrame:
// const element = document.querySelector('.animated');
// function animate() {
//   element.style.transform = 'translateX(100px)';
//   requestAnimationFrame(animate);
// }

// Debounce scroll events:
// Use Intersection Observer instead of scroll listeners
// MotionUI already handles this efficiently

// Reduce animations on low-end devices:
// if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
//   document.body.style.animation = 'none';
// }


/* ================================================================
   12. ACCESSIBILITY CONSIDERATIONS
   ================================================================ */

// Respect user's motion preferences:
// CSS will automatically respect prefers-reduced-motion
// Animations are still visible but simplified

// Ensure animations don't distract from content:
// Use animation-duration of 0.3s to 1s
// Avoid infinite animations for content

// Keyboard navigation works with animations:
// All interactive elements remain focusable

// Screen readers aren't affected:
// Animations are purely visual enhancements


/* ================================================================
   13. BROWSER COMPATIBILITY
   ================================================================ */

// Modern Browsers (100% Support):
// - Chrome/Edge 90+
// - Firefox 88+
// - Safari 14+
// - Mobile browsers

// Fallback for older browsers:
// CSS animations gracefully degrade
// JavaScript checks for browser capabilities


/* ================================================================
   14. CUSTOM ANIMATION TIMING
   ================================================================ */

// Easing Functions Used:
// - ease-out: Fast start, slow end (default for most)
// - ease-in-out: Slow start and end
// - cubic-bezier(0.34, 1.56, 0.64, 1): Bounce effect
// - linear: Constant speed

// Create custom animations:
// <style>
//   @keyframes customReveal {
//     from { opacity: 0; }
//     to { opacity: 1; }
//   }
//   .custom-reveal {
//     animation: customReveal 1s ease-out;
//   }
// </style>


/* ================================================================
   15. TRIGGERING ANIMATIONS PROGRAMMATICALLY
   ================================================================ */

// Add animation class on user interaction:
// document.querySelector('button').addEventListener('click', () => {
//   const element = document.querySelector('.target');
//   element.classList.add('bounce');
//   setTimeout(() => element.classList.remove('bounce'), 600);
// });

// Create custom animation sequences:
// async function animateSequence() {
//   await window.motionUI.fadeElement(el1, 300, true);
//   await window.motionUI.slideElement(el2, 'up', 100, 400);
//   await window.motionUI.fadeElement(el3, 300, false);
// }
// animateSequence();


/* ================================================================
   QUICK REFERENCE - MOST COMMON USE CASES
   ================================================================ */

// Page Load Animation:
// <main class="transition-fade-in">

// Scroll-triggered reveal:
// <section class="fade-in" data-scroll-animation="slide-up">

// Mobile menu slide:
// <nav class="swipe-in-right"> (auto-applied on swipe)

// Emphasis/Attention:
// element.classList.add('bounce'); // or 'shake', 'pulse', 'glow'

// Background parallax:
// <div data-parallax="0.5" style="background-image: url(...)">

// Card entrance:
// <article class="pop-in">

// Loading state:
// <spinner class="rotate-360">

// Error message:
// <div class="shake">Error: Invalid input</div>

// Success feedback:
// <div class="bounce pop-in">✓ Success!</div>


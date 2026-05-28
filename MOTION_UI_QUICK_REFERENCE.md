# Motion UI - Quick Reference Guide

## Overview
Complete animation system with 50+ animations for page transitions, scrolls, parallax effects, and interactive feedback.

---

## 1️⃣ PAGE TRANSITIONS
**When pages load or navigate**

```html
<!-- Auto-apply on main content -->
<main class="transition-fade-in">
    <section class="slide-in-animation">Content</section>
</main>
```

| CSS Class | Effect | Duration |
|-----------|--------|----------|
| `transition-fade-in` | Fade in | 0.6s |
| `transition-fade-out` | Fade out | 0.4s |
| `slide-in-animation` | Slide in with stagger | 0.7s |

---

## 2️⃣ SWIPE TRANSITIONS
**Mobile gestures - auto-triggered**

```html
<!-- Auto-applied on swipe -->
<nav class="nav-links swipe-in-right">Navigation</nav>
```

| CSS Class | Direction | Use Case |
|-----------|-----------|----------|
| `swipe-in-left` | Left entry | Mobile menu open |
| `swipe-in-right` | Right entry | Menu open |
| `swipe-out-left` | Left exit | Close menu |
| `swipe-out-right` | Right exit | Close menu |

---

## 3️⃣ FADE TRANSITIONS
**Simple opacity changes**

```html
<div class="fade-transition-in">Fades in smoothly</div>
<div class="fade-transition-out">Fades out smoothly</div>
```

| CSS Class | Effect |
|-----------|--------|
| `fade-transition-in` | 0.5s fade in |
| `fade-transition-out` | 0.4s fade out |
| `cross-fade` | Crossfade effect |

---

## 4️⃣ SLIDE TRANSITIONS
**Directional movement**

```html
<section class="slide-transition-up">Slides up</section>
<section class="slide-transition-down">Slides down</section>
<section class="slide-transition-left">Slides left</section>
<section class="slide-transition-right">Slides right</section>
```

| CSS Class | Direction |
|-----------|-----------|
| `slide-transition-up` | Bottom → Top |
| `slide-transition-down` | Top → Bottom |
| `slide-transition-left` | Right → Left |
| `slide-transition-right` | Left → Right |

---

## 5️⃣ REVEAL ANIMATIONS
**Clip-path reveals**

```html
<!-- Clip-path reveals -->
<img class="reveal-top" src="img.jpg" alt="">
<img class="reveal-bottom" src="img.jpg" alt="">
<img class="reveal-left" src="img.jpg" alt="">
<img class="reveal-right" src="img.jpg" alt="">

<!-- Text reveals -->
<h2 data-reveal="text">Character by character</h2>
<div data-reveal="lines">
    Line 1<br>
    Line 2<br>
    Line 3
</div>
```

| CSS Class | Effect |
|-----------|--------|
| `reveal-top` | Reveal from top |
| `reveal-bottom` | Reveal from bottom |
| `reveal-left` | Reveal from left |
| `reveal-right` | Reveal from right |

---

## 6️⃣ SCROLL ANIMATIONS
**Triggered entering viewport**

```html
<!-- Auto-observe on scroll -->
<div class="fade-in">Fades in on scroll</div>

<!-- With specific animation -->
<section data-scroll-animation="slide-up">
    Slides up when visible
</section>

<!-- Repeat on each view -->
<div data-scroll-animation="fade-in" data-repeat-animation>
    Animates each time visible
</div>
```

| CSS Class | Effect | Use Case |
|-----------|--------|----------|
| `scroll-fade-in` | Simple fade | Content reveal |
| `scroll-slide-up` | Fade + slide up | Cards, images |
| `scroll-slide-down` | Fade + slide down | Top entries |
| `scroll-slide-left` | Fade + slide left | Left entries |
| `scroll-slide-right` | Fade + slide right | Right entries |
| `scroll-scale-in` | Scale + fade | Emphasis |
| `scroll-rotate-in` | Rotate + scale | Special items |

---

## 7️⃣ PARALLAX EFFECT
**Depth through different scroll speeds**

```html
<!-- Parallax background (moves slow) -->
<div data-parallax="0.3" style="background-image: url('bg.jpg');">
    Background content
</div>

<!-- Parallax foreground (moves normal) -->
<div data-parallax="0.5" class="parallax-element">
    Mid-ground content
</div>

<!-- Fast parallax -->
<div data-parallax="0.7">Quick moving element</div>
```

| Speed Value | Effect | Best For |
|------------|--------|----------|
| `0.3` | Very slow | Backgrounds |
| `0.5` | Medium | Mid-ground |
| `0.7` | Fast | Foreground |

---

## 8️⃣ ADVANCED EFFECTS
**Interactive animations**

```html
<!-- Bounce in -->
<button class="bounce">Click me!</button>

<!-- Pulsing -->
<div class="pulse">Pulsing content</div>

<!-- Shake (for errors) -->
<div class="shake">Error message</div>

<!-- Glow (for attention) -->
<button class="glow">Featured item</button>

<!-- Pop in (scale from 0) -->
<modal class="pop-in">Modal pops in</modal>

<!-- Spinning -->
<loader class="rotate-360">Loading...</loader>

<!-- 3D flip -->
<card class="flip">Card flips on Y</card>
<card class="flip-x">Card flips on X</card>

<!-- Blur fade in -->
<img class="blur-in" src="image.jpg">

<!-- Skew entrance -->
<div class="skew-in">Skewed entry</div>

<!-- Wave motion -->
<hand class="wave">👋</hand>

<!-- Spotlight effect -->
<featured class="spotlight">Glowing item</featured>

<!-- Scale pulse -->
<notice class="scale-pulse">Growing notice</notice>

<!-- Wobble -->
<element class="wobble">Wobbly element</element>

<!-- Swing pendulum -->
<element class="swing">Swinging</element>

<!-- Attention seeker -->
<button class="attention-pulse">Get attention</button>
```

| CSS Class | Duration | Effect |
|-----------|----------|--------|
| `bounce` | 0.6s | Pop-in bounce |
| `pulse` | 2s | Pulsing loop |
| `shake` | 0.4s | Emphasis shake |
| `glow` | 1s | Glowing effect |
| `pop-in` | 0.4s | Scale from 0 |
| `rotate-360` | 1s | Full rotation |
| `flip` | 0.6s | Y-axis flip |
| `flip-x` | 0.6s | X-axis flip |
| `blur-in` | 0.6s | Blur fade |
| `skew-in` | 0.5s | Skew entry |
| `wave` | 0.6s | Wave motion |
| `wobble` | 0.8s | Side wobble |
| `swing` | 0.6s | Pendulum |
| `scale-pulse` | 1.5s | Growing pulse |

---

## 🎮 JAVASCRIPT API

```javascript
// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
    // Access the global MotionUI object
    const motion = window.motionUI;

    // Animate elements
    motion.bounceElement(element);           // Pop-in bounce
    motion.pulseElement(element, 2000);      // Pulse for 2 seconds
    motion.shakeElement(element);            // Shake effect
    motion.flipElement(element, 'Y', 600);   // 3D flip
    motion.glowElement(element);             // Glow effect
    motion.rotateElement(element, 360, 1000); // Rotate 360°

    // Transitions
    motion.fadeElement(element, 300, true);   // Fade out
    motion.fadeElement(element, 300, false);  // Fade in
    motion.slideElement(element, 'up', 100, 400);    // Slide up
    motion.slideElement(element, 'left', 50, 600);   // Slide left

    // Text reveals
    motion.revealText(element);    // Character by character
    motion.revealLines(element);   // Line by line

    // Page navigation
    motion.transitionToPage('about.php');      // Navigate with animation
    motion.triggerPageEnterAnimation();        // Animate page load

    // Stagger animations
    motion.staggerAnimateElements('.item', 'scroll-slide-up', 100);
});
```

---

## ⚡ STAGGERED ANIMATIONS
**Multiple elements with delays**

```html
<!-- Auto-stagger child elements -->
<div class="animate-stagger">
    <article class="item">Card 1</article>
    <article class="item">Card 2</article>
    <article class="item">Card 3</article>
    <article class="item">Card 4</article>
    <article class="item">Card 5</article>
    <article class="item">Card 6</article>
</div>
```

Each child gets sequential delay automatically!

---

## 🎯 MOST COMMON USE CASES

### Hero Section
```html
<section id="hero" class="transition-fade-in">
    <h1 class="slide-transition-up">Welcome</h1>
    <p class="slide-transition-up" style="animation-delay: 0.2s;">
        Subtitle
    </p>
    <button class="pop-in" style="animation-delay: 0.4s;">
        Call to Action
    </button>
</section>
```

### Product Cards
```html
<div class="animate-stagger">
    <article class="card fade-in appear">Product 1</article>
    <article class="card fade-in appear">Product 2</article>
    <article class="card fade-in appear">Product 3</article>
</div>
```

### Feature Section
```html
<section data-parallax="0.3" style="background-image: url(...)">
    <h2 data-scroll-animation="fade-in">Feature Title</h2>
    <p data-scroll-animation="slide-up">Feature description</p>
    <img class="scroll-rotate-in" src="feature.jpg">
</section>
```

### Navigation Menu
```html
<nav class="nav-links">
    <!-- Auto-applies swipe animations on mobile -->
    <a href="/">Home</a>
    <a href="/about">About</a>
    <a href="/menu">Menu</a>
</nav>
```

### Error Feedback
```html
<form>
    <input type="email" id="email">
    <div id="error" class="shake" style="display: none; color: red;">
        Invalid email address
    </div>
</form>

<script>
    document.getElementById('email').addEventListener('invalid', () => {
        const error = document.getElementById('error');
        error.style.display = 'block';
        window.motionUI.shakeElement(error);
    });
</script>
```

### Success Message
```html
<div id="success" class="pop-in bounce glow" 
     style="display: none; color: green; padding: 1rem;">
    ✓ Success!
</div>

<script>
    // Show on success
    document.getElementById('success').style.display = 'block';
</script>
```

---

## 📊 ANIMATION TIMING REFERENCE

| Duration | Best For |
|----------|----------|
| 0.3s - 0.4s | Quick UI feedback, micro-interactions |
| 0.5s - 0.7s | Page transitions, slides, fades |
| 0.8s - 1s | Entrance animations, scroll reveals |
| 1.5s - 2s | Looping animations (pulse, bounce) |
| 3s+ | Background animations, subtle effects |

---

## 🔍 TESTING THE SYSTEM

Open the demo page: `motion-ui-demo.html`
- See all animations live
- Click buttons to trigger effects
- Scroll to see scroll animations
- Try on mobile for swipe gestures

---

## 💡 PRO TIPS

1. **Don't Overdo It** - Use 1-2 animations per element max
2. **Performance** - Use CSS animations instead of JavaScript when possible
3. **Mobile First** - Test swipe animations on actual devices
4. **Accessibility** - Animations respect `prefers-reduced-motion`
5. **Stagger Elements** - Use `animate-stagger` for groups
6. **Delay Properly** - Stagger delays should be 0.1s-0.2s apart
7. **Feedback** - Use bounce/shake for user feedback
8. **Emphasis** - Use glow/pulse for attention-seeking
9. **Parallax** - Use speeds < 0.5 for backgrounds
10. **Test** - Always test on slow devices

---

## 📱 BROWSER SUPPORT
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Android)

---

## 🐛 TROUBLESHOOTING

**Animations not working?**
1. Make sure `animations.js` is loaded before main content
2. Check for typos in CSS class names
3. Verify elements have correct selectors
4. Check console for JavaScript errors

**Performance issues?**
1. Reduce number of simultaneous animations
2. Use CSS animations (faster than JS)
3. Close browser tabs
4. Test on more powerful device

**Scroll animations not triggering?**
1. Add `data-scroll-animation="fade-in"` attribute
2. Or add `fade-in` class
3. Make sure element is in viewport when scrolling

---

## 📚 FILE LOCATIONS

- **Main Animation JS**: `js/animations.js`
- **CSS Animations**: `css/style.css` (Motion UI section)
- **Usage Guide**: `js/ANIMATIONS_GUIDE.js`
- **Demo Page**: `motion-ui-demo.html`
- **This Guide**: `MOTION_UI_QUICK_REFERENCE.md`

---

## 🚀 GET STARTED

1. The system loads automatically on all pages
2. Add CSS classes to any element
3. Or use JavaScript API via `window.motionUI`
4. Visit the demo page to see examples
5. Refer to this guide for quick lookups

**Happy animating! 🎉**

<?php

declare(strict_types=1);

$pageTitle = 'The Ultimate Wings Experience';
$currentPage = 'home';
$navScrolled = false;

require_once __DIR__ . '/includes/header.php';

?>
    <section id="hero" class="hero transition-fade-in">
        <div class="hero-overlay"></div>
        <div class="hero-content slide-transition-up">
            <p class="hero-eyebrow" style="animation-delay: 0.1s;"><?= SITE_TAGLINE ?></p>
            <h1 style="animation-delay: 0.2s;">EAT LIKE A<br><span>WING MASTER!</span></h1>
            <p class="hero-lead" style="animation-delay: 0.3s;">Discover unlimited wings, silog meals, and bold flavors made for sharing. Bring your tropa for an unforgettable feast—celebrate, indulge, and eat like a Wing Master today.</p>
            <div class="hero-actions" style="animation-delay: 0.4s;">
                <button type="button" class="btn btn-primary js-open-menu fade-transition-in" id="view-menu-btn">View Menu</button>
            </div>
        </div>
        <a href="#discover" class="scroll-indicator" aria-label="Scroll to explore">
            <span>Scroll</span>
            <span class="scroll-line"></span>
        </a>
    </section>

    <main class="home-sections transition-fade-in">
        <section id="discover" class="split-section split-section--light fade-transition-in">
            <div class="split-media scroll-rotate-in" style="background-image: url('images/unlimited-rice-wings-tray.png'); background-position: 56% center;" data-scroll-animation="rotate-in"></div>
            <div class="split-content scroll-slide-up" data-scroll-animation="slide-up">
                <h3 class="split-label">Our Menu</h3>
                <p>From unlimited rice and wings to sizzling platters, combo snacks, and refreshing drinks—every craving has a flavor waiting for you.</p>
                <button type="button" class="btn btn-dark js-open-menu fade-transition-in">Explore Menu</button>
            </div>
        </section>

        <section id="story" class="split-section fade-transition-in">
            <div class="split-content scroll-slide-down" data-parallax="0.3" data-scroll-animation="slide-down">
                <h3 class="split-label">Our Story</h3>
                <p>Wing Master brings the heat to Samal with handcrafted wings, hearty silog, and generous portions made for families and friends. Quality ingredients, signature sauces, and warm hospitality—every visit feels like a celebration.</p>
                <a href="<?= page_url('about.php') ?>" class="link-arrow">Learn more</a>
            </div>
            <div class="split-media scroll-scale-in" style="background-image: url('images/story-wing-platter.png'); background-position: 70% center;" data-parallax="0.5" data-scroll-animation="scale-in"></div>
        </section>



        <section id="highlights" class="promo-banner scroll-fade-in" data-scroll-animation="fade-in">
            <div class="container promo-banner-inner">
                <h2 class="slide-transition-up" style="animation-delay: 0.1s;">UNLIMITED WINGS &amp; SILOG</h2>
                <p style="animation-delay: 0.2s;">Signature flavors, sharing platters, and the best wings experience in Samal—feast without limits.</p>
                <button type="button" class="btn btn-primary js-open-menu fade-transition-in" style="animation-delay: 0.3s;">View Full Menu</button>
            </div>
        </section>

        <section id="subscribe" class="subscribe-section">
            <div class="container subscribe-inner scroll-slide-up" data-scroll-animation="slide-up">
                <h2 class="slide-transition-up" style="animation-delay: 0.1s;">Stay in the Loop</h2>
                <p style="animation-delay: 0.2s;">Sign up for promos, new flavors, and updates from Wing Master.</p>
                <form class="subscribe-form" action="subscribe.php" method="post" style="animation-delay: 0.3s;">
                    <input type="email" name="email" placeholder="Email address" aria-label="Email address" required>
                    <button type="submit" class="btn btn-primary pulse">Sign Up</button>
                </form>
                <p class="subscribe-note">Thank you for subscribing!</p>
            </div>
        </section>
    </main>

<?php
require_once __DIR__ . '/includes/menu-popup.php';
require_once __DIR__ . '/includes/footer.php';

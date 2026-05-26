<?php

declare(strict_types=1);

$pageTitle = 'The Ultimate Wings Experience';
$currentPage = 'home';
$navScrolled = false;

require_once __DIR__ . '/includes/header.php';

?>
    <section id="hero" class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content fade-in appear">
            <p class="hero-eyebrow"><?= SITE_TAGLINE ?></p>
            <h1>EAT LIKE A<br><span>WING MASTER!</span></h1>
            <p class="hero-lead">Discover unlimited wings, silog meals, and bold flavors made for sharing. Bring your tropa for an unforgettable feast—celebrate, indulge, and eat like a Wing Master today.</p>
            <div class="hero-actions">
                <button type="button" class="btn btn-primary js-open-menu" id="view-menu-btn">View Menu</button>
                <a href="#visit" class="btn btn-outline">Visit Us</a>
            </div>
        </div>
        <a href="#discover" class="scroll-indicator" aria-label="Scroll to explore">
            <span>Scroll</span>
            <span class="scroll-line"></span>
        </a>
    </section>

    <main class="home-sections">
        <section id="discover" class="split-section split-section--light">
            <div class="split-media fade-in" style="background-image: url('images/unlimited-rice-wings.jpg');"></div>
            <div class="split-content fade-in">
                <h3 class="split-label">Our Menu</h3>
                <p>From unlimited rice and wings to sizzling platters, combo snacks, and refreshing drinks—every craving has a flavor waiting for you.</p>
                <button type="button" class="btn btn-dark js-open-menu">Explore Menu</button>
            </div>
        </section>

        <section id="story" class="split-section">
            <div class="split-content fade-in">
                <h3 class="split-label">Our Story</h3>
                <p>Wing Master brings the heat to Samal with handcrafted wings, hearty silog, and generous portions made for families and friends. Quality ingredients, signature sauces, and warm hospitality—every visit feels like a celebration.</p>
                <a href="<?= page_url('about.php') ?>" class="link-arrow">Learn more</a>
            </div>
            <div class="split-media fade-in" style="background-image: url('images/tropa-sharing-platter.jpg');"></div>
        </section>

        <section id="visit" class="split-section split-section--light">
            <div class="split-media fade-in" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop');"></div>
            <div class="split-content fade-in">
                <h3 class="split-label">Visit Us</h3>
                <p>Drop by for dine-in with your tropa or message us to ask about hours and availability. We're ready when you are—come hungry and leave satisfied.</p>
                <ul class="visit-details">
                    <li><strong>Hours</strong> <?= SITE_HOURS ?></li>
                    <li><strong>Call</strong> <a href="tel:09091639984"><?= SITE_PHONE ?></a></li>
                    <li><strong>Email</strong> <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></li>
                </ul>
                <a href="#footer" class="btn btn-dark">Get in Touch</a>
            </div>
        </section>

        <section id="highlights" class="promo-banner fade-in">
            <div class="container promo-banner-inner">
                <h2>UNLIMITED WINGS &amp; SILOG</h2>
                <p>Signature flavors, sharing platters, and the best wings experience in Samal—feast without limits.</p>
                <button type="button" class="btn btn-primary js-open-menu">View Full Menu</button>
            </div>
        </section>

        <section id="subscribe" class="subscribe-section">
            <div class="container subscribe-inner fade-in">
                <h2>Stay in the Loop</h2>
                <p>Sign up for promos, new flavors, and updates from Wing Master.</p>
                <form class="subscribe-form" action="subscribe.php" method="post">
                    <input type="email" name="email" placeholder="Email address" aria-label="Email address" required>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <p class="subscribe-note">Thank you for subscribing!</p>
            </div>
        </section>
    </main>

<?php
require_once __DIR__ . '/includes/menu-popup.php';
require_once __DIR__ . '/includes/footer.php';

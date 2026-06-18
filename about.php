<?php

declare(strict_types=1);

$pageTitle = 'About Us - Wing Master';
$currentPage = 'about';
$navScrolled = false;

require_once __DIR__ . '/includes/header.php';

?>
    <header class="page-hero about-hero transition-fade-in">
        <div class="about-hero-overlay"></div>
        <div class="container about-hero-inner slide-transition-up">
            <p class="hero-eyebrow" style="animation-delay: 0.1s;">About <?= SITE_NAME ?></p>
            <h1 style="animation-delay: 0.2s;">Built for Big Cravings</h1>
            <p class="hero-lead" style="animation-delay: 0.3s;">Wing Master brings Samal together with saucy wings, hearty silog meals, sizzling plates, and sharing platters made for every kind of feast.</p>
            <div class="hero-actions" style="animation-delay: 0.4s;">
                <a href="<?= page_url('menu.php') ?>" class="btn btn-primary fade-transition-in">Explore Menu</a>
                <a href="<?= page_url('reservation.php') ?>" class="btn btn-outline fade-transition-in">Reserve Table</a>
            </div>
        </div>
    </header>

    <main class="about-page transition-fade-in">
        <section class="split-section split-section--light fade-transition-in">
            <div class="split-media scroll-scale-in" style="background-image: url('images/story-wing-platter.png'); background-position: 68% center;" data-scroll-animation="scale-in"></div>
            <div class="split-content scroll-slide-up" data-scroll-animation="slide-up">
                <h3 class="split-label">Our Story</h3>
                <h2 class="about-section-title">Flavor made for the whole table</h2>
                <p>We started with a simple idea: serve food that feels generous, flavorful, and easy to share. From crispy wings tossed in bold sauces to silog favorites and sizzling comfort plates, every order is made to turn an ordinary meal into a small celebration.</p>
                <a href="<?= page_url('wings-flavors.php') ?>" class="link-arrow">See Our Flavors</a>
            </div>
        </section>

        <section class="split-section fade-transition-in">
            <div class="split-content scroll-slide-down" data-scroll-animation="slide-down">
                <h3 class="split-label">The Wing Master Way</h3>
                <h2 class="about-section-title">Fresh from the kitchen, ready for the crew</h2>
                <p>Whether you are dropping by for a quick rice meal, celebrating with friends, or ordering a platter for the family, we keep the experience simple: good food, satisfying servings, and flavors that make people reach for one more piece.</p>
                <a href="<?= page_url('reservation.php') ?>" class="link-arrow">Plan Your Visit</a>
            </div>
            <div class="split-media scroll-rotate-in" style="background-image: url('images/visit-dining-room.png'); background-position: center;" data-scroll-animation="rotate-in"></div>
        </section>

        <section class="split-section split-section--light about-proof fade-transition-in">
            <div class="split-media scroll-scale-in" style="background-image: url('images/about-sharing-wings.jpg'); background-position: center;" data-scroll-animation="scale-in"></div>
            <div class="split-content scroll-slide-up" data-scroll-animation="slide-up">
                <h3 class="split-label">Why Guests Come Back</h3>
                <h2 class="about-section-title">Hot, hearty, and proudly local</h2>
                <p>Every plate is made around the things guests actually remember: bold flavor, generous servings, and a table that feels easy to come back to.</p>
                <div class="about-proof-list animate-stagger">
                    <article class="about-proof-item">
                        <span>01</span>
                        <strong>Signature Sauces</strong>
                        <p>Sweet, spicy, savory, and creamy flavors give every wing platter its own personality.</p>
                    </article>
                    <article class="about-proof-item">
                        <span>02</span>
                        <strong>Feast Portions</strong>
                        <p>Rice meals, bilao wings, and tropa platters are built for big appetites and shared tables.</p>
                    </article>
                    <article class="about-proof-item">
                        <span>03</span>
                        <strong>Warm Service</strong>
                        <p>Every visit is handled with the kind of welcome that makes guests feel at home.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-banner scroll-fade-in" data-scroll-animation="fade-in">
            <div class="container promo-banner-inner">
                <h2 class="slide-transition-up" style="animation-delay: 0.1s;">Bring Your Tropa Hungry</h2>
                <p style="animation-delay: 0.2s;">Unlimited wings, silog plates, snacks, drinks, and desserts are ready whenever the craving hits.</p>
                <a href="<?= page_url('menu.php') ?>" class="btn btn-primary fade-transition-in" style="animation-delay: 0.3s;">View Full Menu</a>
            </div>
        </section>
    </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

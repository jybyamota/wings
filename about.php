<?php

declare(strict_types=1);

$pageTitle = 'About Us - Wing Master';
$currentPage = 'about';
$navScrolled = false;

require_once __DIR__ . '/includes/header.php';

?>
    <header class="page-hero transition-fade-in">
        <div class="container page-hero-inner slide-transition-up">
            <p class="hero-eyebrow" style="animation-delay: 0.1s;">About <?= SITE_NAME ?></p>
            <h1 style="animation-delay: 0.2s;">Why Choose Us</h1>
            <p class="hero-lead" style="animation-delay: 0.3s;">We take wings seriously. Here's what sets us apart from the rest.</p>
        </div>
    </header>

    <section id="features" class="features page-content fade-transition-in">
        <div class="container">
            <div class="features-grid animate-stagger">
                <div class="feature-card scroll-scale-in fade-transition-in" data-scroll-animation="scale-in">
                    <div class="icon spotlight">🔥</div>
                    <h3>Signature Sauces</h3>
                    <p>Over 15 distinct sauces, from mild garlic parmesan to our fiery ghost pepper challenge.</p>
                </div>
                <div class="feature-card scroll-scale-in fade-transition-in" data-scroll-animation="scale-in">
                    <div class="icon spotlight">🍗</div>
                    <h3>Locally Sourced</h3>
                    <p>We use only the freshest, locally sourced chicken, never frozen, for maximum tenderness.</p>
                </div>
                <div class="feature-card scroll-scale-in fade-transition-in" data-scroll-animation="scale-in">
                    <div class="icon spotlight">🍺</div>
                    <h3>Great Pairings</h3>
                    <p>Refreshing drinks and sides chosen to complement every wing flavor on our menu.</p>
                </div>
            </div>
            <p class="about-cta scroll-slide-up fade-transition-in" data-scroll-animation="slide-up">
                <a href="<?= page_url('index.php') ?>" class="btn btn-primary">Back to Home</a>
            </p>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

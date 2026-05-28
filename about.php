<?php

declare(strict_types=1);

$pageTitle = 'About Us - Wing Master';
$currentPage = 'about';
$navScrolled = true;

require_once __DIR__ . '/includes/header.php';

?>
    <header class="page-hero">
        <div class="container page-hero-inner fade-in">
            <p class="hero-eyebrow">About <?= SITE_NAME ?></p>
            <h1>Why Choose Us</h1>
            <p class="hero-lead">We take wings seriously. Here's what sets us apart from the rest.</p>
        </div>
    </header>

    <section id="features" class="features page-content">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="icon">🔥</div>
                    <h3>Signature Sauces</h3>
                    <p>Over 15 distinct sauces, from mild garlic parmesan to our fiery ghost pepper challenge.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="icon">🍗</div>
                    <h3>Locally Sourced</h3>
                    <p>We use only the freshest, locally sourced chicken, never frozen, for maximum tenderness.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="icon">🍺</div>
                    <h3>Great Pairings</h3>
                    <p>Refreshing drinks and sides chosen to complement every wing flavor on our menu.</p>
                </div>
            </div>
            <p class="about-cta fade-in">
                <a href="<?= page_url('index.php') ?>" class="btn btn-primary">Back to Home</a>
                <a href="<?= page_url('index.php', 'menu') ?>" class="btn btn-dark">View Menu</a>
            </p>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

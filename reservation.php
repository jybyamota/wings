<?php

declare(strict_types=1);

$pageTitle = 'Reservation - Wing Master';
$currentPage = 'reservation';
$navScrolled = false;

require_once __DIR__ . '/includes/header.php';

?>
    <header class="page-hero transition-fade-in">
        <div class="container page-hero-inner slide-transition-up">
            <p class="hero-eyebrow" style="animation-delay: 0.1s;">Reserve Your Table</p>
            <h1 style="animation-delay: 0.2s;">Reservation</h1>
            <p class="hero-lead" style="animation-delay: 0.3s;">Book your table for birthdays, barkada nights, and family celebrations at Wing Master.</p>
        </div>
    </header>

    <section class="split-section split-section--light fade-transition-in">
        <div class="split-content scroll-slide-up" data-scroll-animation="slide-up">
            <h3 class="split-label">Book In Advance</h3>
            <p>Reserve your preferred date and time by calling us directly or sending us a message. We will confirm your reservation as soon as possible.</p>
            <ul class="visit-details">
                <li><strong>Phone</strong> <a href="tel:09091639984"><?= SITE_PHONE ?></a></li>
                <li><strong>Email</strong> <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></li>
                <li><strong>Hours</strong> <?= SITE_HOURS ?></li>
                <li><strong>Facebook</strong> <?= SITE_FACEBOOK ?></li>
            </ul>
            <a href="tel:09091639984" class="btn btn-primary">Reserve Now</a>
        </div>
        <div class="split-media scroll-scale-in" style="background-image: url('images/visit-dining-room.png'); background-position: 56% center;" data-scroll-animation="scale-in"></div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$currentPage = $currentPage ?? 'home';
$navScrolled = $navScrolled ?? false;
$menuClass = 'nav-link-btn js-open-menu';
$visitHref = $currentPage === 'home' ? '#visit' : page_url('index.php', 'visit');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav id="navbar"<?= $navScrolled ? ' class="scrolled"' : '' ?>>
        <div class="container nav-container">
            <a href="<?= page_url('index.php') ?>" class="logo">
                <img src="images/logo.png" alt="Wing Master Silog - Davao's Pride" class="logo-image">
            </a>
            <div class="nav-links fade-transition-in">
                <a href="<?= page_url('index.php') ?>">Home</a>
                <button type="button" class="<?= $menuClass ?>">Menu</button>
                <a href="<?= page_url('wings-flavors.php') ?>">Flavors</a>
                <a href="<?= page_url('about.php') ?>">About</a>
                <a href="<?= htmlspecialchars($visitHref, ENT_QUOTES, 'UTF-8') ?>">Visit Us</a>
                <a href="<?= page_url('reservation.php') ?>" class="no-transition reservation-link">Reservation</a>
            </div>
            <div class="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
    </nav>

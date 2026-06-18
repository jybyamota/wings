<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$currentPage = $currentPage ?? 'home';
$navScrolled = $navScrolled ?? false;
$bodyClass = $currentPage === 'menu' ? 'menu-page' : '';
$cssVersion = (string) filemtime(__DIR__ . '/../css/style.css');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <?php if (in_array($currentPage, ['home', 'menu'], true)): ?>
        <link rel="preload" as="image" href="<?= page_url('images/hero-bg.png') ?>" fetchpriority="high">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= page_url('css/style.css') ?>?v=<?= $cssVersion ?>">
</head>

<body<?= $bodyClass !== '' ? ' class="' . htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
    <nav id="navbar"<?= $navScrolled ? ' class="scrolled"' : '' ?>>
        <div class="container nav-container">
            <a href="<?= page_url('index.php') ?>" class="logo">
                <img src="images/logo.png" alt="Wing Master Silog - Davao's Pride" class="logo-image">
            </a>
            <div class="nav-links fade-transition-in">
                <a href="<?= page_url('index.php') ?>">Home</a>
                <a href="<?= page_url('menu.php') ?>">Menu</a>
                <a href="<?= page_url('wings-flavors.php') ?>">Flavors</a>
                <a href="<?= page_url('about.php') ?>">About</a>
                <a href="<?= page_url('reservation.php') ?>">Reservation</a>
                <?php if ((isset($_SESSION['user_email']) && $currentPage === 'reservation') || (isset($_SESSION['admin_auth']) && $_SESSION['admin_auth'] === true && $currentPage === 'admin')): ?>
                <form method="post" style="display:inline; margin:0 0 0 1.5rem;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" style="background:none; border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.45); font-family:inherit; font-size:0.65rem; font-weight:600; letter-spacing:0.12em; text-transform:uppercase; padding:0.4rem 0.9rem; cursor:pointer; border-radius:2px; transition:all 0.25s; width:auto;" onmouseover="this.style.color='#E8B83C'; this.style.borderColor='rgba(232,184,60,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.45)'; this.style.borderColor='rgba(255,255,255,0.15)'">Sign Out</button>
                </form>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
    </nav>

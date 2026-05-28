<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$animationsVersion = (string) filemtime(__DIR__ . '/../js/animations.js');
$mainVersion = (string) filemtime(__DIR__ . '/../js/main.js');
?>
    <footer id="footer" class="site-footer">
        <div class="container footer-minimal">
            <a href="<?= page_url('index.php') ?>" class="logo footer-logo">WING<span>MASTER</span></a>
            <nav class="footer-nav" aria-label="Footer">
                <a href="<?= page_url('index.php') ?>">Home</a>
                <a href="<?= page_url('menu.php') ?>">Menu</a>
                <a href="<?= page_url('about.php') ?>">About</a>
                <a href="<?= page_url('reservation.php') ?>" class="no-transition reservation-link">Reservation</a>
            </nav>
            <p class="footer-contact"><?= SITE_PHONE ?> · <?= SITE_EMAIL ?> · <?= SITE_FACEBOOK ?></p>
            <p class="footer-bottom">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Motion UI Animations System -->
    <script src="<?= page_url('js/animations.js') ?>?v=<?= $animationsVersion ?>"></script>
    <!-- Main Application Scripts -->
    <script src="<?= page_url('js/main.js') ?>?v=<?= $mainVersion ?>"></script>
</body>

</html>

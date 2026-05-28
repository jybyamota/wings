<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$footerVisitLink = $footerVisitLink ?? (($currentPage ?? 'home') === 'home' ? '#visit' : page_url('index.php', 'visit'));

?>
    <footer id="footer" class="site-footer">
        <div class="container footer-minimal">
            <a href="<?= page_url('index.php') ?>" class="logo footer-logo">WING<span>MASTER</span></a>
            <nav class="footer-nav" aria-label="Footer">
                <a href="<?= page_url('index.php') ?>">Home</a>
                <?php if (($currentPage ?? 'home') === 'home'): ?>
                    <button type="button" class="footer-link-btn js-open-menu">Menu</button>
                <?php else: ?>
                    <a href="<?= page_url('index.php', 'menu') ?>">Menu</a>
                <?php endif; ?>
                <a href="<?= page_url('about.php') ?>">About</a>
                <a href="<?= htmlspecialchars($footerVisitLink, ENT_QUOTES, 'UTF-8') ?>">Visit Us</a>
            </nav>
            <p class="footer-contact"><?= SITE_PHONE ?> · <?= SITE_EMAIL ?> · <?= SITE_FACEBOOK ?></p>
            <p class="footer-bottom">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Motion UI Animations System -->
    <script src="js/animations.js"></script>
    <!-- Main Application Scripts -->
    <script src="js/main.js"></script>
</body>

</html>

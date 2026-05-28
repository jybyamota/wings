<?php

declare(strict_types=1);

$pageTitle = 'Wings Flavors - Wing Master Silog';
$currentPage = 'flavors';

require_once __DIR__ . '/includes/header.php';

?>

    <section class="flavors-hero transition-fade-in">
        <div class="flavors-hero-content slide-transition-up">
            <h1 style="animation-delay: 0.1s;">Our Wing Flavors</h1>
            <p style="animation-delay: 0.2s;">Explore 17 incredible wing flavors crafted to perfection</p>
        </div>
    </section>

    <main class="flavors-section transition-fade-in">
        <div class="container">
            <div class="flavors-grid animate-stagger fade-transition-in">
                <!-- ⭐ BEST SELLERS -->
                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Classic</h3>
                    <p>The timeless favorite - perfectly seasoned crispy wings</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Buffalo Sweet Chili</h3>
                    <p>Tangy buffalo sauce with a hint of sweet chili kick</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Spicy Korean Sriracha</h3>
                    <p>Hot and flavorful Korean sriracha coating</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Honey Glazed</h3>
                    <p>Sweet and sticky honey glaze with a golden finish</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Garlic Parmesan</h3>
                    <p>Savory garlic and parmesan cheese coating</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Buttered Garlic</h3>
                    <p>Aromatic butter-garlic blend, fragrant and delicious</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Teriyaki</h3>
                    <p>Sweet and savory Japanese-inspired teriyaki glaze</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Salted Egg</h3>
                    <p>Creamy salted egg coating with a unique rich flavor</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Sour Cream Dinakdakan</h3>
                    <p>Savory Filipino sour cream with a modern twist</p>
                </div>

                <div class="flavor-card featured scroll-scale-in" data-scroll-animation="scale-in">
                    <div class="flavor-badge">⭐ BEST SELLER</div>
                    <h3>Classic Cheetos</h3>
                    <p>Crunchy Cheetos coating for extra flavor</p>
                </div>

                <!-- REGULAR FLAVORS -->
                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Barbeque</h3>
                    <p>Smoky and tangy BBQ sauce coating</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Soy Garlic</h3>
                    <p>Classic Filipino soy and garlic combination</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Mango Habanero</h3>
                    <p>Tropical mango with a spicy habanero kick</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Tonkatsu</h3>
                    <p>Japanese-style panko breaded with tonkatsu sauce</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Honey Mustard</h3>
                    <p>Sweet honey with tangy mustard blend</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Buffalo Cheetos</h3>
                    <p>Spicy buffalo with crunchy Cheetos coating</p>
                </div>

                <div class="flavor-card scroll-rotate-in" data-scroll-animation="rotate-in">
                    <h3>Yangnyeom</h3>
                    <p>Korean yangnyeom sauce - sweet and spicy</p>
                </div>
            </div>

            <div class="flavors-cta scroll-slide-up" data-scroll-animation="slide-up">
                <h2 style="animation-delay: 0.1s;">Ready to Order?</h2>
                <p style="animation-delay: 0.2s;">Choose your favorite flavors and enjoy them with rice, fries, or as a standalone snack</p>
                <a href="<?= page_url('menu.php') ?>" class="btn btn-primary fade-transition-in" style="animation-delay: 0.3s;">View Full Menu</a>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

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

    <!-- Item Details Modal (Classy Redesign) -->
    <div id="item-details-modal" class="item-details-modal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="item-modal-backdrop" data-item-modal-close></div>
        <div class="item-modal-panel">
            <button class="item-modal-close" type="button" aria-label="Close details" data-item-modal-close>&times;</button>
            <div class="item-modal-content">
                <div class="item-modal-image-col">
                    <div class="item-modal-image"></div>
                    <span class="item-modal-badge">Chef's Special</span>
                </div>
                <div class="item-modal-info-col">
                    <span class="item-modal-category">Category</span>
                    <h3 class="item-modal-title">Item Name</h3>
                    <div class="item-modal-stars">★★★★★</div>
                    <p class="item-modal-subtext"></p>
                    <div class="item-modal-price">₱0.00</div>
                    <div class="item-modal-divider"></div>
                    <p class="item-modal-description">Persuasive description goes here...</p>
                    <div class="item-modal-actions">
                        <a href="reservation.php" class="btn btn-primary">Book Table &amp; Order</a>
                        <button type="button" class="btn btn-secondary" data-item-modal-close>Back to Menu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // INLINE EYE ICON HANDLER - Runs immediately after footer loads
    (function attachEyeIconHandlers() {
        const itemDescriptions = {
            "Wings Solo": "Perfect for solo indulgence! 8 pieces of crispy, golden-fried chicken wings, tossed in your choice of our signature glazes. A delicious, crunch-filled escape.",
            "Wings Tropa": "Made for sharing with your favorite crew. 12 pieces of premium, tender wings drenched in mouthwatering sauce. Gather around and dive in!",
            "Bilao Wings A": "Our legendary 30-piece wing platter served in a traditional bilao. Ideal for family gatherings or game nights. Select up to 3 flavors and rule the feast!",
            "Bilao Wings B": "Go bigger with 40 succulent wings. Perfect for parties, loaded with flavor, and guaranteed to satisfy every single craving. Double down on your favorite sauces!",
            "Bilao Wings C": "The ultimate Wing Master feast! 50 massive, crispy wings to satisfy a whole army. Mix and match flavors to experience the absolute pinnacle of Samal's best wings.",
            "Bilao Wings & Shanghai": "The perfect duo: crispy, savory wings paired with golden, crunchy Lumpiang Shanghai. It's the ultimate party platter that keeps everyone coming back for more.",
            "Flavored Wings & Fries": "Two legendary classics united. Crispy, flavor-packed wings served alongside hot, golden French fries. The perfect dynamic duo for snack time.",
            "Flavored Wings & Nachos": "Crispy wings paired with our signature crunchy nachos. Drizzled with warm cheese sauce for an absolute flavor explosion.",
            "Nachos & Quesadilla": "Warm, cheesy, and crunchy! Perfectly toasted beef quesadilla paired with a generous heap of cheesy, loaded nachos. Pure comfort food.",
            "Fries & Nachos": "Can't decide? Get both! A mountain of hot, crispy fries and crunchy nachos loaded with signature toppings and savory cheese.",
            "Popshot & Fries": "Tender, bite-sized chicken popshots paired with crispy, seasoned fries. Dip, munch, and repeat—the ultimate snack-on-the-go!",
            "Italian Spaghetti": "Sweet, savory, and loaded with rich meat sauce and melted cheese. A Filipino-style Italian favorite that tastes like home.",
            "Korean Corndog": "Super crispy on the outside, warm and incredibly cheesy on the inside. Dusted with sugar and drizzled with mustard and ketchup for the perfect sweet-savory pull.",
            "Korean Corndog Trio": "Why have one when you can have three? A triple threat of our signature Korean corndogs, featuring different fillings for the ultimate cheese pull.",
            "Cheesy Beef Quesadilla": "Flaky, grilled tortillas packed with seasoned ground beef and a blend of gooey, melted cheeses. Grilled to toasted perfection.",
            "Classic French Fries": "Hot, golden, and perfectly salted. Simple, classic, and completely addictive.",
            "Flavored French Fries": "Take your fries to the next level. Tossed in your choice of savory cheese, sour cream, or hot BBQ seasoning.",
            "Kropek Crackers": "Light, airy, and ultra-crunchy crackers with a delicious hint of seafood flavor. The perfect light bite to start your meal.",
            "Cheesy Nachos Solo": "A personal portion of crunchy tortilla chips, generously smothered in our rich, warm cheese sauce and savory toppings.",
            "Cheesy Nachos Tropa": "A massive, shareable mountain of crispy nachos loaded with beef, jalapeños, and drizzled with our signature warm cheese sauce. Built for sharing!",
            "Pancit Guisado": "Traditional stir-fried noodles tossed with fresh vegetables, chicken, and savory seasonings. A delicious, comforting staple for any gathering.",
            "Breaded Fish Fillet": "Light, flaky fish fillets coated in seasoned breadcrumbs and fried to a delicate golden crisp. Served with dynamic tartar dipping sauce.",
            "Chicken Fingers": "Tender strips of premium chicken breast, breaded and fried to golden perfection. Juicy on the inside, crunchy on the out.",
            "Lumpiang Shanghai": "Golden-crisp spring rolls packed with seasoned ground pork and aromatic spices. Samal's favorite finger food, served with sweet & sour dip.",
            "Pork Sinigang": "A sour, savory Filipino classic. Tender pork simmered in a rich tamarind broth with fresh local vegetables. Hearty, warm, and comforting.",
            "Bangus Sinigang": "Local milkfish simmered in our signature tangy tamarind broth with fresh garden vegetables. A refreshing, healthy Filipino favorite.",
            "Tuna Kinilaw": "Fresh Samal tuna ceviche, cured in local vinegar, ginger, onions, and chili. A clean, tangy, and spicy seafood delicacy.",
            "Calamares": "Crispy, tender squid rings coated in a seasoned batter and fried to a perfect golden crunch. Served with our house garlic aioli.",
            "Sinuglaw": "The ultimate local culinary fusion: smoky grilled pork belly (sinugba) tossed with fresh cured tuna ceviche (kinilaw). A spectacular explosion of flavor!",
            "Fried Tofu": "Golden, crispy tofu cubes, soft on the inside, served with a savory, tangy soy-vinegar dipping sauce topped with onions.",
            "4pcs Lumpiang Ubod": "Delicate spring rolls stuffed with fresh, tender heart of palm (ubod) and savory fillings, wrapped to perfection.",
            "4pcs Pork Shrimp Siomai": "Steamed dumplings packed with juicy pork and succulent shrimp, served with chili garlic sauce and calamansi.",
            "4pcs Shark's Fin Dumpling": "Delicately steamed dumplings packed with a rich, savory meat filling, a staple favorite for dimsum lovers.",
            "4pcs Japanese Siomai": "Pork dumplings wrapped in a sheet of nori seaweed, steamed to perfection and served with savory dipping sauce.",
            "4pcs Lumpiang Shanghai": "A bite-sized teaser of our famous golden spring rolls, perfect for kickstarting your appetite.",
            "Iced Tea Blend": "House-brewed sweet iced tea, infused with citrus notes. Perfectly chilled and incredibly refreshing.",
            "Iced Tea Pitcher": "A sharing-sized pitcher of our refreshing signature iced tea blend, perfect for quenching the whole table's thirst.",
            "Bottled Softdrinks": "Chilled bottled soda of your choice—crisp, bubbly, and the perfect pairing for hot wings.",
            "Softdrinks in can": "Ice-cold canned sodas, served refreshing and ready to balance the heat of our spicy wings.",
            "Purified Water": "Clean, ice-cold purified water to keep you refreshed and hydrated.",
            "Blue Lemonade": "A sweet, tangy, and visually stunning electric blue lemonade that's as refreshing as it looks.",
            "Pink Lemonade": "A beautiful, ruby-tinted lemonade with a sweet berry twist. Crisp, refreshing, and delicious.",
            "Cucumber Lemonade": "The ultimate refresher. Tangy lemon juice blended with crisp, cool cucumber for a spa-like revitalization.",
            "Lemonade Pitcher": "A family-sized pitcher of our fresh, zesty lemonade. Perfect for sharing with the tropa.",
            "Flavored Wings Rice Meal": "Three pieces of our signature flavored wings served with a hot scoop of rice. The perfect quick, satisfying lunch.",
            "ShanghaiSilog": "Crispy pork Lumpiang Shanghai served with fragrant garlic fried rice and a perfectly fried sunny-side-up egg.",
            "Fish Fillet Rice Meal": "Crispy breaded fish fillet served with warm rice and tangy dipping sauce. Light yet incredibly satisfying.",
            "Calamares Rice Meal": "Golden-fried calamares rings served with warm steamed rice and garlic dipping sauce.",
            "BurgerSteak Rice Meal": "A juicy beef patty smothered in rich, savory mushroom gravy, served with hot rice.",
            "Porkchop Rice Meal": "Tender, seasoned pork chop grilled or fried to a juicy finish, served with a mountain of hot rice.",
            "Shawarma Rice Meal": "Savory, spiced shawarma beef served over a bed of fragrant rice, topped with garlic sauce and fresh vegetables.",
            "Beef Tapsilog": "Traditional cured beef tapa, fried garlic rice, and a sunny-side-up egg. The king of Filipino breakfasts, served all day.",
            "Chicken Fingers Rice Meal": "Golden chicken fingers paired with hot steamed rice and honey mustard dip. Simple and delicious.",
            "Pork Adobo Rice Meal": "A true national treasure. Tender pork simmered in soy sauce, vinegar, and garlic, served over warm rice.",
            "Pork Sisig Rice Meal": "Sizzling, savory, and spicy pork sisig served with rice. Crispy, rich, and full of bold flavors.",
            "Lechon Kawali Rice Meal": "Crispy, deep-fried pork belly with crackling skin, served with a hot scoop of rice and lechon sauce.",
            "Grilled Liempo Rice Meal": "Marinated pork belly grilled over hot coals for a smoky, caramelized glaze. Served with steamed rice.",
            "Sizzling Sisig Tropa": "A massive, sizzling platter of savory pork sisig, seasoned with onions, chilies, and citrus. The ultimate sharing pulutan.",
            "Sizzling Lechon Kawali Tropa": "Crispy lechon kawali served on a piping hot sizzling plate, drenched in our signature savory gravy.",
            "Sizzling Buttered Shrimp": "Fresh local shrimp sautéed in rich garlic butter, served sizzling hot for a luxurious, sweet-savory bite.",
            "Sizzling SisigSilog": "Savory pork sisig served sizzling with garlic rice and a fresh egg. Mix it hot for the ultimate bite!",
            "Sizzling LechonSilog": "Crispy lechon kawali served sizzling with savory gravy, garlic rice, and a fried egg.",
            "Sizzling BurgerSteak": "Juicy beef patty served sizzling with a generous pour of rich mushroom gravy, garlic rice, and an egg.",
            "Sizzling LiemSilog": "Smoky grilled pork liempo served sizzling with garlic rice, gravy, and a sunny-side-up egg.",
            "Wings Platter": "A magnificent sharing platter of our famous wings, piled high and tossed in your favorite glazes. Made for sharing!",
            "Wings & Fries Platter": "A crowd-pleasing combo platter featuring a heap of flavored wings and crispy French fries. Perfect for group hangouts.",
            "Wings & Nachos Platter": "A stellar combo of our signature flavored wings and loaded cheesy nachos. Built for sharing with the tropa.",
            "Lechon Kawali Tropa": "A large sharing platter of our crispy-skinned deep-fried pork belly. Crunchy, savory, and completely indulgent.",
            "Pork Sisig Tropa": "A family-sized portion of our spicy, savory pork sisig, perfect for sharing around the table.",
            "Lumpiang Shanghai Tropa": "A mountain of our crispy, golden pork spring rolls, ready to feed the whole gang.",
            "Plain Rice": "A steaming hot scoop of premium white rice, the perfect canvas for our flavorful dishes.",
            "Garlic Rice": "Fragrant, stir-fried rice loaded with golden toasted garlic. A classic Filipino favorite.",
            "Plain Rice Platter": "A sharing-sized platter of steaming hot white rice, enough to feed the entire tropa.",
            "Garlic Rice Platter": "A massive platter of fragrant garlic rice, perfect for sharing with your unlimited wing feast.",
            "Gravy": "Our signature rich, savory house gravy. Thick, warm, and perfect for pouring over everything.",
            "Fruit Salad Solo": "A sweet, creamy mixture of tropical fruits, sweet cream, and cheese. A classic Filipino dessert.",
            "Mango Bango Salad": "A refreshing, sweet mango dessert mixed with creamy tapioca pearl dressing. Cool and tropical.",
            "Mango/Avocado Float": "Layered graham crackers, sweet cream, and fresh local mangoes or avocados, chilled to ice-cream perfection."
        };

        const categoryImages = {
            "CHICKEN WINGS": "images/unlimited-rice-wings-sharp.png",
            "COMBO SNACKS": "images/combo-snacks-hd.jpg",
            "SOLO SNACKS": "images/solo-snacks.jpg",
            "SHORT ORDERS": "images/asset-contact-sheet.jpg",
            "APPETIZERS": "images/combo-snacks.jpg",
            "BEVERAGES": "images/beverages.jpg",
            "SINGLE RICE MEALS": "images/sizzling-rice-meal.jpg",
            "SIZZLING": "images/sizzling.jpg",
            "SIZZLING RICE MEAL": "images/sizzling-rice-meal.jpg",
            "TROPA SHARING PLATTER": "images/tropa-sharing-platter.jpg",
            "EXTRA ADD-ONS": "images/unlimited-rice-wings-variety.png",
            "DESSERT": "images/dessert.jpg"
        };

        const badgeList = ["Best Seller", "Tropa's Favorite", "Chef's Choice", "Highly Recommended", "Davao's Pride", "Must Try!"];

        // Attach listeners to eye icons
        function setupListeners() {
            document.querySelectorAll('.menu-item-eye').forEach(eyeIcon => {
                eyeIcon.removeEventListener('click', handleEyeClick);
                eyeIcon.addEventListener('click', handleEyeClick);
            });
        }

        function handleEyeClick(event) {
            event.preventDefault();
            event.stopPropagation();

            const eyeIcon = this;
            const menuItem = eyeIcon.closest('.menu-item');
            if (!menuItem) return;

            const modal = document.getElementById('item-details-modal');
            if (!modal) return;

            const name = menuItem.getAttribute('data-item-name');
            const subtext = menuItem.getAttribute('data-item-sub');
            const price = menuItem.getAttribute('data-item-price');
            const category = menuItem.getAttribute('data-category');
            const itemImage = menuItem.getAttribute('data-item-image');

            modal.querySelector('.item-modal-title').textContent = name;
            modal.querySelector('.item-modal-category').textContent = category;
            modal.querySelector('.item-modal-subtext').textContent = subtext || '';
            modal.querySelector('.item-modal-price').innerHTML = price;
            modal.querySelector('.item-modal-description').textContent = itemDescriptions[name] || 'Delicious food item.';
            modal.querySelector('.item-modal-image').style.backgroundImage = `url('${itemImage || categoryImages[category] || "images/unlimited-rice-wings.jpg"}')`;
            modal.querySelector('.item-modal-badge').textContent = badgeList[Math.floor(Math.random() * badgeList.length)];

            modal.classList.add('is-open');
            document.body.classList.add('item-modal-open');

            eyeIcon.classList.remove('eye-clicked');
            void eyeIcon.offsetWidth;
            eyeIcon.classList.add('eye-clicked');
            setTimeout(() => eyeIcon.classList.remove('eye-clicked'), 500);
        }

        // Close modal function
        function closeItemModal() {
            const modal = document.getElementById('item-details-modal');
            if (modal) {
                modal.classList.remove('is-open');
                document.body.classList.remove('item-modal-open');
            }
        }

        // Initial setup
        setupListeners();

        // Re-setup when accordions expand
        document.querySelectorAll('.menu-category').forEach(category => {
            const button = category.querySelector('button');
            if (button) {
                button.addEventListener('click', () => {
                    setTimeout(setupListeners, 100);
                });
            }
        });

        // Close modal handlers
        const modal = document.getElementById('item-details-modal');
        if (modal) {
            // Handle all close triggers (backdrop, X button, Back button)
            document.querySelectorAll('[data-item-modal-close]').forEach(element => {
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    closeItemModal();
                });
            });
        }
    })();
    </script>

    <!-- Motion UI Animations System -->
    <script src="<?= page_url('js/animations.js') ?>?v=<?= $animationsVersion ?>"></script>
    <!-- Main Application Scripts -->
    <script src="<?= page_url('js/main.js') ?>?v=<?= $mainVersion ?>"></script>
</body>

</html>

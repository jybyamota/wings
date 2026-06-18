document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close menu when a link is clicked
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });
    }

    // 2. Sticky Navbar Effect on Scroll (Disabled as per user request to not overlay on scroll)
    const navbar = document.getElementById('navbar');
    // Navbar positioning is now handled via absolute positioning in CSS to scroll naturally with the page.

    // 3. Scroll Animations (Intersection Observer)
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const appearOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const appearOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, appearOptions);

    fadeElements.forEach(element => {
        appearOnScroll.observe(element);
    });

    const revealFadeIns = container => {
        if (!container) {
            return;
        }

        container.querySelectorAll('.fade-in').forEach(element => {
            // Remove the appear class to reset
            element.classList.remove('appear');
            // Force a reflow to trigger the animation again
            void element.offsetWidth;
            // Add the appear class back to trigger the animation
            element.classList.add('appear');
        });
    };

    // 4. Full-screen sliding menu panel
    const menuSection = document.getElementById('menu');
    const menuPopup = document.getElementById('menu-popup');

    const openMenuPanel = () => {
        if (!menuPopup) {
            window.location.href = 'menu.php';
            return;
        }
        menuPopup.classList.add('is-open');
        menuPopup.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        if (menuSection) {
            revealFadeIns(menuSection);
        }
    };

    const closeMenuPanel = () => {
        if (!menuPopup) {
            return;
        }
        menuPopup.classList.remove('is-open');
        menuPopup.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        if (menuSection) {
            menuSection.querySelectorAll('.fade-in').forEach(element => {
                element.classList.remove('appear');
            });
        }
    };

    document.querySelectorAll('.js-open-menu').forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            openMenuPanel();
        });
    });

    document.querySelectorAll('[data-menu-popup-close]').forEach(control => {
        control.addEventListener('click', closeMenuPanel);
    });

    if (window.location.hash === '#menu' && menuPopup) {
        openMenuPanel();
    }

    document.querySelector('.scroll-indicator')?.addEventListener('click', event => {
        event.preventDefault();
        document.getElementById('discover')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.querySelectorAll('a.reservation-link').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            window.location.href = 'reservation.php';
        });
    });

    const subscribeForm = document.querySelector('.subscribe-form');
    const subscribeNote = document.querySelector('.subscribe-note');

    if (subscribeForm && subscribeNote) {
        subscribeForm.addEventListener('submit', event => {
            event.preventDefault();
            subscribeNote.classList.add('is-visible');
            subscribeForm.reset();
        });
    }

    // 5. Classic card-style menu formatting
    const menuPopupElement = document.getElementById('menu-popup');
    const menuCategories = document.querySelectorAll('.menu-popup .menu-category');

    const applyClassicMenuFormat = () => {
        if (!menuPopupElement || !menuCategories.length) {
            return;
        }

        menuPopupElement.classList.add('menu-format-classic');

        menuCategories.forEach(category => {
            const categoryContent = category.querySelector('.category-content');
            const menuItems = category.querySelectorAll('.menu-item');

            // Accordion open state is managed by animations.js via the is-open class

            menuItems.forEach(item => {
                const itemInfo = item.querySelector('.menu-item-info');
                const itemPrice = item.querySelector('.menu-item-price');

                if (itemInfo && !itemInfo.querySelector('.menu-item-stars')) {
                    const stars = document.createElement('div');
                    stars.className = 'menu-item-stars';
                    stars.textContent = '★★★★★';
                    itemInfo.insertBefore(stars, itemInfo.firstChild);
                }

                if (itemInfo && !itemInfo.querySelector('.menu-item-cta')) {
                    const cta = document.createElement('button');
                    cta.type = 'button';
                    cta.className = 'menu-item-cta';
                    cta.textContent = 'Read More';
                    itemInfo.appendChild(cta);
                }

                if (itemPrice) {
                    itemPrice.classList.add('menu-item-price-tag');
                }
            });
        });
    };

    applyClassicMenuFormat();

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }
        closeMenuPanel();
    });

    // Trigger initial check for elements already in viewport on load
    setTimeout(() => {
        fadeElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if(rect.top < window.innerHeight) {
                element.classList.add('appear');
            }
        });
    }, 100);

    // 6. Scroll Fade-Out Effect
    const fadeOutOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.target.classList.contains('appear')) {
                return;
            }
            if (!entry.isIntersecting) {
                entry.target.classList.add('fade-out-up');
            } else {
                entry.target.classList.remove('fade-out-up');
            }
        });

    // Trigger initial check for elements already in viewport on load
    setTimeout(() => {
        fadeElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if(rect.top < window.innerHeight) {
                element.classList.add('appear');
            }
        });
    }, 100);

    // 6. Scroll Fade-Out Effect
    const fadeOutOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.target.classList.contains('appear')) {
                return;
            }
            if (!entry.isIntersecting) {
                entry.target.classList.add('fade-out-up');
            } else {
                entry.target.classList.remove('fade-out-up');
            }
        });
    }, {
        threshold: [0.1],
        rootMargin: '0px 0px 0px 0px'
    });

    fadeElements.forEach(element => {
        fadeOutOnScroll.observe(element);
    });

    // 7. Item Details Modal Logic
    let itemDetailsModal = document.getElementById('item-details-modal');
    console.log('itemDetailsModal found:', !!itemDetailsModal);
    
    if (!itemDetailsModal) {
        console.log('Creating modal element since it was not found');
        // Create the modal HTML if it doesn't exist
        const modalHTML = `
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
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        itemDetailsModal = document.getElementById('item-details-modal');
    }
    
    if (itemDetailsModal) {
        const modalImage = itemDetailsModal.querySelector('.item-modal-image');
        const modalCategory = itemDetailsModal.querySelector('.item-modal-category');
        const modalTitle = itemDetailsModal.querySelector('.item-modal-title');
        const modalSubtext = itemDetailsModal.querySelector('.item-modal-subtext');
        const modalPrice = itemDetailsModal.querySelector('.item-modal-price');
        const modalDescription = itemDetailsModal.querySelector('.item-modal-description');
        const modalBadge = itemDetailsModal.querySelector('.item-modal-badge');
        
        // High-converting copywriting descriptions mapped by item name
        const itemDescriptions = {
            // Chicken Wings
            "Wings Solo": "Perfect for solo indulgence! 8 pieces of crispy, golden-fried chicken wings, tossed in your choice of our signature glazes. A delicious, crunch-filled escape.",
            "Wings Tropa": "Made for sharing with your favorite crew. 12 pieces of premium, tender wings drenched in mouthwatering sauce. Gather around and dive in!",
            "Bilao Wings A": "Our legendary 30-piece wing platter served in a traditional bilao. Ideal for family gatherings or game nights. Select up to 3 flavors and rule the feast!",
            "Bilao Wings B": "Go bigger with 40 succulent wings. Perfect for parties, loaded with flavor, and guaranteed to satisfy every single craving. Double down on your favorite sauces!",
            "Bilao Wings C": "The ultimate Wing Master feast! 50 massive, crispy wings to satisfy a whole army. Mix and match flavors to experience the absolute pinnacle of Samal's best wings.",
            "Bilao Wings & Shanghai": "The perfect duo: crispy, savory wings paired with golden, crunchy Lumpiang Shanghai. It's the ultimate party platter that keeps everyone coming back for more.",

            // Combo Snacks
            "Flavored Wings & Fries": "Two legendary classics united. Crispy, flavor-packed wings served alongside hot, golden French fries. The perfect dynamic duo for snack time.",
            "Flavored Wings & Nachos": "Crispy wings paired with our signature crunchy nachos. Drizzled with warm cheese sauce for an absolute flavor explosion.",
            "Nachos & Quesadilla": "Warm, cheesy, and crunchy! Perfectly toasted beef quesadilla paired with a generous heap of cheesy, loaded nachos. Pure comfort food.",
            "Fries & Nachos": "Can't decide? Get both! A mountain of hot, crispy fries and crunchy nachos loaded with signature toppings and savory cheese.",
            "Popshot & Fries": "Tender, bite-sized chicken popshots paired with crispy, seasoned fries. Dip, munch, and repeat—the ultimate snack-on-the-go!",

            // Solo Snacks
            "Italian Spaghetti": "Sweet, savory, and loaded with rich meat sauce and melted cheese. A Filipino-style Italian favorite that tastes like home.",
            "Korean Corndog": "Super crispy on the outside, warm and incredibly cheesy on the inside. Dusted with sugar and drizzled with mustard and ketchup for the perfect sweet-savory pull.",
            "Korean Corndog Trio": "Why have one when you can have three? A triple threat of our signature Korean corndogs, featuring different fillings for the ultimate cheese pull.",
            "Cheesy Beef Quesadilla": "Flaky, grilled tortillas packed with seasoned ground beef and a blend of gooey, melted cheeses. Grilled to toasted perfection.",
            "Classic French Fries": "Hot, golden, and perfectly salted. Simple, classic, and completely addictive.",
            "Flavored French Fries": "Take your fries to the next level. Tossed in your choice of savory cheese, sour cream, or hot BBQ seasoning.",
            "Kropek Crackers": "Light, airy, and ultra-crunchy crackers with a delicious hint of seafood flavor. The perfect light bite to start your meal.",
            "Cheesy Nachos Solo": "A personal portion of crunchy tortilla chips, generously smothered in our rich, warm cheese sauce and savory toppings.",
            "Cheesy Nachos Tropa": "A massive, shareable mountain of crispy nachos loaded with beef, jalapeños, and drizzled with our signature warm cheese sauce. Built for sharing!",

            // Short Orders
            "Pancit Guisado": "Traditional stir-fried noodles tossed with fresh vegetables, chicken, and savory seasonings. A delicious, comforting staple for any gathering.",
            "Breaded Fish Fillet": "Light, flaky fish fillets coated in seasoned breadcrumbs and fried to a delicate golden crisp. Served with dynamic tartar dipping sauce.",
            "Chicken Fingers": "Tender strips of premium chicken breast, breaded and fried to golden perfection. Juicy on the inside, crunchy on the out.",
            "Lumpiang Shanghai": "Golden-crisp spring rolls packed with seasoned ground pork and aromatic spices. Samal's favorite finger food, served with sweet & sour dip.",
            "Pork Sinigang": "A sour, savory Filipino classic. Tender pork simmered in a rich tamarind broth with fresh local vegetables. Hearty, warm, and comforting.",
            "Bangus Sinigang": "Local milkfish simmered in our signature tangy tamarind broth with fresh garden vegetables. A refreshing, healthy Filipino favorite.",
            "Tuna Kinilaw": "Fresh Samal tuna ceviche, cured in local vinegar, ginger, onions, and chili. A clean, tangy, and spicy seafood delicacy.",
            "Calamares": "Crispy, tender squid rings coated in a seasoned batter and fried to a perfect golden crunch. Served with our house garlic aioli.",
            "Sinuglaw": "The ultimate local culinary fusion: smoky grilled pork belly (sinugba) tossed with fresh cured tuna ceviche (kinilaw). A spectacular explosion of flavor!",

            // Appetizers
            "Fried Tofu": "Golden, crispy tofu cubes, soft on the inside, served with a savory, tangy soy-vinegar dipping sauce topped with onions.",
            "4pcs Lumpiang Ubod": "Delicate spring rolls stuffed with fresh, tender heart of palm (ubod) and savory fillings, wrapped to perfection.",
            "4pcs Pork Shrimp Siomai": "Steamed dumplings packed with juicy pork and succulent shrimp, served with chili garlic sauce and calamansi.",
            "4pcs Shark's Fin Dumpling": "Delicately steamed dumplings packed with a rich, savory meat filling, a staple favorite for dimsum lovers.",
            "4pcs Japanese Siomai": "Pork dumplings wrapped in a sheet of nori seaweed, steamed to perfection and served with savory dipping sauce.",
            "4pcs Lumpiang Shanghai": "A bite-sized teaser of our famous golden spring rolls, perfect for kickstarting your appetite.",

            // Beverages
            "Iced Tea Blend": "House-brewed sweet iced tea, infused with citrus notes. Perfectly chilled and incredibly refreshing.",
            "Iced Tea Pitcher": "A sharing-sized pitcher of our refreshing signature iced tea blend, perfect for quenching the whole table's thirst.",
            "Bottled Softdrinks": "Chilled bottled soda of your choice—crisp, bubbly, and the perfect pairing for hot wings.",
            "Softdrinks in can": "Ice-cold canned sodas, served refreshing and ready to balance the heat of our spicy wings.",
            "Purified Water": "Clean, ice-cold purified water to keep you refreshed and hydrated.",
            "Blue Lemonade": "A sweet, tangy, and visually stunning electric blue lemonade that's as refreshing as it looks.",
            "Pink Lemonade": "A beautiful, ruby-tinted lemonade with a sweet berry twist. Crisp, refreshing, and delicious.",
            "Cucumber Lemonade": "The ultimate refresher. Tangy lemon juice blended with crisp, cool cucumber for a spa-like revitalization.",
            "Lemonade Pitcher": "A family-sized pitcher of our fresh, zesty lemonade. Perfect for sharing with the tropa.",

            // Single Rice Meals
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

            // Sizzling
            "Sizzling Sisig Tropa": "A massive, sizzling platter of savory pork sisig, seasoned with onions, chilies, and citrus. The ultimate sharing pulutan.",
            "Sizzling Lechon Kawali Tropa": "Crispy lechon kawali served on a piping hot sizzling plate, drenched in our signature savory gravy.",
            "Sizzling Buttered Shrimp": "Fresh local shrimp sautéed in rich garlic butter, served sizzling hot for a luxurious, sweet-savory bite.",

            // Sizzling Rice Meal
            "Sizzling SisigSilog": "Savory pork sisig served sizzling with garlic rice and a fresh egg. Mix it hot for the ultimate bite!",
            "Sizzling LechonSilog": "Crispy lechon kawali served sizzling with savory gravy, garlic rice, and a fried egg.",
            "Sizzling BurgerSteak": "Juicy beef patty served sizzling with a generous pour of rich mushroom gravy, garlic rice, and an egg.",
            "Sizzling LiemSilog": "Smoky grilled pork liempo served sizzling with garlic rice, gravy, and a sunny-side-up egg.",

            // Tropa Sharing Platter
            "Wings Platter": "A magnificent sharing platter of our famous wings, piled high and tossed in your favorite glazes. Made for sharing!",
            "Wings & Fries Platter": "A crowd-pleasing combo platter featuring a heap of flavored wings and crispy French fries. Perfect for group hangouts.",
            "Wings & Nachos Platter": "A stellar combo of our signature flavored wings and loaded cheesy nachos. Built for sharing with the tropa.",
            "Lechon Kawali Tropa": "A large sharing platter of our crispy-skinned deep-fried pork belly. Crunchy, savory, and completely indulgent.",
            "Pork Sisig Tropa": "A family-sized portion of our spicy, savory pork sisig, perfect for sharing around the table.",
            "Lumpiang Shanghai Tropa": "A mountain of our crispy, golden pork spring rolls, ready to feed the whole gang.",

            // Extra Add-Ons
            "Plain Rice": "A steaming hot scoop of premium white rice, the perfect canvas for our flavorful dishes.",
            "Garlic Rice": "Fragrant, stir-fried rice loaded with golden toasted garlic. A classic Filipino favorite.",
            "Plain Rice Platter": "A sharing-sized platter of steaming hot white rice, enough to feed the entire tropa.",
            "Garlic Rice Platter": "A massive platter of fragrant garlic rice, perfect for sharing with your unlimited wing feast.",
            "Gravy": "Our signature rich, savory house gravy. Thick, warm, and perfect for pouring over everything.",

            // Dessert
            "Fruit Salad Solo": "A sweet, creamy mixture of tropical fruits, sweet cream, and cheese. A classic Filipino dessert.",
            "Mango Bango Salad": "A refreshing, sweet mango dessert mixed with creamy tapioca pearl dressing. Cool and tropical.",
            "Mango/Avocado Float": "Layered graham crackers, sweet cream, and fresh local mangoes or avocados, chilled to ice-cream perfection."
        };

        // Category image mapping
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

        // Conversion badges
        const badgeList = [
            "Best Seller",
            "Tropa's Favorite",
            "Chef's Choice",
            "Highly Recommended",
            "Davao's Pride",
            "Must Try!"
        ];
        
        const openItemModal = (name, subtext, price, category, itemImage) => {
            modalTitle.textContent = name;
            modalCategory.textContent = category;
            modalSubtext.textContent = subtext || "";
            modalPrice.innerHTML = price;
            
            const cleanName = name.trim();
            const desc = itemDescriptions[cleanName] || "Juicy, freshly prepared, and loaded with our signature flavor. Made with premium ingredients and guaranteed to satisfy your cravings. Bring your tropa and eat like a Wing Master today!";
            modalDescription.textContent = desc;
            
            // Use per-item image if available, otherwise fall back to category image
            const imgPath = itemImage || categoryImages[category.toUpperCase().trim()] || "images/unlimited-rice-wings.jpg";
            modalImage.style.backgroundImage = `url('${imgPath}')`;
            
            const randomBadge = badgeList[Math.floor(Math.random() * badgeList.length)];
            modalBadge.textContent = randomBadge;
            
            itemDetailsModal.classList.add('is-open');
            document.body.classList.add('item-modal-open');
        };
        
        const closeItemModal = () => {
            itemDetailsModal.classList.remove('is-open');
            document.body.classList.remove('item-modal-open');
        };
        
        // Handle eye icon clicks specifically (not the whole card)
        document.addEventListener('click', (event) => {
            try {
                const eyeIcon = event.target.closest('.menu-item-eye');
                if (eyeIcon) {
                    console.log('🔍 Eye icon clicked!', event.target);
                    event.preventDefault();
                    event.stopPropagation();
                    
                    const menuItem = eyeIcon.closest('.menu-item');
                    if (!menuItem) {
                        console.log('❌ Menu item not found');
                        return;
                    }
                    
                    console.log('✅ Menu item found:', menuItem.getAttribute('data-item-name'));
                    
                    // Add ripple animation to the eye icon
                    eyeIcon.classList.remove('eye-clicked');
                    void eyeIcon.offsetWidth; // force reflow
                    eyeIcon.classList.add('eye-clicked');
                    setTimeout(() => eyeIcon.classList.remove('eye-clicked'), 500);
                    
                    const name = menuItem.getAttribute('data-item-name');
                    const subtext = menuItem.getAttribute('data-item-sub');
                    const price = menuItem.getAttribute('data-item-price');
                    const category = menuItem.getAttribute('data-category');
                    const itemImage = menuItem.getAttribute('data-item-image');
                    
                    console.log('🎬 Opening modal for:', name, 'Image:', itemImage);
                    
                    try {
                        openItemModal(name, subtext, price, category, itemImage);
                        console.log('✅ Modal opened successfully');
                    } catch (modalErr) {
                        console.error('❌ Error opening modal:', modalErr);
                    }
                }
            } catch (err) {
                console.error('❌ Error in eye icon click handler:', err);
            }
        });
        
        // Handle close clicks
        itemDetailsModal.querySelectorAll('[data-item-modal-close]').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                closeItemModal();
            });
        });
        
        // Close with Escape key
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeItemModal();
            }
        });
        
        // ATTACH EYE ICON CLICK LISTENERS DIRECTLY
        const attachEyeIconListeners = () => {
            const eyeIcons = document.querySelectorAll('.menu-item-eye');
            eyeIcons.forEach((eyeIcon) => {
                // Remove existing listeners by cloning
                const newEyeIcon = eyeIcon.cloneNode(true);
                eyeIcon.parentNode.replaceChild(newEyeIcon, eyeIcon);
                
                newEyeIcon.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    try {
                        const menuItem = newEyeIcon.closest('.menu-item');
                        if (!menuItem) return;
                        
                        const name = menuItem.getAttribute('data-item-name');
                        const subtext = menuItem.getAttribute('data-item-sub');
                        const price = menuItem.getAttribute('data-item-price');
                        const category = menuItem.getAttribute('data-category');
                        const itemImage = menuItem.getAttribute('data-item-image');
                        
                        openItemModal(name, subtext, price, category, itemImage);
                        
                        newEyeIcon.classList.remove('eye-clicked');
                        void newEyeIcon.offsetWidth;
                        newEyeIcon.classList.add('eye-clicked');
                        setTimeout(() => newEyeIcon.classList.remove('eye-clicked'), 500);
                    } catch (error) {
                        console.error('Eye icon click error:', error);
                    }
                });
            });
        };
        
        // Attach listeners initially and when menu expands
        attachEyeIconListeners();
        
        // Re-attach when menu category is expanded
        document.querySelectorAll('.menu-category').forEach(category => {
            const button = category.querySelector('button');
            if (button) {
                button.addEventListener('click', () => {
                    setTimeout(() => attachEyeIconListeners(), 100);
                });
            }
        });
    }
});

// GLOBAL EYE ICON CLICK HANDLER - Works outside DOMContentLoaded scope
// This handles the modal opening when users click on eye icons
(function() {
    // High-converting copywriting descriptions mapped by item name
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

    // Category image mapping
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

    // Conversion badges
    const badgeList = [
        "Best Seller",
        "Tropa's Favorite",
        "Chef's Choice",
        "Highly Recommended",
        "Davao's Pride",
        "Must Try!"
    ];

    // Attach click handlers directly to each eye icon
    function attachEyeIconListeners() {
        const eyeIcons = document.querySelectorAll('.menu-item-eye');
        console.log(`[EYE ICON] Found ${eyeIcons.length} eye icons to attach listeners to`);
        
        eyeIcons.forEach((eyeIcon, index) => {
            eyeIcon.setAttribute('data-listener-attached', 'true');
            
            eyeIcon.addEventListener('click', function handleEyeClick(event) {
                console.log(`[EYE ICON] Click handler called for icon ${index}`);
                event.preventDefault();
                event.stopPropagation();

                try {
                    const menuItem = eyeIcon.closest('.menu-item');
                    console.log(`[EYE ICON] Menu item found:`, !!menuItem);
                    if (!menuItem) return;

                    // Get the modal and its elements
                    const modal = document.getElementById('item-details-modal');
                    console.log(`[EYE ICON] Modal found:`, !!modal);
                    if (!modal) return;

                    const modalImage = modal.querySelector('.item-modal-image');
                    const modalCategory = modal.querySelector('.item-modal-category');
                    const modalTitle = modal.querySelector('.item-modal-title');
                    const modalSubtext = modal.querySelector('.item-modal-subtext');
                    const modalPrice = modal.querySelector('.item-modal-price');
                    const modalDescription = modal.querySelector('.item-modal-description');
                    const modalBadge = modal.querySelector('.item-modal-badge');

                    // Get data from menu item
                    const name = menuItem.getAttribute('data-item-name');
                    const subtext = menuItem.getAttribute('data-item-sub') || '';
                    const price = menuItem.getAttribute('data-item-price') || '₱0';
                    const category = menuItem.getAttribute('data-category') || 'MENU ITEM';
                    const itemImage = menuItem.getAttribute('data-item-image');

                    console.log(`[EYE ICON] Opening modal for: ${name}`);

                    // Populate modal
                    modalTitle.textContent = name;
                    modalCategory.textContent = category;
                    modalSubtext.textContent = subtext;
                    modalPrice.innerHTML = price;

                    const description = itemDescriptions[name] || 'Juicy, freshly prepared, and loaded with our signature flavor. Made with premium ingredients and guaranteed to satisfy your cravings. Bring your tropa and eat like a Wing Master today!';
                    modalDescription.textContent = description;

                    // Use item image or category image
                    const imagePath = itemImage || categoryImages[category.toUpperCase().trim()] || 'images/unlimited-rice-wings.jpg';
                    modalImage.style.backgroundImage = `url('${imagePath}')`;

                    // Random badge
                    const randomBadge = badgeList[Math.floor(Math.random() * badgeList.length)];
                    modalBadge.textContent = randomBadge;

                    // Open modal
                    modal.classList.add('is-open');
                    document.body.classList.add('item-modal-open');
                    console.log(`[EYE ICON] Modal opened, is-open class added`);

                    // Add ripple animation
                    eyeIcon.classList.remove('eye-clicked');
                    void eyeIcon.offsetWidth;
                    eyeIcon.classList.add('eye-clicked');
                    setTimeout(() => eyeIcon.classList.remove('eye-clicked'), 500);

                } catch (error) {
                    console.error('[EYE ICON] Error handling eye icon click:', error);
                }
            });
        });
    }
    
    console.log('[INIT] Attaching eye icon listeners');
    attachEyeIconListeners();

    // Handle modal close
    document.addEventListener('click', function(event) {
        if (event.target.getAttribute('data-item-modal-close') !== null) {
            const modal = document.getElementById('item-details-modal');
            if (modal) {
                modal.classList.remove('is-open');
                document.body.classList.remove('item-modal-open');
            }
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('item-details-modal');
            if (modal) {
                modal.classList.remove('is-open');
                document.body.classList.remove('item-modal-open');
            }
        }
    });
    
    // Also use MutationObserver to re-attach listeners when new eye icons are added
    const observer = new MutationObserver(() => {
        attachEyeIconListeners();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})();

// ===== LOGIN / REGISTER FORM INTERACTIONS =====
(function initAuthForm() {
    const authTabsEl = document.getElementById('auth-tabs');
    if (!authTabsEl) return; // not on reservation page or already logged in

    const tabs        = authTabsEl.querySelectorAll('.auth-tab');
    const loginForm   = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const switchLinks = document.querySelectorAll('.auth-switch-link');

    if (!loginForm || !registerForm) return;

    // ---- Tab Switching ----
    function switchTab(tabName) {
        tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === tabName));

        if (tabName === 'login') {
            loginForm.classList.add('is-active');
            registerForm.classList.remove('is-active');
        } else {
            registerForm.classList.add('is-active');
            loginForm.classList.remove('is-active');
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => switchTab(tab.dataset.tab));
    });

    switchLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            switchTab(link.dataset.switch);
        });
    });

    // ---- Password Visibility Toggle ----
    document.querySelectorAll('.auth-eye').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = toggle.dataset.target;
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const eyeShow = toggle.querySelector('.eye-show');
            const eyeHide = toggle.querySelector('.eye-hide');
            if (eyeShow && eyeHide) {
                eyeShow.style.display = isPassword ? 'none' : '';
                eyeHide.style.display = isPassword ? '' : 'none';
            }
        });
    });

    // ---- Submit Loading State ----
    [loginForm, registerForm].forEach(form => {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('.auth-submit');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
            }
        });
    });

    // ---- Focus styling on auth inputs ----
    document.querySelectorAll('.auth-field input').forEach(input => {
        input.addEventListener('focus', () => {
            input.closest('.auth-field')?.classList.add('is-focused');
        });
        input.addEventListener('blur', () => {
            input.closest('.auth-field')?.classList.remove('is-focused');
        });
    });
})();

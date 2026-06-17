# Add data-item-image attributes to menu items in menu.php
$file = "c:\Users\Admin\Downloads\wings-main\menu.php"
$content = Get-Content $file -Raw

# Define image mappings per item (using existing images as temp placeholders)
# Format: item-name -> image-path
# When you have real photos, just update these paths

$imageMap = @{
    # Chicken Wings
    'data-item-name="Wings Solo"' = 'data-item-image="images/unlimited-rice-wings-sharp.png"'
    'data-item-name="Wings Tropa"' = 'data-item-image="images/unlimited-rice-wings-sharp.png"'
    'data-item-name="Bilao Wings A"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Bilao Wings B"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Bilao Wings C"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Bilao Wings &amp; Shanghai"' = 'data-item-image="images/unlimited-rice-wings-tray.png"'

    # Combo Snacks
    'data-item-name="Flavored Wings &amp; Fries"' = 'data-item-image="images/combo-snacks-hd.jpg"'
    'data-item-name="Flavored Wings &amp; Nachos"' = 'data-item-image="images/combo-snacks-hd.jpg"'
    'data-item-name="Nachos &amp; Quesadilla"' = 'data-item-image="images/combo-snacks-hd.jpg"'
    'data-item-name="Fries &amp; Nachos"' = 'data-item-image="images/combo-snacks-hd.jpg"'
    'data-item-name="Popshot &amp; Fries"' = 'data-item-image="images/combo-snacks-hd.jpg"'

    # Solo Snacks
    'data-item-name="Italian Spaghetti"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Korean Corndog"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Korean Corndog Trio"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Cheesy Beef Quesadilla"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Classic French Fries"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Flavored French Fries"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Kropek Crackers"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Cheesy Nachos Solo"' = 'data-item-image="images/solo-snacks.jpg"'
    'data-item-name="Cheesy Nachos Tropa"' = 'data-item-image="images/solo-snacks.jpg"'

    # Short Orders
    'data-item-name="Pancit Guisado"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Breaded Fish Fillet"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Chicken Fingers"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Lumpiang Shanghai"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Pork Sinigang"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Bangus Sinigang"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Tuna Kinilaw"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Calamares"' = 'data-item-image="images/asset-contact-sheet.jpg"'
    'data-item-name="Sinuglaw"' = 'data-item-image="images/asset-contact-sheet.jpg"'

    # Appetizers
    'data-item-name="Fried Tofu"' = 'data-item-image="images/combo-snacks.jpg"'
    'data-item-name="4pcs Lumpiang Ubod"' = 'data-item-image="images/combo-snacks.jpg"'
    'data-item-name="4pcs Pork Shrimp Siomai"' = 'data-item-image="images/combo-snacks.jpg"'
    'data-item-name="4pcs Shark''s Fin Dumpling"' = 'data-item-image="images/combo-snacks.jpg"'
    'data-item-name="4pcs Japanese Siomai"' = 'data-item-image="images/combo-snacks.jpg"'
    'data-item-name="4pcs Lumpiang Shanghai"' = 'data-item-image="images/combo-snacks.jpg"'

    # Beverages
    'data-item-name="Iced Tea Blend"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Iced Tea Pitcher"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Bottled Softdrinks"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Softdrinks in can"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Purified Water"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Blue Lemonade"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Pink Lemonade"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Cucumber Lemonade"' = 'data-item-image="images/beverages.jpg"'
    'data-item-name="Lemonade Pitcher"' = 'data-item-image="images/beverages.jpg"'

    # Single Rice Meals
    'data-item-name="Flavored Wings Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="ShanghaiSilog"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Fish Fillet Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Calamares Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="BurgerSteak Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Porkchop Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Shawarma Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Beef Tapsilog"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Chicken Fingers Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Pork Adobo Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Pork Sisig Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Lechon Kawali Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Grilled Liempo Rice Meal"' = 'data-item-image="images/sizzling-rice-meal.jpg"'

    # Sizzling
    'data-item-name="Sizzling Sisig Tropa"' = 'data-item-image="images/sizzling.jpg"'
    'data-item-name="Sizzling Lechon Kawali Tropa"' = 'data-item-image="images/sizzling.jpg"'
    'data-item-name="Sizzling Buttered Shrimp"' = 'data-item-image="images/sizzling.jpg"'

    # Sizzling Rice Meal
    'data-item-name="Sizzling SisigSilog"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Sizzling LechonSilog"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Sizzling BurgerSteak"' = 'data-item-image="images/sizzling-rice-meal.jpg"'
    'data-item-name="Sizzling LiemSilog"' = 'data-item-image="images/sizzling-rice-meal.jpg"'

    # Tropa Sharing Platter
    'data-item-name="Wings Platter"' = 'data-item-image="images/tropa-sharing-platter.jpg"'
    'data-item-name="Wings &amp; Fries Platter"' = 'data-item-image="images/tropa-sharing-platter.jpg"'
    'data-item-name="Wings &amp; Nachos Platter"' = 'data-item-image="images/tropa-sharing-platter.jpg"'
    'data-item-name="Lechon Kawali Tropa"' = 'data-item-image="images/tropa-sharing-platter.jpg"'
    'data-item-name="Pork Sisig Tropa"' = 'data-item-image="images/tropa-sharing-platter.jpg"'
    'data-item-name="Lumpiang Shanghai Tropa"' = 'data-item-image="images/tropa-sharing-platter.jpg"'

    # Extra Add-Ons
    'data-item-name="Plain Rice"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Garlic Rice"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Plain Rice Platter"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Garlic Rice Platter"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'
    'data-item-name="Gravy"' = 'data-item-image="images/unlimited-rice-wings-variety.png"'

    # Dessert
    'data-item-name="Fruit Salad Solo"' = 'data-item-image="images/dessert.jpg"'
    'data-item-name="Mango Bango Salad"' = 'data-item-image="images/dessert.jpg"'
    'data-item-name="Mango/Avocado Float"' = 'data-item-image="images/dessert.jpg"'
}

foreach ($key in $imageMap.Keys) {
    $value = $imageMap[$key]
    # Insert data-item-image right after data-category="..."
    # We match the specific item name and add the image attribute right after it
    $content = $content -replace [regex]::Escape($key), "$key $value"
}

Set-Content -Path $file -Value $content -NoNewline
Write-Host "Done! Added data-item-image attributes to all menu items."

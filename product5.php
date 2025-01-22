<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - Artisan Heritage</title>
    <link rel =" icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style3.css">
    
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar">
    <div class="navbar-logo">
        <img src="logo.png" alt="Artisan Heritage Logo" class="logo">
        <span class="brand-name" data-lang-en="Artisan Heritage" data-lang-np="हस्तकला धरोहर">Artisan Heritage</span>
    </div>
    <nav class="navbar-links">
        <a href="landingpg.php" data-lang-en="Home" data-lang-np="गृहपृष्ठ" class="nav-link active">Home</a>
        <a href="shop.php" data-lang-en="Shop" data-lang-np="किनमेल" class="nav-link">Shop</a>
        <a href="about.php" data-lang-en="About" data-lang-np="हाम्रोबारे" class="nav-link">About</a>
        <a href="contact.php" data-lang-en="Contact Us" data-lang-np="सम्पर्क गर्नुहोस्">Contact Us</a>
        
        <div class="dropdown">
            <select id="language-select" class="language-select">
                <option value="en">EN</option>
                <option value="np">ने</option>
            </select>
        </div>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
            <div class="user-info">
                <span class="username">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="logout-button" data-lang-en="Logout" data-lang-np="बाहिर निस्कनुहोस्">Logout</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-button" data-lang-en="Login" data-lang-np="लग-इन">Login</a>
        <?php endif; ?>
    </nav>
</header>
    <main class="container">
        <div class="product">
            <div class="product-image">
                <img src="stone buddha.png" alt="buddha stone">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Crystal Shakya Muni Buddha Statue" data-lang-np="क्रिस्टल शाक्य मुनि बुद्ध प्रतिमा">Crystal Shakya Muni Buddha Statue</h1> 
                <p class="subtitle" data-lang-en="Crystal Shakya Muni Buddha Statue" data-lang-np="क्रिस्टल शाक्य मुनि बुद्ध प्रतिमा">Crystal Shakya Muni Buddha Statue</p>
                <p class="price" data-lang-en="Rs 1,00,000" data-lang-np="रु  १,००,०००">Rs 1,00,000</p>
                <a href="form.php?product_name=Crystal Shakya Muni Buddha Statue"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a> 
             
                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 60 cm (27.6 inches)Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)" 
                    data-lang-np="कुल उचाइ: ६० सेमी (२७.६ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (१८.९ इन्च x १८.९ इन्च)">Total Height: 60 cm (27.6 inches)<br>Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)</p>

                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en="Crystal, Gold-plated Copper and Stone settings" 
                    data-lang-np="क्रिस्टल, गोल्ड प्लेटेड कपर र स्टोन सेटिंग्स">
                    Crystal, Gold-plated Copper and Stone settings</p>
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en= "The Gold Plated Crystal Shakyamuni Buddha is a statue or an idol of Shakyamuni Buddha, which is made up of crystal surrounded by the copper which is gold-plated. It also consists of stone settings in different parts. It is totally handmade and carved by one of our finest artist or craftsmen. The weight of this product is 1.63 kg whereas the height and the breadth are 9 inches and 6.5 inches respectively."
                    data-lang-np= "गोल्ड प्लेटेड क्रिस्टल शाक्यमुनि बुद्ध शाक्यमुनि बुद्धको मूर्ति वा मूर्ति हो, जुन तामाले घेरिएको क्रिस्टलले बनेको हुन्छ जुन सुनले ढाकिएको हुन्छ। यसले विभिन्न भागहरूमा ढुङ्गा सेटिङहरू पनि समावेश गर्दछ। यो पूर्णतया हस्तनिर्मित र हाम्रो उत्कृष्ट कलाकार वा शिल्पकारहरू द्वारा नक्काशी गरिएको छ। यो उत्पादनको तौल १.६३ केजी छ भने उचाई र चौडाइ क्रमशः ९ इन्च र ६.५ इन्च छ।">
                    The “Gold Plated Crystal Shakyamuni Buddha” is a statue or an idol of Shakyamuni Buddha, which is made up of crystal surrounded by the copper which is gold-plated. It also consists of stone settings in different parts. It is totally handmade and carved by one of our finest artist or craftsmen. The weight of this product is 1.63 kg whereas the height and the breadth are 9 inches and 6.5 inches respectively.</p>


                
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById("language-select").addEventListener("change", (e) => {
            const lang = e.target.value;
            document.querySelectorAll("[data-lang-en]").forEach(el => {
                el.textContent = el.getAttribute(`data-lang-${lang}`);
            });
        });
            </script>
</body>
</html>
 
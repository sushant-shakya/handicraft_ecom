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
            <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'])?>" class="login-button" data-lang-en="Login" data-lang-np="लग-इन">Login</a>
        <?php endif; ?>
    </nav>
</header>
    <main class="container">
        <div class="product">
            <div class="product-image">
                <img src="bajrayogini.jpg" alt="bajrayogini">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Bajrayogini Statue" data-lang-np="बज्रयोगिनी मूर्ति">Bajrayogini Statue</h1> 
                <p class="subtitle" data-lang-en="Bajrayogini Statue" data-lang-np="बज्रयोगिनी मूर्ति">Bajrayogini Statue</p>
                <p class="price" data-lang-en="Rs 15,000" data-lang-np="रु १५,०००">Rs 15,000</p>
                <a href="form.php?product_name=Bajrayogini Statue"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a>

                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 90 cm (35.4 inches)Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)" 
                    data-lang-np="कुल उचाइ: ९० सेमी (३५.४ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (१८.९ इन्च x १८.९ इन्च)">Total Height: 90 cm (35.4 inches)<br>Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)</p>

                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en=" Wood settings" 
                    data-lang-np=" काठ सेटिंग्स">
                     Stone settings</p>
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en= "A wooden Bajrayogini idol embodies the divine energy of the tantric goddess Bajrayogini, revered in Nepalese and Tibetan traditions. Expertly carved from wood, it showcases intricate details of her graceful posture, adorned with symbolic ornaments and serene expressions. This sacred piece represents spiritual transformation and is perfect for meditation spaces, offering warmth and natural beauty."
                    data-lang-np= "काठको बज्रयोगिनी मूर्तिले तान्त्रिक देवी बज्रयोगिनीको दिव्य ऊर्जालाई मूर्त रूप दिन्छ, जुन नेपाली र तिब्बती परम्पराहरूमा सम्मानित छ। कुशलतापूर्वक काठबाट नक्काशी गरिएको, यसले प्रतीकात्मक गहना र निर्मल अभिव्यक्तिहरूले सजिएको उनको सुन्दर मुद्राको जटिल विवरणहरू प्रदर्शन गर्दछ। यो पवित्र टुक्रा आध्यात्मिक रूपान्तरण को प्रतिनिधित्व गर्दछ र ध्यान स्थानहरु को लागी उपयुक्त छ, न्यानो र प्राकृतिक सौन्दर्य प्रदान गर्दछ।">
                    A wooden Bajrayogini idol embodies the divine energy of the tantric goddess Bajrayogini, revered in Nepalese and Tibetan traditions. Expertly carved from wood, it showcases intricate details of her graceful posture, adorned with symbolic ornaments and serene expressions. This sacred piece represents spiritual transformation and is perfect for meditation spaces, offering warmth and natural beauty.</p>

              
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
 
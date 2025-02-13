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
            <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'])?>"  class="login-button" data-lang-en="Login" data-lang-np="लग-इन">Login</a>
        <?php endif; ?>
    </nav>
</header>
    <main class="container">
        <div class="product">
            <div class="product-image">
                <img src="stoneganesh.png" alt="Ganesh">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Ganesh Stone Statue" data-lang-np="गणेश ढुङ्गाको मूर्ति">Ganesh Stone Statue</h1> 
                <p class="subtitle" data-lang-en="Ganesh Stone Statue" data-lang-np="गणेश ढुङ्गाको मूर्ति">Ganesh Stone Statue</p>
                <p class="price" data-lang-en="Rs 20,000" data-lang-np="रु २०,०००">Rs 20,000</p>
                <a href="form.php?product_name=Ganesh Stone Statue"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a> 

               
                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 90 cm (35.4 inches)Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)" 
                    data-lang-np="कुल उचाइ: ९० सेमी (३५.४ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (१८.९ इन्च x १८.९ इन्च)">Total Height: 90 cm (35.4 inches)<br>Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)</p>
                    
                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en=" Stone settings" 
                    data-lang-np=" स्टोन सेटिंग्स">
                     Stone settings</p>
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en= "A stone Ganesh idol represents Lord Ganesha, the Hindu deity of wisdom, prosperity, and new beginnings. Carved from durable stone, it often features intricate details showcasing the deity's iconic elephant head, potbelly, and multiple arms holding symbolic objects. Ideal for spiritual spaces, its earthy texture and timeless design bring a sense of grounding and reverence."
                    data-lang-np= "ढुङ्गाको गणेश मूर्तिले भगवान गणेश, बुद्धि, समृद्धि र नयाँ सुरुवातको हिन्दू देवतालाई प्रतिनिधित्व गर्दछ। टिकाऊ ढुङ्गाबाट कुँदिएको, यसले अक्सर देवताको प्रतिष्ठित हात्तीको टाउको, पोटेली, र प्रतीकात्मक वस्तुहरू समात्ने बहु हतियारहरू प्रदर्शन गर्ने जटिल विवरणहरू प्रस्तुत गर्दछ। आध्यात्मिक स्थानहरूको लागि आदर्श, यसको माटो बनावट र कालातीत डिजाइनले ग्राउन्डिङ र आदरको भावना ल्याउँछ।">
                    A stone Ganesh idol represents Lord Ganesha, the Hindu deity of wisdom, prosperity, and new beginnings. Carved from durable stone, it often features intricate details showcasing the deity's iconic elephant head, potbelly, and multiple arms holding symbolic objects. Ideal for spiritual spaces, its earthy texture and timeless design bring a sense of grounding and reverence.</p>
                    
             
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
 
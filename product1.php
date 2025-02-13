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
                <img src="shakyamuni.jpg" alt="Shakyamuni Buddha">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Shakyamuni Buddha" data-lang-np="शाक्यमुनि बुद्ध">Shakyamuni Buddha</h1>
                <p class="subtitle" data-lang-en="Siddhartha Gautama Buddha." data-lang-np="सिद्धार्थ गौतम बुद्ध।">Siddhartha Gautama Buddha.</p>
                <p class="price" data-lang-en="Rs 20,000" data-lang-np="रु २०,०००">Rs 20,000</p>
                <a href="form.php?product_name=Shakyamuni Buddha"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a>
                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 70 cm (27.6 inches)Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)" 
                       data-lang-np="कुल उचाइ: ७० सेमी (२७.६ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (१८.९ इन्च x १८.९ इन्च)">Total Height: 70 cm (27.6 inches)<br>Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)</p>
                    
                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en="Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases" 
                       data-lang-np="तामाको पाताबाट बनाइएको, बिस्तृत फिलिग्री काम सहित, विभिन्न प्रकारका असली पत्थरहरूले सजाइएको, र चार सिंह स्ट्यान्ड बेसद्वारा समर्थन गरिएको।">
                       Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases</p>
                    
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en="Indulge in the timeless allure of the Shakyamuni Buddha statue, a profound masterpiece meticulously crafted by the virtuosos of Artisan Heritage. This collector’s item captures the essence of Siddhartha Gautama, the historical Buddha, who embarked on a profound spiritual journey and attained enlightenment beneath the Bodhi tree. With a serene countenance and graceful posture, this statue radiates tranquility and wisdom, inviting you to embark on your own transformative path of self-discovery." 
                       data-lang-np="शाक्यमुनि बुद्धको मूर्तिको कालातीत आकर्षणमा रमाउनुहोस्, हस्तकला धरोहरका कुशल शिल्पकारहरूले सावधानीपूर्वक तयार गरेको एक गहन कलाकृति। यो संग्रहणीय वस्तुले ऐतिहासिक बुद्ध सिद्धार्थ गौतमको सारलाई समेट्छ, जसले गहन आध्यात्मिक यात्रामा सामेल भई बोधि वृक्षमुनि ज्ञान प्राप्त गरे। शान्त अनुहार र सुरुचिपूर्ण आसनको साथ, यो मूर्तिले शान्ति र बुद्धिमत्ता प्रकट गर्छ, तपाइँलाई आत्म-अन्वेषणको आफ्नो परिवर्तनकारी यात्रामा सामेल हुन आमन्त्रण गर्दछ।">
                       Indulge in the timeless allure of the Shakyamuni Buddha statue, a profound masterpiece meticulously crafted by the virtuosos of Artisan Heritage. This collector’s item captures the essence of Siddhartha Gautama, the historical Buddha, who embarked on a profound spiritual journey and attained enlightenment beneath the Bodhi tree. With a serene countenance and graceful posture, this statue radiates tranquility and wisdom, inviting you to embark on your own transformative path of self-discovery.</p>
                </div>
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
 
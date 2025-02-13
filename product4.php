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
                <img src="guruurgennorlaa.jpg" alt="Guru Urgen Norlaa">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Guru Urgen Norlaa" data-lang-np="गुरु उर्गेन नोर्ला">Guru Urgen Norlaa</h1> 
                <p class="subtitle" data-lang-en="Guru Urgen Norlaa" data-lang-np="गुरु उर्गेन नोर्ला">Guru Urgen Norlaa</p>
                <p class="price" data-lang-en="Rs 35,000" data-lang-np="रु ३५,०००">Rs 35,000</p>
                <a href="form.php?product_name=Guru Urgen Norlaa"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a> 
             
               
                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 70 cm (27.6 inches)Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)" 
                    data-lang-np="कुल उचाइ: ७० सेमी (२७.६ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (१८.९ इन्च x १८.९ इन्च)">Total Height: 70 cm (27.6 inches)<br>Length x Width: 48 cm x 48 cm (18.9 inches x 18.9 inches)</p>

                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en="Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases" 
                    data-lang-np="तामाको पाताबाट बनाइएको, बिस्तृत फिलिग्री काम सहित, विभिन्न प्रकारका असली पत्थरहरूले सजाइएको, र चार सिंह स्ट्यान्ड बेसद्वारा समर्थन गरिएको।">
                    Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases</p>
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en= "Behold the captivating Guru Urgen Norlaa statue, a divine masterpiece meticulously crafted by Artisian Heritage. This remarkable collector's item encapsulates the essence of spiritual devotion and artistic mastery, reflecting the rich heritage of Buddhist traditions. Immerse yourself in the sacred energy emanating from this exquisite statue, as it invites you to explore the depths of wisdom and compassion.
                    Crafted with unwavering attention to detail, the Guru Orgyen Norlha statue is adorned with a resplendent layer of 24K mercury gold, casting a luminous glow that radiates divinity. The skilled hands of Artisian Heritage' artisans bring the statue to life with delicate strokes of acrylic colors, capturing the nuances of Guru Orgyen Norlha's serene expression and enlightened presence."
                    data-lang-np= "मनमोहक गुरु अर्गेन नोर्लाको मूर्ति हेर्नुहोस्, आर्टिसियन हेरिटेजले सावधानीपूर्वक बनाएको ईश्वरीय उत्कृष्ट कृति। यो उल्लेखनीय कलेक्टरको वस्तुले बौद्ध परम्पराको समृद्ध सम्पदा झल्काउने आध्यात्मिक भक्ति र कलात्मक निपुणताको सारलाई समेट्छ। यस उत्कृष्ट मूर्तिबाट निस्कने पवित्र ऊर्जामा आफूलाई डुबाउनुहोस्, किनकि यसले तपाईंलाई बुद्धि र करुणाको गहिराइहरू अन्वेषण गर्न निम्तो दिन्छ।
                    विवरणमा अटुट ध्यान दिएर बनाइएको, गुरु अर्ग्यान नोर्ल्हा मूर्तिलाई 24K पारा सुनको चम्किलो तहले सजाइएको छ, जसले दिव्यतालाई विकिरण गर्ने चमकदार चमक छ। आर्टिसियन हेरिटेजका कारीगरहरूका कुशल हातहरूले एक्रिलिक रंगहरूको नाजुक स्ट्रोकहरूद्वारा मूर्तिलाई जीवन्त बनाइदिन्छन्, गुरु ओर्गेन नोर्ल्हाको निर्मल अभिव्यक्ति र प्रबुद्ध उपस्थितिको सूक्ष्मताहरू खिच्छन्।">
                    Behold the captivating Guru Urgen Norlaa statue, a divine masterpiece meticulously crafted by Artisian Heritage. This remarkable collector's item encapsulates the essence of spiritual devotion and artistic mastery, reflecting the rich heritage of Buddhist traditions. Immerse yourself in the sacred energy emanating from this exquisite statue, as it invites you to explore the depths of wisdom and compassion.
                        Crafted with unwavering attention to detail, the Guru Orgyen Norlha statue is adorned with a resplendent layer of 24K mercury gold, casting a luminous glow that radiates divinity. The skilled hands of Artisian Heritage' artisans bring the statue to life with delicate strokes of acrylic colors, capturing the nuances of Guru Orgyen Norlha's serene expression and enlightened presence.</p>

                    
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
 
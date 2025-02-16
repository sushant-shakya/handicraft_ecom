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
                <img src="greentara.jpg" alt="Green Tara">
            </div>
            <div class="product-details">
                <h1 data-lang-en="Green Tara" data-lang-np="हरियो तारा">Green Tara</h1>            
                <p class="subtitle" data-lang-en="Green Tara" data-lang-np="हरियो तारा">Green Tara</p>
                <p class="price" data-lang-en="Rs 30,000" data-lang-np="रु ३०,०००">Rs 30,000</p>
                <a href="form.php?product_name=Green Tara"><button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button></a>
                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="Total Height: 70 cm (27.6 inches)Length x Width: 48 cm x 48 cm (27.6 inches x 27.6 inches)" 
                    data-lang-np="कुल उचाइ: ७० सेमी (२७.६ इन्च)लम्बाई x चौडाई: ४८ सेमी x ४८ सेमी (२७.६ इन्च x २७.६ इन्च)">Total Height: 70 cm (27.6 inches)<br>Length x Width: 48 cm x 48 cm (27.6 inches x 27.6 inches)</p>
                    
                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en="Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases" 
                    data-lang-np="तामाको पाताबाट बनाइएको, बिस्तृत फिलिग्री काम सहित, विभिन्न प्रकारका असली पत्थरहरूले सजाइएको, र चार सिंह स्ट्यान्ड बेसद्वारा समर्थन गरिएको।">
                    Crafted from Copper Sheets with Intricate Filigree Work Adorned with Various Types of Genuine Stones Supported by Four Lion Stand Bases</p>

                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en="The lovely feminine representation of all compassion, Green Tara embodies the good, wise, and miraculous deeds of all Buddhas. The energy winds of the body and breath are also represented by Green Tara, who stands for the aspect of a Buddha that shields us from fear. It is believed that the ability to act, to move through life, and to achieve our goals are all influenced by Tara. Her name does indeed mean "she who ferries over and as such, she is regarded as the mother of all awakened Buddhas. As a bodhisattva, Tara has committed her life to put an end to the suffering of all creatures.
                    data-lang-np="सबै करुणाको सुन्दर स्त्री प्रतिनिधित्व, हरियो ताराले सबै बुद्धहरूको असल, बुद्धिमानी र चमत्कारी कार्यहरूलाई मूर्त रूप दिन्छ। शरीर र सासको ऊर्जा हावाहरू पनि हरियो तारा द्वारा प्रतिनिधित्व गरिन्छ, जसले हामीलाई डरबाट जोगाउने बुद्धको पक्षको लागि खडा हुन्छ। यो विश्वास गरिन्छ कि कार्य गर्ने क्षमता, जीवन मार्फत अघि बढ्ने र हाम्रो लक्ष्यहरू प्राप्त गर्न ताराबाट प्रभावित हुन्छ। उनको नामको साँच्चै अर्थ हो "उनी जो माथि चढ्छिन् र यसरी, उनलाई सबै जागृत बुद्धहरूको आमाको रूपमा मानिन्छ। बोधिसत्वको रूपमा, ताराले सबै प्राणीहरूको पीडाको अन्त्य गर्न आफ्नो जीवन प्रतिबद्ध गरिन्।>
                    The lovely feminine representation of all compassion, Green Tara embodies the good, wise, and miraculous deeds of all Buddhas. The energy winds of the body and breath are also represented by Green Tara, who stands for the aspect of a Buddha that shields us from fear. It is believed that the ability to act, to move through life, and to achieve our goals are all influenced by Tara. Her name does indeed mean "she who ferries over", and as such, she is regarded as the mother of all awakened Buddhas. As a bodhisattva, Tara has committed her life to put an end to the suffering of all creatures.</p>

                    
                    
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
 
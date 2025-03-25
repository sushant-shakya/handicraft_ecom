<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Heritage</title>
    <link rel =" icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/about1.css">
    <style>
        /* Add dropdown styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            color: #333;
            cursor: pointer;
            padding: 8px 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 6px;
            min-width: 200px;
            z-index: 1000;
            margin-top: 8px;
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
            border-bottom: 1px solid #eee;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .dropdown-menu a:hover {
            background: #f8f9fa;
        }

        .user-dropdown:hover .dropdown-menu,
        .dropdown-menu.show {
            display: block;
        }

        .caret {
            border-top: 5px solid #333;
            border-right: 5px solid transparent;
            border-left: 5px solid transparent;
            margin-left: 5px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="navbar-logo">
            <img src="../assets/logo.png" alt="Artisan Heritage Logo" class="logo">
            <span class="brand-name" data-lang-en="Artisan Heritage" data-lang-np="हस्तकला धरोहर">Artisan Heritage</span>
        </div>
        <nav class="navbar-links">
            <a href="landingpg.php" data-lang-en="Home" data-lang-np="गृहपृष्ठ" class="nav-link">Home</a>
            <a href="shop.php" data-lang-en="Shop" data-lang-np="किनमेल" class="nav-link">Shop</a>
            <a href="about.php" data-lang-en="About" data-lang-np="हाम्रोबारे" class="nav-link active">About</a>
            <a href="contact.php" data-lang-en="Contact Us" data-lang-np="सम्पर्क गर्नुहोस्">Contact Us</a>
            <div class="dropdown">
                <select id="language-select" class="language-select">
                    <option value="en">EN</option>
                    <option value="np">ने</option>
                </select>
            </div>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
                <div class="user-dropdown">
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <button class="dropdown-toggle">
                            👤 <?= htmlspecialchars($_SESSION['username']) ?>
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="../src/manage-products.php" data-lang-en="Manage Products" data-lang-np="उत्पादन व्यवस्थापन">
                                Manage Products
                            </a>
                            <a href="../src/admin-dashboard.php" data-lang-en="Dashboard" data-lang-np="ड्यासबोर्ड">
                                Admin Dashboard
                            <a href="../src/user-role-managment.php" data-lang-en="User Role Management" data-lang-np="प्रयोगकर्ता भूमिका व्यवस्थापन">
                                Manage User Roles
                            </a>
                            <a href="logout.php" data-lang-en="Logout" data-lang-np="लगआउट">
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="user-info">
                            <span class="username">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
                            <a href="logout.php" class="logout-button" data-lang-en="Logout" data-lang-np="लगआउट">Logout</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'])?>" class="login-button" data-lang-en="Login" data-lang-np="लगइन">Login</a>
            <?php endif; ?>
        </nav>
    </header>

     <!-- About Section -->
     <section class="about">
        <div class="about-content">
            <h1 data-lang-en="About Us" data-lang-np="हाम्रोबारे">About Us</h1>
            <p data-lang-en="At Artisan Heritage, we celebrate the timeless craftsmanship and artistic legacy of Nepal. Our platform is dedicated to bringing the beauty and uniqueness of handmade artifacts, sculptures, and art pieces to a global audience while supporting the skilled artisans who create them."
               data-lang-np="आर्टिसन हेरिटेजमा, हामी नेपालको कालातीत शिल्प कौशल र कलात्मक विरासत मनाउँछौं। हाम्रो प्लेटफर्म हस्तनिर्मित कलाकृतिहरू, मूर्तिकलाहरू, र कला टुक्राहरूको सौन्दर्य र विशिष्टतालाई विश्वव्यापी दर्शकहरूमा ल्याउन समर्पित छ र तिनीहरूलाई सिर्जना गर्ने दक्ष कारीगरहरूलाई समर्थन गर्दछ।">At Artisan Heritage, we celebrate the timeless craftsmanship and artistic legacy of Nepal. Our platform is dedicated to bringing the beauty and uniqueness of handmade artifacts, sculptures, and art pieces to a global audience while supporting the skilled artisans who create them

            </p>
            <img src="../assets/about1.jpg" alt="Crafting Art">
            <h2 data-lang-en="Who We Are" data-lang-np="हामी को हौँ">
                "Who We Are"
            </h2>
            <p data-lang-en="We are a passionate team dedicated to preserving Nepal’s rich cultural heritage and bridging the timeless art of craftsmanship to the global market. From detailed Thangka paintings to intricately carved wood sculptures, we curate exceptional pieces that represent Nepal's artistic excellence and creativity. Our mission is to offer not just products, but a story — the story of Nepal's skilled artisans and their unwavering dedication to their craft."
               data-lang-np="हामी नेपालको समृद्ध सांस्कृतिक सम्पदाको संरक्षण गर्न र शिल्पकलाको कालातीत कलालाई विश्व बजारमा पुर्‍याउन समर्पित एक भावुक टोली हौं। विस्तृत थाङ्का चित्रहरूदेखि जटिल रूपमा नक्काशी गरिएका काठको मूर्तिहरूसम्म, हामीले नेपालको कलात्मक उत्कृष्टता र सिर्जनात्मकतालाई प्रतिनिधित्व गर्ने असाधारण टुक्राहरू क्युरेट गर्छौं। हाम्रो मिशन हो। उत्पादन मात्र नभई एउटा कथा प्रस्तुत गर्नका लागि - नेपालको सीपको कथा कारीगरहरू र उनीहरूको शिल्पप्रतिको अटल समर्पण।">We are a passionate team dedicated to preserving Nepal’s rich cultural heritage and bridging the timeless art of craftsmanship to the global market. From detailed Thangka paintings to intricately carved wood sculptures, we curate exceptional pieces that represent Nepal's artistic excellence and creativity. Our mission is to offer not just products, but a story — the story of Nepal's skilled artisans and their unwavering dedication to their craft.

            </p>
            <img src="../assets/about2.png" alt="Artisan at Work">
        </div>
        <div class="about-section">
            <h2 data-lang-en="Spiritually Blessed for You" data-lang-np="चार्मिक वान तपाई">Spiritually Blessed for You</h2>
            <p data-lang-en="At Artisan Heritage, devotion infuses our craft. Each statue, guided by patient and precise hands, is consecrated before reaching you, invoking blessings of spiritual fulfillment. Infused with years of charm, love, and commitment, every piece enriches your surroundings, a value far more than tangible beauty."
               data-lang-np="कलाकार विरासतमा, भक्तिले हाम्रो शिल्पलाई प्रभाव पार्छ। प्रत्येक मूर्ति, धैर्य र सटीक हातहरूद्वारा निर्देशित, आध्यात्मिक पूर्तिको आशीर्वादको आह्वान गर्दै, तपाईं पुग्नु अघि पवित्र गरिन्छ। वर्षौंको आकर्षण, प्रेम र प्रतिबद्धताले भरिएको, प्रत्येक टुक्राले तपाईंको परिवेशलाई समृद्ध बनाउँछ। मूर्त सौन्दर्य भन्दा धेरै मूल्यवान छ।">At Artisan Heritage, devotion infuses our craft. Each statue, guided by patient and precise hands, is consecrated before reaching you, invoking blessings of spiritual fulfillment. Infused with years of charm, love, and commitment, every piece enriches your surroundings, a value far more than tangible beauty.
            </p>
            
        </div>

        <div class="about-section">
            <h2 data-lang-en="Support Our Artisans Today" data-lang-np=" हाम्रा कारीगरहरूलाई समर्थन गर्नुहोस्">Support Our Artisans Today</h2>
            <p data-lang-en="                Every purchase you make helps preserve Nepal's cultural heritage and supports the livelihoods of skilled craftsmen. By choosing Artisan Heritage, you actively take part in bringing exceptional beauty and spiritual craftsmanship to the world. Your purchase makes a difference—thank you for being a part of this journey!
            " data-lang-np="तपाईंले गर्नुभएका प्रत्येक खरिदले नेपालको सांस्कृतिक सम्पदाको संरक्षण गर्न र दक्ष शिल्पकारहरूको जीविकालाई समर्थन गर्दछ। कारीगर सम्पदा छनोट गरेर, तपाईं सक्रिय रूपमा असाधारण सौन्दर्य र आध्यात्मिक शिल्प कौशल संसारमा ल्याउन भाग लिनुहुन्छ। तपाईंको खरिदले फरक पार्छ—यस यात्राको हिस्सा बन्नु भएकोमा धन्यवाद!"> Every purchase you make helps preserve Nepal's cultural heritage and supports the livelihoods of skilled craftsmen. By choosing Artisan Heritage, you actively take part in bringing exceptional beauty and spiritual craftsmanship to the world. Your purchase makes a difference—thank you for being a part of this journey!

            </p>
        </div>
    </section>

    <footer>
        <div class="socials">
            <p data-lang-en="Follow us on:" data-lang-np="हामीलाई फलो गर्नुहोस्:">Follow us on:</p>
            <a href="https://www.facebook.com/">Facebook</a>
            <a href="https://www.instagram.com/">Instagram</a>
            <a href="https://www.youtube.com/">YouTube</a>
        </div>
        <div class="contact-info">
            <p data-lang-en="CONTACT" data-lang-np="सम्पर्क">CONTACT</p>
            <p>+977-9847650007</p>
            <p data-lang-en="Email: artisanheritage@gmail.com" data-lang-np="इमेल: artisanheritage@gmail.com">
                Email: artisanheritage@gmail.com
            </p>
            <p data-lang-en="Address: Patan, Lalitpur, Nepal, 44600" data-lang-np="ठेगाना: पाटन, ललितपुर, नेपाल, ४४६००">
                Address: Patan, Lalitpur, Nepal, 44600
            </p>
        </div>
    </footer>
    <!-- Simplified JavaScript for Language Switching -->
    <script>
        document.getElementById("language-select").addEventListener("change", (e) => {
            const lang = e.target.value;
            document.querySelectorAll("[data-lang-en]").forEach(el => {
                el.textContent = el.getAttribute(`data-lang-${lang}`);
            });
        });

        // Dropdown interaction
document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document.querySelectorAll('.user-dropdown');
            
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const menu = dropdown.querySelector('.dropdown-menu');
                    menu.classList.toggle('show');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const openMenus = document.querySelectorAll('.dropdown-menu.show');
                openMenus.forEach(menu => {
                    if (!menu.parentElement.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            });
        });
        </script>
</body>
</html>


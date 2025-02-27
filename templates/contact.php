<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact-us</title>
        <link rel =" icon" href="../assets/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="../assets/cont.css">
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
            <a href="about.php" data-lang-en="About" data-lang-np="हाम्रोबारे" class="nav-link">About</a>
            <a href="contact.php" data-lang-en="Contact Us" data-lang-np="सम्पर्क गर्नुहोस्" class="nav-link active">Contact Us</a>
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
    <div class="contact-container">
        <div class="contact-info">
            <h2 data-lang-en="How can we help you?" data-lang-np="हामी तपाईंलाई कसरी सहयोग गर्न सक्छौं?">How can we help you?</h2>
            <div class="contact-details">
                <h4 data-lang-en="Contact" data-lang-np="सम्पर्क">Contact</h4>
                <p data-lang-en="९७७-९८४७६५००७" data-lang-np="९७७-९८४७६५००७">977-9847650007</p>
                <p><a href="mailto:artisanheritage@gmail.com">artisanheritage@gmail.com</a></p>
            </div>
            <h2 data-lang-en="Where are we located?" data-lang-np="हाम्रो स्थान कहाँ छ?">Where are we located?</h2>
            <div class="location-details">
                <h4 data-lang-en="Showroom Address" data-lang-np="शोरूम ठेगाना">Showroom Address</h4>
                <p data-lang-en="Patan, Lalitpur" data-lang-np="पाटन, ललितपुर">Patan, Lalitpur</p>
                <p data-lang-en="Nepal, 44600" data-lang-np="नेपाल, ४४६००">Nepal, 44600</p>
            </div>
            <h2 data-lang-en="Want to see what we’re up to?" data-lang-np="हामी के गर्दैछौं जान्न चाहनुहुन्छ?">Want to see what we’re up to?</h2>
            <div class="social-media">
                <h4 data-lang-en="Follow Us" data-lang-np="हाम्रो अनुसरण गर्नुहोस्">Follow Us</h4>
                <div class="icons">
                    <a href="#"><img src="instagram-icon.png" alt="Instagram"></a>
                    <a href="#"><img src="email-icon.png" alt="Email"></a>
                    <a href="#"><img src="whatsapp-icon.png" alt="WhatsApp"></a>
                </div>
            </div>
        </div>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.5022812522473!2d85.32379601474175!3d27.679859133548918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1916f464c067%3A0xa497e1b0d693589!2sLabim%20Mall!5e0!3m2!1sen!2snp!4v1701309127642!5m2!1sen!2snp"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
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

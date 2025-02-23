<?php
session_start();

// Database connection using PDO
try {
    $db_host = "localhost:3306";
    $db_name = "handicraftdb";
    $db_user = "root";
    $db_pass = "11111111";
    
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get featured products using PDO
function getFeaturedProducts($pdo, $limit = 6) {
    try {
        $sql = "SELECT * FROM product ORDER BY ProductID LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching featured products: " . $e->getMessage());
        return [];
    }
}

// Get products
$featured_products = getFeaturedProducts($pdo);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Heritage</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style1.css">
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
                <div class="user-dropdown">
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <button class="dropdown-toggle">
                            👤 <?= htmlspecialchars($_SESSION['username']) ?>
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="manage-products.php" data-lang-en="Manage Products" data-lang-np="उत्पादन व्यवस्थापन">
                                Manage Products
                            </a>
                            <a href="admin-dashboard.php" data-lang-en="Dashboard" data-lang-np="ड्यासबोर्ड">
                                Admin Dashboard
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


    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 data-lang-en="Exquisite Newari Handicrafts" data-lang-np="नेवारी हस्तकला">Exquisite Newari Handicrafts</h1>
            <p data-lang-en="Experience the artistry and craftsmanship of the Newar people, who have been creating fine crafts for centuries." 
               data-lang-np="नेवारी जातिको उत्कृष्ट कारीगरीको अनुभव गर्नुहोस्, जो शताब्दीयौँदेखि कलात्मक वस्तुहरू बनाउँदै आएका छन्।">
                Experience the artistry and craftsmanship of the Newar people, who have been creating fine crafts for centuries.
            </p>
           
        </div>
    </section>

    <section class="featured-image">
        <div class="featured-grid">
            <a href="shop.php?filter=metal" class="featured-item" data-type="metal">
                <img src="image1.png" alt="Metal Product">
            </a>
            <a href="shop.php?filter=stone" class="featured-item" data-type="stone">
                <img src="image2.png" alt="Stone Product">
            </a>
            <a href="shop.php?filter=wood" class="featured-item" data-type="wood">
                <img src="image3.png" alt="Wood Product">
            </a>
        </div>
    </section>
    
 <!-- Featured Products Section -->
 <section class="featured-products">
        <h2 data-lang-en="Featured Products" data-lang-np="विशेष उत्पादनहरू">Featured Products</h2>
        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
                <a href="product.php?id=<?= htmlspecialchars($product['ProductID']) ?>" class="product-link">
                    <div class="product" data-type="<?= htmlspecialchars(strtolower($product['materials'])) ?>" 
                         data-price="<?= htmlspecialchars($product['Price']) ?>">
                        <img src="<?= htmlspecialchars($product['Image_path']) ?>" 
                             alt="<?= htmlspecialchars($product['ProductName']) ?>">
                        <h3 data-lang-en="<?= htmlspecialchars($product['ProductName']) ?>"
                            data-lang-np="<?= htmlspecialchars($product['ProductName']) ?>">
                            <?= htmlspecialchars($product['ProductName']) ?>
                        </h3>
                        <p data-lang-en="Rs <?= number_format($product['Price']) ?>"
                           data-lang-np="रु <?= number_format($product['Price']) ?>">
                            Rs <?= number_format($product['Price']) ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="shop.php">
            <button class="view-more" data-lang-en="View More" data-lang-np="थप हेर्नुहोस्">View More</button>
        </a>
    </section>


    <!-- Footer -->
    <footer>
        <div class="socials">
            <p data-lang-en="Follow us on:" data-lang-np="हामीलाई फलो गर्नुहोस्:">Follow us on:</p>
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">YouTube</a>
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

document.querySelectorAll('.featured-item').forEach(item => {
    item.addEventListener('click', event => {
        event.preventDefault(); // Prevent default anchor behavior
        const filterType = item.getAttribute('data-type'); // Extract filter type
        window.location.href = `shop.php?filter=${filterType}`; // Redirect to shop page with filter
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

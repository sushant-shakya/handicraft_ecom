<?php
session_start();
// Database connection
$db_host = "localhost:3306";
$db_user = "root";
$db_pass = "11111111";
$db_name = "handicraftdb";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get featured products
function getFeaturedProducts($conn, $limit = 6) {
    $sql = "SELECT * FROM product ORDER BY ProductID LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get products
$featured_products = getFeaturedProducts($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Heritage</title>
  
    <link rel =" icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style1.css">
</head>
<body>

    <!-- Navigation Bar -->
<header class="navbar">
    <div class="navbar-logo">
    <a href="landingpg.php>
        <img src="logo.png" alt="Artisan Heritage Logo" class="logo">
        <span class="brand-name" data-lang-en="Artisan Heritage" data-lang-np="हस्तकला धरोहर">Artisan Heritage</span>
</a>
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
                <a href="product1.php?id=<?php echo htmlspecialchars($product['ProductID']); ?>" class="product-link">
                    <div class="product" data-type="<?php echo htmlspecialchars(strtolower($product['materials'])); ?>" 
                         data-price="<?php echo htmlspecialchars($product['Price']); ?>">
                        <img src="<?php echo htmlspecialchars($product['Image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                        <h3 data-lang-en="<?php echo htmlspecialchars($product['ProductName']); ?>"
                            data-lang-np="<?php echo htmlspecialchars($product['ProductName']); ?>">
                            <?php echo htmlspecialchars($product['ProductName']); ?>
                        </h3>
                        <p data-lang-en="Rs <?php echo number_format($product['Price']); ?>"
                           data-lang-np="रु <?php echo number_format($product['Price']); ?>">
                            Rs <?php echo number_format($product['Price']); ?>
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
    </script>

</body>
</html>

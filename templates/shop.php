<?php
session_start();

// Database configuration
$db_host = "localhost:3306";
$db_name = "handicraftdb";
$db_user = "root";
$db_pass = "11111111";

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get filtered products
function getProducts($pdo, $type = 'all', $min_price = 0, $max_price = PHP_FLOAT_MAX, $search = '') {
    $params = [];
    $sql = "SELECT * FROM product WHERE 1=1";

    if ($type != 'all') {
        $sql .= " AND LOWER(materials) LIKE :type";
        $params[':type'] = '%' . strtolower($type) . '%';
    }

    if ($min_price > 0) {
        $sql .= " AND Price >= :min_price";
        $params[':min_price'] = $min_price;
    }

    if ($max_price < PHP_FLOAT_MAX) {
        $sql .= " AND Price <= :max_price";
        $params[':max_price'] = $max_price;
    }

    if (!empty($search)) {
        $sql .= " AND (LOWER(ProductName) LIKE :search OR LOWER(Subtitle) LIKE :search)";
        $params[':search'] = '%' . strtolower($search) . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get filter parameters
$type = $_GET['filter'] ?? 'all';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' 
             ? floatval($_GET['max_price']) 
             : PHP_FLOAT_MAX;
$search = $_GET['search'] ?? '';

// Get filtered products
$products = getProducts($pdo, $type, $min_price, $max_price, $search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop -Artisan Heritage</title>
    <link rel =" icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style2.css">
    
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="navbar-logo">
            <img src="logo.png" alt="Artisan Heritage Logo" class="logo">
            <span class="brand-name" data-lang-en="Artisan Heritage" data-lang-np="हस्तकला धरोहर">Artisan Heritage</span>
        </div>
        <nav class="navbar-links">
            <a href="landingpg.php" data-lang-en="Home" data-lang-np="गृहपृष्ठ" class="nav-link">Home</a>
            <a href="shop.php" data-lang-en="Shop" data-lang-np="किनमेल" class="nav-link active">Shop</a>
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
        <h1 data-lang-en="Discover Your Tranquil Gem." data-lang-np="आफ्नो शान्त रत्न खोज्नुहोस्।">Discover Your Tranquil Gem.</h1>
        <p data-lang-en="Find beautiful, calming pieces to brighten your life. Shop now." 
           data-lang-np="तपाईंको जीवन उज्यालो पार्न सुन्दर, शान्त टुक्राहरू खोज्नुहोस्। अहिले किनमेल गर्नुहोस्।">
            Find beautiful, calming pieces to brighten your life. Shop now.
        </p>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search for products">
            <button id="search-button" type="button">Search</button>
        </div>
    </div>
    </div>
</section>

 <!-- Main Content Area with Filters and Products -->
 <section class="shop-content">
     <!-- Filters Section -->
     <aside class="filters">
            <h2 data-lang-en="Filters" data-lang-np="फिल्टरहरू">Filters</h2>
            <form id="filter-form" method="GET" action="">
                <div class="filter-group">
                    <label for="price-min">Price (From):</label>
                    <input type="number" name="min_price" id="price-min" value="<?= htmlspecialchars($min_price) ?>" placeholder="रु">
                </div>
                <div class="filter-group">
                    <label for="price-max">Price (To):</label>
                    <input type="number" name="max_price" id="price-max" 
       value="<?= ($max_price !== PHP_FLOAT_MAX) ? htmlspecialchars($max_price) : '' ?>" 
       placeholder="Rs">

                </div>
                <div class="filter-group">
                    <label for="product-type">Product Type:</label>
                    <select name="filter" id="product-type">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="metal" <?= $type === 'metal' ? 'selected' : '' ?>>Metal</option>
                        <option value="wood" <?= $type === 'wood' ? 'selected' : '' ?>>Wood</option>
                        <option value="stone" <?= $type === 'stone' ? 'selected' : '' ?>>Stone</option>
                    </select>
                </div>
                <button type="submit" class="filter-button">Apply Filter</button>
            </form>
        </aside>


        <!-- Products Section -->
        <div class="products">
            <h2>Products</h2>
            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <p class="no-products">No products found matching your criteria.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <a href="product.php?id=<?= htmlspecialchars($product['ProductID']) ?>" class="product-link">
                            <div class="product" 
                                 data-type="<?= htmlspecialchars(strtolower($product['materials'])) ?>" 
                                 data-price="<?= htmlspecialchars($product['Price']) ?>">
                                <img src="<?= htmlspecialchars($product['Image_path']) ?>" 
                                     alt="<?= htmlspecialchars($product['ProductName']) ?>">
                                <h3><?= htmlspecialchars($product['ProductName']) ?></h3>
                                <p>Rs <?= number_format($product['Price']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
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

        <script>
// Language Switching
document.getElementById("language-select").addEventListener("change", function(e) {
        const lang = e.target.value;
        document.querySelectorAll("[data-lang-en]").forEach(el => {
            el.textContent = el.getAttribute(`data-lang-${lang}`);
        });
    });


//filter
function filterProducts() {
    const urlParams = new URLSearchParams(window.location.search);
    const productType = urlParams.get("filter") || "all";
    const priceMin = parseFloat(document.getElementById("price-min").value) || 0;
    const priceMax = parseFloat(document.getElementById("price-max").value) || Infinity;

    document.querySelectorAll(".product").forEach(product => {
        const price = parseFloat(product.dataset.price);
        const type = product.dataset.type;
        const showProduct = (productType === "all" || type === productType) && (price >= priceMin && price <= priceMax);
        product.style.display = showProduct ? "block" : "none";
    });

    document.getElementById("product-type").value = productType || "all";
}

window.addEventListener("DOMContentLoaded", filterProducts);



//search
             // Search functionality
    document.getElementById("search-button").addEventListener("click", function() {
        const searchQuery = document.getElementById("search-input").value.trim();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set("search", searchQuery);
        window.location.href = currentUrl.toString();
    });

    // Clear previous results
    resultsContainer.innerHTML = ""; 

    // if (!query) {
    //     resultsContainer.innerHTML = "<p style='color: red;'>Please enter a search term.</p>";
    //     return;
    // }

    let hasMatch = false;

    // Loop through all products and toggle visibility based on search
    products.forEach(product => {
        const name = product.querySelector("h3").textContent.toLowerCase();

        if (name.includes(query)) {
            product.style.display = "block"; // Show matched product
            hasMatch = true;
        } else {
            product.style.display = "none"; // Hide non-matching products
        }
    });

    // If no match found, show a message
    if (!hasMatch) {
        resultsContainer.innerHTML = "<p style='color: red;'>No products found.</p>";
    }
});
        </script>
        
        </body>
        </html>
        
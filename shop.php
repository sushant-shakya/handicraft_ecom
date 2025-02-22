<?php 
session_start();
$db_host = "localhost:3306";
$db_user = "root";
$db_pass = "11111111";
$db_name = "handicraftdb";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get filtered products
function getProducts($conn, $type = 'all', $min_price = 0, $max_price = PHP_FLOAT_MAX, $search = '') {
    $sql = "SELECT * FROM product WHERE 1=1";
    $params = [];
    $types = "";

    if ($type != 'all') {
        $sql .= " AND LOWER(materials) LIKE ?";
        $type_param = "%" . strtolower($type) . "%";
        $params[] = $type_param;
        $types .= "s";
    }

    if ($min_price > 0) {
        $sql .= " AND Price >= ?";
        $params[] = $min_price;
        $types .= "d";
    }

    if ($max_price < PHP_FLOAT_MAX) {
        $sql .= " AND Price <= ?";
        $params[] = $max_price;
        $types .= "d";
    }

    if (!empty($search)) {
        $sql .= " AND (LOWER(ProductName) LIKE ? OR LOWER(Subtitle) LIKE ?)";
        $search_param = "%" . strtolower($search) . "%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ss";
    }

    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get filter parameters
$type = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_FLOAT_MAX;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get filtered products
$products = getProducts($conn, $type, $min_price, $max_price, $search);
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
        <div class="filter-group">
            <label for="price-min" data-lang-en="Price (From):" data-lang-np="मूल्य (देखि):">Price (From):</label>
            <input type="number" id="price-min" placeholder="रु">
        </div>
        <div class="filter-group">
            <label for="price-max" data-lang-en="Price (To):" data-lang-np="मूल्य (सम्म):">Price (To):</label>
            <input type="number" id="price-max" placeholder="रु">
        </div>
        <div class="filter-group">
            <label for="product-type" data-lang-en="Product Type:" data-lang-np="उत्पादन प्रकार:">Product Type:</label>
            <select id="product-type">
                <option value="all" data-lang-en="All" data-lang-np="सबै">All</option>
                <option value="metal" data-lang-en="Metal" data-lang-np="धातु">Metal</option>
                <option value="wood" data-lang-en="Wood" data-lang-np="काठ">Wood</option>
                <option value="stone" data-lang-en="Stone" data-lang-np="ढुंगा">Stone</option>
            </select>
        </div>
        <button onclick="filterProducts()" class="filter-button" data-lang-en="Apply Filter" data-lang-np="फिल्टर लागू गर्नुहोस्">Apply Filter</button>
    </aside>


        <!-- Products Section -->
        <div class="products">
            <h2 data-lang-en="Products" data-lang-np="उत्पादनहरू">Products</h2>
            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <p class="no-products">No products found matching your criteria.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <a href="product1.php?id=<?php echo htmlspecialchars($product['ProductID']); ?>" class="product-link">
                            <div class="product" 
                                 data-type="<?php echo htmlspecialchars(strtolower($product['materials'])); ?>" 
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
            document.getElementById("language-select").addEventListener("change", (e) => {
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
            document.getElementById("search-button").addEventListener("click", function () {
    const query = document.getElementById("search-input").value.trim().toLowerCase();
    const resultsContainer = document.getElementById("search-results");
    const products = document.querySelectorAll(".product");

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
        
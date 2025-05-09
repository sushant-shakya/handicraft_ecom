<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

// Function to get filtered products with robust price validation
function getProducts($pdo, $type = 'all', $min_price = 0, $max_price = PHP_FLOAT_MAX, $search = '') {
    $params = [];
    $sql = "SELECT * FROM product WHERE 1=1";

    if ($type != 'all') {
        $sql .= " AND type = :type";
        $params[':type'] = $type;
    }

    // Only add price conditions if they are valid and meaningful
    if ($min_price > 0 && $max_price == PHP_FLOAT_MAX) {
        // If only min_price is provided, add a condition that effectively ensures no products match
        $sql .= " AND Price > :max_price";
        $params[':max_price'] = $max_price;
    } elseif ($min_price > 0) {
        $sql .= " AND Price >= :min_price";
        $params[':min_price'] = $min_price;

        if ($max_price < PHP_FLOAT_MAX) {
            $sql .= " AND Price <= :max_price";
            $params[':max_price'] = $max_price;
        }
    }

    if (!empty($search)) {
        $sql .= " AND (LOWER(ProductName) LIKE :search OR LOWER(Subtitle) LIKE :search)";
        $params[':search'] = '%' . strtolower($search) . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get and validate filter parameters
$type = $_GET['filter'] ?? 'all';
$min_price = isset($_GET['min_price']) ? abs(floatval($_GET['min_price'])) : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' 
             ? abs(floatval($_GET['max_price'])) 
             : PHP_FLOAT_MAX;
$search = $_GET['search'] ?? '';

// Ensure max price is not less than min price
if ($max_price < $min_price) {
    $max_price = PHP_FLOAT_MAX;
}

// Get filtered products
$products = getProducts($pdo, $type, $min_price, $max_price, $search);

// Determine if no products message should be shown
$show_no_products_message = (
    (isset($_GET['min_price']) && $min_price > 0 && $max_price === PHP_FLOAT_MAX && empty($products)) || 
    empty($products)
);

// Get and validate filter parameters
$type = $_GET['filter'] ?? 'all';
$min_price = isset($_GET['min_price']) ? abs(floatval($_GET['min_price'])) : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' 
             ? abs(floatval($_GET['max_price'])) 
             : PHP_FLOAT_MAX;
$search = $_GET['search'] ?? '';

// Ensure max price is not less than min price
if ($max_price < $min_price) {
    $max_price = PHP_FLOAT_MAX;
}

// Get filtered products
$products = getProducts($pdo, $type, $min_price, $max_price, $search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop -Artisan Heritage</title>
    <link rel =" icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/style2.css">
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
        .input-error {
            border: 2px solid red;
        }
        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
        .no-products-message {
            text-align: center;
            color: #888;
            margin: 20px 0;
            font-size: 1.2em;
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
                    <input type="number" 
                           name="min_price" 
                           id="price-min" 
                           value="<?= $min_price > 0 ? htmlspecialchars($min_price) : '' ?>" 
                           placeholder="Minimum Price" 
                           min="0" 
                           step="0.01" >
                    <div id="min-price-error" class="error-message"></div>
                </div>

                <div class="filter-group">
                    <label for="price-max">Price (To):</label>
                    <input type="number" 
                           name="max_price" 
                           id="price-max" 
                           value="<?= ($max_price !== PHP_FLOAT_MAX) ? htmlspecialchars($max_price) : '' ?>" 
                           placeholder="Maximum Price" 
                           min="0" 
                           step="0.01" >
                    <div id="max-price-error" class="error-message"></div>
                </div>


                <div class="filter-group">
                    <label for="product-type">Product Type:</label>
                    <select name="filter" id="product-type">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="metal" <?= $type === 'metal' ? 'selected' : '' ?>>Metal</option>
                        <option value="stone" <?= $type === 'stone' ? 'selected' : '' ?>>Stone</option>
                        <option value="wood" <?= $type === 'wood' ? 'selected' : '' ?>>Wood</option>
                    </select>
                </div>

                <button type="submit" class="filter-button">Apply Filter</button>
            </form>
        </aside>

        <!-- Products Section -->
        <!-- Products Section -->
        <div class="products">
            <h2>Products</h2>
            <?php if ($show_no_products_message): ?>
                <div class="no-products-message">
                    <?php 
                    if ($min_price > 0 && $max_price === PHP_FLOAT_MAX) {
                        echo "No products found for Rs " . number_format($min_price);
                    } elseif ($max_price === PHP_FLOAT_MAX) {
                        echo "No products found for Rs " . number_format($max_price) ;
                    }else{
                        echo "No products found matching your criteria.";
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="product.php?id=<?= htmlspecialchars($product['ProductID']) ?>" class="product-link">
                            <div class="product" 
                                 data-type="<?= htmlspecialchars(strtolower($product['type'])) ?>" 
                                 data-price="<?= htmlspecialchars($product['Price']) ?>">
                                <img src="<?= htmlspecialchars('../src/'.$product['Image_path']) ?>" 
                                     alt="<?= htmlspecialchars($product['ProductName']) ?>">
                                <h3><?= htmlspecialchars($product['ProductName']) ?></h3>
                                <p>Rs <?= number_format($product['Price']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
        const showProduct = (productType === "all" || type.includes(productType)) && 
                           (price >= priceMin && (priceMax === Infinity || price <= priceMax));
        product.closest('.product-link').style.display = showProduct ? "block" : "none";
    });

    document.getElementById("product-type").value = productType || "all";
}

window.addEventListener("DOMContentLoaded", filterProducts);


   document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const minPriceInput = document.getElementById('price-min');
    const maxPriceInput = document.getElementById('price-max');
    const minPriceError = document.getElementById('min-price-error');
    const maxPriceError = document.getElementById('max-price-error');

    // Retrieve filter parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const minPriceParam = urlParams.get('min_price');
    const maxPriceParam = urlParams.get('max_price');

    // Set input values from URL parameters if they exist
    if (minPriceParam !== null) {
        minPriceInput.value = parseFloat(minPriceParam);
    }

    if (maxPriceParam !== null && maxPriceParam !== '') {
        maxPriceInput.value = parseFloat(maxPriceParam);
    }

    // Price validation function
    function validatePrices() {
        // Reset previous error states
        minPriceInput.classList.remove('input-error');
        maxPriceInput.classList.remove('input-error');
        minPriceError.textContent = '';
        maxPriceError.textContent = '';

        let isValid = true;

        // Validate minimum price
        const minPrice = minPriceInput.value.trim() !== '' 
            ? parseFloat(minPriceInput.value) 
            : 0;

        if (isNaN(minPrice) || minPrice < 0) {
            minPriceInput.classList.add('input-error');
            minPriceError.textContent = 'Minimum price must be a non-negative number';
            isValid = false;
        }

        // Validate maximum price if provided
        if (maxPriceInput.value.trim() !== '') {
            const maxPrice = parseFloat(maxPriceInput.value);

            if (maxPrice < 0) {
                maxPriceInput.classList.add('input-error');
                maxPriceError.textContent = 'Maximum price must be a non-negative number';
                isValid = false;
            }

            // Check if max price is less than min price
            if (isNaN(maxPrice) || maxPrice < minPrice) {
                maxPriceInput.classList.add('input-error');
                maxPriceError.textContent = 'Maximum price cannot be less than minimum price';
                isValid = false;
            }
        }

        return isValid;
    }

    // Add event listeners for real-time validation
    minPriceInput.addEventListener('input', validatePrices);
    maxPriceInput.addEventListener('input', validatePrices);

    // Form submission validation
    filterForm.addEventListener('submit', function(event) {
        if (!validatePrices()) {
            event.preventDefault();
        }
    });

    // Initial validation when page loads
    validatePrices();
});

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
        
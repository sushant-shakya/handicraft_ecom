<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM product WHERE ProductID = ?");
$stmt->bindParam(1, $product_id, PDO::PARAM_INT); // Correct PDO parameter binding
$stmt->execute();

// Fetch the result
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - Artisan Heritage</title>
    <link rel =" icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/style3.css">
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
   
    <main class="container">
        <div class="product">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars('../src/'.$product['Image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
            </div>
            <div class="product-details">
                <h1 data-lang-en="<?php echo htmlspecialchars($product['ProductName']); ?>"
                    data-lang-np="<?php echo htmlspecialchars($product['ProductName']); ?>">
                    <?php echo htmlspecialchars($product['ProductName']); ?>
                </h1>
                
                <p class="subtitle" data-lang-en="<?php echo htmlspecialchars($product['Subtitle']); ?>"
                   data-lang-np="<?php echo htmlspecialchars($product['Subtitle']); ?>">
                    <?php echo htmlspecialchars($product['Subtitle']); ?>
                </p>
                
                <p class="price" data-lang-en="Rs <?php echo number_format($product['Price']); ?>"
                   data-lang-np="रु <?php echo number_format($product['Price']); ?>">
                    Rs <?php echo number_format($product['Price']); ?>
                </p>

                <a href="form.php?product_id=<?php echo $product['ProductID']; ?>&product_name=<?php echo urlencode($product['ProductName']); ?>">
                    <button class="order-button" data-lang-en="Order" data-lang-np="अर्डर गर्नुहोस्">Order</button>
                </a>

                <div class="details">
                    <h3 data-lang-en="Dimension:" data-lang-np="आकार:">Dimension:</h3>
                    <p data-lang-en="<?php echo htmlspecialchars($product['dimension']); ?>"
                       data-lang-np="<?php echo htmlspecialchars($product['dimension']); ?>">
                        <?php echo htmlspecialchars($product['dimension']); ?>
                    </p>
                    
                    <h3 data-lang-en="Materials:" data-lang-np="सामग्री:">Materials:</h3>
                    <p data-lang-en="<?php echo htmlspecialchars($product['materials']); ?>"
                       data-lang-np="<?php echo htmlspecialchars($product['materials']); ?>">
                        <?php echo htmlspecialchars($product['materials']); ?>
                    </p>
                    
                    <h3 data-lang-en="Description:" data-lang-np="विवरण:">Description:</h3>
                    <p data-lang-en="<?php echo htmlspecialchars($product['Description']); ?>"
                       data-lang-np="<?php echo htmlspecialchars($product['Description']); ?>">
                        <?php echo htmlspecialchars($product['Description']); ?>
                    </p>
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
 
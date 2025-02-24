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

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM product WHERE ProductID = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: shop.php");
    exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - Artisan Heritage</title>
    <link rel =" icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../style3.css">
    
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="navbar-logo">
            <img src="../logo.png" alt="Artisan Heritage Logo" class="logo">
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
                    <a href="./logout.php" class="logout-button" data-lang-en="Logout" data-lang-np="बाहिर निस्कनुहोस्">Logout</a>
                </div>
            <?php else: ?>
                <a href="./login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'])?>"  class="login-button" data-lang-en="Login" data-lang-np="लग-इन">Login</a>
            <?php endif; ?>
        </nav>
    </header>
   
    <main class="container">
        <div class="product">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars('../'.$product['Image_path']); ?>" 
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
 
<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../templates/login.php');
    exit();
}

require __DIR__ . '/../database/dbConnectionWithPDO.php';

// Handle Delete Request
if (isset($_POST['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM product WHERE ProductID = ?");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch(PDOException $e) {
        echo "Delete failed: " . $e->getMessage();
    }
}

// Fetch all products
try {
    $stmt = $pdo->query("SELECT * FROM product ORDER BY ProductID");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Query failed: " . $e->getMessage();
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ff9900;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            position: relative;
        }
        .action-menu {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
            right: 0;
        }
        .action-menu button {
            width: 100%;
            padding: 10px;
            border: none;
            background: white;
            text-align: left;
            cursor: pointer;
        }
        .action-menu button:hover {
            background: #f4f4f4;
        }
        .add-button {
            background-color: #ff9900;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .home-button {
            background-color: #ff9900;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .admindsh-button {
            background-color: #ff9900;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .userrolemanage-button {
            background-color: #ff9900;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #e68a00;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Manage Products</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="<?= $_SESSION['message_type'] ?>-message">
            <?= $_SESSION['message'] ?>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        endif; 
    ?>

    <a href="add-product-form.php">
        <button class="add-button">Add New Product</button>
    </a>
    <a href="../templates/landingpg.php">
        <button class="home-button">Home</button>
    </a>
    <a href="admin-dashboard.php">
        <button class="admindsh-button">Admin dashboard</button>
    </a>
    <a href="user-role-managment.php">
        <button class="userrolemanage-button">Manage user role</button>
    </a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Subtitle</th>
                <th>Price</th>
                <th>Dimension</th>
                <th>Product Type</th>
                <th>Material</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['ProductName']) ?></td>
                <td><?= htmlspecialchars($product['Subtitle']) ?></td>
                <td><?= number_format($product['Price']) ?></td>
                <td><?= htmlspecialchars($product['dimension']) ?></td>
                <td><?= htmlspecialchars($product['type']) ?></td>
                <td><?= htmlspecialchars($product['materials']) ?></td>
                <td><?= htmlspecialchars($product['Description']) ?></td>
                
                <td class="actions">
                    <button onclick="toggleMenu(this)">...</button>
                    <div class="action-menu">
                        <button onclick="editProduct(<?= $product['ProductID'] ?>)">Edit</button>
                        <button onclick="confirmDelete(<?= $product['ProductID'] ?>)">Delete</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Hidden form for delete operation -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_id" id="deleteId">
    </form>

    <script>
        function toggleMenu(button) {
            let menu = button.nextElementSibling;
            document.querySelectorAll('.action-menu').forEach(menuItem => {
                if (menuItem !== menu) {
                    menuItem.style.display = 'none';
                }
            });
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        function editProduct(productId) {
            window.location.href = `edit-product.php?id=${productId}`;
        }

        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('deleteId').value = productId;
                document.getElementById('deleteForm').submit();
            }
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.matches('button')) {
                document.querySelectorAll('.action-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>
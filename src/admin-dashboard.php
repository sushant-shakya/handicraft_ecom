<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../templates/login.php');
    exit();
}
// Include database connection
require __DIR__ . '/../database/dbConnectionWithPDO.php';

// Handle delete action
if (isset($_POST['delete_order']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM `Order` WHERE OrderID = ?");
        $stmt->execute([$order_id]);
        
        // Redirect to refresh the page after deletion
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error_message = "Failed to delete order: " . $e->getMessage();
    }
}

// Fetch orders from the database
try {
    $stmt = $pdo->prepare("
        SELECT 
            o.OrderID, 
            o.FullName, 
            u.Email, 
            o.Phone, 
            o.ProductName, 
            o.Quantity, 
            o.PaymentMethod, 
            o.Address, 
            o.OrderDate 
        FROM `Order` o
        INNER JOIN `User` u ON o.UserID = u.UserID
        ORDER BY o.OrderDate DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Failed to fetch orders: " . $e->getMessage();
    exit();
} finally {
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #555;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
        .home-button, .managepdt-button, .userrolemanage-button {
            background-color:#333;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .delete-button {
            background-color: #ff3333;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #cc0000;
        }
        /* Confirmation dialog styling */
        .confirmation-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .dialog-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 400px;
            border-radius: 5px;
            text-align: center;
        }
        .dialog-buttons button {
            margin: 0 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .confirm-delete {
            background-color: #ff3333;
            color: white;
        }
        .cancel-delete {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h3>List of Orders</h3>
    
    <?php if (isset($error_message)): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <a href="../templates/landingpg.php">
        <button class="home-button">Home</button>
    </a>
    <a href="manage-products.php">
        <button class="managepdt-button">Manage Products</button>
    </a>
    <a href="user-role-managment.php">
        <button class="userrolemanage-button">Manage User Role</button>
    </a>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Payment Method</th>
                <th>Address</th> 
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($order['FullName']); ?></td>
                        <td><?php echo htmlspecialchars($order['Email']); ?></td>
                        <td><?php echo htmlspecialchars($order['Phone']); ?></td>
                        <td><?php echo htmlspecialchars($order['ProductName']); ?></td>
                        <td><?php echo htmlspecialchars($order['Quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['PaymentMethod']); ?></td>
                        <td><?= htmlspecialchars($order['Address']) ?></td>
                        <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
                        <td>
                            <button class="delete-button" onclick="confirmDelete(<?php echo $order['OrderID']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No orders found in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Confirmation Dialog -->
    <div id="confirmationDialog" class="confirmation-dialog">
        <div class="dialog-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this order? This action cannot be undone.</p>
            <div class="dialog-buttons">
                <form id="deleteForm" method="POST">
                    <input type="hidden" id="orderIdInput" name="order_id" value="">
                    <input type="hidden" name="delete_order" value="1">
                    <button type="button" class="cancel-delete" onclick="hideDialog()">Cancel</button>
                    <button type="submit" class="confirm-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(orderId) {
            document.getElementById('orderIdInput').value = orderId;
            document.getElementById('confirmationDialog').style.display = 'block';
        }
        
        function hideDialog() {
            document.getElementById('confirmationDialog').style.display = 'none';
        }
    </script>
</body>
</html>
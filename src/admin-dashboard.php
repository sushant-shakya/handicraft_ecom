<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
// Include database connection
require 'dbConnectionWithPDO.php';

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
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h3>List of Orders</h3>
    <table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Address</th> 
        <th>Order Date</th>
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
                        <td><?= htmlspecialchars($order['Address']) ?></td>
                        <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No orders found in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

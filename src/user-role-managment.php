<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../templates/login.php');
    exit();
}

// Database connection
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

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['role'])) {
    try {
        $stmt = $pdo->prepare("UPDATE `User` SET role = :role WHERE UserID = :user_id");
        $stmt->execute([
            ':role' => $_POST['role'],
            ':user_id' => $_POST['user_id']
        ]);
        $success_message = "Role updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating role: " . $e->getMessage();
    }
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user']) && isset($_POST['user_id'])) {
    try {
        // You might want to add additional checks here to prevent deleting the last admin
        // For example, check if there will still be at least one admin after deletion
        
        $stmt = $pdo->prepare("DELETE FROM `User` WHERE UserID = :user_id");
        $stmt->execute([
            ':user_id' => $_POST['user_id']
        ]);
        $success_message = "User deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting user: " . $e->getMessage();
    }
}

// Fetch all users - Fixed SQL syntax
try {
    $stmt = $pdo->query("SELECT UserID, UserName, Email, role FROM `User`");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error fetching users: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Roles - Artisan Heritage</title>
    <link rel="icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../style1.css">
    <style>
        .role-management {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .role-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .role-table th,
        .role-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .role-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .role-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .update-btn {
            padding: 0.5rem 1rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .update-btn:hover {
            background: #45a049;
        }

        .delete-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .action-cell {
            display: flex;
            gap: 0.5rem;
        }

        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Add navigation button */
        .home-button {
            padding: 0.5rem 1rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .admindsh-button {
            padding: 0.5rem 1rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .managepdt-button {
            padding: 0.5rem 1rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 2rem;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .modal-cancel {
            padding: 0.5rem 1rem;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-confirm {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="role-management">
        <div class="nav-buttons">
            <a href="../templates/landingpg.php" class="home-button">Home</a>
            <a href="admin-dashboard.php" class="admindsh-button">Admin Dashboard</a>
            <a href="manage-products.php" class="managepdt-button">Manage Products</a>
        </div>
        
        <h1>Manage User Roles</h1>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <table class="role-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['UserName']); ?></td>
                        <td><?php echo htmlspecialchars($user['Email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td class="action-cell">
                            <form method="POST" action="" style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                <select name="role" class="role-select">
                                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" class="update-btn">Update Role</button>
                            </form>
                            <button class="delete-btn" onclick="confirmDelete(<?php echo $user['UserID']; ?>, '<?php echo htmlspecialchars($user['UserName']); ?>')">Delete User</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete user <span id="deleteUserName"></span>?</p>
            <p>This action cannot be undone.</p>
            
            <div class="modal-actions">
                <button class="modal-cancel" onclick="closeModal()">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <input type="hidden" name="delete_user" value="1">
                    <button type="submit" class="modal-confirm">Delete User</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('deleteModal');
        
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
            modal.style.display = 'block';
        }
        
        function closeModal() {
            modal.style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
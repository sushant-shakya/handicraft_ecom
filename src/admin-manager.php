<?php
session_start();
require_once 'dbConnectionWithPDO.php'; // Ensure this contains your PDO connection

class AdminManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Set a user as admin
    public function setAdmin($userId, $permissions = []) {
        try {
            // Start transaction
            $this->pdo->beginTransaction();

            // Update user role
            $stmt = $this->pdo->prepare("UPDATE `User` SET role = 'admin' WHERE UserID = :userId");
            $stmt->execute(['userId' => $userId]);

            // Set default permissions
            $defaultPermissions = [
                'can_manage_products' => true,
                'can_manage_users' => true,
                'can_view_orders' => true
            ];

            // Merge with custom permissions if provided
            $finalPermissions = array_merge($defaultPermissions, $permissions);

            // Insert into admin_permissions
            $stmt = $this->pdo->prepare("
                INSERT INTO admin_permissions 
                (UserID , can_manage_products, can_manage_users, can_view_orders)
                VALUES 
                (:userId, :can_manage_products, :can_manage_users, :can_view_orders)
            ");

            $stmt->execute([
                'userId' => $userId,
                'can_manage_products' => $finalPermissions['can_manage_products'],
                'can_manage_users' => $finalPermissions['can_manage_users'],
                'can_view_orders' => $finalPermissions['can_view_orders']
            ]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error setting admin: " . $e->getMessage());
            return false;
        }
    }

    // Remove admin privileges
    public function removeAdmin($userId) {
        try {
            $this->pdo->beginTransaction();

            // Update user role back to normal user
            $stmt = $this->pdo->prepare("UPDATE `User` SET role = 'user' WHERE UserID = :userId");
            $stmt->execute(['userId' => $userId]);

            // Remove from admin_permissions
            $stmt = $this->pdo->prepare("DELETE FROM admin_permissions WHERE UserID  = :userId");
            $stmt->execute(['userId' => $userId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error removing admin: " . $e->getMessage());
            return false;
        }
    }

    // Check if user is admin
    public function isAdmin($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT role FROM `User` WHERE UserID = :userId");
            $stmt->execute(['userId' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user && $user['role'] === 'admin';
        } catch (PDOException $e) {
            error_log("Error checking admin status: " . $e->getMessage());
            return false;
        }
    }

    // Get admin permissions
    public function getAdminPermissions($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM admin_permissions 
                WHERE UserID = :userId
            ");
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting admin permissions: " . $e->getMessage());
            return false;
        }
    }
}

// Example usage in set_admin.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
    
    if ($userId) {
        $adminManager = new AdminManager($pdo);
        if ($adminManager->setAdmin($userId)) {
            echo "Admin privileges granted successfully";
        } else {
            echo "Error granting admin privileges";
        }
    }
}
?>
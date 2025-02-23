<?php
require_once 'admin-manager.php';

// Check if current user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// List users and provide admin controls
$stmt = $pdo->query("SELECT UserID, username, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <tr>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
            <form method="post" action="set_admin.php">
                <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                <?php if ($user['role'] === 'admin'): ?>
                    <button type="submit" name="action" value="remove">Remove Admin</button>
                <?php else: ?>
                    <button type="submit" name="action" value="set">Make Admin</button>
                <?php endif; ?>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
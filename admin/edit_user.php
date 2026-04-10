<?php
require_once("../config/db.php");
$mysqli = get_mysqli();

if(!isset($_GET['id'])){
    header("Location: users.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM users WHERE id =?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    die("User not found");
}

$user = $result->fetch_assoc();

$page_title = "Edit User";

ob_start();
?>
<?php if(isset($_GET['success'])): ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
        Action completed successfully!
    </div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
        Error Updating Details
    </div>
<?php endif; ?>
<div class="form-container">
    <form action="../controllers/AdminController.php" method="POST" >
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

        <div class="form-grid">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            </div>
            <div class="form-group">
                <label>Role</label>
                    <select name="role">
                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
            </div>
        </div>
        <div class="form-actions">
            <a href="users.php" class="btn cancel">Cancel</a>
            <button type="submit" name="update_user" class="btn primary">Update User</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include("layout.php");
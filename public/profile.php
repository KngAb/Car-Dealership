<?php
session_start();
require_once "../config/db.php";
$mysqli = get_mysqli();

$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT first_name, middle_name, last_name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$profile_errors = $_SESSION['profile_errors'] ?? [];
unset($_SESSION['profile_errors']);

ob_start();
?>
<div class="page-header">
    <h2>My Profile</h2>
    <p>Manage your account information</p>
</div>
<?php if(isset($_GET['success'])): ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
        Action completed successfully!
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
        <?php
            if($_GET['error'] == 'match') echo "New passwords do not match!";
            elseif($_GET['error'] == 'wrong_current') echo "Current password is incorrect!";
            else echo "Something went wrong.";
        ?>
    </div>
<?php endif; ?>
<div class="card-grid">
    <div class="card">
         <h4>Account Information</h4>
         <p><strong>First Name:</strong><?= htmlspecialchars($user['first_name'])?></p>
         <p><strong>Last Name:</strong><?= htmlspecialchars($user['last_name'])?></p>
         <p><strong>Middle Name:</strong><?= htmlspecialchars($user['middle_name'])?></p>
         <p><strong>Email:</strong><?= htmlspecialchars($user['email'])?></p>
         <p><strong>Phone:</strong><?= htmlspecialchars($user['phone'])?></p>
    </div>
    <div class="card">
        <h4>Update Profile</h4>
        <form action="../controllers/profileController.php" method="POST">
             <div class="form-row">
               <div class="form-group">
                <label for="">First Name</label>
                <input type="text" name='first_name' value="<?= htmlspecialchars($user['first_name'])?>" required>
                <?= isset($profile_errors['f_name']) ? "<span style='color:red; font-size:11px;'>".$profile_errors['f_name']."</span>" : "" ?>
              </div>
              <div class="form-group">
                <label for="">Middle Name</label>
                <input type="text" name='middle_name' value="<?= htmlspecialchars($user['middle_name'])?>" required>
                <input type="text" name='middle_name' value="<?= htmlspecialchars($user['middle_name']) ?>">
              </div>
              <div class="form-group">
                <label for="">Last Name</label>
                <input type="text" name='last_name' value="<?= htmlspecialchars($user['last_name'])?>" required>
                <?= isset($profile_errors['l_name']) ? "<span style='color:red; font-size:11px;'>".$profile_errors['l_name']."</span>" : "" ?>
              </div>
              </div>
              <div class="form-group">
                <label for="">Email Address</label>
                <input type="email" name='email' value="<?= htmlspecialchars($user['email'])?>" required>
                <?= isset($profile_errors['email']) ? "<span style='color:red; font-size:11px;'>".$profile_errors['email']."</span>" : "" ?>
            </div>
            <div class="form-group">
                <label for="">Phone</label>
                <input type="text" name='phone' value="<?= htmlspecialchars($user['phone'])?>" required>
                <?= isset($profile_errors['phone']) ? "<span style='color:red; font-size:11px;'>".$profile_errors['phone']."</span>" : "" ?>
            </div>
            <button class="btn-sm" type="submit" >Update Profile</button>
        </form>
    </div>
    <div class="card">
        <h4>Change Password</h4>
        <form action="../controllers/passwordController.php" method="POST">
            <div class="form-group">
                <label for="">Current Password</label>
                <input type="password" name="current_password" id=""required>
            </div>
            <div class="form-group">
                <label for="">New Password</label>
                <input type="password" name="new_password" id=""required>
            </div>
             <div class="form-group">
                <label for="">Current Password</label>
                <input type="password" name="confirm_password" id=""required>
            </div>
            <button class="btn-sm" type="submit">Change Password</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Profile";
require 'dashboardLayout.php';
?>
<?php 
include '../includes/header.php' ;
require_once '../controllers/csrf.php';
$csrf = generateToken();
?>
<section class="auth-section">
    <div class="auth-container">
    <h2>Login</h2>
    <p class="auth-subtitle">Login to Continue to AB Autos</p>
    <?php if(isset($_GET['error'])): ?>
        <div class="auth-error">
            Invalid email or password
        </div>
    <?php endif; ?>    
    <form action="../controllers/loginController.php" method="POST" class="auth-form">
        
        <div class="form-group">
            <label for="">Email Address</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" required>
        </div>

          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf)?>">

        <button type="submit" class="btn">Login</button>
    </form>
    
    <p class="auth-footer">Don't have an account?<a href="register.php">Create Account</a></p>
</div>
</section>

<?php include "../includes/footer.php"; ?>
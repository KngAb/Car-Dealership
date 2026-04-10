<?php 
include "../includes/header.php";
require_once '../controllers/csrf.php';
$csrf = generateToken();

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];


$fnameError = $errors['fnameError'] ?? "";
$mnameError = $errors['mnameError'] ?? "";
$lnameError = $errors['lnameError'] ?? "";
$emailError = $errors['emailError'] ?? "";
$phoneError = $errors['phoneError'] ?? "";
$passwordError = $errors['passwordError'] ?? "";
$cpasswordError = $errors['cpasswordError'] ?? "";
$picError = $errors['picError'] ?? "";


$first_name = $old['first_name'] ?? "";
$middle_name = $old['middle_name'] ?? "";
$last_name = $old['last_name'] ?? "";
$email = $old['email'] ?? "";
$phone = $old['phone'] ?? "";

// 4. CRITICAL: Clear the session errors so they don't persist on next refresh
unset($_SESSION['errors']);
unset($_SESSION['old']);
?>

<section class="auth-section">
    <div class="auth-container">
    <h2>Create Account</h2>
    <form action="../controllers/registerController.php" method="POST" class="auth-form" enctype="multipart/form-data">
        <div class="form-row">
           <div class="form-group">
            <label for="">First Name</label>
            <input type="text" name="first_name" value="<?= $first_name ?>" >
            <div id="fnameError" class="<?php if($fnameError) echo 'form-error' ;?>"><?=  $fnameError ?></div>

           </div>
           <div class="form-group">
            <label for="">Middle Name</label>
            <input type="text" name="middle_name" value="<?= $middle_name ?>" >
            <div id="mnameError" class="<?php if($mnameError) echo 'form-error' ;?>"><?=  $mnameError ?></div>

           </div>
           <div class="form-group">
            <label for="">Last Name</label>
            <input type="text" name="last_name" value="<?= $last_name ?>" >  
            <div id="lnameError" class="<?php if($lnameError) echo 'form-error' ;?>"><?=  $lnameError ?></div>

           </div>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" value="<?= $email ?>" >
            <div id="emailError" class="<?php if($emailError) echo 'form-error' ;?>"><?=  $emailError ?></div>

        </div>
        <div class="form-group">
            <label for=""> Phone</label>
            <input type="text" name="phone" value="<?= $phone ?>" >
            <div id="phoneError" class="<?php if($phoneError) echo 'form-error' ;?>"><?=  $phoneError ?></div>

        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" >
            <div id="passwordError" class="<?php if($passwordError) echo 'form-error' ;?>"><?=  $passwordError ?></div>
        </div>
        <div class="form-group">
            <label for="">Confirm Password</label>
            <input type="password" name="cpassword">
            <div id="cpasswordError" class="<?php if($cpasswordError) echo 'form-error' ;?>"><?=  $cpasswordError ?></div>
        </div>
        
        <div class="upload-box">
            <label for="">Upload Images</label>
            <input type="file" name="image">
            <div id="picError" class="<?php if($picError) echo 'form-error' ;?>"><?=  $picError ?></div>

        </div>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf)?>">

        <button type="submit" class="btn"> Sign Up</button>
    </form>
     <p class="auth-footer">Already have an account? <a href="login.php">Login</a></p>
    </div>

</section>

   
<?php include "../includes/footer.php"; ?>
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include("../includes/header.php"); 
?>



<div class="payment-container failed">

    <div class="icon">✖</div>

    <h2>Payment Failed</h2>
    <p>Something went wrong with your payment. Please try again.</p>

    <div class="actions">
        <a href="cart.php" class="btn">Return to Cart</a>
        <a href="checkout.php" class="btn-outline">Try Again</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>

<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include("../includes/header.php"); 

?>


<div class="payment-container success">

    <div class="icon">✔</div>

    <h2>Payment Successful!</h2>
    <p>Your order has been completed successfully.</p>

    <div class="actions">
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
        <a href="car_list.php" class="btn-outline">Browse More Cars</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>

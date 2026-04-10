<?php
session_start();
require "../config/db.php";
$mysqli = get_mysqli();

if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.php");
    exit();
}
include("../includes/header.php"); 
include("../includes/nav.php"); 

$user_id = $_SESSION['user_id'];

$sql = $mysqli->prepare("SELECT cart.*, cars.brand, cars.model, cars.year, cars.price, cars.image FROM cart JOIN cars ON cart.car_id = cars.id WHERE cart.user_id = ?");

$sql->bind_param("i", $user_id);

$sql->execute();

$result= $sql->get_result();

$total = 0;
?>

<div class="cart-container">
    <h2>Your Cart</h2>
    <?php if($result->num_rows > 0): ?>
        <div class="cart-items">
            <?php while($row = $result->fetch_assoc()): 
                $total+= $row['price']; 
            ?>
            <div class="cart-item" data-id="<?= $row['car_id'] ?>">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="car image">
                <div class="item-details">
                    <h3><?= htmlspecialchars($row['brand']) ?> <?= htmlspecialchars($row['model']) ?> <?= htmlspecialchars($row['year']) ?></h3>
                    <p>₦<?= number_format($row['price']) ?></p>
                </div>
                <button class="remove-btn">Remove</button>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="cart-summary">
            <h3>Total: ₦<?= number_format($total) ?></h3>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p>Your cart is empty</p>
    <?php endif; ?>    
</div>

<?php include("../includes/footer.php"); ?>


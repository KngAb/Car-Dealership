<?php
session_start();
require "../config/db.php";
$mysqli = get_mysqli();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$errors = $_SESSION['checkout_errors'] ?? [];
$old = $_SESSION['old_checkout'] ?? [];

unset($_SESSION['checkout_errors']);
unset($_SESSION['old_checkout']);

include("../includes/header.php"); 
$user_id = $_SESSION['user_id'];

$sql = $mysqli->prepare("SELECT cart.*, cars.brand, cars.model, cars.year, cars.image, cars.price FROM cart JOIN cars ON cart.car_id = cars.id WHERE cart.user_id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$result = $sql->get_result();

$total = 0;
?>
<h2 class="title">Checkout</h2>
<?php if(isset($errors['cartError'])): ?>
    <div class="auth-error" style="max-width: 1100px; margin: 10px auto; background: #fee2e2; color: #b91c1c; padding: 10px; text-align: center; border-radius: 8px;">
        <?= $errors['cartError'] ?>
    </div>
<?php endif; ?>
<div class="checkout-container">
    <div class="checkout-form">
        <h2>Checkout Details</h2>
        <form action="../controllers/checkoutController.php" method="POST" class="out-form">
            <div class="form-group">
                <label for="">Full Name</label>
            <input type="text" name="name" required>
            <?php if(isset($errors['nameError'])): ?>
                    <span class="form-error" style="display: inline-block;  color: red; font-size: 13px;"><?= $errors['nameError'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="">Phone Number</label>
            <input type="text" name="phone" required>
            <?php if(isset($errors['phoneError'])): ?>
                    <span class="form-error" style="display: inline-block; color: red; font-size: 13px;"><?= $errors['phoneError'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="">Delivery Address</label>
            <textarea name="address" id="" required></textarea>
            <?php if(isset($errors['addressError'])): ?>
                    <span class="form-error" style="display: inline-block;  color: red; font-size: 13px;"><?= $errors['addressError'] ?></span>
                <?php endif; ?>
            </div>
            
             
            <button type="submit" class="pay-btn">Proceed to Payment</button>
            
        </form>
    </div>

<div class="checkout-summary">
    <h3>Order Summary</h3>
    <?php if($result->num_rows>0): ?>
      <?php while($row = $result->fetch_assoc()): $total += $row['price']; ?>
      <div class="summary-item">
        <img src="<?= $row['image'] ?>" alt="">
        <div>
            <p>
                <?= $row['brand'] ?>
                <?= $row['model'] ?>
                <?= $row['year'] ?>
            </p>
            <span>₦ <?= number_format($row['price']) ?></span>
        </div>
      </div> 
      <?php endwhile; ?>
      <div class="total">
        <h3>Total: ₦ <?= number_format($total) ?></h3>
      </div>
    <?php else: ?>
        <p>No items in cart</p>
    <?php endif; ?> 
</div> 
</div>
<?php include("../includes/footer.php"); ?>

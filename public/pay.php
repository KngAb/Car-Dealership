<?php
session_start();
require "../config/db.php";

$mysqli = get_mysqli();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include("../includes/header.php"); 

$uid = $_SESSION['user_id'];
$ref = $_GET['ref'];
$order_id = $_GET['order_id'] ?? null;

if(!$order_id){
    header("Location: cart.php");
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $uid);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    header("Location: cart.php");
    exit();
}

$stmt1 = $mysqli->prepare("SELECT order_items.*, cars.brand, cars.model, cars.year FROM order_items JOIN cars ON order_items.car_id = cars.id WHERE order_items.order_id = ?");
$stmt1->bind_param("i", $order_id);
$stmt1->execute();
$items = $stmt1->get_result();
?>
<h2 class="title">Verify Payment</h2>

<div class="pay-container">
    <h2>Complete Your Payment</h2>
    <div class="order-summary">
        <?php while($item = $items->fetch_assoc()): ?>
            <div class="item">
                <p><?= $item['brand'] ?> <?= $item['model'] ?> <?= $item['year'] ?></p>
                <span>₦<?= number_format($item['price']) ?></span>
            </div>
        <?php endwhile; ?>
        <div class="total">
            <h3>Total:  ₦<?= number_format($order['total_amount']) ?> </h3>
        </div>
    </div>
    <button class="pay-btn" onclick="payWithPaystack()">
        Pay Now
    </button>
</div>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="/Car_Dealership/public/assets/js/pay.js"></script>

<script>
const orderId = <?= $order_id ?>;
const amount = <?= $order['total_amount'] ?>;
const email = "<?= $_SESSION['email'] ?>";
</script>
<?php include("../includes/footer.php"); ?>

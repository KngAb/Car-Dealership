<?php
session_start();
require_once("../config/db.php");

include("../includes/header.php");
include("../includes/nav.php");

$mysqli = get_mysqli();

if(!isset($_GET['id'])){
    echo "Car not found";
    exit();
}

$id = intval($_GET['id']);

$stmt = $mysqli->prepare('SELECT * FROM cars WHERE id=?');
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Car not found";
    exit();
}

$car= $result->fetch_assoc();
?>

<section class="car-details">
    <div class="grid">
        <div class="back-btn">
            <button class="btn" onclick="history.back()">←Back</button>
        </div>
        <div class="car-image">
            <img src="<?= htmlspecialchars($car['image']) ?>" alt="">
        </div>
        <div class="car-info">
            <h1>
                <?= htmlspecialchars($car['brand'])?>
                <?= htmlspecialchars($car['model'])?>
                <?= htmlspecialchars($car['year'])?>
            </h1>
            <h2 class="price">PRICE: ₦<?= number_format($car['price']) ?></h2>
            <ul class="car-specs">
                <li><strong>Brand: </strong> <?= htmlspecialchars($car['brand'])?></li>
                <li><strong>Model: </strong> <?= htmlspecialchars($car['model'])?></li>
                <li><strong>Year: </strong> <?= htmlspecialchars($car['year'])?></li>
                <li><strong>Mileage: </strong> <?= htmlspecialchars($car['mileage']) ??  'N/A'?></li>
                <li><strong>Fuel Type: </strong> <?= htmlspecialchars($car['fuel_type'])?></li>
                <li><strong>Transmission: </strong> <?= htmlspecialchars($car['transmission'])?></li>
            </ul>

            <p class="description">
                <strong>Description:</strong><?= htmlspecialchars($car['description'] ?? 'No Description')?>
            </p>
            <?php
                $check = $mysqli->prepare("SELECT * FROM cart WHERE user_id = ? AND car_id = ?");
                $check->bind_param("ii", $uid, $car['id']);
                $check->execute();
                $res = $check->get_result();
            ?>
            <?php if($res->num_rows > 0): ?>
                <form action="../controllers/cartController.php" method="POST" class="cart-form">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                    <button type="submit" class="cart-btn">Added</button>
                </form>
            <?php else: ?>
                <form action="../controllers/cartController.php" method="POST" class="cart-form">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                <button type="submit" class="cart-btn">Add to Cart</button>
                </form>
            <?php endif ?>    
            <div class="car-actions">
                <a href="book_test_drive.php?car_id=<?= $car['id'] ?>" class="btn">Book Test Drive</a>
            </div>
        </div>
    </div>
</section>
<?php include("../includes/footer.php"); ?>
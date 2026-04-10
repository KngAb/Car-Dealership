<?php
session_start();
require_once '../config/db.php';
$mysqli = get_mysqli();

$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $mysqli->prepare("SELECT wishlist.id AS wishlist_id, cars.id, cars.brand, cars.model, cars.year , cars.price, cars.image FROM wishlist JOIN cars ON wishlist.car_id = cars.id WHERE wishlist.user_id = ?");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>

<div class="page-header">
    <h2>Your Saved Cars</h2>
    <p>Cars you've added to your wishlist.</p>
</div>

<?php if($result->num_rows>0): ?>

<div class="test-drive-grid">

<?php while($car = $result->fetch_assoc()): ?>
 
<div class="drive-card">
    <img src="<?=htmlspecialchars($car['image'])?>" alt="Car Image">
    <div class="drive-info">
        <h3>
            <?= htmlspecialchars($car['brand'])?>
            <?= htmlspecialchars($car['model'])?>
            <?= htmlspecialchars($car['year'])?>
        </h3>
        <p class="drive-date">₦<?= htmlspecialchars($car['price'])?></p>
        <div class="drive-actions">
            <a href="car_details.php?id=<?=$car['id']?>" class="btn-sm">View</a>
            <a  onclick="return confirm('Are you sure you want to unsave this car?')" href="removed_saved.php?id=<?=$car['wishlist_id']?>" class="btn-danger">Remove</a>
        </div>
    </div>
</div>

<?php endwhile; ?>

</div>

<?php else: ?>

<div class="empty-state">
    <h3>No Saved Cars Yet</h3>
    <p>Brouse our inventory and save cars you like.</p>
    <a href="car_list.php" class="btn">Browse Cars</a>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
$title = "Saved Cars";
require 'dashboardLayout.php';
?>
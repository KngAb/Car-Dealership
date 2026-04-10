<?php
session_start();
require_once('../config/db.php');
ob_start();
$user_id = $_SESSION['user_id']  ?? null;

$drives = [];

if($user_id){
    $mysqli = get_mysqli();
    $stmt = $mysqli->prepare("SELECT bookings.id AS booking_id, cars.id, cars.brand, cars.model, cars.year, cars.image, bookings.booking_date, bookings.booking_time FROM bookings JOIN cars ON bookings.car_id = cars.id WHERE bookings.user_id = ? ORDER BY bookings.booking_date ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute(); 
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $drives [] = $row;
    }
}
?>
<div class="page-header">
    <h2>Your Test Drives</h2>
    <p>View and manage your scheduled test drives.</p>
</div>


    <?php if(empty($drives)): ?>
        <div class="empty-state">
            <h3>No Test drives</h3>
            <p>You have not booked any test drives yet</p>
            <a href="../public/book_test_drive.php" class="btn">Book A Drive</a>
        </div>
    <?php else: ?>
     <div class="test-drive-grid">
        <?php foreach($drives as $drive): ?>
            <div class="drive-card">
                <img src="<?= htmlspecialchars($drive['image'])?>" alt="">
                <div class="drive-info">
                    <h3>
                        <?= htmlspecialchars($drive['brand'])?>
                        <?= htmlspecialchars($drive['model'])?>
                        <?= htmlspecialchars($drive['year'])?>
                    </h3>

                    <p class="drive-date">
                        Test Drive Date:
                        <strong><?= htmlspecialchars($drive['booking_date'])?></strong>
                    </p>
                     <p class="drive-date">
                        Test Drive Time:
                        <strong><?= htmlspecialchars($drive['booking_time'])?></strong>
                    </p>
                    <div class="drive-actions">
                        <a href="car_details.php?id=<?=$drive['id'] ?>" class="btn-sm">View Details</a>
                        <a href="cancel_booking.php?id=<?= $drive['booking_id']?>" onclick="return confirm('Are you sure you want to cancel this test')" class="btn-danger">Cancel</a>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
     </div>
<?php
$content = ob_get_clean();
$title = "My Test Drives";
require "dashboardLayout.php";
?>
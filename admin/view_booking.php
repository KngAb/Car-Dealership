<?php
require_once "../config/db.php";
$mysqli = get_mysqli();

if(!isset($_GET['id'])){
    header("Location: bookings.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $mysqli->prepare("SELECT b.*, u.first_name AS user_name, u.email, c.model AS car_model, c.brand AS car_brand, c.year AS car_year FROM bookings b JOIN users u ON b.user_id = u.id JOIN cars c ON b.car_id = c.id WHERE b.id = ? ");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    die("Booking not found");
}

$booking = $result->fetch_assoc();

$page_title = "Booking Details";

ob_start();
?>

<div class="details-container">
    <h2>Booking #<?= $booking['id'] ?></h2>
    <div class="detail-grid">   
        <div class="detail-card">
            <h3>User Info</h3>
            <p><strong>Name:</strong><?= $booking['user_name'] ?></p>
            <p><strong>Email:</strong><?= $booking['email'] ?></p>
        </div>
        <div class="detail-card">
            <h3>Car Info</h3>
            <p><strong>Brand:</strong><?= $booking['car_brand'] ?></p>
            <p><strong>Model:</strong><?= $booking['car_model'] ?></p>
            <p><strong>Year:</strong><?= $booking['car_year'] ?></p>
        </div>
        <div class="detail-card">
            <h3>Booking Info</h3>
            <p><strong>Created At:</strong><?= $booking['created_at'] ?></p>
            <p><strong>Test Drive Date:</strong><?= $booking['booking_date'] ?></p>
            <p><strong>Test Drive Time:</strong><?= $booking['booking_time'] ?></p>
            <p>
                <strong>Status:</strong>
                <span class="status <?= strtolower($booking['status']) ?>">
                    <?= ucfirst($booking['status']) ?>
                </span>
            </p>
        </div>
    </div>
    <div class="form-actions">
        <a href="bookings.php" class="btn cancel">Back</a>

        <?php if($booking['status'] === 'pending'): ?>
            <a href="../controllers/BookingController.php?approve=<?= $booking['id']?>" class="btn success">Approve</a>
        <?php endif; ?>
        <?php if($booking['status'] !== 'cancelled'): ?>
            <a href="../controllers/BookingController.php?cancel=<?= $booking['id'] ?>" class="btn delete" onclick="return confirm('Cancel this booking?')">
               Cancel
            </a>
        <?php endif; ?>
        <?php if($booking['status'] === 'approved'): ?>
            <a href="../controllers/BookingController.php?complete=<?= $booking['id'] ?>" class="btn primary">Mark Completed</a>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include "layout.php";
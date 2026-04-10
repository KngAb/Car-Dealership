<?php
 session_start();
 include_once "../config/db.php";
 $mysqli = get_mysqli();
 ob_start();
?>

<div class="welcome">
    <h2>Welcome back, <?=htmlspecialchars($_SESSION['user_name']);?></h2>
    <p>Here's what's happening with your account</p>
</div>
<div class="profile-card">
    <div class="profile-details">
        <p>Name: <?= htmlspecialchars($_SESSION['full_name']) ?></p>
        <p>Email: <?= $_SESSION['email'] ?? 'No email' ?></p>
        <p>Phone Number: <?= $_SESSION['phone'] ?? 'No phone' ?></p>

        <a href="profile.php" class="btn-sm">Edit Profile</a>
    </div>
</div>
<div class="card-grid">
    <div class="card">
        <h4>Upcoming Test Drives</h4>
        <?php 
         $uid = $_SESSION['user_id'];
         $stmt= $mysqli->prepare("SELECT * FROM bookings WHERE  user_id = ?");
         $stmt->bind_param("i", $uid);
         $stmt->execute();
         $res = $stmt->get_result();
         $row_count = $res->num_rows;
        ?>
        <p><?= ($row_count>0) ? "You have $row_count upcoming test drives " : "No upcoming test drive"?></p>
        <a href="my_test_drives.php">View Booking</a>
    </div>
    <div class="card">
        <h4>Your Saved Cars</h4>
        <?php
         $stmt1 = $mysqli->prepare("SELECT * FROM wishlist WHERE user_id = ?");
         $stmt1->bind_param("i", $uid);
         $stmt1->execute();
         $row1 = $stmt1->get_result();
         $row_count1 = $row1->num_rows;
        ?>
        <p><?= ($row_count1>0) ? "You have saved $row_count1 cars" : "No cars saves"?></p>
        <a href="saved_cars.php">View Saved Cars</a>
    </div>
   
    <div class="card">
        <h4>Browse Cars</h4>
        <p></p>
        <a href="car_list.php">View Other Cars</a>
    </div>

</div>
<?php
$content = ob_get_clean();
$title = "Dashboard";
require 'dashboardLayout.php';
?> 
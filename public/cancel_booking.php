<?php
session_start();
require_once "../config/db.php";

if(isset($_SESSION['user_id']) && isset($_GET['id'])){
    $mysqli = get_mysqli();
    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("DELETE FROM bookings WHERE user_id = ? AND id = ?");
    $stmt->bind_param("ii", $user_id, $booking_id);
    if($stmt->execute()){
        header("Location:my_test_drives.php?msg=cancelled");
    }else{
        header("Location:my_test_drives.php?msg=error");
    }
}else{
    header("Location:my_test_drives.php");
}

exit;

?>
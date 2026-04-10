<?php
session_start();
require_once("../config/db.php");
$mysqli = get_mysqli();

if(isset($_GET['complete'])){
    $id = (int) $_GET['complete'];
    $complete = "completed";
    $stmt = $mysqli->prepare("UPDATE bookings SET status= ? WHERE id= ?");
    $stmt->bind_param("si", $complete, $id);
    if($stmt->execute()){
      header("Location: ../admin/view_booking.php?id=$id");
    }
}
if(isset($_GET['approve'])){
    $id = (int) $_GET['approve'];
    $approved = "approved";
    $stmt = $mysqli->prepare("UPDATE bookings SET status= ? WHERE id= ?");
    $stmt->bind_param("si", $approved, $id);
    if($stmt->execute()){
       header("Location: ../admin/view_booking.php?id=$id");
    }
}
if(isset($_GET['cancel'])){
    $id = (int) $_GET['cancel'];
    $cancelled = "cancelled"; // Must match your CSS .status.cancelled
    
    $stmt = $mysqli->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $cancelled, $id);
    
    if($stmt->execute()){
    $connector = (strpos($_SERVER['HTTP_REFERER'], '?') !== false) ? '&' : '?';
    header("Location: " . $_SERVER['HTTP_REFERER'] . $connector . "success=1");
    exit;
    }else {
    $connector = (strpos($_SERVER['HTTP_REFERER'], '?') !== false) ? '&' : '?';
    header("Location: " . $_SERVER['HTTP_REFERER'] . $connector . "error=1");
    exit;
    }
}
 
if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect'] = '/Car_Dealership/public/book_test_drive.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_SESSION['post_data'] = $_POST;
    }
    header("Location: ../public/login.php");
    exit;
}

$saved_data = isset($_SESSION['post_data']) ? $_SESSION['post_data'] : [] ;

unset($_SESSION['post_data']);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $car_id = intval($_POST['car_id']);
    $date = $_POST['test-date'];
    $time = $_POST['test-time'];
    $message = htmlspecialchars($_POST['message']);
   
    
    $errors = [];

    if(empty($name) || strlen($name) < 3){
        $errors['nameError'] = "Please enter your full name (min 3 chars)";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['emailError'] = "Please enter a valid email address";
    }
    if(empty($phone) || !preg_match("/^[0-9]{10,15}$/", $phone)){
        $errors['phoneError'] = "Please enter a valid phone number";
    }
    if($car_id <= 0) {
        $errors['carError'] = "Please select a valid car.";
    }
    if(empty($date)) {
        $errors['dateError'] = "Please select a date.";
    } elseif(strtotime($date) < strtotime(date('Y-m-d'))) {
        $errors['dateError'] = "You cannot book a test drive in the past.";
    }

    if(empty($time)) {
        $errors['timeError'] = "Please select a preferred time.";
    }
     
    if(!empty($errors)) {
        $_SESSION['booking_errors'] = $errors;
        $_SESSION['post_data'] = $_POST; // Save old data
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $uid = $_SESSION['user_id'];
    $booking_type = "Test Drive";
    $status = "pending";

    $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, car_id, booking_date, booking_type, status, full_name, email, phone, booking_time, message) values(?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('iissssssss', $uid, $car_id, $date, $booking_type, $status, $name, $email, $phone, $time, $message);

    if($stmt->execute()){
        $connector = (strpos($_SERVER['HTTP_REFERER'], '?') !== false) ? '&' : '?';
        header("Location:".$_SERVER['HTTP_REFERER']. $connector."&success=1");
    }else{
        header("Location:".$_SERVER['HTTP_REFERER']."&error=1");

    }
    $stmt->close();
}
<?php
session_start();
require '../config/db.php';

if(!isset($_SESSION['user_id'])){
    // header("Location: ../public/login.php");
    echo json_encode(["status"=>"error"]);
    header("Location: ../public/login.php");
    exit();
}

$mysqli = get_mysqli();

$user_id = $_SESSION['user_id'];
$car_id = $_POST['car_id'] ?? null;

if(!$car_id){
    // header("Location: index.php");
    echo json_encode(["status"=>"error"]);
    header("Location: index.php");

    exit();
}


/*Check if already saved*/
$stmt = $mysqli->prepare("SELECT id FROM wishlist WHERE user_id = ? AND car_id=?");
$stmt->bind_param("ii", $user_id, $car_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    /*remove from wishlist*/
    $stmt = $mysqli->prepare("DELETE FROM wishlist WHERE user_id=? AND car_id=?");
    $stmt->bind_param("ii", $user_id, $car_id);
    $stmt->execute();
    echo json_encode(["status"=>"removed"]);
}else{
    /*add to wishlist*/
    $stmt = $mysqli->prepare("INSERT INTO wishlist (user_id, car_id ) VALUES(?,?)");
    $stmt->bind_param("ii", $user_id, $car_id);
    $stmt->execute();
    echo json_encode(["status"=>"added"]);
}


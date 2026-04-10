<?php
session_start();
if(!isset($_SESSION['user_id'])){
    // header("Location: ../public/login.php");
    echo json_encode(["status"=>"error"]);
    exit();
}

require "../config/db.php";

$mysqli = get_mysqli();

$user_id = $_SESSION['user_id'];
$price = $_POST['price'] ?? null;
$car_id = $_POST['car_id'] ?? null;

if(!$car_id){
    echo json_encode(["status"=>"error"]);
    exit();
}
$check = $mysqli->prepare("SELECT * FROM cart WHERE user_id = ? AND car_id = ?");
$check->bind_param("ii", $user_id, $car_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
    $stmt = $mysqli->prepare("DELETE FROM cart WHERE user_id=? AND car_id = ? ");
    $stmt->bind_param("ii", $user_id, $car_id);
    $stmt->execute();
    echo json_encode(["status"=>"removed"]);
}else{
    $stmt = $mysqli->prepare("INSERT INTO cart (user_id, car_id, price) VALUES(?,?,?)");
    $stmt->bind_param("iii", $user_id, $car_id, $price);
    $stmt->execute();
    echo json_encode(["status"=>"added"]);
}
?>
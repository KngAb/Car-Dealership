<?php
session_start();
require "../config/db.php";

$mysqli = get_mysqli();
$user_id = $_SESSION['user_id'];

$name = trim(htmlspecialchars($_POST['name'] ?? ''));
$phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
$address = trim(htmlspecialchars($_POST['address'] ?? ''));

$errors = [];

if (empty($name) || strlen($name) < 3) {
    $errors['nameError'] = "Please enter your full name.";
}
if (empty($phone) || !preg_match("/^[0-9]{10,15}$/", $phone)) {
    $errors['phoneError'] = "Please enter a valid phone number.";
}
if (empty($address) || strlen($address) < 10) {
    $errors['addressError'] = "Please provide a detailed delivery address.";
}

$cart = $mysqli->prepare("SELECT cart.*, cars.price FROM cart JOIN cars ON cart.car_id = cars.id WHERE cart.user_id = ?");
$cart->bind_param("i", $user_id);
$cart->execute();
$res = $cart->get_result();

if ($res->num_rows === 0) {
    $errors['cartError'] = "Your cart is empty.";
}

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['old_checkout'] = $_POST;
    header("Location: ../public/checkout.php");
    exit();
}

$pending = "Pending";
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$ref = uniqid('TXN-', true);
$total = 0;
$items = [];

while($row = $res->fetch_assoc()){
    $total += $row['price'];
    $items[] = $row;
}

$order = $mysqli->prepare("INSERT INTO orders (user_id, total_amount, name, phone, address, status, payment_ref) VALUES(?,?,?, ?, ?, ?,?)");
$order->bind_param("idsssss", $user_id, $total, $name, $phone, $address, $pending, $ref);
$order->execute();
$order_id = $mysqli->insert_id;

foreach($items as $item){
    $car_id = $item['car_id'];
    $price = $item['price'];

    $order_item = $mysqli->prepare("INSERT INTO order_items(order_id, car_id, price) VALUES(?,?,?)");
    $order_item->bind_param("iid", $order_id, $car_id, $price);
    $order_item->execute();
}

header("Location: ../public/pay.php?order_id=".$order_id."&user_id=".$user_id."&ref=".$ref);
?>
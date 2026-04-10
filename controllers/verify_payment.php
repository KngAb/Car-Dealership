<?php
require "../config/db.php";

$mysqli = get_mysqli();

$reference = $_GET['reference'];
$order_id = $_GET['order_id'];
$user_id = $_GET['user_id'];

// VERIFY WITH PAYSTACK API
$url = "https://api.paystack.co/transaction/verify/".$reference;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer sk_test_3404e476404364ade183c0b8992c95036df1df09"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if($result['status'] && $result['data']['status'] == 'success'){

    // mark order as paid
    $stmt = $mysqli->prepare("UPDATE orders SET status='paid' WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // OPTIONAL: mark cars as sold
    $mysqli->query(" UPDATE cars SET status='sold' WHERE id IN (SELECT car_id FROM order_items WHERE order_id='$order_id')");
    $mysqli->query("DELETE FROM cart WHERE user_id = '$user_id'");
    header("Location: ../public/success.php");
}else{
    header("Location: ../public/failed.php");
}
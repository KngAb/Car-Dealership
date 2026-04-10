<?php
session_start();
require_once "../config/db.php";

if(isset($_SESSION['user_id']) && isset($_GET['id'])){
    $mysqli = get_mysqli();
    $uid = $_SESSION['user_id'];
    $wishlist_id = $_GET['id'];

    $stmt = $mysqli->prepare("DELETE FROM wishlist WHERE user_id = ? AND id = ?");
    $stmt->bind_param("ii", $uid, $wishlist_id);
    if($stmt->execute()){
        header("Location:saved_cars.php?msg=removed");
    }else{
        header("Location:saved_cars.php?msg=error");
    }
}else{
    header("Location:saved_cars.php");
}
?>
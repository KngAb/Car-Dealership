<?php
session_start();
require_once "../config/db.php";
$mysqli = get_mysqli();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $user_id = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if($new !== $confirm){
        header("Location: ../public/profile.php?error=match");
        exit();
    }
    
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if(password_verify($current, $user['password'])){
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $new_hashed, $user_id);
        $update->execute();
        header("Location: ../public/profile.php?success=password_changed");
    }else{
        header("Location: ../public/profile.php?error=wrong_current");
    }
    exit;
}
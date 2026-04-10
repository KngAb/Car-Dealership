<?php
session_start();
require_once "../config/db.php";

$mysqli = get_mysqli();

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $user_id = $_SESSION['user_id'];

    $f_name  = trim(htmlspecialchars($_POST['first_name']));
    $m_name  = trim(htmlspecialchars($_POST['middle_name']));
    $l_name  = trim(htmlspecialchars($_POST['last_name']));
    $email   = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $phone   = trim(htmlspecialchars($_POST['phone']));

    $errors = [];

    if (empty($f_name) || !preg_match("/^[a-zA-Z\s]+$/", $f_name)) {
        $errors['f_name'] = "First name must contain only letters.";
    }
    
    if (empty($l_name) || !preg_match("/^[a-zA-Z\s]+$/", $l_name)) {
        $errors['l_name'] = "Last name must contain only letters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
    }

    if (empty($phone) || !preg_match("/^[0-9]{10,15}$/", $phone)) {
        $errors['phone'] = "Please provide a valid phone number (10-15 digits).";
    }

    $check = $mysqli->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
    $check->bind_param("ssi", $email, $phone, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors['exists'] = "This email or phone number is already registered to another account.";
    }

    if (!empty($errors)) {
        $_SESSION['profile_errors'] = $errors;
        header("Location: ../public/profile.php");
        exit;
    }

    $stmt = $mysqli->prepare("UPDATE users SET first_name=?, middle_name=?, last_name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssssi", $f_name, $m_name, $l_name, $email, $phone, $user_id );

    if($stmt->execute()){
        $_SESSION['user_name'] = $f_name;
        header("Location: ../public/profile.php?success=profile_updated");
    }else{
        header("Location: ../public/profile.php?error=update_failed");
    }
    exit;
}
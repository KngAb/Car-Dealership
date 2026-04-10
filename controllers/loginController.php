<?php 
session_start();

require_once('../config/db.php');
require_once('csrf.php');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: '../public/login.php'");
}

verifyToken($_POST['csrf_token'] ?? '');

$username= trim($_POST['email']);
$password = $_POST['password'];

$mysqli = get_mysqli();

$stmt = $mysqli -> prepare("SELECT * FROM users WHERE email = ? LIMIT 1");

$stmt-> bind_param('s', $username);

$stmt-> execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if(!$user){
    header("Location: ../public/login.php?error");
    exit();
}
if(!password_verify($password, $user['password'])){
    header("Location: ../public/login.php?error");
    exit();
}
session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['first_name'];
$_SESSION['full_name'] = $user['first_name']." ". $user['middle_name']. " ". $user['last_name'];
$_SESSION['role'] = $user['role'];
$_SESSION['profile_pic'] = $user['profile_pic'];
$_SESSION['email'] = $user['email'];
$_SESSION['phone'] = $user['phone'];


if($_SESSION['role']=='super_admin' || $_SESSION['role']=='admin'){
  header("Location:../admin/dashboard.php");  
}else{
  if(isset($_SESSION['redirect'])){
    $url = $_SESSION['redirect'];
    unset($_SESSION['redirect']);
    header("Location:". $url);
  }else{
  header("Location: ../public/index.php");
  }
}
exit();

$mysqli->close();

?>
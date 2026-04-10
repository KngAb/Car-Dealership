<?php
if (session_status() === PHP_SESSION_NONE){
   session_start();
} 
   function requirelogin(){
    if(!$_SESSION['user_id']){
        header("Location:../public/login.php");
        exit();
    }
   }
   
   function requireAdmin(){
    requirelogin();
    if($_SESSION['role']!=='admin' && $_SESSION['role']!=='super_admin'){
        header("Location:../public/dashboard.php");
        exit();
    }
   }

if(isset($_POST['update_user'])){
    require_once("../config/db.php");
    $conn = get_mysqli();

    $id = $_POST['user_id'];
    $first = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET first_name=?, middle_name=?, last_name=?, email=?, phone=?, role=? WHERE id=?");
    $stmt->bind_param("ssssssi", $first, $middle, $last, $email, $phone, $role, $id);
   

    if($stmt->execute()){  
      header("Location: ../admin/edit_user.php?id=$id&success&no=$phone");
      exit();
    }else{
       header("Location: ../admin/edit_user.php?id=$id&error");
      exit();  
    }
}
?>
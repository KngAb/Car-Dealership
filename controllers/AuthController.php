<?php
if (session_status() === PHP_SESSION_NONE){
   session_start();
} 
   
if(!$_SESSION['user_id']){
    header("Location:../public/login.php");
}

if($_SESSION['role']!=='user'){
    header("Location:../public/login.php");
    exit();
}
?>
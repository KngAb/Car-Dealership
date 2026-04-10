<?php
if(session_status()=== PHP_SESSION_NONE){
session_start();
}

//generate a token

function generateToken(){
    if(empty($_SESSION['csrf_token'])){
        //random_bytes(32) generates 32 cryptographically secure random bytes
        //bin2hex() converts bytes to a readable string
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyToken($token){
    if(!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'],$token)){
        die("Invalid Request Token");
    }
}
?>
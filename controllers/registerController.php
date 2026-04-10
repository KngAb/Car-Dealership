<?php
  session_start();

require_once '../config/db.php';
require  "../config/cloudinary.php";
require_once 'csrf.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../public/register.php");
    exit;
}

//CSRF protection
verifyToken($_POST['csrf_token'] ?? '');

function sanitize($input){
    $input = trim($input);
    $input = stripcslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

$first_name = sanitize($_POST['first_name']);
$middle_name = sanitize($_POST['middle_name']);
$last_name = sanitize($_POST['last_name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);
$password = sanitize($_POST['password']);
$cpassword = sanitize($_POST['cpassword']);
$img = $_FILES['image']['tmp_name'];


//Validation
if(empty($first_name)){
    $fnameError = 'This field can not be left empty';
}else{
    if(!preg_match("/^[A-z\s]+$/",$first_name)){
        $fnameError = "Please enter only alphabets";
    }
    if(strlen($first_name)<3){
        $fnameError = "Name too short";
    }
}
if(empty($middle_name)){
    $mnameError = 'This field can not be left empty';
}else{
    if(!preg_match("/^[A-z\s]+$/",$middle_name)){
        $mnameError = "Please enter only alphabets";
    }
    if(strlen($middle_name)<3){
        $mnameError = "Name too short";
    }
}
if(empty($last_name)){
    $lnameError = 'This field can not be left empty';
}else{
    if(!preg_match("/^[A-z\s]+$/",$last_name)){
        $lnameError = "Please enter only alphabets";
    }
    if(strlen($last_name)<3){
        $lnameError = "Name too short";
    }
}

if(empty($email)){
    $emailError="This field cannot be left empty";
}else{
    if(strlen($email)<6){
        $emailError = "Your email is not complete";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailError = "Your email is not valid";
    }
}
if(empty($phone)){
    $phoneError = 'This field can not be left empty';
}else{
    if(!preg_match("/^[0-9]+$/",$phone)){
        $phoneError = "Please enter a valid number";
    }
    if(strlen($phone)<11){
        $phoneError = "Phone number too short";
    }
}
if(empty($password)){
    $passwordError = "This field cannot be left empty";
}else{
    if(strlen($password)<8){
        $passwordError = "Password too short";
    }else{
        if(!preg_match('/[A-Z]/',$password)){
            $passwordError = "No uppercase letter present";
        }
        if(!preg_match('/[a-z]/',$password)){
            $passwordError = "No lowercase letter present";
        }
        if(!preg_match('/[0-9]/',$password)){
            $passwordError = "No digit present";
        }
        if(!preg_match('/\W/',$password)){
            $passwordError = "No symbol present";
        }
    }
}
if(empty($cpassword)){
    $cpasswordError = "Please re-enter password";
}else{
    if($cpassword !== $password){
        $cpasswordError = "Please re_enter the correct password";
    }
}
if(empty($img)){
    $picError = "No picture was selected";
}

//Hash Password
$password_hash = password_hash($password, PASSWORD_DEFAULT);
//initializing database connection object
$mysqli = get_mysqli();
$mysqli->begin_transaction();//either everything succeeds or nothing is saved

    //check duplicate user
    $check = $mysqli->prepare("SELECT id FROM users WHERE email = ? OR phone = ? ");

    $check->bind_param('ss', $email, $phone);
    $check->execute();
    $check->store_result();

    if($check->num_rows>0){
        $emailError ="User already exists" ;
    }
$errors_found = false;
foreach(['fnameError', 'mnameError', 'lnameError', 'emailError', 'phoneError', 'passwordError', 'cpasswordError','picError'] as $err){
    if(!empty($$err)) $errors_found = true;
}

if($errors_found){
    $_SESSION['errors'] = compact('fnameError', 'mnameError', 'lnameError', 'emailError', 'phoneError', 'passwordError', 'cpasswordError','picError');
    $_SESSION['old'] = $_POST;
    header("Location: ../public/register.php");
    exit;
}
if(empty($fnameError) && empty($mnameError) && empty($lnameError) && empty($emailError) && empty($phoneError) && empty($passwordError) && empty($cPasswordError) && empty($picError)){
$upload = $Cloudinary->uploadApi()->upload($img);
$profile = $upload['secure_url'];
try{
    
    

    //Insert User
    $stmt = $mysqli->prepare("INSERT INTO users(first_name,middle_name,last_name, email, phone, password, role, profile_pic) VALUES(?,?,?,?,?,?,'user', ?)");

    $stmt->bind_param("sssssss", $first_name, $middle_name, $last_name, $email, $phone, $password_hash, $profile);

    $stmt->execute();
    $user_id = $stmt->insert_id;

    $mysqli->commit();//permanently saves all database changes

    //Auto login user
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $first_name;
    $_SESSION['full_name'] = $first_name." ". $middle_name. " ". $last_name;
    $_SESSION['profile_pic'] = $profile;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;

    header("Location: ../public/dashboard.php");
}catch(Exception $e){
    $mysqli->rollback();//undoes all database changes made during transaction
    error_log($e->getMessage());
    echo $e;
    die("Registration failed");
}finally{
    $mysqli->close();
}
}
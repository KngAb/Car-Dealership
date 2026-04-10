<?php
require_once("../config/db.php");
require  "../config/cloudinary.php";

$mysqli = get_mysqli();

$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];
$types = "";
if(isset($_POST['update_car'])){
    $id = $_POST['car_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $mileage = $_POST['mileage'];
    $fuel_type = $_POST['fuel_type'];
    $year = $_POST['year'];
    $transmission = $_POST['transmission'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $img = $_FILES['image']['tmp_name'];
    if(!empty($img)){
      $upload = $Cloudinary->uploadApi()->upload($img);
      $img1 = $upload['secure_url'];

      $stmt = $mysqli->prepare("UPDATE cars SET brand = ?, model = ?, mileage = ?, fuel_type =?, year=?, transmission=?, price=?, status=?, description=?, image=? WHERE id = ?");
      $stmt->bind_param("ssssisdsssi", $brand, $model, $mileage, $fuel_type, $year, $transmission, $price, $status, $description, $img1, $id);
      if($stmt->execute()){
         header("Location: ../admin/edit_car.php?id=$id&success");
         exit();
      }else{
         header("Location: ../admin/edit_car.php?id=$id&error");
         exit;
      }
    }else{
      $stmt = $mysqli->prepare("UPDATE cars SET brand = ?, model = ?, mileage = ?, fuel_type =?, year=?, transmission=?, price=?, status=?, description=? WHERE id = ?");
      $stmt->bind_param("ssssisdssi", $brand, $model, $mileage, $fuel_type, $year, $transmission, $price, $status, $description, $id);
        if($stmt->execute()){
            header("Location: ../admin/edit_car.php?id=$id&success");
            exit();
        }else{
            header("Location: ../admin/edit_car.php?id=$id&error");
            exit;
        }
    }
}
if(isset($_POST['add_car'])){
    
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $mileage = $_POST['mileage'];
    $fuel_type = $_POST['fuel_type'];
    $year = $_POST['year'];
    $transmission = $_POST['transmission'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $upload = $Cloudinary->uploadApi()->upload($_FILES['image']['tmp_name']);
    $img = $upload['secure_url'];

    $stmt = $mysqli->prepare("INSERT INTO cars( brand, model, image, year, price, mileage, fuel_type,transmission, description, status) VALUES(?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssidsssss", $brand, $model, $img, $year, $price, $mileage, $fuel_type, $transmission, $description, $status);
    if($stmt->execute()){
        header("Location: ../admin/add_car.php?success=car_added");
    }else{
        header("Location: ../admin/add_car.php?error=failed");
    }
    exit();
}

if(!empty($_GET['brand'])){
    $sql .=" AND brand = ? ";
    $params[] = $_GET['brand'];
    $types .="s";
}

if(!empty($_GET['model'])){
    $sql .=" AND model = ? ";
    $params[] = $_GET['model'];
    $types .="s";
}

if(!empty($_GET['max_price'])){
    $range = $_GET['max_price'];
    //check for +
    if(strpos($range, '+') !==false){
        //remove + and convert to integer
        $min_price = (int)str_replace('+','', $range);
        $sql .= " AND price >= ?";
        $params[] = $min_price;
        $types .= "i" ;
    }elseif(strpos($range, '-') !==false){
        $parts = explode('-', $range);
        $min_price = (int)$parts[0];
        $max_price = (int)$parts[1];
        $sql .=" AND price BETWEEN ? AND ?";
        $params[] = $min_price;
        $params[] = $max_price;
        $types .= "ii";
    }
  
}

$sql .= " ORDER BY created_at DESC";

$stmt = $mysqli->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
if($stmt->execute()){
$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);

include "../public/car_list.php";
exit();
}
?>
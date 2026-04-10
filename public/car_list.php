<?php
session_start();
require_once("../config/db.php");
include("../includes/header.php");
include("../includes/nav.php");

//initialize database connection database
$mysqli = get_mysqli();
//Fetch cars
if(!isset($cars)){
    $stmt = $mysqli->query("SELECT * FROM cars ORDER BY created_at DESC");
    $cars = $stmt->fetch_all(MYSQLI_ASSOC);
}
$savedCars = [];
if(isset($_SESSION['user_id'])){
    $uid = $_SESSION['user_id'];
    $stmt = $mysqli->prepare("SELECT car_id FROM wishlist WHERE user_id=?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row= $result->fetch_assoc()){
        $savedCars[] = $row['car_id'];
    }
    }
?>


<section class="inventory-hero">
    <div class="inventory-hero-content">
        <h1>Browse Our Cars</h1>
        <p>Find the perfect car that fits your lifestyle</p>
    </div>
</section>

<section class="search-box">
        <form action="../controllers/Carcontroller.php" method="GET" class="grid">
        
        <select name="brand" id="make">
            <option value="">All Brands</option>
            <!--Get unique brands-->
            <?php 
            $brand_stmt = $mysqli->query("SELECT DISTINCT brand FROM cars ORDER BY brand ASC");
            while($row = $brand_stmt->fetch_assoc()):
              $selected = (isset($_GET['brand']) && $_GET['brand'] == $row['brand']) ? 'selected' : '';            
            ?>
            <option value="<?= htmlspecialchars($row['brand']) ?>" <?= $selected ?>>
                <?= htmlspecialchars($row['brand'])?>
            </option>
            <?php endwhile; ?>
        </select>     
        <select name="model" id="model">
            <option value="">Model</option>
            <?php 
            $brand_stmt = $mysqli->query("SELECT DISTINCT model FROM cars ORDER BY model ASC");
            while($row = $brand_stmt->fetch_assoc()):
                $selected = (isset($_GET['model']) && $_GET['model'] == $row['model']) ? 'selected' : '';   
            ?>
            <option value="<?= htmlspecialchars($row['model'])?>" <?=$selected?>><?= htmlspecialchars($row['model'])?></option>
             <?php endwhile; ?>
        </select>     
        <select name="max_price" id="price">
            <option value="">Price Range</option>
             <?php $p_val = $_GET['max_price'] ?? "";?>
            <option value="0-1000000" <?= $p_val == '0-1000000' ? 'selected': ''?>>Under 1M</option>
            <option value="1000001-10000000" <?= $p_val == '1000001-10000000' ? 'selected': ''?>>Under 10M</option>
            <option value="10000001-25000000" <?= $p_val == '10000001-25000000' ? 'selected': ''?>>Under 25M</option>
            <option value="25000001-50000000" <?= $p_val == '25000001-50000000' ? 'selected': ''?>>Under 50M</option>
            <option value="50000000+" <?= $p_val == '50000000+' ? 'selected': ''?>>Over 50M</option>
            
        </select>
        <button class="btn">Search</button>
     </form>
</section>
<section class="car-list">
    <h2>Available Cars</h2>
    <div class="car-grid grid">
        <?php foreach($cars ?? [] as $car):?>
            <div class="car-card">
                <?php
                $check = $mysqli->prepare("SELECT * FROM cart WHERE user_id = ? AND car_id = ?");
                $check->bind_param("ii", $uid, $car['id']);
                $check->execute();
                $res = $check->get_result();
            ?>
                <form action="../controllers/cartController.php" method="POST" class="cart-form">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                    <input type="hidden" name="price" value="<?= $car['price'] ?>">
                    <button type="submit" class="cart-btn <?= ($res->num_rows > 0) ? 'added' : '' ?>">🛒</button>
                </form>
            
                <!-- Save car -->
            <form action="../controllers/saveController.php" class="wishlist-form" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id']?>">
                <button type="submit" class="wishlist-btn <?= in_array($car['id'], $savedCars) ? 'saved' : ''?>">❤</button>
            </form>
                  <img src="<?= htmlspecialchars($car['image'])?>" alt="car"> 
                  <h3 >
                    <?= htmlspecialchars($car['brand'])?>
                    <?= htmlspecialchars($car['model'])?>
                    <?= htmlspecialchars($car['year'])?>
                  </h3>
                  <p class="price">₦<?= number_format($car['price'])?></p>
                  <a href="car_details.php?id=<?= $car['id'] ?>" class="btn-sm"> View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include("../includes/footer.php");?>

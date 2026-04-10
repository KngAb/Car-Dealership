<?php
session_start();
require_once("../config/db.php");
include("../includes/header.php"); 
include("../includes/nav.php"); 

//Initializing database connection object
$mysqli = get_mysqli();
//Fetching cars from Database
$stmt = $mysqli->query("SELECT * FROM cars ORDER BY created_at DESC LIMIT 6");
$cars = $stmt->fetch_all(MYSQLI_ASSOC);

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
<section class="hero">
    <div class="hero-content grid">
       <?php if(isset($_SESSION['user_id'])): ?>
           <h1>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?> </h1>
           <p>Ready to find your next ride?</p><br>
           <a href="car_list.php"><button class="btn">Browse cars</button></a>
        <?php else: ?>
             <h1>Find Your Perfect Car</h1>
             <p>Trusted cars. Best Prices. Zero Stress.</p><br>
             <a href="car_list.php"><button class="btn">Browse cars</button></a>
        <?php endif; ?>     
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
            ?>
            <option value="<?= htmlspecialchars($row['brand'])?>"><?= htmlspecialchars($row['brand'])?></option>
            <?php endwhile; ?>
        </select>     
        <select name="model" id="model">
            <option value="">Model</option>
            <?php 
            $brand_stmt = $mysqli->query("SELECT DISTINCT model FROM cars ORDER BY model ASC");
            while($row = $brand_stmt->fetch_assoc()):
            ?>
            <option value="<?= htmlspecialchars($row['model'])?>"><?= htmlspecialchars($row['model'])?></option>
             <?php endwhile; ?>
        </select>     
        <select name="max_price" id="price">
            <option value="">Price Range</option>
             
            <option value="0-1000000">Under 1M</option>
            <option value="1000001-10000000">Under 10M</option>
            <option value="10000001-25000000">Under 25M</option>
            <option value="25000001-50000000">Under 50M</option>
            <option value="50000000+">Over 50M</option>
            
        </select>
        <button class="btn">Search</button>
     </form>
</section>
<section class="featured">
    <h2>Featured Vehicles</h2>
    <div class="car-grid grid">
        <?php foreach($cars as $car):?>
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
            <form action="../controllers/saveController.php" class="wishlist-form" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id']?>">
                <button type="submit" class="wishlist-btn <?= in_array($car['id'], $savedCars) ? 'saved' : ''?>">❤</button>
            </form>
            <img src="<?= htmlspecialchars($car['image'])?>" alt="">
            <h3>
               <?= htmlspecialchars($car['brand'])?> 
               <?= htmlspecialchars($car['model'])?> 
               <?= htmlspecialchars($car['year'])?>
            </h3>
            <p>₦<?= number_format(htmlspecialchars($car['price']))?></p>
            <a href="car_details.php?id=<?= $car['id']?>" class="btn-sm">View Details</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php if(isset($_SESSION['user_id'])): ?>
    <section class="why-us">
        
    </section>
<?php else: ?>
<section class="why-us">
    <h2>Why Choose AB Autos</h2>
    <div class="features grid">
        <div>&#10003;Verified Cars</div>
        <div>&#10003;Trusted Dealer</div>
        <div>&#10003;Flexible Payment</div>
        <div>&#10003;Warranty Support</div>
    </div>
</section>
<?php endif; ?>
<section class="how-it-works">
    <h2>How It Works</h2>
    <ol class="grid">
        <li><img src="\Car_Dealership\public\assets\images\site\browsecars.png" alt="browse car icon" class="icon" width="100%">Browse Cars</li>
        <li><img src="\Car_Dealership\public\assets\images\site\bookinspection.png" alt="browse car icon" class="icon" width="100%">Book inspection</li>
        <li><img src="\Car_Dealership\public\assets\images\site\drivehappy.png" alt="browse car icon" class="icon" width="100%">Drive away Happy</li>
    </ol>
</section>
<section class="cta ">
    <h2>Ready to Get Started</h2>
    <a href="car_list.php" class="btn">Browse Cars</a>
</section>
<?php include("../includes/footer.php"); ?>
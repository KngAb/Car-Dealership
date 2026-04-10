<?php $currentPage = basename($_SERVER['PHP_SELF']);
?>
<?php
$cartCount = 0;

if(isset($_SESSION['user_id'])){
    require_once("../config/db.php");
    $mysqli = get_mysqli();

    $uid = $_SESSION['user_id'];
    $result = $mysqli->query("SELECT COUNT(*) as count FROM cart WHERE user_id='$uid'");
    $row = $result->fetch_assoc();
    $cartCount = $row['count'];
}
?>
<header class="site-nav">
   <nav class="navbar grid">
   <div class="logo">AB Autos</div>
     <ul class="nav-menu">
       <a href="/Car_Dealership/public/index.php"><li class="nav-item <?php if ($currentPage=='index.php') echo'active';?>">Home</li></a>
       <a href="/Car_Dealership/public/car_list.php"><li class="nav-item <?php if ($currentPage=='car_list.php' || $currentPage =='Carcontroller.php') echo'active';?>">Cars</li></a>
       <a href="/Car_Dealership/public/book_test_drive.php"><li class="nav-item <?php if ($currentPage=='book_test_drive.php') echo'active';?>">Book Test Drive</li></a>
       <li class="nav-item b <?php if (isset($_SESSION['user_id'])) echo'deactivate';?>"><a href="/Car_Dealership/public/login.php" class="btn">Login</a></li>
       <a href="/Car_Dealership/public/cart.php"><li class="nav-item <?php if ($currentPage=='cart.php')echo'active'; ?> <?php if (!isset($_SESSION['user_id'])) echo'deactivate'; ?>">🛒 Cart  (<?= $cartCount ?>)</li></a>
       <a href="/Car_Dealership/public/dashboard.php"><li class="nav-item <?php if (!isset($_SESSION['user_id'])) echo'deactivate';?>">Dashboard</li></a>
       <a href="/Car_Dealership/public/logout.php"><li class="nav-item <?php if (!isset($_SESSION['user_id'])) echo'deactivate';?>">Logout</li></a>
     </ul>
    <a href="login.php" id="mob-login"><button class="btn <?php if (isset($_SESSION['user_id'])) echo'deactivate';?>">LOGIN</button></a>
     <div class="hamburger">
         <span class="bar"></span>
         <span class="bar"></span>
         <span class="bar"></span>
     </div>
   </nav>
</header>
<?php 
require_once "../controllers/AdminController.php";
include "../includes/admin_header.php";
requirelogin();
requireAdmin();
$currentPage = basename($_SERVER["PHP_SELF"]); 
?>

<div class="admin-container">
    <aside class="sidebar">
        <h2>Admin</h2>
        <ul> 
            <li><a href="dashboard.php" class='<?php if($currentPage == "dashboard.php") echo "selected" ; ?>'>Dashboard</a></li>
            <li><a href="add_car.php" class='<?php if($currentPage == "add_car.php") echo "selected" ; ?>'>Add Car</a></li>
            <li><a href="manage_car.php" class='<?php if($currentPage == "manage_car.php") echo "selected" ; ?>'>Manage Cars</a></li>
            <li><a href="bookings.php" class='<?php if($currentPage == "bookings.php") echo "selected" ; ?>'>Bookings</a></li>
            <li><a href="users.php" class='<?php if($currentPage == "users.php") echo "selected" ; ?>'>Users</a></li>
            <li><a href="../public/logout.php">Logout</a></li>
        </ul>
    </aside>
    <div class="overlay"></div>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <div class="hamburger ">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
            <div class="topbar">
                <h1><?php echo $page_title ?? "Admin Panel" ;?></h1>
            </div>
            <div class="topbar-right">
                <div class="user-info">
                <img src="<?= $_SESSION['profile_pic'] ?? '../assets/images/default.png' ?>" class="avatar">
                <span>Hi, <?= htmlspecialchars($_SESSION['user_name'])?></span>
                </div>
            </div>
        </header>
        
        <div class="content">
            <?php echo $content; ?>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
<?php 
  require_once "../controllers/AuthController.php";

  include("../includes/header.php");
  
  $currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="logo">
            <h2>AB AUTOS</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="<?php if ($currentPage=='dashboard.php') echo'active';?>">Dashboard</a>
            <a href="my_test_drives.php" class="<?php if ($currentPage=='my_test_drives.php') echo'active';?>">Test Drives</a>
            <a href="saved_cars.php" class="<?php if ($currentPage=='saved_cars.php') echo'active';?>">Saved Cars</a>
            <a href="profile.php" class="<?php if ($currentPage=='profile.php') echo'active';?>">Profile</a>
            <a href="index.php" class="<?php if ($currentPage=='index.php') echo'active';?>">Home</a>
        </nav>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
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
                <h3><?= $title ?? 'Dashboard' ?></h3>
            </div>
            <div class="topbar-right">
                <div class="user-info">
                    <img src="<?= $_SESSION['profile_pic'] ?? '../assets/images/default.png' ?>" class="avatar">
                    <span><?= htmlspecialchars($_SESSION['user_name'])?></span>
                </div>
            </div>

        </header>
        <section class="content">
            <?= $content ?>
        </section>
    </main>
</div>
<?php include("../includes/footer.php"); ?>
<?php 
require("../config/db.php");
$page_title = "Dashboard";
$mysqli = get_mysqli();
ob_start();
?>

<div class="cards">
    <div class="card">
        <h3>Total Cars</h3>
        <?php
        $sql = "SELECT * FROM cars WHERE 1";
        $stmt = $mysqli->query($sql);
        $result= $stmt->num_rows;
        ?>
        <p><?= $result ?></p>
        <a href="manage_car.php" class="btn click">View Cars</a>
    </div>
    <div class="card">
        <h3>Total Users</h3>
          <?php
        $sql1 = "SELECT * FROM users WHERE 1";
        $stmt1 = $mysqli->query($sql1);
        $result1= $stmt1->num_rows;
        ?>
        <p><?= $result1 ?></p>
       <a href="users.php" class="btn click">View Users</a>
    </div>
    <div class="card">
        <h3>Test Bookings</h3>
        <?php
        $p = 'pending';
        $sql2 = $mysqli->prepare("SELECT * FROM bookings WHERE 1 AND status=?");
        $sql2->bind_param("s", $p); 
        $sql2->execute();
        $stmt2 = $sql2->get_result();
        $result2= $stmt2->num_rows;
        ?>
        <p><?= $result2 ?></p>
        <a href="bookings.php" class="btn click">View Bookings</a>
    </div>
</div>
<div class="tables">
    <div class="table-box">
        <h3>Recent Booking</h3>
        <table>
            <thead>
                <tr>
                <th>User</th>
                <th>Car</th>
                <th>Status</th>
            </tr>
            </thead>
           <tbody>
            <?php
            $sql3 = "SELECT b.*, u.first_name AS user_name, c.brand AS car_brand, c.model AS car_model, c.year AS car_year from bookings b JOIN users u ON b.user_id = u.id JOIN cars c ON b.car_id = c.id WHERE 1 ORDER BY b.created_at DESC LIMIT 4";
            $stmt3 = $mysqli->query($sql3);
            ?>
            <?php while( $result3 = $stmt3->fetch_assoc()): ?>
             <tr>
                <td><?= $result3['user_name']?></td>
                <td><?= $result3['car_brand']?> <?= $result3['car_model']?> <?= $result3['car_year']?></td>
                <td><span class="status <?= strtolower($result3['status']) ?>"><?= ucfirst($result3['status'])?></span></td>
            </tr>
            <?php endwhile;?>
           </tbody>
        </table>
    </div>
</div>
<div>
    <div class="table-box">
        <h3>Latest Users</h3> 
        <table>
            <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql4 = "SELECT * FROM users ORDER BY created_at DESC LIMIT 4";
                $stm4 = $mysqli->query($sql4); 
                ?>
                <?php while($result4 = $stm4->fetch_assoc()):?>
                 <tr>
                   <td><?= $result4['first_name']?> <?= $result4['last_name']?></td>
                   <td><?= $result4['email']?></td>
                   <td><?= $result4['role']?></td>
                 </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
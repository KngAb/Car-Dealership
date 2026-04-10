<?php 
include "../config/db.php";

$mysqli = get_mysqli();

$limit = 1;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page<1) $page = 1;
$offset = ($page-1) * $limit;

$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = "WHERE 1 ";

if ($status){
    $where .= " AND b.status = '".$mysqli->real_escape_string($status)."'";
}
if ($search){
    $search_escaped = $mysqli->real_escape_string($search);
    $where .= " AND( u.first_name LIKE '%$search_escaped%' OR u.last_name LIKE '%$search_escaped%')";
}
if ($from && $to){
    $where .= " AND b.booking_date BETWEEN '$from' AND '$to'";
}

$count_result = $mysqli->query("SELECT COUNT(*) as total FROM bookings b JOIN users u ON b.user_id = u.id $where");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows/$limit);

$sql = "SELECT b.*, u.first_name AS user_name, u.last_name AS user_name2, c.model AS car_model FROM bookings b JOIN users u ON b.user_id = u.id JOIN cars c on b.car_id = c.id $where LIMIT $limit OFFSET $offset";


$result = $mysqli->query($sql);

ob_start();
?>
<h2>Manage Bookings</h2>
<form action="" method="GET" class="filter-bar">
    <select name="status" id="">
        <option value="">All Status</option>
        <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
        <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
    <input type="date" name="from" value="<?= $from ?>" id="">
    <input type="date" name="to" value="<?= $to ?>" id="">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" Placeholder="Search User......" id="">
    <button type="submit" class="btn primary">Filter</button>
</form>
<div class="table-container">
    <table>
       <thead>
         <tr>
            
            <th>User</th>
            <th>Car Model</th>
            <th>Booking Date</th>
            <th>Testting Drive Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
       </thead>
       <tbody>
           <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['user_name']?></td>
                <td><?= $row['car_model']?></td>
                <td><?= $row['created_at']?></td>
                <td><?= $row['booking_date']?></td>
                <td>
                    <span class="status <?= strtolower($row['status']) ?>">
                        <?= ucfirst($row['status'])?>
                    </span>
                </td>
                <td class="actions">
                    <a href="view_booking.php?id=<?= $row['id'] ?>" class="btn view">View</a>
                    <?php if($row['status'] !== 'cancelled'): ?>
                    <a href="../controllers/BookingController.php?cancel=<?= $row['id']?>" class="btn delete" onclick ="return confirm('Cancel this booking')">Cancel</a>    
                     <?php endif; ?>
                </td>
            </tr>
          <?php endwhile; ?>
       </tbody>
    </table>
</div>
<div class="pagination">
    <?php if($page>1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page'=>$page - 1]))?>" class="btn">Previous</a>
    <?php endif; ?>
    <?php for($i=1; $i <= $total_pages ; $i++): ?>
        <a href="?<?= http_build_query(array_merge($_GET,['page' => $i])) ?>" class="btn <?= ($i == $page) ? 'active': '' ?>">
            <?= $i ?>
        </a>   
    <?php endfor; ?>
    <?php if($page < $total_pages): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="btn">Next</a>
    <?php endif; ?> 
</div>
<?php
$page_title = "Bookings";
$content = ob_get_clean();
include "layout.php";
?>
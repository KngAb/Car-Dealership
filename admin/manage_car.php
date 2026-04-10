<?php
include "../config/db.php";

$mysqli = get_mysqli();

$limit = 1;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page<1) $page = 1;
$offset = ($page-1) * $limit;

$brand = $_GET['brand'] ?? '';
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';


$where = "WHERE 1";


if($brand){
   $where .= " AND brand = '".$mysqli->real_escape_string($brand)."'";
}

if($status){
   $where .= " AND status = '".$mysqli->real_escape_string($status)."'";
}

if($search){
    $search_escaped = $mysqli->real_escape_string($search);
    $where .= " AND( model LIKE '%$search_escaped%' OR brand LIKE '%$search_escaped%')";
}

$count_result = $mysqli->query("SELECT COUNT(*) as total FROM cars $where");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows/$limit);

$sql = "SELECT * FROM cars $where LIMIT $limit OFFSET $offset";

$result = $mysqli->query($sql);
 
ob_start();
?>
<form method="GET" class="filter-bar"> 
    <select name="brand" id="">
        <?php 
         $sql2 = "SELECT DISTINCT brand, status FROM cars WHERE 1 ORDER BY brand ASC";
         $stmt = $mysqli->query($sql2);
        ?>
        <option value="">Brand</option>
        <?php while($res = $stmt->fetch_assoc()):?>
        <option value="<?= $res['brand'] ?>" <?= $brand == $res['brand'] ? 'selected' : '' ?>>
            <?= $res['brand'] ?>
        </option>
        <?php endwhile; ?>
    </select>
    <select name="status" id="">
        <option value="">All Status</option>
        <option value="available" <?= $status == 'available' ? 'selected' : '' ?> >Available</option>
        <option value="sold" <?= $status == 'sold' ? 'selected' : '' ?>> Sold</option>
    </select>
    <input type="text" name="search" placeholder="Search...." value<?= htmlspecialchars($search)?>>
    <button type="submit" class="btn primary">Search</button>
</form>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Model</th>
                <th>Brand</th>
                <th>Year</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($car = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $car['model']?></td>
                <td><?= $car['brand']?></td>
                <td><?= $car['year']?></td>
                <td><?= number_format($car['price'])?></td>
                <td>
                    <span class="status <?= $car['status']?>"><?= ucfirst($car['status'])?></span>
                </td>
                <td class="actions">
                    <a href="edit_car.php?id=<?= $car['id']?>" class="btn edit">Edit</a>    
                    <a href="delete_car.php?id=<?= $car['id']?>" class="btn delete" onclick="return confirm('Delete this car?')">Delete</a>
                    <a href="../public/car_details.php?id=<?= $car['id']?>" class="btn view">View</a>    
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
$page_title = "Manage Cars";
$content = ob_get_clean();
include "layout.php";
?>
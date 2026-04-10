<?php
include "../config/db.php";
$mysqli = get_mysqli();

$limit = 1;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page<1) $page = 1;
$offset = ($page-1) * $limit;


$role = $_GET['role'] ?? '';
$search = $_GET['search'] ?? '';

$where = "WHERE 1 ";


if($role){
    $where .= " AND role ='".$mysqli->real_escape_string($role)."'";
}
if($search){
    $search_escaped = $mysqli->real_escape_string($search);
    $where .= " AND (first_name LIKE '%$search_escaped%' OR middle_name LIKE '%$search_escaped%' OR last_name LIKE '%$search_escaped%')";
}

$count_result = $mysqli->query("SELECT COUNT(*) as total FROM users $where");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows/$limit);

$sql = "SELECT * FROM users $where LIMIT $limit OFFSET $offset";

$result = $mysqli->query($sql);
$page_title = "Manage Users";
ob_start();
?>

<h2>Manage Users</h2>
<form action="" method="GET" class="filter-bar">
    <select name="role" id="">
        <option value="">All Roles</option>
        <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="user" <?= $role == 'user' ? 'selected' : '' ?>>User</option>
    </select>
    <input type="text" name="search" placeholder="Search Users" value="<?= htmlspecialchars($search) ?>">

    <button type="submit" class="btn primary">Search</button>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $result->fetch_assoc()):?>
            <tr>
                <td>
                    <?= $user['first_name']?> 
                    <?= $user['middle_name']?> 
                    <?= $user['last_name']?>
                </td>
                <td><?= $user['email']?></td>
                <td><?=ucfirst($user['role'])?></td>
                <td><?=ucfirst($user['phone'])?></td>
                <td class="actions">
                    <a href="edit_user.php?id=<?=$user['id']?>" class="btn edit">Edit</a>
                    <a href="../controllers/AdminController.php?delete_user=<?= $user['id']?>" class="btn delete" onclick="return confirm('Delete this user?')">Delete</a>
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
$content = ob_get_clean();
include("layout.php");

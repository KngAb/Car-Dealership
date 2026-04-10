<?php
require_once "../config/db.php";
$mysqli = get_mysqli();

if(!isset($_GET['id'])){
    header("Location: manage_car.php");
    exit;
}

$id=(int)$_GET['id'];

$stmt= $mysqli->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    die("Car not found");
}

$car = $result->fetch_assoc();

$page_title = "Edit Car";

ob_start();
?>
<?php if(isset($_GET['success'])): ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
        Action completed successfully!
    </div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
        Error Updating Car
    </div>
<?php endif; ?>
<div class="form-container">
    <form action="../controllers/CarController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="">Car Brand</label>
                <input type="text" name="brand" value="<?= htmlspecialchars($car['brand'])?>" required>
            </div>
             <div class="form-group">
                <label for="">Car Model</label>
                <input type="text" name="model" value="<?= htmlspecialchars($car['model'])?>" required>
            </div>
             <div class="form-group">
                <label for="">Mileage</label>
                <input type="text" name="mileage" value="<?= $car['mileage']?>" required>
            </div>
            <div class="form-group">
                <label for="">Fuel Type</label>
                <select name="fuel_type" id="">
                    <option value="">Fuel Type</option>
                    <option value="petrol"<?= $car['fuel_type'] == 'petrol' ? 'selected' : '' ?>>Petrol</option>
                    <option value="diesel"<?= $car['fuel_type'] == 'diesel' ? 'selected' : '' ?>>Diesel</option>
                    <option value="electric"<?= $car['fuel_type'] == 'electric' ? 'selected' : '' ?>>Electric</option>
                    <option value="hybrid"<?= $car['fuel_type'] == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Year</label>
                <input type="number" name="year" value="<?= $car['year'] ?>">
            </div>
            <div class="form-group">
                <label for="">Transmission</label>
                <select name="transmission" id="">
                    <option value="">Transmission</option>
                    <option value="manual"<?= $car['transmission'] == 'manual' ? 'selected' : '' ?>>Manual</option>
                    <option value="automatic"<?= $car['transmission'] == 'automatic' ? 'selected' : '' ?>>Automatic</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" value="<?= $car['price'] ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="available" <?= $car['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="sold" <?= $car['status'] == 'sold' ? 'selected' : '' ?>>Sold</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($car['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Replace Image</label>
            <input type="file" name="image">
        </div>
        <div class="form-actions">
            <a href="manage_car.php" class="btn cancel">Cancel</a>

            <button type="submit" name="update_car" class="btn primary">Update Car</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include("layout.php");
?>
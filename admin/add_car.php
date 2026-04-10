<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
include "../config/db.php";

ob_start();
?>
<?php if(isset($_GET['success'])): ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
        Action completed successfully!
    </div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
        Error Adding Cars
    </div>
<?php endif; ?>
<div class="form-container">
    <form action="../controllers/CarController.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label for="">Car Brand</label>
                <input type="text" name="brand" placeholder="Enter Car Brand" required>
            </div>
            <div class="form-group">
                <label for="">Car Model</label>
                <input type="text" name="model" placeholder="Enter Car Model" required>
            </div>
        
            <div class="form-group">
                <label for="">Mileage</label>
                <input type="text" name="mileage" placeholder="Enter Mileage" required>
            </div>
            <div class="form-group">
                <label for="">Fuel Type</label>
                <select name="fuel_type" id="">
                    <option value="">Choose Fuel Type</option>
                    <option value="petrol">Petrol</option>
                    <option value="diesel">Diesel</option>
                    <option value="electric">Electric</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Year</label>
                <input type="number" name="year" placeholder="Enter Year">
            </div>
            <div class="form-group">
                <label for="">Transmission</label>
                <select name="transmission" id="">
                    <option value="">Select Transmission</option>
                    <option value="automatic"> Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Price</label>
                <input type="number" name="price" placeholde="Enter Price" required>
            </div>
            <div class="form-group">
                <label for="">Status</label>
                <select name="status" id="">
                    <option value="">Select Availabilty Status</option>
                    <option value="sold">Sold</option>
                    <option value="available">Available</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" id="" placeholder="Enter car description"></textarea>
        </div>
        <div class="upload-box">
            <label for="">Upload Images</label>
            <input type="file" name="image" multiple>
        </div>
        <div class="form-actions">
            <a href="dashboard.php" class="btn cancel">Cancel</a>
            <button type="submit" name="add_car" class="btn primary">Add Car</button>
        </div>
        <input type="hidden" name="dealer_id" value="<?= $_SESSION['user_id']; ?>">
    </form>
</div>

<?php

$page_title= "Add New Car";
$content = ob_get_clean();
include "layout.php";
?>
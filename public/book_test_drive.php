<?php
session_start();
require_once("../config/db.php");

include("../includes/header.php");
include("../includes/nav.php");

$mysqli = get_mysqli();
//Fetch cars for dropdown
$result = $mysqli->query("SELECT id, brand, model, year FROM cars ORDER BY brand ASC");
$cars = $result->fetch_all(MYSQLI_ASSOC);

$saved_data = [];
if (isset($_SESSION['post_data'])) {
    $saved_data = $_SESSION['post_data'];
    // Clear it so the form doesn't stay pre-filled forever
    unset($_SESSION['post_data']); 
}

$selectedCar = null;
if(isset($_GET['car_id'])){
    $car_id = intval($_GET['car_id']);
    $stmt = $mysqli->prepare("SELECT id, brand, model, year FROM cars WHERE id=?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $selectedCar = $result->fetch_assoc();
    }
}

$errors = $_SESSION['booking_errors'] ?? [];
unset($_SESSION['booking_errors']); // Clear immediately

// Helper to show error class
function errorClass($key, $errors) {
    return isset($errors[$key]) ? 'form-error' : '';
}
?>

<section class="testing-hero">
    <div class="hero-content">
           <h1>Book a Test Drive</h1>
           <p>Experience your dream car before making the decision</p>
    </div>
</section>

<section class="test-drive-section">
    <div class="test-drive-container">
        <?php if(isset($_GET['success'])): ?>
    <div style=" border-radius: 12px; margin: 20px auto; text-align:center; background:green; width: 50%; color: white; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
        Booked  successfully!
    </div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
        Error Booking Test Drive
    </div>
<?php endif; ?>
        <h2>Schedule Your Test Drive</h2>
        
        <form action="../controllers/BookingController.php" method='POST' class="test-drive-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="">Your Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars( $saved_data['name'] ?? '')?>"  required>
                    <?php if(isset($errors['nameError'])): ?>
            <div class="form-error" style="font-size: 13px; color: red;"><?= $errors['nameError'] ?></div>
        <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($saved_data['email'] ?? '')?>" required>
                    <?php if(isset($errors['emailError'])): ?>
            <div class="form-error" style="font-size: 13px; color: red;"><?= $errors['emailError'] ?></div>
        <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="">Phone Number</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($saved_data['phone'] ?? '')?>"  required>
                    <?php if(isset($errors['phoneError'])): ?>
            <div class="form-error" style="font-size: 13px; color: red;"><?= $errors['phoneError'] ?></div>
        <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="">Select Car</label>
                    <select name="car_id" id="" required>
                        <option value="">Choose a Car</option>
                        <?php foreach($cars as $car):?>
                            <?php 
                            $selected = false;
                            if(isset($selectedCar) && $selectedCar['id'] == $car['id']){
                                $selected = true;
                            }elseif(isset($saved_data) && $saved_data['car_id'] == $car['id']){
                                $selected = true;
                            }
                            ?>
                        <option value="<?= $car['id']?>" <?= $selected ? 'selected' : '' ?>>
                            <?= htmlspecialchars($car['brand'])?>
                            <?= htmlspecialchars($car['model'])?>
                            <?= htmlspecialchars($car['year'])?>
                        </option>
                        <?php endforeach;?>
                    </select>
                    <?php if(isset($errors['carError'])): ?>
            <div class="form-error" style="font-size: 13px; color: red;"><?= $errors['carError'] ?></div>
        <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="">Preferred Date</label>
                    <input type="date" name="test-date" value="<?= htmlspecialchars($saved_data['test-date']?? '') ?>"  required>
                    <?php if(isset($errors['dateError'])): ?>
            <div class="form-error" style="font-size: 13px; color: red;"><?= $errors['dateError'] ?></div>
        <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="">Preferred Time</label>
                    <input type="time" name="test-time" value="<?= htmlspecialchars($saved_data['test-time'] ?? '')?>"  required>
                </div>
            </div>
            <div class="form-group">
                <Label>Additional Message</Label>
                <textarea name="message" rows="4" id=""  placeholder="Any special request or question?"> <?= htmlspecialchars($saved_data['message'] ?? '')?></textarea>
            </div>

            <button class="btn">Book Test Drive</button>
        </form>
    </div>
</section>
<?php include("../includes/footer.php");?>
<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in doctor's email
$current_email = $_SESSION['email'];

// Database connection
$conn = new mysqli("localhost", "root", "", "health_diary");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctor details
$sql = "SELECT * FROM doctors WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_email);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialty = $_POST['specialty'];
    $license_number = $_POST['license_number'];
    $experience = $_POST['experience'];

    // Check if the new email is already in use by another doctor
    $email_check_sql = "SELECT id FROM doctors WHERE email = ? AND email != ?";
    $email_check_stmt = $conn->prepare($email_check_sql);
    $email_check_stmt->bind_param("ss", $email, $current_email);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    if ($email_check_result->num_rows > 0) {
        $_SESSION['error'] = "This email is already in use by another account.";
    } else {
        // Update the doctor's information
        $update_sql = "UPDATE doctors SET name = ?, email = ?, phone = ?, specialty = ?, license_number = ?, experience = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssiss", $name, $email, $phone, $specialty, $license_number, $experience, $current_email);

        if ($update_stmt->execute()) {
            // Update the session email to reflect the new email
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile!";
        }

        $update_stmt->close();
    }

    $email_check_stmt->close();
    header("Location: dProfile.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <h1>MyProfile</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="specialty">Specialty:</label>
                <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($doctor['specialty']); ?>" required>
            </div>
            <div class="form-group">
                <label for="license_number">License Number:</label>
                <input type="text" id="license_number" name="license_number" value="<?php echo htmlspecialchars($doctor['license_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="experience">Experience (Years):</label>
                <input type="number" id="experience" name="experience" value="<?php echo htmlspecialchars($doctor['experience']); ?>" required>
            </div>
            <button type="submit">Update Profile</button>
        </form>
        <a href="Ddash.php" class="btn">Back to Dashboard</a>
        <a href="ChangePassD.php" class="btn">Change Password</a>
    </div>
</body>
</html>

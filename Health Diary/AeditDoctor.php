<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$doctor_username = $_SESSION['username'];

// Database connection
$conn = new mysqli("localhost", "root", "", "health_diary");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the doctor details
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    if (!$doctor) {
        echo "<script>alert('Doctor not found or unauthorized access.'); window.location='AmanageDoctor.php';</script>";
        exit();
    }
} else {
    header("Location:AmanageDoctor.php");
    exit();
}

// Update doctor details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_password=$_POST['password'];
    $phone = $_POST['phone'];
    $speciality = $_POST['specialty'];
    $license_number = $_POST['license_number'];
    $experience=$_POST['experience'];
    
    
    // Check if the email or phone already exists
    $emailCheck = "SELECT * FROM doctors WHERE email = ? AND id != ?";
    $phoneCheck = "SELECT * FROM doctors WHERE phone = ? AND id != ?";

    // Prepare statements to check email and phone
    $emailStmt = $conn->prepare($emailCheck);
    $emailStmt->bind_param("ss", $email, $id);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();

    $phoneStmt = $conn->prepare($phoneCheck);
    $phoneStmt->bind_param("ss", $phone, $id);
    $phoneStmt->execute();
    $phoneResult = $phoneStmt->get_result();

    // Check if the email or phone is already registered
    if ($emailResult->num_rows > 0) {
        echo "Email is already taken.";
    } elseif ($phoneResult->num_rows > 0) {
        echo "Phone number is already registered!";
    } else {
        if (!empty($new_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update query including password
            $sql = "UPDATE doctors 
                    SET name = ?, email = ?, password = ?, phone = ?, specialty = ?, license_number = ?, experience = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $name, $email, $hashed_password, $phone, $speciality, $license_number, $experience, $id);
        } else {
            // Update query without password
            $sql = "UPDATE doctors
                    SET name = ?, email = ?, phone = ?, specialty = ?, license_number = ?, experience = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $name, $email, $phone, $speciality, $license_number, $experience, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Doctor details updated successfully!'); window.location='AmanageDoctor.php';</script>";
        } else {
            echo "<script>alert('Error updating doctor details: " . $stmt->error . "');</script>";
        }
    }

    $emailStmt->close();
    $phoneStmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Container for the form */
.container {
    width: 70%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
}

h1 {
    text-align: center;
    color: #007BFF;
}

/* Form styling */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-size: 14px;
    font-weight: bold;
    color: #333;
}

input, textarea {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="text"], input[type="email"], input[type="number"], textarea {
    width: 100%;
    box-sizing: border-box;
}

/* Styling for buttons */
button {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

/* Styling for back link */
a {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #007BFF;
    font-size: 16px;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Doctor Details</h1>
        <form method="POST">
            <label>Full Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" required><br>
            <label>Password:</label><br>
            <input type="password" name="password" value="<?php echo htmlspecialchars($doctor['password']); ?>" required><br>
            <label>Phone:</label><br>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>" required><br>
            <label>Speciality:</label><br>
            <input type="text" name="specialty" value="<?php echo htmlspecialchars($doctor['specialty']); ?>" required><br>
            <label>License No:</label><br>
            <input type="text" name="license_number" value="<?php echo htmlspecialchars($doctor['license_number']); ?>" required><br>
            <label>Experience Years:</label><br>
            <input type="number" name="experience" value="<?php echo htmlspecialchars($doctor['experience']); ?>" required><br>
            <button type="submit">Save</button>
        </form>
        <a href="AmanageDoctor.php">Back to Manage Doctors</a>
    </div>
</body>
</html>

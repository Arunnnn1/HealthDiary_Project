<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_username = $_SESSION['username'];

// Database connection
$conn = new mysqli("localhost", "root", "", "health_diary");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the patient details
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM docpatients WHERE id = ? AND doctor_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $doctor_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if (!$patient) {
        echo "<script>alert('Patient not found or unauthorized access.'); window.location='managPatient.php';</script>";
        exit();
    }
} else {
    header("Location: manage_patients.php");
    exit();
}

// Update patient details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $blood_group = $_POST['blood_group'];
    $med_history = $_POST['med_history'];

    $sql = "UPDATE docpatients 
            SET name = ?, email = ?, phone = ?, address = ?, age = ?, blood_group = ?, med_history = ? 
            WHERE id = ? AND doctor_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisssi", $name, $email, $phone, $address, $age, $blood_group, $med_history, $id, $doctor_username);

    if ($stmt->execute()) {
        echo "<script>alert('Patient details updated successfully!'); window.location='managePatient.php';</script>";
    } else {
        echo "<script>alert('Error updating patient details: " . $stmt->error . "');</script>";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
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
        <h1>Edit Patient Details</h1>
        <form method="POST">
            <label>Full Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required><br>
            <label>Phone:</label><br>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required><br>
            <label>Address:</label><br>
            <input type="text" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required><br>
            <label>Age:</label><br>
            <input type="number" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required><br>
            <label>Blood Group:</label><br>
            <input type="text" name="blood_group" value="<?php echo htmlspecialchars($patient['blood_group']); ?>" required><br>
            <label>Medical History:</label><br>
            <textarea name="med_history"><?php echo htmlspecialchars($patient['med_history']); ?></textarea><br><br>
            <button type="submit">Save</button>
        </form>
        <a href="managePatient.php">Back to Manage Patients</a>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "health_diary");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$doctor_username = $_SESSION['username'];

// Ensure the record belongs to the logged-in doctor
$sql = "SELECT * FROM medhistory WHERE id = ? AND d_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $doctor_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: You can only edit your own records.");
}

$record = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_name = $_POST['p_name'];
    $blood_group = $_POST['blood_group'];
    $blood_pressure = $_POST['blood_pressure'];
    $blood_sugar = $_POST['blood_sugar'];
    $temperature = $_POST['temperature'];
    $prescription = $_POST['prescription'];

    $update_sql = "UPDATE medhistory SET p_name = ?, blood_group = ?, blood_pressure = ?, blood_sugar = ?, temperature = ?, prescription = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $p_name, $blood_group, $blood_pressure, $blood_sugar, $temperature, $prescription, $id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Record updated successfully!'); window.location='medhis.php';</script>";
    } else {
        echo "Error updating record: " . $update_stmt->error;
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Record</title>
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
    <h1>Edit Medical Record</h1>
    <form method="POST">
        <label>Patient Name:</label>
        <input type="text" name="p_name" value="<?= htmlspecialchars($record['p_name']) ?>" required><br>

        <label>Blood Group:</label>
        <input type="text" name="blood_group" value="<?= htmlspecialchars($record['blood_group']) ?>" required><br>

        <label>Blood Pressure:</label>
        <input type="text" name="blood_pressure" value="<?= htmlspecialchars($record['blood_pressure']) ?>" required><br>

        <label>Blood Sugar:</label>
        <input type="text" name="blood_sugar" value="<?= htmlspecialchars($record['blood_sugar']) ?>" required><br>

        <label>Temperature:</label>
        <input type="text" name="temperature" value="<?= htmlspecialchars($record['temperature']) ?>" required><br>

        <label>Prescription:</label>
        <textarea name="prescription" required><?= htmlspecialchars($record['prescription']) ?></textarea><br>

        <button type="submit">Update Record</button>
    </form>
    <a href="medhis.php">Back to Medical History</a>
    </div>
</body>
</html>

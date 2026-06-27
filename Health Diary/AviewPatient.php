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

// Fetch the patient details
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM patients WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if (!$patient) {
        echo "<script>alert('Patient not found or unauthorized access.'); window.location='AmanagePatient.php';</script>";
        exit();
    }
} else {
    header("Location: AmanagePatient.php");
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
    <title>Patient Details</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Container for the page */
.container {
    width: 80%;
    margin: 0 auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
}

/* Header */
h1 {
    text-align: center;
    color: #007BFF;
}

/* Patient Details */
.patient-details {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.patient-info p {
    font-size: 16px;
    margin: 10px 0;
}

.patient-info p strong {
    color: #333;
}

/* Back link styling */
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
        <h1>Patient Details</h1>
        <div class="patient-details">
            <div class="patient-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></p>
                <p><strong>Date of Birth</strong> <?php echo htmlspecialchars($patient['dob']); ?></p>
                <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group']); ?></p>
            </div>
        </div>
        <a href="AmanagePatient.php">Back to Manage Patients</a>
    </div>
</body>
</html>

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

// Fetch the record only if it belongs to the logged-in doctor
$sql = "SELECT * FROM medhistory WHERE id = ? AND d_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $doctor_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: You can only view your own records.");
}

$record = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Medical Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .record-details {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .field {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .value {
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="record-details">
        <h1>Medical Record Details</h1>
        <div class="field">Patient Name:</div>
        <div class="value"><?= htmlspecialchars($record['p_name']) ?></div>

        <div class="field">Blood Group:</div>
        <div class="value"><?= htmlspecialchars($record['blood_group']) ?></div>

        <div class="field">Blood Pressure:</div>
        <div class="value"><?= htmlspecialchars($record['blood_pressure']) ?> mm Hg</div>

        <div class="field">Blood Sugar:</div>
        <div class="value"><?= htmlspecialchars($record['blood_sugar']) ?> mg/dL</div>

        <div class="field">Temperature:</div>
        <div class="value"><?= htmlspecialchars($record['temperature']) ?> °C</div>

        <div class="field">Prescription:</div>
        <div class="value"><?= nl2br(htmlspecialchars($record['prescription'])) ?></div>
        <div class="field">Date/Time:</div>
        <div class="value"><?= htmlspecialchars($record['date_added']) ?> </div>

        <a href="medhis.php">Back to Records</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

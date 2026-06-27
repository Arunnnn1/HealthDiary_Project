<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "health_diary");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $p_name = $_POST['p_name'];
    $blood_group = $_POST['blood_group'];
    $email=$_POST['email'];
    $d_name=$_SESSION['username'];
    $blood_pressure=$_POST['blood_pressure'];
    $blood_sugar=$_POST['blood_sugar'];
    $temperature=$_POST['temperature'];
    $prescription=$_POST['prescription'];

    $sql = "INSERT INTO medhistory (p_name, blood_group, email, d_name, blood_pressure, blood_sugar, temperature, prescription)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $p_name, $blood_group, $email, $d_name, $blood_pressure, $blood_sugar, $temperature, $prescription);

    if ($stmt->execute()) {
        echo "<script>alert('Patient medical data added successfully!'); window.location='Ddash.php';</script>";
    } else {
        echo "<script>alert('Error adding data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn {
    display: inline-block;
    text-decoration: none;
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="container">
    <a href="Ddash.php" class="btn">Back to Dashboard</a><br><br>
        <h1>Add Medical history</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="p_name">Full Name:</label>
                <input type="text" id="p_name" name="p_name" required>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group:</label>
                <select id="blood_group" name="blood_group" required>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            </div>
            <h3>Medical History</h3>
            <div class="form-group">
            <label for="blood_pressure">Blood Pressure:</label>
        <input type="text" id="blood_pressure" name="blood_pressure" required><br>

        <label for="blood_sugar">Blood Sugar:</label>
        <input type="text" id="blood_sugar" name="blood_sugar" required><br>


        <label for="temperature">Temperature:</label>
        <input type="text" id="temperature" name="temperature" required><br>

        <label for="prescription">Prescription:</label>
        <textarea id="prescription" name="prescription" rows="4" required></textarea><br>

            </div>
            <button type="submit">Save</button>
        </form>
        
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
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

input, textarea {
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
a{
    cursor:pointer;

}

    </style>
</head>
<body>
    <div class="container">
    <form method="POST" action=""  onsubmit="return validateForm()">
        <h2> Appointment Form</h2>
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <span class="error" id="emailError"></span>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                <span class="error" id="phoneError"></span>
            </div>
            <div class="form-group">
                <label for="dname">Doctor Name:</label>
                <input type="text" id="dname" name="dname" placeholder="Enter doctor name" required>
            </div>
            <div class="form-group">
                <label for="date">Appointment Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="date">Time:</label>
                <input type="time" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="reason">Reason for Appointment:</label>
                <textarea id="reason" name="reason" placeholder="Enter the reason for your appointment" rows="4" required></textarea>
            </div>
            <button type="submit">Submit</button>
                        <div>
                <a href="Pdash.php"><br><center>Cancel</center></br></a>
            </div>
        </form>
    </div>
    <script>
        function validateForm() {
            let isValid = true;


            // Email Validation
            const email = document.getElementById('email').value.trim();
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                emailError.textContent = 'Enter a valid email.';
                isValid = false;
            } else {
                emailError.textContent = '';
            }

            // Phone Validation
            const phone = document.getElementById('phone').value.trim();
            const phoneError = document.getElementById('phoneError');
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(phone)) {
                phoneError.textContent = 'Enter a valid 10-digit phone number.';
                isValid = false;
            } else {
                phoneError.textContent = '';
            }


            return isValid;
        }
    </script>
</body>
</html>


<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "health_diary"; // Ensure your database name matches

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $doctor_name = $_POST['dname'];
    $appointment_date = $_POST['date'];
    $time= $_POST['time'];
    $reason = $_POST['reason'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($doctor_name) || empty($appointment_date) ||empty($time) || empty($reason)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Insert data into the appointments table
        $sql = "INSERT INTO appointments (name, email, phone, doctor_name, appointment_date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssssss", $name, $email, $phone, $doctor_name, $appointment_date, $time, $reason);

            if ($stmt->execute()) {
                echo "<script>alert('Appointment request submitted successfully!');
                window.location.href = 'Pdash.php';
                </script>";
            } else {
                echo "<script>alert('Error submitting appointment request: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to prepare the SQL statement.');</script>";
        }
    }
}

// Close connection
$conn->close();
?>
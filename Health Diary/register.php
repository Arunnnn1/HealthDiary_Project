<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" method="POST" action="register.php" onsubmit="return validateForm()">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name">
            <span class="error" id="nameError"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
            <span class="error" id="emailError"></span>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">
            

            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <span class="error" id="passwordError"></span>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword">
            <span class="error" id="confirmPasswordError"></span>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob">
            <span class="error" id="dobError"></span>

            <label for="bloodGroup">Blood Group:</label>
            <input type="text" id="bloodGroup" name="bloodGroup">
            <span class="error" id="bloodGroupError"></span>

            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Name Validation
            const name = document.getElementById('name').value.trim();
            const nameError = document.getElementById('nameError');
            if (name === '') {
                nameError.textContent = 'Full name is required.';
                isValid = false;
            } else {
                nameError.textContent = '';
            }

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

            // DOB Validation
            const dob = document.getElementById('dob').value.trim();
            const dobError = document.getElementById('dobError');
            if (dob === '') {
                dobError.textContent = 'Date of birth is required.';
                isValid = false;
            } else {
                dobError.textContent = '';
            }

            // Password Validation
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            if (password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters.';
                isValid = false;
            } else {
                passwordError.textContent = '';
            }

            // Confirm Password Validation
            const confirmPassword = document.getElementById('confirmPassword').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            if (confirmPassword !== password) {
                confirmPasswordError.textContent = 'Passwords do not match.';
                isValid = false;
            } else {
                confirmPasswordError.textContent = '';
            }

            return isValid;
        }
    </script>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_diary";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $dob = $_POST['dob'];
    $bloodGroup = trim($_POST['bloodGroup']);

    // Check if email or phone already exists
    $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email or Phone already exists!'); window.location.href = 'register.php';</script>";
    } elseif ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'register.php';</script>";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO patients (name, email, phone, password, dob, blood_group) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $hashedPassword, $dob, $bloodGroup);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location.href = 'login.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

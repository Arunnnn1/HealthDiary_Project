<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Doctor</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="container">
  <a href="Adash.php">Back to Dashboard</a>
    <h2>Add Doctor</h2>
    <form method="POST" action="addDoctor.php" onsubmit="return validateForm()">
      <div>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
        <span class="error" id="nameError"></span>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <span class="error" id="emailError"></span>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <span class="error" id="passwordError"></span>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <span class="error" id="confirmPasswordError"></span>
      </div>

      <label for="phone">Phone:</label>
      <input type="text" id="phone" name="phone" required>
      <span class="error" id="phoneError"></span>
      
        <label for="specialty">Specialty:</label>
        <input type="text" id="specialty" name="specialty">

        <label for="licenseNumber">Medical License Number:</label>
        <input type="text" id="licenseNumber" name="licenseNumber">

        <label for="experience">Years of Experience:</label>
        <input type="number" id="experience" name="experience">
      <!-- Submit Button -->
      <button type="submit" name="submit">Save</button>
    </form>
    
  </div>

  <script>
    function showFields() {
      var doctorFields = document.getElementById("doctorFields");
      doctorFields.style.display = "none";
    }

    function validateForm() {
      let isValid = true;

      // Common validations
      const name = document.getElementById('name').value.trim();
      const nameError = document.getElementById('nameError');
      if (name === '') {
        nameError.textContent = 'Full name is required.';
        nameError.style.display = 'block';
        isValid = false;
      } else {
        nameError.style.display = 'none';
      }

      const email = document.getElementById('email').value.trim();
      const emailError = document.getElementById('emailError');
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (!emailRegex.test(email)) {
        emailError.textContent = 'Enter a valid email.';
        emailError.style.display = 'block';
        isValid = false;
      } else {
        emailError.style.display = 'none';
      }

      const phone = document.getElementById('phone').value.trim();
      const phoneError = document.getElementById('phoneError');
      const phoneRegex = /^[0-9]{10}$/;
      if (!phoneRegex.test(phone)) {
        phoneError.textContent = 'Enter a valid 10-digit phone number.';
        phoneError.style.display = 'block';
        isValid = false;
      } else {
        phoneError.style.display = 'none';
      }

      const password = document.getElementById('password').value;
      const passwordError = document.getElementById('passwordError');
      if (password.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters.';
        passwordError.style.display = 'block';
        isValid = false;
      } else {
        passwordError.style.display = 'none';
      }

      const confirmPassword = document.getElementById('confirmPassword').value;
      const confirmPasswordError = document.getElementById('confirmPasswordError');
      if (confirmPassword !== password) {
        confirmPasswordError.textContent = 'Passwords do not match.';
        confirmPasswordError.style.display = 'block';
        isValid = false;
      } else {
        confirmPasswordError.style.display = 'none';
      }

      const specialty = document.getElementById('specialty').value.trim();
        const licenseNumber = document.getElementById('licenseNumber').value.trim();
        const experience = document.getElementById('experience').value.trim();

        if (!specialty || !licenseNumber || !experience) {
          alert('Please complete all  fields.');
          isValid = false;
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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $specialty = $_POST['specialty'];
    $licenseNumber = $_POST['licenseNumber'];
    $experience = $_POST['experience'];
    
    // Check if the email or phone already exists
    $emailCheck = "SELECT * FROM doctors WHERE email = ?";
    $phoneCheck = "SELECT * FROM doctors WHERE phone = ?";

    // Prepare statements to check email and phone
    $emailStmt = $conn->prepare($emailCheck);
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();

    $phoneStmt = $conn->prepare($phoneCheck);
    $phoneStmt->bind_param("s", $phone);
    $phoneStmt->execute();
    $phoneResult = $phoneStmt->get_result();

    // Check if the email or phone is already registered
    if ($emailResult->num_rows > 0) {
        echo "Email is already taken.";
    } elseif ($phoneResult->num_rows > 0) {
        echo "Phone number is already registered!";
    } elseif ($password !== $confirmPassword) {
        echo "Passwords do not match!";
    } else {
        // Hash the password before inserting
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the doctor into the database
        $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, password, specialty, license_number, experience) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $hashedPassword, $specialty, $licenseNumber, $experience);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Doctor added successfully!');
                    window.location.href = 'AmanageDoctor.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $emailStmt->close();
    $phoneStmt->close();
}

$conn->close();
?>
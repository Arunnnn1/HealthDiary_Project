<?php
// Start the session at the very beginning
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if form fields are not empty
    if (empty($email) || empty($password)) {
        $_SESSION['message'] = "All fields are required!";
    } else {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $dbpassword = "";  // Update with your DB password
        $dbname = "health_diary";  // Ensure your DB is correctly named

        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to fetch user data
        $sql = "SELECT * FROM patients WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, now verify password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['user_id'] = $user['id'];  // Store user ID
                $_SESSION['role'] = $role;  // Store the role
                $_SESSION['username'] = $user['name']; // Store the username
                header("Location:appointment.php");
                
            } else {
                $_SESSION['message'] = "Invalid password!";
            }
        } else {
            $_SESSION['message'] = "User not found!";
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>

    <!-- Display login messages (success or error) -->
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        // Clear message after displaying it once
        unset($_SESSION['message']);
    }
    ?>
    <form action="AppointmentLogin.php" method="POST">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <div class="register">
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</div>

</body>
</html>
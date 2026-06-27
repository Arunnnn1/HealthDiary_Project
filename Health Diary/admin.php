<?php
// Database connection details
$servername = "localhost";  // Database host (usually 'localhost')
$username = "root";         // Database username
$password = "";             // Database password (leave empty if no password)
$dbname = "health_diary";   // Name of the existing database

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$name = "Admin"; // Replace with admin name
$email = "admin@gmail.com"; // Replace with admin email
$password = "admin123"; // Replace with the plain text password

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert admin
$sql = "INSERT INTO admins (name,email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss",$name, $email, $hashedPassword );

if ($stmt->execute()) {
    echo "Admin inserted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$conn->close();
?>

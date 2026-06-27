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

// Prepare SQL query to insert multiple rows
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Admin table created successfully!";
} else {
    echo "Error : " . $conn->error;
}

// Close the connection
$conn->close();
?>

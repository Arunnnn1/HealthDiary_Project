<?php
// Step 1: Database connection parameters
$servername = "localhost"; // MySQL server (or IP address)
$username = "root";        // MySQL username
$password = "";            // MySQL password (empty for localhost)
$dbname = "health_diary";  // Database name

// Step 2: Create a connection to MySQL
$conn = new mysqli($servername, $username, $password);

// Step 3: Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 4: Create the database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully!";
} else {
    echo "Error creating database: " . $conn->error;
}

// Step 5: Close connection
$conn->close();
?>

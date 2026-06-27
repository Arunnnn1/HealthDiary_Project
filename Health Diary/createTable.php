<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_diary";

// Create a connection to the health_diary database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Table for Patients
$sql = "CREATE TABLE IF NOT EXISTS patients (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone INT(10) UNIQUE NOT NULL,
    dob DATE NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'patients' created successfully!<br>";
} else {
    echo "Error creating table 'patients': " . $conn->error . "<br>";
}

// Table for Doctors
$sql = "CREATE TABLE IF NOT EXISTS doctors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone INT(10) UNIQUE NOT NULL,
    specialty VARCHAR(50),
    license_number VARCHAR(50) UNIQUE NOT NULL,
    experience INT(3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'doctors' created successfully!<br>";
} else {
    echo "Error creating table 'doctors': " . $conn->error . "<br>";
}


// Close connection
$conn->close();
?>

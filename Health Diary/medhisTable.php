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

// SQL to create the docpatients table

$sql = "CREATE TABLE medhistory(
    id INT AUTO_INCREMENT PRIMARY KEY,
    p_name VARCHAR(100),
    blood_group VARCHAR(10),
    email VARCHAR(50)  NOT NULL,
    d_name VARCHAR(100),
    blood_pressure VARCHAR(50),
    blood_sugar VARCHAR(50),
    temperature DECIMAL(5,2),
    prescription TEXT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the CREATE TABLE query
if ($conn->query($sql) === TRUE) {
   echo "Table 'medhistory' created successfully!<br>";
} else {
   echo "Error creating table: " . $conn->error . "<br>";
}




// Close connection
$conn->close();
?>
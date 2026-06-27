<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "health_diary");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$doctor_username = $_SESSION['username'];

// Delete only if the record belongs to the logged-in doctor
$sql = "DELETE FROM medhistory WHERE id = ? AND d_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $doctor_username);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Record deleted successfully!'); window.location='medhis.php';</script>";
    } else {
        echo "<script>alert('Error: You can only delete your own records.'); window.location='view_records.php';</script>";
    }
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

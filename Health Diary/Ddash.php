<?php
// Start session and check role
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username']; // Doctor's username from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Dashboard</title>
    <link rel="stylesheet" href="Ddash.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">HD</div>
            <ul>
                <li><a href="dProfile.php">Profile</a></li>
                <li><a href="Ddash.php">Dashboard</a></li>
                <li><a href="dAppointment.php">Appointment History</a></li>
                <li><a href="addmed.php">Add Medical History</a></li>
                <li><a href="medhis.php">Medical History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome, Dr. <?php echo htmlspecialchars($username); ?>!</h1>
            </header>
            
            <!-- Appointment Cards -->
            <div class="appointment-cards">
            <div class="card" onclick="location.href='pendingAppointment.php'">
                    <h3>Pending Appointments</h3>
                    <p>View pending appointments.</p>
                </div>
                <div class="card" onclick="location.href='dAppointment.php'">
                    <h3>My Appointments</h3>
                    <p>View Appointment History.</p>
                </div>
                <div class="card" onclick="location.href='addmed.php'">
                    <h3>Medical History</h3>
                    <p>Add Patient's medical history</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

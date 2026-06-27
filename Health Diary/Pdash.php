<?php
session_start();

// Check if the patient is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "patient") {
    header('Location: login.php');  // If not logged in, redirect to login page
    exit;
}

$username = $_SESSION['username'];  // Get the username from session
?>
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="Pdash.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">HD</div>
            <ul>
                <li><a href="pProfile.php">Profile</a></li>
                <li><a href="Pdash.php">Dashboard</a></li>
                <li><a href="appointment.php">Book Appointment</a></li>
                <li><a href="pAppointmentstatus.php">Appointment History</a></li>
                <li><a href="pMedhis.php">Medical History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            </header>
            
            <!-- Appointment Cards -->
            <div class="appointment-cards">
                <div class="card" onclick="location.href='pAppointmentstatus.php'">
                    <h3>My Appointments</h3>
                    <p>View appointment history.</p>
                </div>
                <div class="card" onclick="location.href='pMedhis.php'">
                    <h3>Medical History</h3>
                    <p>View Medical History.</p>
                </div>
                <div class="card" onclick="location.href='appointment.php'">
                    <h3>Book My Appointment</h3>
                    <p>Book Appointment.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

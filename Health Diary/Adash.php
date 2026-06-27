<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Adash.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">HD</div>
            <ul>
                <li><a href="aProfile.php">Profile</a></li>
                <li><a href="Adash.php">Dashboard</a></li>
                <li><a href="adminAppointment.php">Appointment History</a></li>
                <li><a href="addDoctor.php">Add Doctors</a></li>
                <li><a href="AmanageDoctor.php">Manage Doctors</a></li>
                <li><a href="AmanagePatient.php">Manage Patients</a></li>
                <li><a href="aMedhis.php">Medical History</a></li>
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
                <div class="card" onclick="location.href='adminAppointment.php'">
                    <h3>Appointments</h3>
                    <p>View Appointment History.</p>
                </div>
                <div class="card" onclick="location.href='AmanageDoctor.php'">
                    <h3>Doctors</h3>
                    <p>Manage Doctor.</p>
                </div>
                <div class="card" onclick="location.href='AmanagePatient.php'">
                    <h3>Patients</h3>
                    <p>Manage Patient.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

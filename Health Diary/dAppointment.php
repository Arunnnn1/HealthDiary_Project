<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "doctor") {
    header('Location: login.php');  // If not logged in, redirect to login page
    exit;
}

$email = $_SESSION['email'];  // Get the email from session
$username = $_SESSION['username'];  // Get the username from session

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "health_diary";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointments for the logged-in doctor
$sql = "SELECT name, appointment_date, time, reason, status FROM appointments WHERE doctor_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <link rel="stylesheet" href="Ddash.css">
    <style>
        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .appointment-table th, .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #007BFF;
            color: white;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.approved {
            color: green;
        }

        .status.rejected {
            color: red;
        }
    </style>
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
                <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            </header>

            <!-- Appointment Status Table -->
            <div>
                <h2>My Appointments</h2>
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Appointment Date</th>
                            <th>Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td class="status <?php echo htmlspecialchars($row['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
$stmt->close();
$conn->close();
?>

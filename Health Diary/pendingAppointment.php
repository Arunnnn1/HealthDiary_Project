<?php
// Start session and check role
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username']; // Doctor's username from session

// Database connection
$servername = "localhost";
$db_username = "root"; // Ensure this matches your DB user
$db_password = ""; // Ensure this matches your DB password
$dbname = "health_diary";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle appointment approval/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['action'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action']; // 'approve' or 'reject'

    // Update the appointment status based on the action
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $sql_update = "UPDATE appointments SET status = ? WHERE id = ? AND doctor_name = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sis", $status, $appointment_id, $username);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Appointment " . htmlspecialchars($status) . " successfully!');</script>";
    } else {
        echo "<script>alert('Error updating appointment status.');</script>";
    }
    $stmt_update->close();
}

// Fetch pending appointments
$sql = "SELECT * FROM appointments WHERE doctor_name = ? AND status = 'pending'";
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
    <title>Doctors Dashboard</title>
    <link rel="stylesheet" href="Ddash.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .button {
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button.reject {
            background-color: #e74c3c;
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
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome, Dr. <?php echo htmlspecialchars($username); ?>!</h1>
            </header>

            <h2>Pending Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="button">Approve</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="reject" class="button reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4">No pending appointments.</td>
                        </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

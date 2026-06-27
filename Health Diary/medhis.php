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

$doctor_username = $_SESSION['username'];
 
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Ensure the record belongs to the logged-in doctor
    $sql = "SELECT * FROM medhistory WHERE id = ? AND d_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $delete_id, $doctor_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Record exists, delete it
        $delete_sql = "DELETE FROM medhistory WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $delete_id);
        if ($delete_stmt->execute()) {
            echo "<script>alert('Record deleted successfully.'); window.location='medhis.php';</script>";
        } else {
            echo "<script>alert('Error deleting record.');</script>";
        }
        $delete_stmt->close();
    } else {
        echo "<script>alert('You cannot delete this record.');</script>";
    }

    $stmt->close();
}

// Fetch records associated with the doctor
$sql = "SELECT * FROM medhistory WHERE d_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Records</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 90%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }
        .view{
            background-color: #28a745;
        }
        .edit {
            background-color: #28a745;
        }
        .delete {
            background-color: #dc3545;
        }
        .delete:hover {
            background-color: #c82333;
        }
        .d{
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            text-align: center;
        }
        .dash {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            text-align: center;
        }
        .dash:hover{
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Patient Medical Records</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Blood Group</th>
            <th>Blood Pressure</th>
            <th>Blood Sugar</th>
            <th>Temperature</th>
            <th>Prescription</th>
            <th>Date/Time</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['p_name']) ?></td>
            <td><?= htmlspecialchars($row['blood_group']) ?></td>
            <td><?= htmlspecialchars($row['blood_pressure']) ?> mm Hg</td>
            <td><?= htmlspecialchars($row['blood_sugar']) ?> mg/dL</td>
            <td><?= htmlspecialchars($row['temperature']) ?> °C</td>
            <td><?= htmlspecialchars($row['prescription']) ?></td>
            <td><?= htmlspecialchars($row['date_added']) ?></td>
            <td>
            <a href="viewMedhis.php?id=<?php echo $row['id']; ?>" class="view">View</a>
            <a href="editMedhis.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
            <a href="medhis.php?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="8">No medical history of patient.</td>
                    </tr>
                <?php endif; ?>
    </table>
    <a href="Ddash.php" class="d">Back to Dashboard</a>
    <a href="addmed.php" class="d">Add Medical Record</a>
        </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
 
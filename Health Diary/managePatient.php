<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_username = $_SESSION['username'];
$conn = new mysqli("localhost", "root", "", "health_diary");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM docpatients WHERE id = ? AND doctor_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $delete_id, $doctor_username);

    if ($stmt->execute()) {
        echo "<script>alert('Patient deleted successfully!'); window.location='managePatient.php';</script>";
    } else {
        echo "<script>alert('Error deleting patient.');</script>";
    }

    $stmt->close();
}

$sql = "SELECT * FROM docpatients WHERE doctor_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
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
        .add-patient {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            text-align: center;
        }
        .add-patient:hover{
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Patients</h1>
        <a href="addPatient.php" class="add-patient">Add New Patient</a>
        <table>
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
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
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                                <a href="viewPatient.php?id=<?php echo $row['id']; ?>" class="view">View</a>
                                <a href="editPatient.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="managePatient.php?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="Ddash.php" class="add-patient">Back to Dashboard</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
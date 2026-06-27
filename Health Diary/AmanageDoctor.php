<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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
    $sql = "DELETE FROM doctors WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor deleted successfully!'); window.location='AmanageDoctor.php';</script>";
    } else {
        echo "<script>alert('Error deleting Doctor.');</script>";
    }

    $stmt->close();
}

$sql = "SELECT * FROM doctors";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
        <h1>Manage Doctors</h1>
        <table>
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Speciality</th>
                    <th>Licence No</th>
                    <th>Experience</th>
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
                            <td><?php echo htmlspecialchars($row['specialty']); ?></td>
                            <td><?php echo htmlspecialchars($row['license_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['experience']); ?></td>
                            <td>
                                <a href="AviewDoctor.php?id=<?php echo $row['id']; ?>" class="view">View</a>
                                <a href="AeditDoctor.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="AmanageDoctor.php?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No doctors found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="Adash.php" class="dash">Back to Dashboard</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
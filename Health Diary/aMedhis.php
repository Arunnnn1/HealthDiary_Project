<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];  // Get the email from session
$username = $_SESSION['username'];  // Get the username from session

$conn = new mysqli("localhost", "root", "", "health_diary");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 


// Fetch records associated with the doctor
$sql = "SELECT * FROM medhistory";
$stmt = $conn->prepare($sql);
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
            max-width: 80%;
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
            <th>Patient Name</th>
            <th>Blood Group</th>
            <th>Email</th>
            <th>Doctor Name</th>
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
            <td><?= htmlspecialchars($row['p_name']) ?></td>
            <td><?= htmlspecialchars($row['blood_group']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['d_name']) ?></td>
            <td><?= htmlspecialchars($row['blood_pressure']) ?> mm Hg</td>
            <td><?= htmlspecialchars($row['blood_sugar']) ?> mg/dL</td>
            <td><?= htmlspecialchars($row['temperature']) ?> °C</td>
            <td><?= htmlspecialchars($row['prescription']) ?></td>
            <td><?= htmlspecialchars($row['date_added']) ?></td>
            <td>
            <a href="aViewMed.php?id=<?php echo $row['id']; ?>" class="view">View</a>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="8">No medical history of patient.</td>
                    </tr>
                <?php endif; ?>
    </table>
    <a href="Adash.php" class="d">Back to Dashboard</a>
        </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
 
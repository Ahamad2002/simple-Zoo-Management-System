<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'zoo');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user counts
$totalUsers = $conn->query("SELECT COUNT(user_id) AS count FROM Users")->fetch_assoc()['count'];
$totalVolunteers = $conn->query("SELECT COUNT(user_id) AS count FROM Users WHERE role = 'volunteer'")->fetch_assoc()['count'];
$totalAdmins = $conn->query("SELECT COUNT(user_id) AS count FROM Users WHERE role = 'admin'")->fetch_assoc()['count'];
$totalVisitors = $conn->query("SELECT COUNT(user_id) AS count FROM Users WHERE role = 'visitor'")->fetch_assoc()['count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="welcome-section">
        <h2>Welcome, Admin!</h2>
        <p>Manage the ZooParc Zoological Park with the tools and resources below.</p>
    </section>

    <section class="admin-stats">
        <div class="stat-card">
            <div class="circle total-users">
                <p><?php echo $totalUsers; ?></p>
            </div>
            <h3>Total Users</h3>
        </div>
        <div class="stat-card">
            <div class="circle total-volunteers">
                <p><?php echo $totalVolunteers; ?></p>
            </div>
            <h3>Total Volunteers</h3>
        </div>
        <div class="stat-card">
            <div class="circle total-admins">
                <p><?php echo $totalAdmins; ?></p>
            </div>
            <h3>Total Admins</h3>
        </div>
        <div class="stat-card">
            <div class="circle total-visitors">
                <p><?php echo $totalVisitors; ?></p>
            </div>
            <h3>Total Visitors</h3>
        </div>
    </section>

</main>

<?php include('php/footer.php'); ?>

</body>
</html>

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

// Fetch contact inquiries from the database
$sql = "SELECT * FROM ContactInquiries";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiries - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/contactdetails.css">
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="inquiries-section">
        <h2>Contact Inquiries</h2>
        <table>
            <thead>
                <tr>
                    <th>Inquiry ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['inquiry_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['message']}</td>
                                <td>{$row['submitted_at']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No contact inquiries found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'zoo');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch booked history for the logged-in user
$sql = "SELECT b.*, e.event_name, e.event_date, e.start_time, e.end_time, e.location 
        FROM BookedEvents b
        JOIN Events e ON b.event_id = e.event_id
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked History - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/bookedhistory.css">
</head>
<body>

<?php include('php/header.php'); ?>

<main>
    <section class="booked-history-container">
        <h2>Your Booked Events</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['event_name']}</td>
                        <td>{$row['event_date']}</td>
                        <td>{$row['start_time']} - {$row['end_time']}</td>
                        <td>{$row['location']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>You have not booked any events yet.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

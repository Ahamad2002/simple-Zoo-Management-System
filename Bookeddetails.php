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

$sql = "SELECT e.event_name, e.event_date, e.start_time, e.end_time, COUNT(b.booking_id) AS booking_count
        FROM BookedEvents b
        JOIN Events e ON b.event_id = e.event_id
        GROUP BY b.event_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Event Details - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/booked-details.css">
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="booked-details-container">
        <h2>Booked Event Details</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Event Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Booking Count</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['event_name']}</td>
                        <td>{$row['event_date']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>{$row['booking_count']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No bookings found.</p>";
        }

        $conn->close();
        ?>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

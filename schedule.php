<?php
session_start();

// Check if the user is logged in and is a volunteer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'zoo');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in volunteer's ID
$volunteer_id = $_SESSION['user_id'];

// Fetch events assigned to this volunteer
$sql = "SELECT * FROM Events WHERE created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Schedule - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/schedule.css">
</head>
<body>

<?php include('php/volunteer-header.php'); ?>

<main>
    <section class="schedule-container">
        <h2>Your Schedule</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($event = $result->fetch_assoc()) {
                $imagePath = $event['image'] ? $event['image'] : 'images/default-image.png'; // Default image if none provided
                echo "<div class='event-card'>
                        <img src='{$imagePath}' alt='{$event['event_name']}' class='event-image'>
                        <div class='event-details'>
                            <h3>{$event['event_name']}</h3>
                            <p>{$event['description']}</p>
                            <p><strong>Date:</strong> {$event['event_date']}</p>
                            <p><strong>Time:</strong> {$event['start_time']} - {$event['end_time']}</p>
                            <p><strong>Location:</strong> {$event['location']}</p>
                            <a href='educational.php?event_id={$event['event_id']}' class='educational-button'>Add Educational Content</a>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No events assigned to you yet.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

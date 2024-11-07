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

// Get the event_id from the URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch event details
    $sql = "SELECT e.*, u.name AS volunteer_name FROM Events e JOIN Users u ON e.created_by = u.user_id WHERE e.event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $imagePath = $event['image'] ? $event['image'] : 'images/default-image.png'; // Default image if none provided

        // Fetch educational content related to the event
        $edu_sql = "SELECT * FROM EducationalContent WHERE event_id = ?";
        $edu_stmt = $conn->prepare($edu_sql);
        $edu_stmt->bind_param("i", $event_id);
        $edu_stmt->execute();
        $edu_result = $edu_stmt->get_result();
    } else {
        echo "<p>Event not found.</p>";
        exit;
    }

    $stmt->close();
} else {
    echo "<p>No event selected.</p>";
    exit;
}

// Handle booking
if (isset($_POST['book_event'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO BookedEvents (event_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);
    if ($stmt->execute()) {
        echo "<p class='success'>Event booked successfully!</p>";
    } else {
        echo "<p class='error'>Error booking event. Please try again.</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/event-details.css">
</head>
<body>

<main>
    <section class="event-details-container">
        <div class="event-card">
            <div class="event-image-container">
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
            </div>
            <div class="event-details">
                <h2><?php echo $event['event_name']; ?></h2>
                <p><?php echo $event['description']; ?></p>
                <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?></p>
                <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
              
                <form action="" method="POST">
                    <button type="submit" name="book_event" class="book-button">Book Event</button>
                </form>
            </div>
        </div>

        <section class="educational-content-section">
            <h3>Educational Content</h3>
            <?php
            if ($edu_result->num_rows > 0) {
                while ($edu = $edu_result->fetch_assoc()) {
                    echo "<div class='educational-content'>
                            <h4>{$edu['title']}</h4>
                            <p>{$edu['content']}</p>
                            <p><strong>Upload date:</strong> {$edu['upload_date']}</p>
                          </div>";
                }
            } else {
                echo "<p>No educational content available for this event.</p>";
            }
            ?>
        </section>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

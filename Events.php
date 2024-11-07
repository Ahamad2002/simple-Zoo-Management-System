<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/events.css">
</head>
<body>

<?php include('php/header.php'); ?>

<main>
    <section class="events-section">
        <h2>Upcoming Events</h2>
        
        <!-- Search Bar -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search events..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
        
        <div class="events-grid">
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'zoo');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch events from the database
            $searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
            $sql = "SELECT event_id, event_name, image FROM Events WHERE event_name LIKE ? ORDER BY event_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $imagePath = $row['image'] ? $row['image'] : 'images/default-image.png'; // Default image if none provided
                    echo "<div class='event-card'>
                            <a href='eventdetails.php?event_id={$row['event_id']}'>
                                <img src='{$imagePath}' alt='{$row['event_name']}'>
                                <h3>{$row['event_name']}</h3>
                            </a>
                          </div>";
                }
            } else {
                echo "<p>No events found.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

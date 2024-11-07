<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <?php include('php/header.php'); ?>

    <main>
        <!-- Hero Section with Carousel -->
        <section class="hero">
            <div class="hero-carousel">
                <div class="slide active">
                    <img src="images/zoo-banner1.png" alt="ZooParc Welcome">
                    <div class="caption">
                        <h1>Welcome to ZooParc Zoological Park</h1>
                        <p>Home to over 2,000 animals from 200 species</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="images/zoo-banner2.png" alt="Explore Wildlife">
                    <div class="caption">
                        <h1>Explore the Wonders of Wildlife</h1>
                        <p>Discover our diverse animal habitats</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="images/zoo-banner3.jpg" alt="Join Our Community">
                    <div class="caption">
                        <h1>Join Our Community</h1>
                        <p>Become a volunteer and make a difference</p>
                    </div>
                </div>
            </div>
            <div class="carousel-controls">
                <span class="prev">&#10094;</span>
                <span class="next">&#10095;</span>
            </div>
        </section>

        <!-- Featured Animals Section -->
        <section class="featured-animals">
            <h2>Featured Animals</h2>
            <div class="animal-grid">
                <div class="animal-card">
                    <img src="images/panda.jpeg" alt="Giant Panda">
                    <h3>Giant Panda</h3>
                </div>
                <div class="animal-card">
                    <img src="images/lion.jpg" alt="Lion">
                    <h3>Lion</h3>
                </div>
                <div class="animal-card">
                    <img src="images/elephant.jpg" alt="Elephant">
                    <h3>Asian Elephant</h3>
                </div>
                <div class="animal-card">
                    <img src="images/orangutan.jpeg" alt="Orangutan">
                    <h3>Orangutan</h3>
                </div>
                
        </section>

        <!-- Upcoming Events Section -->
        <section class="upcoming-events">
            <h2>Upcoming Events</h2>
            <div class="events-list">
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'zoo');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch the latest 4 upcoming events
                $sql = "SELECT event_id, event_name, event_date, image FROM Events ORDER BY event_date ASC LIMIT 4";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Check if the event has an image, else use a default image
                        $imagePath = $row['image'] ? $row['image'] : 'images/default-image.png';
                        echo '
                        <div class="event-card">
                            <img src="' . $imagePath . '" alt="' . $row['event_name'] . '" class="event-image">
                            <h3><a href="eventdetails.php?event_id=' . $row['event_id'] . '">' . $row['event_name'] . '</a></h3>
                            <p>Date: ' . date("F j, Y", strtotime($row['event_date'])) . '</p>
                        </div>';
                    }
                } else {
                    echo "<p>No upcoming events found.</p>";
                }

                $conn->close();
                ?>
            </div>
            <div class="view-all">
                <a href="events.php" class="view-all-button">View All Events</a>
            </div>
        </section>

        <!-- Visitor Information Section -->
        <section class="visitor-info">
            <h2>Visitor Information</h2>
            <div class="info-grid">
                <div class="info-card">
                    <img src="images/opening-hours.jpeg" alt="Opening Hours">
                    <h3>Opening Hours</h3>
                    <p>Monday - Sunday: 9:00 AM - 5:00 PM</p>
                </div>
                <div class="info-card">
                    <img src="images/ticket-prices.jpeg" alt="Ticket Prices">
                    <h3>Ticket Prices</h3>
                    <p>Adults: $20 | Children: $10 | Seniors: $15</p>
                </div>
                <div class="info-card">
                    <img src="images/directions.jpeg" alt="Directions">
                    <h3>Directions</h3>
                    <p>Find us at 123 Wildlife Avenue, ZooCity</p>
                </div>
            </div>
        </section>

    </main>

    <?php include('php/footer.php'); ?>

    <script src="js/scripts.js"></script>
</body>
</html>

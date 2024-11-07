<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/manage-events.css">
    <script>
        function confirmDelete(eventId) {
            if (confirm("Are you sure you want to delete this event?")) {
                document.getElementById('delete-form-' + eventId).submit();
            }
        }

        function editEvent(eventId, eventName, description, eventDate, startTime, endTime, location, createdBy, imagePath) {
            document.getElementById('event_id').value = eventId;
            document.getElementById('event_name').value = eventName;
            document.getElementById('description').value = description;
            document.getElementById('event_date').value = eventDate;
            document.getElementById('start_time').value = startTime;
            document.getElementById('end_time').value = endTime;
            document.getElementById('location').value = location;
            document.getElementById('created_by').value = createdBy;
            document.getElementById('submit-button').value = "Update Event";
            document.getElementById('image-upload-container').style.display = "none";
        }

        function addEvent() {
            document.getElementById('event_id').value = '';
            document.getElementById('event_name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('event_date').value = '';
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';
            document.getElementById('location').value = '';
            document.getElementById('created_by').value = '';
            document.getElementById('image').value = '';
            document.getElementById('submit-button').value = "Add Event";
            document.getElementById('image-upload-container').style.display = "block";
        }
    </script>
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="event-management">
        <h2>Manage Events</h2>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'zoo');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_event_id'])) {
                // Delete event
                $delete_event_id = $_POST['delete_event_id'];
                $sql = "DELETE FROM Events WHERE event_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $delete_event_id);
                $stmt->execute();
                echo "<p class='success'>Event deleted successfully.</p>";
            } else {
                // Handle image upload
                $imagePath = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $imageName = basename($_FILES['image']['name']);
                    $imagePath = "images/" . $imageName;
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                }

                // Add or update event
                $event_id = $_POST['event_id'];
                $event_name = $_POST['event_name'];
                $description = $_POST['description'];
                $event_date = $_POST['event_date'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $location = $_POST['location'];
                $created_by = $_POST['created_by'];

                if ($event_id) {
                    // Update existing event
                    if ($imagePath) {
                        $sql = "UPDATE Events SET event_name = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, location = ?, created_by = ?, image = ? WHERE event_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssisi", $event_name, $description, $event_date, $start_time, $end_time, $location, $created_by, $imagePath, $event_id);
                    } else {
                        $sql = "UPDATE Events SET event_name = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, location = ?, created_by = ? WHERE event_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssii", $event_name, $description, $event_date, $start_time, $end_time, $location, $created_by, $event_id);
                    }
                    echo "<p class='success'>Event updated successfully.</p>";
                } else {
                    // Add new event
                    $sql = "INSERT INTO Events (event_name, description, event_date, start_time, end_time, location, created_by, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssis", $event_name, $description, $event_date, $start_time, $end_time, $location, $created_by, $imagePath);
                    echo "<p class='success'>Event added successfully.</p>";
                }

                $stmt->execute();
            }
        }

        // Fetch events from the database
        $sql = "SELECT e.*, u.name AS volunteer_name FROM Events e JOIN Users u ON e.created_by = u.user_id";
        $result = $conn->query($sql);

        // Fetch volunteers from the database for selection
        $volunteers_sql = "SELECT user_id, name FROM Users WHERE role = 'volunteer'";
        $volunteers_result = $conn->query($volunteers_sql);
        $volunteers = [];
        while($volunteer = $volunteers_result->fetch_assoc()) {
            $volunteers[] = $volunteer;
        }
        ?>

        <!-- Event Form -->
        <form action="manage-events.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="event_id" id="event_id">
            <label for="event_name">Event Name:</label>
            <input type="text" name="event_name" id="event_name" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="3" required></textarea>
            <label for="event_date">Event Date:</label>
            <input type="date" name="event_date" id="event_date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>
            <label for="created_by">Assign Volunteer:</label>
            <select name="created_by" id="created_by" required>
                <option value="">Select Volunteer</option>
                <?php foreach($volunteers as $volunteer): ?>
                    <option value="<?php echo $volunteer['user_id']; ?>"><?php echo $volunteer['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <div id="image-upload-container">
                <label for="image">Upload Image:</label>
                <input type="file" name="image" id="image">
            </div>
            <input type="submit" id="submit-button" value="Add Event">
        </form>

        <!-- Events Table -->
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Event Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Location</th>
                    <th>Volunteer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $imagePath = $row['image'] ? $row['image'] : 'images/default-image.png'; // Default image if none provided
                        echo "<tr>
                                <td><img src='{$imagePath}' alt='{$row['event_name']}' width='100'></td>
                                <td>{$row['event_name']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['event_date']}</td>
                                <td>{$row['start_time']}</td>
                                <td>{$row['end_time']}</td>
                                <td>{$row['location']}</td>
                                <td>{$row['volunteer_name']}</td>
                                <td>
                                    <button type='button' onclick='editEvent({$row['event_id']}, \"{$row['event_name']}\", \"{$row['description']}\", \"{$row['event_date']}\", \"{$row['start_time']}\", \"{$row['end_time']}\", \"{$row['location']}\", \"{$row['created_by']}\", \"{$row['image']}\")' class='edit-btn'>✎</button>
                                    <form id='delete-form-{$row['event_id']}' action='manage-events.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='delete_event_id' value='{$row['event_id']}'>
                                        <button type='button' onclick='confirmDelete({$row['event_id']})' class='delete-btn'>🗑️</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No events found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        $conn->close();
        ?>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

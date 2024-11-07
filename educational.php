<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Educational Content - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/manage-educational.css">
    <script>
        function confirmDelete(contentId) {
            if (confirm("Are you sure you want to delete this content?")) {
                document.getElementById('delete-form-' + contentId).submit();
            }
        }

        function editContent(contentId, eventId, title, content) {
            document.getElementById('content_id').value = contentId;
            document.getElementById('event_id').value = eventId;
            document.getElementById('title').value = title;
            document.getElementById('content').value = content;
            document.getElementById('submit-button').value = "Update Content";
        }

        function addContent() {
            document.getElementById('content_id').value = '';
            document.getElementById('event_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('content').value = '';
            document.getElementById('submit-button').value = "Add Content";
        }
    </script>
</head>
<body>
<?php include('php/volunteer-header.php'); ?>
<main>
    <section class="educational-content-management">
        <h2>Manage Educational Content</h2>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'zoo');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_content_id'])) {
                // Delete content
                $delete_content_id = $_POST['delete_content_id'];
                $sql = "DELETE FROM EducationalContent WHERE content_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $delete_content_id);
                $stmt->execute();
                echo "<p class='success'>Content deleted successfully.</p>";
            } else {
                // Add or update content
                $content_id = $_POST['content_id'];
                $event_id = $_POST['event_id'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                $uploaded_by = 1; // Replace with the logged-in user's ID

                if ($content_id) {
                    // Update existing content
                    $sql = "UPDATE EducationalContent SET event_id = ?, title = ?, content = ? WHERE content_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("issi", $event_id, $title, $content, $content_id);
                    echo "<p class='success'>Content updated successfully.</p>";
                } else {
                    // Add new content
                    $sql = "INSERT INTO EducationalContent (event_id, title, content, uploaded_by) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("issi", $event_id, $title, $content, $uploaded_by);
                    echo "<p class='success'>Content added successfully.</p>";
                }

                $stmt->execute();
            }
        }

        // Fetch educational content from the database
        $sql = "SELECT e.*, ev.event_name FROM EducationalContent e JOIN Events ev ON e.event_id = ev.event_id";
        $result = $conn->query($sql);
        ?>

        <!-- Educational Content Form -->
        <form action="educational.php" method="POST">
            <input type="hidden" name="content_id" id="content_id">
            <label for="event_id">Event:</label>
            <select name="event_id" id="event_id" required>
                <option value="">Select Event</option>
                <?php
                // Fetch events from the database for selection
                $events_sql = "SELECT event_id, event_name FROM Events";
                $events_result = $conn->query($events_sql);
                while($event = $events_result->fetch_assoc()) {
                    echo "<option value='{$event['event_id']}'>{$event['event_name']}</option>";
                }
                ?>
            </select>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="5" required></textarea>
            <input type="submit" id="submit-button" value="Add Content">
        </form>

        <!-- Educational Content Table -->
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['event_name']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['content']}</td>
                                <td>
                                    <button type='button' onclick='editContent({$row['content_id']}, {$row['event_id']}, \"{$row['title']}\", \"{$row['content']}\")' class='edit-btn'>Edit</button>
                                    <form id='delete-form-{$row['content_id']}' action='educational.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='delete_content_id' value='{$row['content_id']}'>
                                        <button type='button' onclick='confirmDelete({$row['content_id']})' class='delete-btn'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No educational content found.</td></tr>";
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

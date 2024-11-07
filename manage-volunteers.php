<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Volunteers - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/manage-volunteers.css">
    <script>
        function confirmAction(action, volunteerId) {
            let message = (action === 'approve') 
                ? "Are you sure you want to approve this volunteer?" 
                : "Are you sure you want to reject this volunteer?";
            
            if (confirm(message)) {
                document.getElementById(action + '-form-' + volunteerId).submit();
            }
        }
    </script>
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="volunteer-management">
        <h2>Manage Volunteers</h2>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Areas of Interest</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'zoo');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM Volunteers";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['contact_number']}</td>
                                <td>{$row['areas_of_interest']}</td>
                                <td>
                                    <form id='approve-form-{$row['volunteer_id']}' action='manage-volunteers.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='volunteer_id' value='{$row['volunteer_id']}'>
                                        <input type='hidden' name='action' value='approve'>
                                        <button type='button' onclick='confirmAction(\"approve\", {$row['volunteer_id']})' class='approve-btn'>Approve</button>
                                    </form>
                                    <form id='reject-form-{$row['volunteer_id']}' action='manage-volunteers.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='volunteer_id' value='{$row['volunteer_id']}'>
                                        <input type='hidden' name='action' value='reject'>
                                        <button type='button' onclick='confirmAction(\"reject\", {$row['volunteer_id']})' class='reject-btn'>Reject</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No volunteer requests found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $volunteer_id = $_POST['volunteer_id'];
            $action = $_POST['action'];

            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'zoo');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($action == 'approve') {
                // Get volunteer data
                $sql = "SELECT * FROM Volunteers WHERE volunteer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $volunteer_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $volunteer = $result->fetch_assoc();

                if ($volunteer) {
                    // Insert into Users table
                    $name = $volunteer['name'];
                    $email = $volunteer['email'];
                    $password = $volunteer['password'];
                    $role = 'volunteer';

                    $sql = "INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $name, $email, $password, $role);

                    if ($stmt->execute()) {
                        // Delete from Volunteers table
                        $sql = "DELETE FROM Volunteers WHERE volunteer_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $volunteer_id);
                        $stmt->execute();
                        echo "<p class='success'>Volunteer approved successfully.</p>";
                    } else {
                        echo "<p class='error'>Error approving volunteer. Please try again.</p>";
                    }
                }
            } elseif ($action == 'reject') {
                // Delete from Volunteers table
                $sql = "DELETE FROM Volunteers WHERE volunteer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $volunteer_id);
                if ($stmt->execute()) {
                    echo "<p class='success'>Volunteer rejected successfully.</p>";
                } else {
                    echo "<p class='error'>Error rejecting volunteer. Please try again.</p>";
                }
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/manage-users.css">
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }

        function editUser(userId, name, email, role) {
            document.getElementById('user_id').value = userId;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;

            // Hide the password field and label when editing
            document.getElementById('password-label').style.display = 'none';
            document.getElementById('password').style.display = 'none';
            
            document.getElementById('submit-button').value = "Update User";
        }

        function addUser() {
            // Clear form and reset for new user addition
            document.getElementById('user_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('role').value = 'visitor';

            // Show the password field and label for new user creation
            document.getElementById('password-label').style.display = 'block';
            document.getElementById('password').style.display = 'block';
            document.getElementById('password').value = ''; // Clear password field
            
            document.getElementById('submit-button').value = "Add User";
        }
    </script>
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="user-management">
        <h2>Manage Users</h2>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'zoo');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_user_id'])) {
                // Delete user
                $delete_user_id = $_POST['delete_user_id'];
                $sql = "DELETE FROM Users WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $delete_user_id);
                $stmt->execute();
                echo "<p class='success'>User deleted successfully.</p>";
            } else {
                // Add or update user
                $user_id = $_POST['user_id'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                
                if ($user_id) {
                    // Update existing user
                    $sql = "UPDATE Users SET name = ?, email = ?, role = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $name, $email, $role, $user_id);
                    echo "<p class='success'>User updated successfully.</p>";
                } else {
                    // Add new user
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $sql = "INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $name, $email, $password, $role);
                    echo "<p class='success'>User added successfully.</p>";
                }

                $stmt->execute();
            }
        }

        // Fetch users from the database
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        ?>

        <!-- User Form -->
        <form action="Manageuser.php" method="POST">
            <input type="hidden" name="user_id" id="user_id">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="admin">Admin</option>
                <option value="volunteer">Volunteer</option>
                <option value="visitor">Visitor</option>
            </select>
            <label for="password" id="password-label">Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" id="submit-button" value="Add User">
        </form>

        <!-- Users Table -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <button type='button' onclick='editUser({$row['user_id']}, \"{$row['name']}\", \"{$row['email']}\", \"{$row['role']}\")' class='edit-btn'>Edit</button>
                                    <form id='delete-form-{$row['user_id']}' action='Manageuser.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='delete_user_id' value='{$row['user_id']}'>
                                        <button type='button' onclick='confirmDelete({$row['user_id']})' class='delete-btn'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found.</td></tr>";
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

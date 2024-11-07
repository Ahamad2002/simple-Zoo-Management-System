<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/Contact.css">
    <script>
        function checkLogin(event) {
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                // User is not logged in
                echo 'var loggedIn = false;';
            } else {
                // User is logged in
                echo 'var loggedIn = true;';
            }
            ?>
            if (!loggedIn) {
                event.preventDefault(); // Prevent form submission
                if (confirm("You must be logged in to send a message. Would you like to log in now?")) {
                    window.location.href = "login.php";
                }
            }
        }
    </script>
</head>
<body>

<?php include('php/header.php'); ?>

<main>
    <section class="contact-container">
        <h2>Contact Us</h2>
        <p>If you have any questions, feel free to reach out to us using the form below.</p>

        <form action="contact.php" method="POST" onsubmit="checkLogin(event)">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <input type="submit" name="submit" value="Send Message">
        </form>

        <?php
        if (isset($_POST['submit'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit();
            }

            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            $user_id = $_SESSION['user_id'];

            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'zoo');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO ContactInquiries (user_id, name, email, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $user_id, $name, $email, $message);

            if ($stmt->execute()) {
                echo "<p class='success'>Thank you for contacting us! We'll get back to you soon.</p>";
            } else {
                echo "<p class='error'>There was an error submitting your message. Please try again later.</p>";
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

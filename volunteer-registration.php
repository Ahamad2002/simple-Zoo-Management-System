<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/volunteer-registration.css">
</head>
<body>

<?php include('php/header.php'); ?>

<main>
    <section class="registration-container">
        <h2>Volunteer Registration</h2>
        <p>Join us and make a difference by becoming a volunteer at ZooParc Zoological Park.</p>

        <form action="volunteer-registration.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number">

            <label for="areas_of_interest">Areas of Interest:</label>
            <textarea id="areas_of_interest" name="areas_of_interest" rows="4"></textarea>

            <input type="submit" name="register" value="Register">
        </form>

        <?php
        if (isset($_POST['register'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $contact_number = $_POST['contact_number'];
            $areas_of_interest = $_POST['areas_of_interest'];

            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'zoo');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO Volunteers (name, email, password, contact_number, areas_of_interest) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $password, $contact_number, $areas_of_interest);

            if ($stmt->execute()) {
                echo "<p class='success'>Thank you for registering as a volunteer! We'll get in touch with you soon.</p>";
            } else {
                echo "<p class='error'>There was an error with your registration. Please try again later.</p>";
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

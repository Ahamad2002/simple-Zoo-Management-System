<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<main>
    <section class="registration-container">
        <div class="logo-container">
            <img src="images/logo.jpg" alt="ZooParc Logo" class="zoo-logo">
            <h1>ZooParc Zoological Park</h1>
        </div>
        <section class="registration-form">
            <h2>User Registration</h2>
            <form action="register.php" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" name="register" value="Register">
            </form>
            <p class="login-prompt">Already have an account? <a href="login.php" class="login-link">Login</a></p>
        </section>
    </section>
</main>

<?php
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'visitor'; 

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'zoo');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO Users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

</body>
</html>

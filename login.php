<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<main>
    <section class="login-container">
        <div class="logo-container">
            <img src="images/logo.jpg" alt="ZooParc Logo" class="zoo-logo">
            <h1>ZooParc Zoological Park</h1>
        </div>
        <section class="login-form">
            <h2>User Login</h2>
            <?php
            session_start();

            $error = '';

            if (isset($_POST['login'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'zoo');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM Users WHERE email='$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['role'] = $row['role'];

                        // Redirect based on user role
                        if ($row['role'] === 'admin') {
                            header("Location: adminindex.php");
                        } elseif ($row['role'] === 'volunteer') {
                            header("Location: volunteerindex.php");
                        } else {
                            header("Location: index.php");
                        }
                        exit;
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "No user found with that email.";
                }

                $conn->close();
            }

            if (!empty($error)) {
                echo "<p class='error'>$error</p>";
            }
            ?>
            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" name="login" value="Login">
            </form>
            <p class="signup-prompt">Don't have an account? <a href="register.php" class="signup-link">Sign up</a></p>
        </section>
    </section>
</main>

</body>
</html>

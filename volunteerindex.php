<?php
session_start();

// Check if the user is logged in as a volunteer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/volunteer.css">
</head>
<body>

<?php include('php/volunteer-header.php'); ?>

<main>
    <section class="welcome-section">
        <h2>Welcome, Volunteer!</h2>
        <p>Thank you for your dedication to helping ZooParc Zoological Park. Below are some tools and resources to help you manage your volunteer activities.</p>
    </section>

    <section class="volunteer-options">
        <div class="option-card">
            <h3><a href="schedule.php">Your Schedule</a></h3>
            <p>View and manage your upcoming volunteer schedule and duties.</p>
        </div>
        <div class="option-card">
            <h3><a href="educational.php">Educational Content</a></h3>
            <p>Access and manage educational materials related to your volunteer activities.</p>
        </div>
       
        <div class="option-card">
            <h3><a href="resources.php">Volunteer Resources</a></h3>
            <p>Access resources, guidelines, and information to help you in your volunteer role.</p>
        </div>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

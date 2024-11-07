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
    <title>Volunteer Resources - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/resources.css">
</head>
<body>

<?php include('php/volunteer-header.php'); ?>

<main>
    <section class="resources-section">
        <h2>Volunteer Resources</h2>
        <p>Access resources, guidelines, and information to help you in your volunteer role.</p>

        <div class="resources-list">
            <div class="resource-card">
                <img src="images/guideline.png" alt="Guidelines">
                <h3>Guidelines for Volunteers</h3>
                <p>Follow these comprehensive guidelines to ensure a safe, enjoyable, and productive experience for all volunteers and visitors.</p>
                <ul>
                    <li>Always wear your volunteer badge.</li>
                    <li>Follow the zoo's safety protocols at all times.</li>
                    <li>Be punctual and reliable for your shifts.</li>
                    <li>Respect the animals and their habitats.</li>
                    <li>Assist visitors with a friendly attitude.</li>
                </ul>
            </div>

            <div class="resource-card">
                <img src="images/training.png" alt="Training Materials">
                <h3>Training Materials</h3>
                <p>Enhance your knowledge and skills with these training materials designed to help you excel in your volunteer role.</p>
                <ul>
                    <li>Animal care basics and handling techniques.</li>
                    <li>Emergency response procedures.</li>
                    <li>Effective communication with visitors.</li>
                    <li>Understanding the zoo's mission and values.</li>
                </ul>
            </div>

            <div class="resource-card">
                <img src="images/safety.png" alt="Safety Procedures">
                <h3>Safety Procedures</h3>
                <p>Learn about the safety procedures to follow while working at the ZooParc, ensuring the well-being of both volunteers and visitors.</p>
                <ul>
                    <li>Fire and emergency evacuation plans.</li>
                    <li>First aid and medical emergency protocols.</li>
                    <li>Handling dangerous or unexpected situations.</li>
                    <li>Proper use of safety equipment.</li>
                </ul>
            </div>

            <div class="resource-card">
                <img src="images/handbook.png" alt="Volunteer Handbook">
                <h3>Volunteer Handbook</h3>
                <p>The volunteer handbook contains all the necessary information you need to know, from zoo policies to volunteer expectations.</p>
                <ul>
                    <li>Zoo policies and procedures.</li>
                    <li>Volunteer rights and responsibilities.</li>
                    <li>Contact information for volunteer coordinators.</li>
                    <li>Details about volunteer rewards and recognition.</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

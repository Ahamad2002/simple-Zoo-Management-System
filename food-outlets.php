<?php

$conn = new mysqli('localhost', 'root', '', 'zoo');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM FoodOutlets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Outlets - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/food-outlets.css">
</head>
<body>

<?php include('php/header.php'); ?>

<main>
    <section class="food-outlets-section">
        <h2>Our Food Outlets</h2>
        <div class="food-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $imagePath = $row['image'] ? $row['image'] : 'images/default-food.png'; // Default image if none provided
                    echo "<div class='food-card'>
                            <img src='{$imagePath}' alt='{$row['food_name']}'>
                            <div class='food-details'>
                                <h3>{$row['food_name']}</h3>
                                <p>{$row['description']}</p>
                                <p class='price'>\${$row['price']}</p>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No food items available at the moment.</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>
</main>

<?php include('php/footer.php'); ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Outlets - ZooParc Zoological Park</title>
    <link rel="stylesheet" href="css/manage-food-outlets.css">
    <script>
        function confirmDelete(foodId) {
            if (confirm("Are you sure you want to delete this food item?")) {
                document.getElementById('delete-form-' + foodId).submit();
            }
        }

        function editFood(foodId, foodName, description, price, imagePath) {
            document.getElementById('food_id').value = foodId;
            document.getElementById('food_name').value = foodName;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('current-image').src = imagePath;
            document.getElementById('image-upload').style.display = 'none'; // Hide the image upload input when editing
            document.getElementById('submit-button').value = "Update Food Item";
        }

        function addFood() {
            document.getElementById('food_id').value = '';
            document.getElementById('food_name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('price').value = '';
            document.getElementById('image').value = '';
            document.getElementById('image-upload').style.display = 'block'; // Show the image upload input when adding
            document.getElementById('submit-button').value = "Add Food Item";
        }
    </script>
</head>
<body>

<?php include('php/admin-header.php'); ?>

<main>
    <section class="food-management">
        <h2>Manage Food Outlets</h2>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'zoo');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_food_id'])) {
                // Delete food item
                $delete_food_id = $_POST['delete_food_id'];
                $sql = "DELETE FROM FoodOutlets WHERE food_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $delete_food_id);
                $stmt->execute();
                echo "<p class='success'>Food item deleted successfully.</p>";
            } else {
                // Handle image upload
                $imagePath = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $imageName = basename($_FILES['image']['name']);
                    $imagePath = "images/" . $imageName;
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                }

                // Add or update food item
                $food_id = $_POST['food_id'];
                $food_name = $_POST['food_name'];
                $description = $_POST['description'];
                $price = $_POST['price'];

                if ($food_id) {
                    // Update existing food item
                    if ($imagePath) {
                        $sql = "UPDATE FoodOutlets SET food_name = ?, description = ?, price = ?, image = ? WHERE food_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssdsi", $food_name, $description, $price, $imagePath, $food_id);
                    } else {
                        $sql = "UPDATE FoodOutlets SET food_name = ?, description = ?, price = ? WHERE food_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssdi", $food_name, $description, $price, $food_id);
                    }
                    echo "<p class='success'>Food item updated successfully.</p>";
                } else {
                    // Add new food item
                    $sql = "INSERT INTO FoodOutlets (food_name, description, price, image) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssds", $food_name, $description, $price, $imagePath);
                    echo "<p class='success'>Food item added successfully.</p>";
                }

                $stmt->execute();
            }
        }

        // Fetch food items from the database
        $sql = "SELECT * FROM FoodOutlets";
        $result = $conn->query($sql);
        ?>

        <!-- Food Item Form -->
        <form action="managefood-outlets.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="food_id" id="food_id">
            <label for="food_name">Food Name:</label>
            <input type="text" name="food_name" id="food_name" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="3" required></textarea>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
            <div id="image-upload">
                <label for="image">Upload Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <img id="current-image" src="#" alt="Current Image" style="display:none; width: 100px; height: 100px; margin-bottom: 10px;">
            <input type="submit" id="submit-button" value="Add Food Item">
        </form>

        <!-- Food Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $imagePath = $row['image'] ? $row['image'] : 'images/default-image.png';
                        echo "<tr>
                                <td>{$row['food_name']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['price']}</td>
                                <td><img src='{$imagePath}' alt='{$row['food_name']}' style='width: 100px; height: 100px;'></td>
                                <td>
                                    <button type='button' onclick='editFood({$row['food_id']}, \"{$row['food_name']}\", \"{$row['description']}\", {$row['price']}, \"{$imagePath}\")' class='edit-btn'>✎ Edit</button>
                                    <form id='delete-form-{$row['food_id']}' action='managefood-outlets.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='delete_food_id' value='{$row['food_id']}'>
                                        <button type='button' onclick='confirmDelete({$row['food_id']})' class='delete-btn'>🗑️ Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No food items found.</td></tr>";
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

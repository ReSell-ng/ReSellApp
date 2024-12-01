<?php
session_start(); // Ensure the session is started
include('../includes/config.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to add a product.");
    }

    // Collect form data
    $title = mysqli_real_escape_string($mysql, $_POST['title']);
    $category = mysqli_real_escape_string($mysql, $_POST['category']);
    $description = mysqli_real_escape_string($mysql, $_POST['description']);
    $price = floatval($_POST['price']);
    $seller_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']); // Create a unique file name
        $imagePath = "../uploads/images/" . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Insert the data into the database, including seller_id
            $sql = "INSERT INTO products (title, description, price, category_id, image_url, seller_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysql->prepare($sql);
            $stmt->bind_param('ssdisi', $title, $description, $price, $category, $imagePath, $seller_id);

            if ($stmt->execute()) {
                echo "Product added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "No image uploaded or an error occurred.";
    }
} else {
    echo "Invalid request method.";
}

// Close the connection
$mysql->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Item</title>
    <link rel="stylesheet" href="../assets/styles/additem.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="header">
            <div class="nav">
                <button id="hamburger" class="iconbtn">
                    <i class="fa-duotone fa-solid fa-bars"></i>
                </button>
                <div class="logo">
                    <img src="../assets/images/logo6.png" alt="logo" class="logoresize">
                </div>
                <button id="profilebtn" class="iconbtn">
                    <i class="fa-regular fa-user"></i>
                </button>
                <button id="cartbtn" class="iconbtn">
                    <i class="fa-regular fa-cart-shopping"></i>
                </button>
            </div>
            <form action="additem.php" method="POST" enctype="multipart/form-data">
                <div class="searchsection">
                    <div class="searchbar">
                        <input type="text" placeholder="Search everything...">
                        <span class="separator">|</span>
                        <select name="category" id="categories">
                            <option value="" disabled selected>Select a category</option>
                            <?php
                            // Fetch categories from the database
                            include('../includes/config.php');
                            $sql = "SELECT id, category_name FROM categories";
                            $result = $mysql->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No categories available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button id="searchbtn" class="iconbtn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="additemctn">
            <h1>Add New Item for Sale</h1>
            <form action="additem.php" method="POST" enctype="multipart/form-data" id="addItemForm">
                <label for="title">Product Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="category">Category</label>
                <select name="category" id="categories" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php
                    // Fetch categories again here to populate the select options
                    $sql = "SELECT id, category_name FROM categories";
                    $result = $mysql->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No categories available</option>";
                    }
                    ?>
                </select>

                <label for="description">Product Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" required>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Add Item</button>
            </form>
        </div>
    </main>
</body>
</html>
<?php
header("Content-Type: application/json"); // Set content type to JSON
require '../includes/config.php'; // Include database connection
session_start();

// Ensure the request method is valid
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle API requests based on query parameters
    $response = [];

    if (isset($_GET['product_id'])) {
        // Fetch a specific product by ID
        $product_id = intval($_GET['product_id']);
        
        // Query to fetch product details
        $sql = "SELECT 
                    products.*, 
                    categories.category_name AS category_name, 
                    users.first_name, 
                    users.last_name, 
                    users.email, 
                    users.phone_number 
                FROM 
                    products
                LEFT JOIN 
                    categories ON products.category_id = categories.id
                LEFT JOIN 
                    users ON products.seller_id = users.id
                WHERE 
                    products.id = ?";
        
        $stmt = $mysql->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $response = $result->fetch_assoc();
            } else {
                http_response_code(404);
                $response['error'] = "Product not found.";
            }
        } else {
            http_response_code(500);
            $response['error'] = "Database error: " . $mysql->error;
        }
    } elseif (isset($_GET['category_id'])) {
        // Fetch products by category
        $category_id = intval($_GET['category_id']);
        
        // Query to fetch products by category
        $sql = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $mysql->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            $response = $products;
        } else {
            http_response_code(500);
            $response['error'] = "Database error: " . $mysql->error;
        }
    } else {
        // Fetch all products
        $sql = "SELECT * FROM products";
        $result = $mysql->query($sql);
        
        if ($result) {
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            $response = $products;
        } else {
            http_response_code(500);
            $response['error'] = "Database error: " . $mysql->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle adding a new product (similar to additem.php)
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized. Please log in."]);
        exit;
    }
    
    $title = mysqli_real_escape_string($mysql, $_POST['title']);
    $category = mysqli_real_escape_string($mysql, $_POST['category']);
    $description = mysqli_real_escape_string($mysql, $_POST['description']);
    $price = floatval($_POST['price']);
    $seller_id = $_SESSION['user_id']; // Logged-in user's ID

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $imagePath = "../uploads/images/" . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Insert product into database
            $sql = "INSERT INTO products (title, description, price, category_id, image_url, seller_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysql->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ssdisi', $title, $description, $price, $category, $imagePath, $seller_id);
                if ($stmt->execute()) {
                    http_response_code(201);
                    echo json_encode(["message" => "Product added successfully!"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to add product: " . $stmt->error]);
                }
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to prepare SQL: " . $mysql->error]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to upload image."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "No image uploaded or an error occurred."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method."]);
}

// Close the database connection
$mysql->close();
?>
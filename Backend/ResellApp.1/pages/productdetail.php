<?php
session_start();
require '../includes/config.php';

// Check if the product ID is passed and valid
if (!isset($_GET['product_id']) || !filter_var($_GET['product_id'], FILTER_VALIDATE_INT)) {
    die("Invalid Product ID.");
}

$product_id = intval($_GET['product_id']);

// Fetch product details
$sql = "
    SELECT 
        products.*, 
        categories.category_name, 
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

if (!$stmt) {
    die("SQL preparation failed: " . $mysql->error);
}

$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Fetch categories for search dropdown
$categories_result = $mysql->query("SELECT * FROM categories");
if (!$categories_result) {
    die("Error fetching categories: " . $mysql->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="../assets/styles/productdetail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="header">
            <div class="nav">
                <button id="hamburger" class="iconbtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="logo">
                    <img src="../assets/images/logo6.png" alt="FUTO RESELL" class="logoresize">
                </div>
                <a href="profile.php" id="profilebtn" class="iconbtn">
                    <i class="fa-regular fa-user"></i>
                </a>
                <a href="cart.php" id="cartbtn" class="iconbtn">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
            </div>
            <form action="categories.php" method="GET">
    <div class="searchsection">
        <div class="searchbar">
            <input 
                type="text" 
                name="search_query" 
                placeholder="Search everything..." 
                value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>"
            >
            <span class="separator">|</span>
            <select name="category">
                <option value="allcategories">All categories</option>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <option 
                        value="<?php echo htmlspecialchars($category['id']); ?>" 
                        <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button id="searchbtn" class="iconbtn">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</form>
        </div>

        <div class="productdetailscontainer">
            <div class="productimages">
                <img id="mainImage" src="  <?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                <div class="thumbnailgallery">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Thumbnail">
                </div>
            </div>
            <div class="productinfo">
                <h1 id="productname"><?php echo htmlspecialchars($product['title']); ?></h1>
                <p id="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                <p id="productDescription"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="price" id="productPrice">$<?php echo htmlspecialchars($product['price']); ?></p>

                <!-- Conditional Add to Cart Button -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button id="addToCartBtn" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                <?php else: ?>
                    <p>Please <a href="login.php">log in</a> to add to cart.</p>
                <?php endif; ?>
            </div>
            <div class="seller-info">
                <h3>Seller Information</h3>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <p id="sellerName"><?php echo htmlspecialchars($product['first_name'] . ' ' . $product['last_name']); ?></p>
                    <p id="contactInfo"><?php echo htmlspecialchars($product['email']); ?> | <?php echo htmlspecialchars($product['phone_number']); ?></p>
                <?php else: ?>
                    <p>Please <a href="login.php">log in</a> to view seller information.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId })
            }).then(response => response.json()).then(data => {
                alert(data.message);
            });
        }
    </script>
</body>
</html>
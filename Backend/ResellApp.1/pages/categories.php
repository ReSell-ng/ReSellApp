<?php
include('../includes/config.php');

// Get search query and category from the request
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$search_category = isset($_GET['category']) ? trim($_GET['category']) : 'allcategories';

// Build the base SQL query
$query = "
    SELECT products.*, categories.category_name 
    FROM products 
    INNER JOIN categories ON products.category_id = categories.id 
    WHERE 1=1
";
$params = [];
$types = "";

// Add search query filter if provided
if (!empty($search_query)) {
    $query .= " AND (products.title LIKE ? OR products.description LIKE ?)";
    $search_term = "%$search_query%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

// Add category filter if provided and not "allcategories"
if (!empty($search_category) && $search_category !== 'allcategories') {
    $query .= " AND categories.category_name = ?";
    $params[] = $search_category;
    $types .= "s";
}

// Prepare and execute the statement
$stmt = $mysql->prepare($query);
if ($stmt === false) {
    die("SQL Error: " . $mysql->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Fetch all categories for dropdown
$categories_result = $mysql->query("SELECT * FROM categories");
if ($categories_result === false) {
    die("Error fetching categories: " . $mysql->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FUTO RESELL</title>
    <link rel="stylesheet" href="../assets/styles/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="nav">
            <button id="hamburger" class="iconbtn">
                <i class="fa-solid fa-bars"></i>
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
        <form action="categories.php" method="GET">
            <div class="searchsection">
                <div class="searchbar">
                    <input 
                        type="text" 
                        name="search_query" 
                        placeholder="Search everything..." 
                        value="<?php echo htmlspecialchars($search_query); ?>">
                    <span class="separator">|</span>
                    <select name="category">
                        <option value="allcategories">All categories</option>
                        <?php while ($category = $categories_result->fetch_assoc()): ?>
                            <option 
                                value="<?php echo htmlspecialchars($category['category_name']); ?>" 
                                <?php echo ($search_category === $category['category_name']) ? 'selected' : ''; ?>>
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

    <!-- Products Section -->
    <div class="products-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <?php
                $image_path = "" . $product['image_url'];
                if (!file_exists($image_path)) {
                    $image_path = '../uploads/images/default_image.jpg'; // Use default image if not found
                }
                ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($image_path); ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="productdetail.php?product_id=<?php echo htmlspecialchars($product['id']); ?>">
                        <button>View Details</button>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
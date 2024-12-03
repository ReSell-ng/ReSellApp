<?php
session_start();
require '../../../includes/config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../pages/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Fetch products in the user's cart
$sql = "
    SELECT 
        p.id AS product_id, 
        p.title, 
        p.description, 
        p.price, 
        p.image_url 
    FROM 
        cart c 
    JOIN 
        products p ON c.product_id = p.id 
    WHERE 
        c.user_id = ?";
$stmt = $mysql->prepare($sql);

if (!$stmt) {
    die("SQL preparation failed: " . $mysql->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching cart items: " . $stmt->error);
}

$cart_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../../../assets/styles/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <nav class="nav">
            <div class="logo">
                <img class="logoresize" src="../../../assets/images/logo.png" alt="Logo">
            </div>
            <button class="iconbtn">
                <i class="fa-solid fa-user"></i>
            </button>
        </nav>
    </header>

    <main>
        <!-- Search Bar -->
        <form action="orders.php" method="GET">
            <div class="searchsection">
                <div class="searchbar">
                    <input 
                        type="text" 
                        name="search_query" 
                        placeholder="Search your orders..." 
                        value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>"
                    >
                </div>
                <button id="searchbtn" class="iconbtn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>

        <!-- Orders Section -->
        <div class="orders-container">
            <h2 class="section-header">My Orders</h2>
            <?php if (empty($cart_items)): ?>
                <p>No products in your cart yet. <a href="../../categories.php">Start shopping</a>!</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-card">
                        <a href="productdetail.php?product_id=<?php echo htmlspecialchars($item['product_id']); ?>">
                            <img src="../../../<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="price">$<?php echo htmlspecialchars($item['price']); ?></p>
                        </a>
                        <button>View Details</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
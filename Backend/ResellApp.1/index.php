<?php
session_start(); // Start session

// Include database connection
include('includes/config.php');

// Check if the user is logged in
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Get the search query and category from the GET request
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$search_category = isset($_GET['category']) ? trim($_GET['category']) : 'allcategories';

// Build the base SQL query
$sql = "SELECT * FROM products";
$params = [];
$types = "";

// If there is a search query, add it to the SQL query
if (!empty($search_query)) {
    $sql .= " WHERE name LIKE ?";
    $search_term = "%$search_query%";
    $params[] = $search_term;
    $types .= "s"; // Specify the type of the parameter (string)
}

// If there is a category filter, modify the query
if ($search_category !== 'allcategories') {
    if (!empty($params)) {
        $sql .= " AND category_id = (SELECT id FROM categories WHERE category_name = ?)";
    } else {
        $sql .= " WHERE category_id = (SELECT id FROM categories WHERE category_name = ?)";
    }
    $params[] = $search_category;
    $types .= "s"; // Specify the type of the parameter (string)
}

// Prepare and execute the query
$stmt = $mysql->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row; // Store the entire product row
    }
}

// Fetch categories for the dropdown menu
$categories_sql = "SELECT category_name FROM categories";
$categories_result = $mysql->query($categories_sql);
$categories = [];
if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row['category_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FUTO RESELL</title>
    <link rel="stylesheet" href="assets/styles/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <!-- Header Section -->
        <div ="navbar">
            <div class="upnav">
                <div class="navbar-left">
                    <button id="hamburger" class="iconbtn"><i class="fa-solid fa-bars"></i></button>
                </div>
                <div class="navbar-center">
                    <div class="logo">
                        <img src="assets/images/logo6.png" alt="ReSell logo" class="logoreize">
                    </div>
                </div>
                <div class="navbar-right">
                    <button id="profilebtn" class="iconbtn">
                    <i class="fa-regular fa-user"></i>
                    <?php if ($username): ?>
                        <span style="color: green;"><?php echo htmlspecialchars($username); ?></span>
                    <?php endif; ?>
                </button>
                <button id="cartbtn" class="iconbtn"><i class="fa-regular fa-cart-shopping"></i></button>
                </div>
            </div>
        </div>
        <div class="down-nav">
            <!--Search bar -->
            <form action="pages/categories.php" method="POST">
                <div class="searchsection">
                    <div class="searchbar">
                        <input type="text" name="search_query" placeholder="Search everything...">
                        <span class="separator">|</span>
                        <select name="category">
                            <option value="allcategories">All categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" id="searchbtn" class="iconbtn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>
        <!-- Sidebar Menu -->
        <div id="sidemenu">
            <div class="menuhead">
                <button class="iconbtn" id="backbtn"><i class="fa-solid fa-chevron-left"></i></button>
            </div>
            <hr>
            <p>My Categories</p>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li><a href="pages/categories.php?category=<?php echo urlencode($category); ?>">
    <?php echo htmlspecialchars($category); ?>
</a></li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <div class="menubuttom">
                <?php if ($username): ?>
                    <a href="pages/additem.php"><button id="sellbtn">Sell on ReSell</button></a>
                <?php else: ?>
                    <a href="auth/signup.php"><button id="sellbtn">Sell on ReSell</button></a>
                <?php endif; ?>
                <a href="contact.php">Contact Support</a>
            </div>
        </div>
        <div id="profilemenu">
            <div class="profilemenuhead">
                <button class="iconbtn" id="profileclosebtn">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            </div>
            <hr>
            <div class="profilemenubody">
                <?php if ($username): ?>
                    <h3>Welcome, <?php echo htmlspecialchars($username); ?>!</h3>
                    <a href="pages/dashboard.php">
                        <button type="button" id="dashboardbtn">Go to Dashboard</button>
                    </a>
                <?php else: ?>
                    <h3>Please sign into your account</h3>
                    <a href="auth/signin.php">
                        <button type="submit" id="signinbtn">Sign in</button>
                    </a>
                <?php endif; ?>
            </div>
            <div class="profilemenubottom">
                <?php if (!$username): ?>
                    <h3>Don&alpot have an account?</h3>
                    <a href="auth/signup.php">
                        <button type="button" id="signupbtn">Sign up</button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="bodycontent">
            <div class="marquee">
        <div class="marqueetext">
            Get groceries & more delivered in 2 hours or less around FUTO...
        </div>
    </div>
     <section class="hero">
            <div class="hero-content">
                <div id="hero-heading">
                    <h1>Discover Amazing Deals on Pre-Loved Items</h1>
                </div>
                <div id="hero-subheading">
                    <p>
                        Find great items from trusted sellers, right in your community
                    </p>
                </div>

            </div>
        </section>
    <div class="slidectn">
        <img src="assets/images/content1.mp4" alt="" class="contents">
        <img src="assets/images/content4.mp4" alt="" class="contents">
    </div>
    <div class="widget">
        <div class="topwidget">
            <button id="sellwidget" class="leftwidget">
                <div class="leftwidget">
                    <p>
                        SELL ON RESELL
                        <i class="fa-solid fa-angle-down" class="iconresize"></i>
                    </p>
                </div>
            </button>
            <div class="rightwidget">
                <button id="accessorieswidget" class="uprightwidget">
                    <div class="uprightwidget">
                        Accessories
                        <img src="assets/images/airpod.png" alt="" class="airpod">
                    </div>
                </button>
                <button id="laptopwidget" class="downrightwidget">
                    <div class="downrightwidget">
                        Laptops
                        <img src="assets/images/laptop.png" alt="" class="airpod">
                    </div>
                </button>
            </div>
        </div>
        <button id="foodwidget" class="buttomwidget">
            <div class="buttomwidget">
                Food
                <img src="assets/images/food.png" alt="" class="airpod">
            </div>
        </button>
    </div>
    <!-- Product Listing -->
        <div class="product-listing">
            <?php if (empty($products)) {
    echo "No products found.";
    exit; // Stop script execution
}
?>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="uploads/<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                        <a href="pages/productdetail.php?product_id=<?php echo htmlspecialchars($product['id']); ?>">
                            <button>View Details</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

        

        <!-- Footer -->
        <div class="footer">
            <div class="upfooter">
                <div class="leftupfooter">
                    <p>About Us</p>
                    <ul>
                        <li><a href="#">About ReSell</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Escrow T&C</a></li>
                    </ul>
                </div>
                <div class="rightupfooter">
                    <p>Help</p>
                    <ul>
                        <li><a href="mailto:support@resell.com">Support@ReSell.com</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                </div>
            </div>
            <div class="downfooter">
        <div class="leftdownfooter">
            <p>
                Resources
            </p>
            <ul>
                <li><a href="">Pricing and Fees</a></li>
                <li><a href="">Boost Product</a></li>
                <li><a href="Escrow on ReSell"></a></li>
            </ul>
        </div>
        <div class="middledownfooter">
            <p>
                Ads Region
            </p>
            <ul>
                <li><a href="">Eziobodo</a></li>
                <li><a href="">Umuchima</a></li>
                <li><a href="">Ihiagwa</a></li>
            </ul>
        </div>
        <div class="rightdownfooter">
            <p>
                Stay connected
            </p>
        </div>
    </div>
            <div class="footernote">
                <p>ReSell Africa &copy; 2024. All Rights Reserved</p>
            </div>
        </div>
    </main>
    <script src="assets/JS/landing.js"></script>
</body>
</html>
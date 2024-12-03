<?php
// Start session and include necessary files
session_start();
require_once '../../includes/config.php'; // Database connection
require_once '../../includes/functions.php'; // Additional security functions

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php'); // Redirect to login page if not logged in
    exit();
}

// Fetch user details using MySQLi
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $mysql->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Redirect to login if user details are not found
    if (!$user) {
        header('Location: ../../auth/login.php');
        exit();
    }
} else {
    die("Error preparing statement: " . $mysql->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styles/txt.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <h2>Account...</h2>
        </div>
        
        <div id="top">
            <h3>
                <i class="fas fa-user-circle" style="font-size:36px"></i>
                Welcome <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name'], ENT_QUOTES, 'UTF-8') ?>
            </h3>
        </div>
        
        <div id="content">
            <h4 id="my-account">MY ACCOUNT</h4>
            <p id="manage-product">
                <i class="fas fa-box" style="margin-right: 10px;"></i>
                <a href="manage_products.php">Manage Products</a>
            </p>
            <p id="likes">
                <i class="fas fa-heart" style="margin-right: 10px;"></i>
                <a href="profile/likes.php">Likes</a>
            </p>
            <p id="notification">
                <i class="fas fa-bell" style="margin-right: 10px;"></i>
                <a href="profile/notifications.php">Notifications</a>
            </p>
            <p id="orders">
                <i class="fas fa-shopping-cart" style="margin-right: 10px;"></i>
                <a href="profile/orders.php">Orders</a>
            </p>
            <p id="settings">
                <i class="fas fa-cog" style="margin-right: 10px;"></i>
                <a href="profile/settings.php">Settings</a>
            </p>
            <p id="support">
                <i class="fas fa-headset" style="margin-right: 10px;"></i>
                <a href="profile/support.php">ReSell Support</a>
            </p>
            <p id="logout">
                <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i>
                <a href="../../auth/logout.php">Log out</a>
            </p>
        </div>
    </div>
</body>
</html>
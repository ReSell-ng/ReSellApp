<?php
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    // For API requests, respond with JSON
    if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized access. Please log in.']);
        exit;
    }
    // Redirect for regular requests
    header('Location: ../auth/login.php');
    exit;
}

// Include configuration and database setup
include_once '../includes/config.php';

// Handle API requests
if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
    header('Content-Type: application/json');

    // Fetch user-specific data (e.g., notifications, adverts, etc.)
    $user_id = $_SESSION['user_id'];

    // Fetch example notifications and adverts
    $notifications = [];
    $adverts = [];

    // Query for notifications
    $notif_query = "SELECT * FROM notifications WHERE user_id = ?";
    $stmt = $mysql->prepare($notif_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notif_result = $stmt->get_result();
    while ($row = $notif_result->fetch_assoc()) {
        $notifications[] = $row;
    }

    // Query for adverts
    $adverts_query = "SELECT * FROM adverts WHERE user_id = ?";
    $stmt = $mysql->prepare($adverts_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $adverts_result = $stmt->get_result();
    while ($row = $adverts_result->fetch_assoc()) {
        $adverts[] = $row;
    }

    // Return data as JSON
    echo json_encode([
        'user' => [
            'id' => $user_id,
            'first_name' => $_SESSION['first_name'] ?? null,
            'last_name' => $_SESSION['last_name'] ?? null,
            'email' => $_SESSION['email'] ?? null,
        ],
        'notifications' => $notifications,
        'adverts' => $adverts
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FUTO RESELL</title>
    <link rel="stylesheet" href="../assets/styles/landing.css">
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../auth/logout.php"><i class="fa-regular fa-user"></i></a>
                    <?php else: ?>
                        <i class="fa-regular fa-user"></i>
                    <?php endif; ?>
                </button>
                <button id="cartbtn" class="iconbtn">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="additem.php"><i class="fa-regular fa-cart-shopping"></i></a>
                    <?php else: ?>
                        <i class="fa-regular fa-cart-shopping"></i>
                    <?php endif; ?>
                </button>
            </div>
        </div>

        <div id="sidemenu">
            <div class="menuhead">
                <button class="iconbtn" id="backbtn">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            </div>
            <hr>
            <p>Log out</p>
            <hr>
            <div class="menubottom">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../auth/logout.php">
                        <button id="sellbtn">Log out</button>
                    </a>
                <?php else: ?>
                    <a href="../auth/signup.php">
                        <button id="sellbtn">Sell on ReSell</button>
                    </a>
                <?php endif; ?>
                <a href="">Contact Support</a>
            </div>
        </div>

        <div class="content">
            <h1>Welcome, 
                <?php
                echo isset($_SESSION['first_name'], $_SESSION['last_name']) 
                ? htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) 
                : 'User'; 
                ?>
            </h1>

            <div class="dashboard-options">
                <a href="notifications.php">Notifications</a>
                <a href="my_adverts.php">My Adverts</a>
                <a href="profile_settings.php">Profile Settings</a>
            </div>
        </div>
    </main>
</body>
</html>
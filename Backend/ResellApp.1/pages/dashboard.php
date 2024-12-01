<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
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
                        <!-- Redirect to Dashboard if logged in -->
                        <a href="../auth/logout.php"><i class="fa-regular fa-user"></i></a>
                    <?php else: ?>
                        <!-- Redirect to Sign In if not logged in -->
                        <i class="fa-regular fa-user"></i>
                    <?php endif; ?>
                </button>
            
                <button id="cartbtn" class="iconbtn">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Redirect to Add Item if logged in -->
                        <a href="additem.php"><i class="fa-regular fa-cart-shopping"></i></a>
                    <?php else: ?>
                        <!-- You could also redirect to a sign-in page if not logged in, if desired -->
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
                <!-- Redirect based on login status -->
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
                <!-- Add more options as needed -->
            </div>
        </div>
     </main>

</body>
</html>
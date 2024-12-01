<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php if (isset($_SESSION['user_id'])): ?>
    <?php header('Location: ../index.php'); ?>
    <?php else: ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/styles/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="header">
            <a href="../index.html">
                <div class="logo">
                    <img src="../assets/images/logo6.png" alt="logo">
                </div>
            </a>
        </div>
        <hr>
        <div class="registration">
            <div class="signupwithgoogle">
                <h2>Create an Account</h2>
                <p>
                    Join thousands of users buying and selling products on ReSell daily.
                </p>
                <button id="googlesignin">
                    <img src="../assets/images/googlelogo.PNG" alt="gshs"> <p>
                        Continue with Google
                    </p>
                </button>
            </div>
            <div class="line-with-text">
                <span>OR</span>
            </div>
            
            <form action="register.php" method="POST" id="createAccountForm">
                <div class="input-container">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <label for="firstName">First Name</label>
                </div>
                <div class="input-container">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <label for="lastName">Last Name</label>
                </div>
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <label for="emailAddress">Email Address</label>
                </div>
                <div class="input-container">
                    <input type="number" name="phone_number" placeholder="Phone Number" required>
                    <label for="phoneNumber">Phone Number</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <button type="button" id="toggle-password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                <div class="input-container">
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                    <label for="confirmPassword">Confirm Password</label>
                    <button type="button" id="toggle-confirmpassword">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    <!-- Message for password match -->
                    <span id="password-match-message"></span>
                </div>
                <div class="privacypolicy">
                    <p>
                        By creating an account, you agree to our <a href="">Privacy Policy,</a> <a href=""> Terms and Conditions</a>
                    </p>
                </div>
                <div class="registerbtn">
                    <button type="submit" id="createaccount">Create Account</button>
                </div>
            </form>
            <div class="footer">
                <p>
                    Already have an account? <a href="signin.php"> Sign in</a>
                </p>
            </div>
        </div>
    </main>
    <script src="../assets/JS/signup.js"></script>
</body>
</html>

<?php endif; ?>

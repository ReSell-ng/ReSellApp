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
    <link rel="stylesheet" href="../assets/styles/signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body
    <main>
        <div class="header">
            <a href="../index.html">
                <div class="logo">
                    <img src="../assets/images/logo6.png" alt="logo">
                </div>
            </a>
        </div>
        <hr>
            <div class="signinwithgoogle">
                <h2>Log into your account</h2>
                <button id="googlesignin">
                    <img src="../assets/images/googlelogo.PNG" alt="gshs"> <p>
                        Continue with Google
                    </p>
                </button>
            </div>
            <div class="line-with-text">
                <span>OR</span>
            </div>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form action="login.php" method="POST" id="signInForm">
                <div class="input-container">
                    <input type="text" name="username" id="userName" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <button type="button" id="toggle-password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                <div class="signinbtn">
                    <button type="submit" id="loginbtn">Log In</button>
                </div>
            </form>
            <div class="footer">
                <p>
                    Dont have an account? <a href="signup.php"> Sign up</a>
                </p>
            </div>
    </main>
    <script src="../assets/JS/signin.js"></script>
</body>
</html>
<?php
// Display error message if login fails
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<?php endif;?>
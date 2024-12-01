<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/styles/forgotpassword.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="header">
            <a href="landing-page.html">
                <div class="logo">
                    <img src="../assets/images/logo6.png" alt="logo">
                </div>
            </a>
        </div>
        <hr>
            <div class="resetpassword">
                <h2>Password Reset</h2>
                <p>Enter your email, and well send you a link to reset your password</p>
            </div>
            <form action="">
                <div class="input-container">
                    <input type="email" id="emailAddress" placeholder="Email address" required>
                    <label for="emailaddress">Email address</label>
                </div>
                <div class=resetbtn>
                    <button id="sendResetLink">
                        Send reset link
                    </button>
                </div>
            </form>
    </main>
    <script src="../assets/JS/signin.js"></script>
</body>
</html>
<?php
session_start();  // Start the session at the very top of the file

include_once '../includes/config.php';  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $username = $mysql->real_escape_string($_POST['username']);
    $email = $mysql->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Clean the password to convert special characters for safety
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    // Initialize user object
    $user = null;

    // Check if the input is either a valid username or email
    if (!empty($username)) {
        // If the username is provided, check if it exists
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("s", $username);
    } else if (!empty($email)) {
        // If the email is provided, check if it exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("s", $email);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);

        // Store user information in the session
        $_SESSION['user_id'] = $user['id'];  // Store user ID in session
        $_SESSION['username'] = $user['username'];  // Store username in session

        // Close session writing to ensure data is saved
        session_write_close();

        // Redirect to the home page or dashboard
        header('Location: ../index.php');
        exit; // Stop further script execution after the redirect
    } else {
        // Login failed, show error message
        $error_message = 'Invalid username, email, or password.';
    }
}
?>

<!-- Your HTML form would follow here -->
<?php
include_once '../includes/config.php';  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $first_name = htmlspecialchars($mysql->real_escape_string($_POST['first_name']), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars($mysql->real_escape_string($_POST['last_name']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($mysql->real_escape_string($_POST['email']), ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($mysql->real_escape_string($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $phone_number = htmlspecialchars($mysql->real_escape_string($_POST['phone_number']), ENT_QUOTES, 'UTF-8');

    // Check if the phone number already exists
    $stmt = $mysql->prepare("SELECT * FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_user = $result->fetch_assoc();

    if ($existing_user) {
        // Phone number already exists, show an error message
        echo "Phone number already exists. Please use a different phone number.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = $mysql->prepare("INSERT INTO users (first_name, last_name, email, username, password_hash, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $hashed_password, $phone_number);

        if ($stmt->execute()) {
            // Registration successful, redirect
            header('Location: signin.php');
            exit;
        } else {
            // Error inserting data
            echo "Error: " . $stmt->error;
        }
    }
}
?>
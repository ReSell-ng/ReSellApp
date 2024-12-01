<?php
include('../includes/config.php');  // Include database connection
session_start();  // Start the session

// Response structure
$response = [
    "status" => "error",
    "message" => "Invalid request.",
];

// Check the type of operation
$operation = isset($_GET['operation']) ? $_GET['operation'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'register') {
    // Handle user registration
    $first_name = htmlspecialchars($mysql->real_escape_string($_POST['first_name']), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars($mysql->real_escape_string($_POST['last_name']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($mysql->real_escape_string($_POST['email']), ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($mysql->real_escape_string($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $phone_number = htmlspecialchars($mysql->real_escape_string($_POST['phone_number']), ENT_QUOTES, 'UTF-8');

    // Check if phone number already exists
    $stmt = $mysql->prepare("SELECT * FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = "Phone number already exists. Please use a different phone number.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $mysql->prepare("INSERT INTO users (first_name, last_name, email, username, password_hash, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $hashed_password, $phone_number);

        if ($stmt->execute()) {
            $response['status'] = "success";
            $response['message'] = "Registration successful.";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'login') {
    // Handle user login
    $username_or_email = $mysql->real_escape_string($_POST['username_or_email']);
    $password = $_POST['password'];

    // Check if input is a username or email
    $query = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? 
        "SELECT * FROM users WHERE email = ?" : 
        "SELECT * FROM users WHERE username = ?";

    $stmt = $mysql->prepare($query);
    $stmt->bind_param("s", $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $response['status'] = "success";
        $response['message'] = "Login successful.";
        $response['data'] = [
            "user_id" => $user['id'],
            "username" => $user['username']
        ];
    } else {
        $response['message'] = "Invalid username, email, or password.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $operation === 'logout') {
    // Handle user logout
    session_destroy();
    session_unset();

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    $response['status'] = "success";
    $response['message'] = "Logout successful.";
} else {
    // Invalid request
    $response['message'] = "Invalid operation.";
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
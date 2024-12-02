<?php
header('Content-Type: application/json'); // Set response type to JSON
session_start(); // Start the session

include_once '../includes/config.php'; // Include the database connection

$response = ['success' => false, 'message' => '']; // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON payload

    $username = $mysql->real_escape_string($data['username'] ?? '');
    $email = $mysql->real_escape_string($data['email'] ?? '');
    $password = $data['password'] ?? '';

    // Sanitize password
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    if (!empty($username) || !empty($email)) {
        // Prepare query based on input type
        if (!empty($username)) {
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("s", $username);
        } else {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("s", $email);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true); // Regenerate session ID for security

            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session

            $response['success'] = true;
            $response['message'] = 'Login successful';
            $response['data'] = [
                'user_id' => $user['id'],
                'username' => $user['username']
            ];
        } else {
            $response['message'] = 'Invalid username, email, or password.';
        }
    } else {
        $response['message'] = 'Please provide a username or email.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); // Return response as JSON
exit;
?>
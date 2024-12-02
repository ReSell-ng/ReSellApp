<?php
header('Content-Type: application/json'); // Set response type to JSON
include_once '../includes/config.php'; // Include the database connection

$response = ['success' => false, 'message' => '']; // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Collect and sanitize form data
    $first_name = htmlspecialchars($mysql->real_escape_string($data['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars($mysql->real_escape_string($data['last_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($mysql->real_escape_string($data['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($mysql->real_escape_string($data['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $password = $data['password'] ?? '';
    $phone_number = htmlspecialchars($mysql->real_escape_string($data['phone_number'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($phone_number)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    // Check if the phone number already exists
    $stmt = $mysql->prepare("SELECT id FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'Phone number already exists. Please use a different phone number.';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = $mysql->prepare("INSERT INTO users (first_name, last_name, email, username, password_hash, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $hashed_password, $phone_number);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Registration successful!';
        } else {
            $response['message'] = 'Error: ' . $stmt->error;
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); // Return response as JSON
exit;
?>
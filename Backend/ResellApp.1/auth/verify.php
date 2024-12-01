<?php
require_once('../include/config.php');  // Database connection

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);

    // Look for the user with this token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Token is valid, verify the user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update the user's status to verified
        $updateStmt = $pdo->prepare("UPDATE users SET verified = 1, verification_token = NULL WHERE id = :id");
        $updateStmt->bindParam(':id', $user['id']);
        if ($updateStmt->execute()) {
            echo "Your account has been verified! You can now log in.";
        } else {
            echo "Error verifying your account.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
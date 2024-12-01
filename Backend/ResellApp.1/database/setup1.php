<?php
$db = new PDO('sqlite:/data/data/com.github.ziracms.pocketphp/files/ResellApp.1/database/futo.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Adjust the path to your config file

try {
    // Add the admin column if it doesn't exist
    $db->exec("ALTER TABLE users ADD COLUMN is_admin INTEGER DEFAULT 0");

    // Make an existing user an admin
    $username = 'Sam2812'; // Replace with the actual username
    $query = $db->prepare("UPDATE users SET is_admin = 1 WHERE username = ?");
    $query->execute([$username]);

    echo "Setup complete: User $username is now an admin.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
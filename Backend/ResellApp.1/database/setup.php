<?php
include_once '../includes/config.php';

try {
    $sqlFilePath = realpath(__DIR__ . '/setup.sql');
    
    if ($sqlFilePath && file_exists($sqlFilePath)) {
        $sqlCommands = file_get_contents($sqlFilePath);
        $pdo->exec($sqlCommands);
        echo "Database setup completed successfully!";
    } else {
        throw new Exception("setup.sql file not found in database directory.");
    }
} catch (Exception $e) {
    die("Error setting up database: " . $e->getMessage());
}
?>

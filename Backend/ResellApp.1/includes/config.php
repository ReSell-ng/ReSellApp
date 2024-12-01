<?php
// config.php
$servername = "0.0.0.0"; // Database server
$username = "root";        // Your MySQL username
$password = "root";            // Your MySQL password (if any)
$dbname = "Reselldb";   // Your database name

// Create connection
$mysql = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
else {
    //echo "Connection Successful";
}
?>
<?php
session_start();
// Destroy the session
session_destroy();
session_unset();
// Clear session cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}
// Redirect to a different page with cache control headers
header('Location: ../index.php');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
exit;
?>
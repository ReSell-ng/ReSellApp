<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Please log in to add to cart.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id']);
$user_id = intval($_SESSION['user_id']);

$sql = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
$stmt = $mysql->prepare($sql);
$stmt->bind_param('ii', $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Product added to cart.']);
} else {
    echo json_encode(['message' => 'Error adding to cart.']);
}
?>
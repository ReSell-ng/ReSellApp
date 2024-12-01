<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Please log in to manage your cart.']);
    exit;
}

$action = $_GET['action'] ?? null; // Determines the API action (view, update, delete)
$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($_SESSION['user_id']);

switch ($action) {
    case 'view':
        // View all items in the cart for the logged-in user
        $sql = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }

        echo json_encode(['cart' => $cart_items]);
        break;

    case 'update':
        // Update quantity of a specific item in the cart
        $cart_id = intval($data['cart_id']);
        $quantity = intval($data['quantity']);

        if ($quantity <= 0) {
            echo json_encode(['message' => 'Quantity must be greater than zero.']);
            exit;
        }

        $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('iii', $quantity, $cart_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Cart updated successfully.']);
        } else {
            echo json_encode(['message' => 'Error updating cart.']);
        }
        break;

    case 'delete':
        // Remove a specific item from the cart
        $cart_id = intval($data['cart_id']);

        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('ii', $cart_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Item removed from cart.']);
        } else {
            echo json_encode(['message' => 'Error removing item from cart.']);
        }
        break;

    default:
        echo json_encode(['message' => 'Invalid action.']);
}
?>
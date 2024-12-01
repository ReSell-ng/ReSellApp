<?php
header('Content-Type: application/json');
include('../includes/config.php');

$action = isset($_GET['action']) ? $_GET['action'] : 'list_all'; // Default action is to list all products
$data = json_decode(file_get_contents('php://input'), true);

$response = [];

try {
    switch ($action) {
        case 'by_category':
            // Fetch products by category
            $category_name = isset($data['category']) ? $data['category'] : null;

            if (!$category_name) {
                echo json_encode(['message' => 'Category name is required.']);
                exit;
            }

            $stmt = $mysql->prepare("
                SELECT products.* 
                FROM products 
                INNER JOIN categories ON products.category_id = categories.id 
                WHERE categories.category_name = ?
            ");
            $stmt->bind_param("s", $category_name);
            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);

            $response = ['products' => $products];
            break;

        case 'search':
            // Search products by query and optional category
            $search_query = isset($data['search_query']) ? $data['search_query'] : '';
            $search_category = isset($data['category']) ? $data['category'] : 'allcategories';

            if (empty($search_query)) {
                echo json_encode(['message' => 'Search query is required.']);
                exit;
            }

            $query = "
                SELECT products.* 
                FROM products 
                INNER JOIN categories ON products.category_id = categories.id 
                WHERE products.title LIKE ?";
            $params = ["%$search_query%"];

            if ($search_category !== 'allcategories') {
                $query .= " AND categories.category_name = ?";
                $params[] = $search_category;
            }

            $stmt = $mysql->prepare($query);
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);

            $response = ['products' => $products];
            break;

        case 'list_all':
        default:
            // List all products
            $stmt = $mysql->prepare("SELECT * FROM products");
            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);

            $response = ['products' => $products];
            break;
    }
} catch (Exception $e) {
    $response = ['message' => 'Error: ' . $e->getMessage()];
}

echo json_encode($response);
?>
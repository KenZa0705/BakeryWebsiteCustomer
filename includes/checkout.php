<?php
// Include necessary files
require_once 'dbh.inc.php';  // Ensure your database connection is established

// Fetch POST data
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve customer info (assuming it's stored in the session)
session_start();
$customer_id = $_SESSION['user']['customer_id'];

// Prepare the SQL for inserting into the orders table
$total_price = $data['totalPrice'];
$status = 'Processing';

// Start a transaction to ensure data integrity
// Updated PHP to reduce quantity_available after checkout
$pdo->beginTransaction();
try {
    // Insert into the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->execute([$customer_id, $total_price, $status]);

    // Get the last inserted order ID
    $order_id = $pdo->lastInsertId();

    // Insert into the order_details table and update product stock
    foreach ($data['items'] as $item) {
        // Find the product ID by product name
        $product_stmt = $pdo->prepare("SELECT product_id, quantity_available FROM products WHERE name = ?");
        $product_stmt->execute([$item['name']]);
        $product = $product_stmt->fetch(PDO::FETCH_ASSOC);
        $product_id = $product['product_id'];
        $quantity_available = $product['quantity_available'];

        if ($product_id) {
            // Insert into order_details
            $details_stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $details_stmt->execute([$order_id, $product_id, $item['quantity']]);

            // Update the quantity_available in the products table
            $new_quantity_available = $quantity_available - $item['quantity'];
            $update_product_stmt = $pdo->prepare("UPDATE products SET quantity_available = ? WHERE product_id = ?");
            $update_product_stmt->execute([$new_quantity_available, $product_id]);
        }
    }

    // Commit the transaction
    $pdo->commit();

    // Send a success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback if there's an error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message'=> $e->getMessage()]);
}

?>
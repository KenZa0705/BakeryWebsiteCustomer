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
$pdo->beginTransaction();
try {
    // Insert into the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->execute([$customer_id, $total_price, $status]);

    // Get the last inserted order ID
    $order_id = $pdo->lastInsertId();

    // Insert into the order_details table
    foreach ($data['items'] as $item) {
        // Find the product ID by product name
        $product_stmt = $pdo->prepare("SELECT product_id FROM products WHERE name = ?");
        $product_stmt->execute([$item['name']]);
        $product_id = $product_stmt->fetchColumn();

        if ($product_id) {
            // Insert into order_details
            $details_stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $details_stmt->execute([$order_id, $product_id, $item['quantity']]);
        }
    }

    // Commit the transaction
    $pdo->commit();

    // Send a success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback if there's an error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


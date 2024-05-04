<?php
require_once 'dbh.inc.php'; 

// Fetch POST data
$data = json_decode(file_get_contents('php://input'), true);

// get info of logged in customer in session
session_start();
$customer_id = $_SESSION['user']['customer_id'];

$total_price = 0;
$status = 'Processing';

// Start a transaction
$pdo->beginTransaction();
try {
    // add to the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->execute([$customer_id, $total_price, $status]);

    // Get the last inserted order ID
    $order_id = $pdo->lastInsertId();

    // Process each item in the order and update the total price
    foreach ($data['items'] as $item) {
        // Find the product details by product name
        $product_stmt = $pdo->prepare("SELECT product_id, quantity_available, price FROM products WHERE name = ?");
        $product_stmt->execute([$item['name']]);
        $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product_id = $product['product_id'];
            $quantity_available = $product['quantity_available'];
            $price = $product['price'];

            // Insert into order_details
            $details_stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $details_stmt->execute([$order_id, $product_id, $item['quantity']]);

            // Update product stock
            $new_quantity_available = $quantity_available - $item['quantity'];
            $update_product_stmt = $pdo->prepare("UPDATE products SET quantity_available = ? WHERE product_id = ?");
            $update_product_stmt->execute([$new_quantity_available, $product_id]);

            // Calculate total price for this item and add it to the order total
            $item_total_price = $price * $item['quantity'];
            $total_price += $item_total_price;

            // Add record in the payment_details table
            $update_payments = $pdo->prepare("INSERT INTO payment_details (order_id, payment_method, payment_status) VALUES (?, ?, ?)");
            $update_payments->execute([$order_id, 'Cash', 'Unpaid']); 
        }
    }

    // Update the total price in the orders table
    $order_total_price = $pdo->prepare("UPDATE orders SET total_price = ? WHERE order_id = ?");
    $order_total_price->execute([$total_price, $order_id]);

    // Commit the transaction
    $pdo->commit();

    // Send a success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback if there's an error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

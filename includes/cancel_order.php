<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'dbh.inc.php';

    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];
    $product = $_POST['product'];

    try {
        $query = "UPDATE orders SET status = 'Canceled' WHERE order_id = :order_id AND status = 'Processing'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $query2 = "UPDATE products SET quantity_available = quantity_available + $quantity WHERE name = '$product'";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute();

        echo "Order " . $order_id . " has been successfully canceled.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>

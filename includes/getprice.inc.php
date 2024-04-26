<?php
require_once "dbh.inc.php";

try {       
    $product = $_POST['productName'];
    $query = 'SELECT price from products WHERE product_id = :product_id;';
    $price = $pdo->query($query)->bindParam(':product_id', $product)->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "". $e->getMessage() ."";
}
<?php
require_once 'dbh.inc.php';

$query = "SELECT name, price, quantity_available FROM products ORDER BY quantity_available DESC";

// Define an array of image paths corresponding to each product
$imagePaths = [
    'Pandesal' => 'image/pandesal.png',
    'Tasty' => 'image/tasty.png',
    'Spanish Bread' => 'image/spanishbread.png',
    'Pan de Coco' => 'image/pandecoco.png',
    'Kababayan' => 'image/kababayan.png',
    'Kalihim' => 'image/kalihim.png',
    'Putok' => 'image/putok.png',
    'Hopia' => 'image/hopia.png',
];

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

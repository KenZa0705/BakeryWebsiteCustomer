<?php
// Include the database connection file
require_once 'dbh.inc.php';

// Query to fetch data from your database
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
    // Add more entries as needed
];

try {
    // Prepare the query
    $stmt = $pdo->prepare($query);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all rows
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any errors
    die("Error: " . $e->getMessage());
}

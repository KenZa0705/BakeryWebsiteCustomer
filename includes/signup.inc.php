<?php
require_once 'dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];

    try {
        // Begin transaction
        $pdo->beginTransaction();
        // Insert the data to customers table
        $query = "INSERT INTO customers (first_name, last_name, phone, email, password, address) VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $pdo->prepare($query);
        // run query
        $stmt->execute([$fname, $lname, $number, $email, $password, $address]);
        // Commit the transaction
        $pdo->commit();
        header("Location: ../index.php?accountcreation=success#Order");
        exit();
    } catch (PDOException $e) {
        // Rollback the transaction if an error occurs
        $pdo->rollBack();
        // Redirect with error message
        header("Location: ../index.php?error=query_failed#Order");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}


<?php
session_start();
require_once 'dbh.inc.php';

// Check if username and password are provided
if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if username exists
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        // User authenticated, store user info in session
        $_SESSION['user'] = $user;
        // Redirect to dashboard
        header("Location: ../menupage.php");
        exit();
    } else {
        // User not found or incorrect password, redirect back to login page with error message
        header("Location: ../login.php?error=Incorrect Email or password");
        exit();
    }
} else {
    // Redirect back to login page if username or password is not provided
    header("Location: ../login.php?error=Username and password are required");
    exit();
}


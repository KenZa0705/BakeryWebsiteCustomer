<?php
session_start();
require_once 'dbh.inc.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if email exists
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
        // Incorrect email or password, use JavaScript alert and history.back()
        $error = 'Incorrect Email or password';
        echo "<script>alert('$error'); history.back();</script>";
        exit();
    }


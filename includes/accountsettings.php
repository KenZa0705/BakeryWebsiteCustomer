<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Retrieve user's information from session
$user = $_SESSION['user'];
?>

<h1>Account Settings</h1>
<h2>Welcome! <?php echo $user['first_name']; ?></h2>
<section>
    <nav>
        <ul>
            <li><a href="../landingpage.php">Update Account</a></li>
            <li>View Orders</li>
            <li><a href="../menupage.php">Shopping Cart</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</section>
<script src="../script.js"></script>
</body>
</html>
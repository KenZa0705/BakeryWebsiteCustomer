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
$customer_id = $_SESSION['user']['customer_id'];
$firstName = $_SESSION['user']['first_name'];
$lastName = $_SESSION['user']['last_name'];
$email = $_SESSION['user']['email'];
$phone = $_SESSION['user']['phone'];
$address = $_SESSION['user']['address'];
?>

<button onclick="openBox('updateAccountDiv')" class="updateButton">Update</button>

<?php
require_once 'includes/dbh.inc.php';

// Check if the product ID exists in the database
$query = "SELECT * FROM customers WHERE customer_id = :customer_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer</title>
</head>

<body>
    <div id="updateAccountDiv" class="updateAccount_Div" style="display: none">
        <div class="update-content">
            <span class="close" onclick="closeBox('updateAccountDiv');">&times;</span>
            <h2 class="UpdateAccount-title">Update Account Details</h2>
            <form action="includes/update_account.php" method="POST" class="updateAccount-content">
                <span class="close" onclick="history.back();">&times;</span>
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name"
                    placeholder="<?php echo $firstName; ?>"><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name"
                    placeholder="<?php echo $lastName; ?>"><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"
                    placeholder="<?php echo $email; ?>"><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone"
                    placeholder="<?php echo $phone; ?>"><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address"
                    placeholder="<?php echo $address; ?>"><br>
                <label for="password">Password:</label>
                <input type="text" id="password" name="password"
                    placeholder="<?php echo $password; ?>"><br>                   
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
<script src="script.js"></script>
</html>



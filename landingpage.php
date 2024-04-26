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

<button onclick="openBox('updateAccountDiv')" class="updateButton">Update</button>

<?php
require_once 'dbh.inc.php';

$customer_id = $user['customer_id'];

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
            <form action="update_account.php" method="POST" class="updateAccount-content">
                <span class="close" onclick="history.back();">&times;</span>
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name"
                    placeholder="<?php echo htmlspecialchars($customer['first_name']); ?>"><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name"
                    placeholder="<?php echo htmlspecialchars($customer['last_name']); ?>"><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"
                    placeholder="<?php echo htmlspecialchars($customer['email']); ?>"><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone"
                    placeholder="<?php echo htmlspecialchars($customer['phone']); ?>"><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address"
                    placeholder="<?php echo htmlspecialchars($customer['address']); ?>"><br>
                <label for="password">Password:</label>
                <input type="text" id="password" name="password"
                    placeholder="<?php echo htmlspecialchars($customer['password']); ?>"><br>                   
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>

</html>


<?php
require_once 'dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];

    // Retrieve the current product details from the database
    $query = "SELECT * FROM customers WHERE customer_id = :customer_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);


    // Initialize arrays to store updated values and placeholders
    $update_values = [];
    $placeholders = [];

    // Check each field for updates and build the query accordingly
    if (!empty($_POST['first_name']) && $_POST['first_name'] != $customer['first_name']) {
        $update_values[] = 'first_name = :first_name';
        $placeholders[':first_name'] = $_POST['first_name'];
    }
    if (!empty($_POST['last_name']) && $_POST['last_name'] != $customer['last_name']) {
        $update_values[] = 'last_name = :last_name';
        $placeholders[':last_name'] = $_POST['last_name'];
    }
    if (!empty($_POST['email']) && $_POST['email'] != $customer['email']) {
        $update_values[] = 'email = :email';
        $placeholders[':email'] = $_POST['email'];
    }

    if (!empty($_POST['phone']) && $_POST['phone'] != $customer['phone']) {
        $update_values[] = 'phone = :phone';
        $placeholders[':phone'] = $_POST['phone'];
    }

    if (!empty($_POST['address']) && $_POST['address'] != $customer['address']) {
        $update_values[] = 'address = :address';
        $placeholders[':address'] = $_POST['address'];
    }
    if (!empty($_POST['password']) && $_POST['password'] != $customer['password']) {
        $update_values[] = 'password = :password';
        $placeholders[':password'] = $_POST['password'];
    }

    // If there are updates, construct and execute the update query
    if (!empty($update_values)) {
        $update_query = "UPDATE customers SET " . implode(', ', $update_values) . " WHERE customer_id = :customer_id";
        $stmt = $pdo->prepare($update_query);
        $placeholders[':customer_id'] = $customer_id;
        $stmt->execute($placeholders);
        echo "Customer updated successfully.";
        header('Location: ../menuPage.php');
    } else {
        echo "No changes were made.";
        header('../menuPage.php');
    }

} else {
    // Redirect to the update form if accessed directly without POST data
    header("Location: ../menuPage.php");
    exit;
}

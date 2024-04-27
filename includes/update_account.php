<?php
require_once 'dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
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
    if (!empty($_POST['first_name']) && $_POST['first_name'] != $firstName) {
        $update_values[] = 'first_name = :first_name';
        $placeholders[':first_name'] = $_POST['first_name'];
    }
    if (!empty($_POST['last_name']) && $_POST['last_name'] != $lastName) {
        $update_values[] = 'last_name = :last_name';
        $placeholders[':last_name'] = $_POST['last_name'];
    }
    if (!empty($_POST['email']) && $_POST['email'] != $email) {
        $update_values[] = 'email = :email';
        $placeholders[':email'] = $_POST['email'];
    }

    if (!empty($_POST['phone']) && $_POST['phone'] != $phone) {
        $update_values[] = 'phone = :phone';
        $placeholders[':phone'] = $_POST['phone'];
    }

    if (!empty($_POST['address']) && $_POST['address'] != $address) {
        $update_values[] = 'address = :address';
        $placeholders[':address'] = $_POST['address'];
    }
    if (!empty($_POST['password']) && $_POST['password'] != $password) {
        $update_values[] = 'password = :password';
        $placeholders[':password'] = $_POST['password'];
    }

    // If there are updates, construct and execute the update query
    if (!empty($update_values)) {
        $update_query = "UPDATE customers SET " . implode(', ', $update_values) . " WHERE customer_id = :customer_id";
        $stmt = $pdo->prepare($update_query);
        $placeholders[':customer_id'] = $customer_id;
        $stmt->execute($placeholders);

        // Refresh the session data with the updated information
        $query = "SELECT * FROM customers WHERE customer_id = :customer_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $updated_customer = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update the session with the new customer data
        session_start();
        $_SESSION['user'] = $updated_customer;

        echo "Customer updated successfully.";
        header('Location: ../menupage.php');
    } else {
        echo "No changes were made.";
        header('../menupage.php');
    }

} else {
    // Redirect to the update form if accessed directly without POST data
    header("Location: ../menupage.php");
    exit;
}

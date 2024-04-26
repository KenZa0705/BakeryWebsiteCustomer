
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
        // Prepare SQL statement
        $query = "INSERT INTO customers (first_name, last_name, phone, email, password, address) VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $pdo->prepare($query);

        // Execute SQL statement
        $stmt->execute([$fname, $lname, $number, $email, $password, $address]);

        // Redirect after successful insertion

        header("Location: ../index.php?accountcreation=success#Order");
        exit();
    } catch (PDOException $e) {
        // Redirect with error message
        header("Location: ../index.php?error=query_failed#Order");
        exit();
    }

} else {
    // Redirect if not a POST request
    header("Location: ../index.php");
    exit();
}
?>
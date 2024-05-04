<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

//Get user info
$user = $_SESSION['user'];
$customer_id = $_SESSION['user']['customer_id'];
$firstName = $_SESSION['user']['first_name'];
$lastName = $_SESSION['user']['last_name'];
$email = $_SESSION['user']['email'];
$phone = $_SESSION['user']['phone'];
$address = $_SESSION['user']['address'];

require_once 'dbh.inc.php';
//get cust info
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
    <title>Account Settings</title>
    <link rel="stylesheet" href="accountsettings.css">
</head>

<body>
<div class="background-above-nav">
    <h1>Account Settings</h1>
    <h2>Welcome! <?php echo $firstName; ?></h2>
</div>
        <nav>
            <ul>
                <li><a onclick="openBox('updateAccountDiv')" class="updateButton">Update</a></li>
                <li><a onclick="openBox('viewOrders')" class="viewOrdersButton">View Orders</a></li>
                <li><a href="../menupage.php">Shopping Cart</a></li>
                <li><a href="logout.php" onclick="promptMessage('Logged Out Successfully')">Log out</a></li>
            </ul>
        </nav>
    <div class="background-below-nav">
    <div id="updateAccountDiv" class="updateAccount_Div" style="display: none">
        <div class="update-content">
            <span class="close" onclick="closeBox('updateAccountDiv');">&times;</span>
            <h2 class="UpdateAccount-title">Update Account Details</h2>
            <form action="update_account.php" method="POST" class="updateAccount-content">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $firstName; ?>"><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $lastName; ?>"><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" placeholder="<?php echo $email; ?>"><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" placeholder="<?php echo $phone; ?>"><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="<?php echo $address; ?>"><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="******"><br>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
    <div id="viewOrders" class="viewOrders" style="display: none;">
        <div class="orders-content">
            <span class="close" onclick="closeBox('viewOrders');">&times;</span>
            <h2 class=viewOrders-title>Orders</h2>
            <div>
                <!-- Button to view cancelable orders -->
                <button onclick="openBox('cancelOrders')" class="cancelButton">Cancel Order</button>
            </div>

            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Date Ordered</th>
                    <th>Price</th>
                    <th>status</th>
                    <th>Products</th>
                    <th>Quantity</th>
                </tr>
                <?php
                require_once 'dbh.inc.php';

                try {
                    $query = "SELECT o.order_id,o.order_date,p.price * od.quantity AS price,
                    o.status,p.name AS product_name,od.quantity
                  FROM order_details AS od
                  JOIN orders AS o ON od.order_id = o.order_id
                  JOIN products AS p ON od.product_id = p.product_id
                  WHERE customer_id = :customer_id
                  ORDER BY order_id DESC;";
                    $orders = $pdo->prepare($query);
                    $orders->bindParam(':customer_id', $customer_id);
                    $orders->execute();
                    $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($orders as $row) {
                        echo "
                            <tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['order_date']}</td>
                                <td>{$row['price']} PHP</td>
                                <td>{$row['status']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                            </tr>";
                    }

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                ?>
            </table>
        </div>
    </div>
    <div id="cancelOrders" class="cancelOrders" style="display: none;">
        <div class="orders-content">
            <span class="close" onclick="closeBox('cancelOrders');">&times;</span>
            <h2 class="cancelOrders-title">Cancelable Orders</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Date Ordered</th>
                    <th>Price</th>
                    <th>status</th>
                    <th>Products</th>
                    <th>Quantity</th>
                    <th>Cancel</th>
                </tr>
                <?php
                require_once 'dbh.inc.php';

                try {
                    // Fetch orders with "Processing" status
                    $query = "SELECT o.order_id, o.order_date, p.price * od.quantity AS price, o.status, 
                                p.name AS product_name, od.quantity
                                FROM order_details AS od
                                JOIN orders AS o ON od.order_id = o.order_id
                                JOIN products AS p ON od.product_id = p.product_id
                                WHERE o.status = 'Processing' AND customer_id = :customer_id
                                ORDER BY order_id DESC;";
                    $cancelable_orders = $pdo->prepare($query);
                    $cancelable_orders->bindParam(':customer_id', $customer_id);
                    $cancelable_orders->execute();
                    $cancelable_orders = $cancelable_orders->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($cancelable_orders as $row) {
                        echo "
                            <tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['order_date']}</td>
                                <td>{$row['price']} PHP</td>
                                <td>{$row['status']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td><button onclick=\"confirmCancelOrder('{$row['order_id']}','{$row['product_name']}','{$row['quantity']}')\">Cancel</button></td>
                            </tr>";
                    }

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                ?>
            </table>
        </div>
    </div>
    </div>
    <script src="../script.js"></script>
</body>

</html>
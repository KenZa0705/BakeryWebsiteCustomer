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
    $customer_id = $_SESSION['user']['customer_id'];
    $firstName = $_SESSION['user']['first_name'];
    $lastName = $_SESSION['user']['last_name'];
    $email = $_SESSION['user']['email'];
    $phone = $_SESSION['user']['phone'];
    $address = $_SESSION['user']['address'];

    require_once 'dbh.inc.php';

    // Check if the product ID exists in the database
    $query = "SELECT * FROM customers WHERE customer_id = :customer_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <h1>Account Settings</h1>
    <h2>Welcome! <?php echo $firstName; ?></h2>
    <section>
        <nav>
            <ul>
                <li><button onclick="openBox('updateAccountDiv')" class="updateButton">Update</button></li>
                <li><button onclick="openBox('viewOrders')" class="viewOrdersButton">View Orders</button></li>
                <li><a href="../menupage.php">Shopping Cart</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </nav>
    </section>
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

            <table>
                <tr>
                    <th></th>
                    <th>Date Ordered</th>
                    <th>Price</th>
                    <th>status</th>
                    <th>Products</th>
                    <th>Quantity</th>
                </tr>
                <?php
                require_once 'dbh.inc.php';

                try {
                    $query = "SELECT 
                    o.order_id,
                    o.order_date,
                    o.total_price,
                    o.status,
                    p.name AS product_name,
                    od.quantity
                  FROM 
                    order_details AS od
                  JOIN 
                    orders AS o ON od.order_id = o.order_id
                  JOIN 
                    products AS p ON od.product_id = p.product_id
                  WHERE customer_id = :customer_id
                  ORDER BY order_id DESC;
                            ";
                    $orders = $pdo->prepare($query);
                    $orders->bindParam(':customer_id', $customer_id);
                    $orders->execute();
                    $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($orders as $row) {
                        echo "
                    <tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['order_date']}</td>
                        <td>{$row['total_price']} PHP</td>
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
    <script src="../script.js"></script>
</body>

</html>
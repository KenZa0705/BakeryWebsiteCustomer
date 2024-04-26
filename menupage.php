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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bakeryweb</title>
    <!--    <link rel="stylesheet" href="style2.css">-->
    <style>
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            /* Distribute items evenly */
        }

        .product-box {
            flex: 0 0 calc(25% - 10px);
            /* Adjust for margin */
            box-sizing: border-box;
            margin-bottom: 20px;
            /* Add some space between rows */
            border: 1px solid #ddd;
            /* Add border to each box */
            padding: 10px;
            z-index: 1;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            background-color: #fac031;
            border-radius: 10px;
            color: #fff;
        }

        .menu_image img {
            width: 100%;
            height: auto;
            background-color: #fff;
            border-radius: 10px;
        }

        .menu_info {
            padding-left: 10px;
            align-items: center;
            text-align: center;
        }

        .menu_btn {
            display: inline-block;
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            background-color: #fac031;
            border-style: solid;
            cursor: pointer;
        }

        .menu_btn:hover {
            background-color: #f59e07;
            cursor: pointer;
        }

        /*Other Table */
        .other_products_table_wrapper {
            max-height: 500px;
            max-width: 600px;
            /* Adjust this value as needed */
            overflow-y: auto;
            border: 5px solid #f59e07;
            /* Optional: Add border for visual clarity */
            border-radius: 5px;
            /* Optional: Add border radius for rounded corners */
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 100px;

        }

        .other_products_table {
            width: 100%;
            border-collapse: collapse;
        }

        .other_products_thead th,
        .other_products_tbody td {
            border: 1px solid #ccc;
            /* Optional: Add border for table cells */
            padding: 8px;
        }

        .other_products_thead {
            background-color: #fac031;
            color: #fff;
            /* Optional: Add background color for table header */
        }

        /* Added CSS for sidebar */
        .sidebar {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 300px;
            background-color: #fff;
            z-index: 999;
            padding: 20px;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            background-color: #fac031;
        }

        .sidebar-close {
            text-align: right;
            cursor: pointer;
        }

        .menu #cartButton {
            border-color: #fff;
            background-color: #f59e07;
            color: #fff;
            padding-top: 10px;
            padding-right: 10px;
            padding-bottom: 10px;
            margin-left: 95%;
            border-radius: 10px;

        }

        #cartTotalQuantity {
            align-items: flex-end;
            margin-left: 5px;
            border-radius: 25%;
            border-color: red;
            background-color: red;
            padding: 10px;
        }

        .cart-menu {
            margin-bottom: 20px;
        }

        .cart-menu h3 {
            text-align: center;
            border-style: solid;
            border-color: #fff;
            color: #fff;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 10px;
        }

        .sidebar--footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 25px;
        }

        .cart-item span {
            color: white;
            padding-right: 10px;
            font-size: 25px;
            display: inline-block;
        }

        .cart-item button {
            border-radius: 5px;
            background-color: #fac031;
            border-color: #fff;
            cursor: pointer;
        }

        .cart-item input {
            display: block;
            width: 50px;
            border-color: #fff;
            cursor: pointer;
        }

        /*General */
        h1 {
            text-align: center;
            display: inline;
        }

        h2 {
            text-align: center;
        }

        h4 {
            margin-left: 80%;
        }

        .nav-button-container {
            display: flex;
            /* Use flexbox to align elements in a row */
            justify-content: flex-end;
            /* Aligns items to the end (right side) */
            gap: 10px;
            /* Space between buttons */
            padding-right: 20px;
            /* Additional padding to the right, if needed */
        }

        .navButton {
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            border: 1px solid #f59e07;
            background-color: #f59e07;
            cursor: pointer;
        }
    </style>

</head>

<body>
    <!-- Display user's first name -->
    <h1>Welcome, <?php echo $user['first_name']; ?></h1>
    <div class="nav-button-container">
        <button id="AccountSettings" onclick="accountsettings()" class="navButton">Account</button>
        <button id="logout" onclick="logout()" class="navButton">Logout</button>
    </div>


    <div class="menu" id="Menu">
        <h2>Shopping<span> Cart</span></h2>
        <button id="cartButton">Cart<sup id="cartTotalQuantity">0</sup>
        </button>
        <div class="menu_box">
            <?php
            require_once 'includes/images.php';
            ?>
            <div class="menu_card">
                <h2>Best Selling Products</h2>
                <!-- Table for products with images -->
                <h4><a href="#other_products">Check out our other amazing products</a></h4>
                <div class="product-container">
                    <?php foreach ($results as $row): ?>
                        <?php if (isset($imagePaths[$row['name']])): ?>
                            <div class="product-box">
                                <div class="menu_image">
                                    <img src="<?php echo $imagePaths[$row['name']]; ?>" alt="<?php echo $row['name']; ?>">
                                </div>
                                <div class="menu_info">
                                    <h2><?php echo $row['name']; ?></h2>
                                    <span><?php echo $row['price']; ?> PHP</span><br><br>
                                    <a class="menu_btn add-to-cart" data-name="<?php echo $row['name']; ?>"
                                        data-price="<?php echo $row['price']; ?>">Add to cart</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <!-- Table for products without images -->
                <h2 id="other_products">Other Amazing Products</h2>
                <div class="other_products_table_wrapper">
                    <table class="other_products_table">
                        <thead class="other_products_thead">
                            <tr class="other_products_tr">
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="other_products_tbody">
                            <?php foreach ($results as $row): ?>
                                <?php if (!isset($imagePaths[$row['name']])): ?>
                                    <tr class="other_products_tr">
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['price']; ?></td>
                                        <td>
                                            <a class="menu_btn add-to-cart" data-name="<?php echo $row['name']; ?>"
                                                data-price="<?php echo $row['price']; ?>">Add to cart</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-close" id="sidebarClose">
            <span>X</span>
        </div>
        <div class="cart-menu">
            <h3>My Cart</h3>
            <div class="cart-items"></div>
        </div>
        <div class="sidebar--footer">
            <h5>Total</h5>
            <div class="cart-total">₱0.00</div>
            <button class="checkout-btn">Checkout</button>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
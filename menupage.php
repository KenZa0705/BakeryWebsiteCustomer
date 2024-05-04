<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bakeryweb</title>
    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <!--Welcome Message, Header -->
    <h1>Welcome, <?php echo $user['first_name']; ?></h1>
    <div class="nav-button-container">
        <button id="AccountSettings" onclick="accountsettings()" class="navButton">Account</button>
        <a href="includes/logout.php" id="logout" onclick="promptMessage('Logged Out Successfully')"
            class="navButton">Logout</a>
    </div>

    <!-- Menu -->
    <div class="menu" id="Menu">
        <h2>Shopping<span> Cart</span></h2>
        <button id="cartButton"><img src="image/cart.png" alt="cart" class="cart"><sup id="cartTotalQuantity">0</sup>
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
                                        data-price="<?php echo $row['price']; ?>"
                                        data-quantity-available="<?php echo $row['quantity_available']; ?>">
                                        Add to cart
                                    </a><br>
                                    <span>Available Stocks: <?php echo $row['quantity_available']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- table for other products -->
                <h2 id="other_products">Other Amazing Products</h2>
                <div class="other_products_table_wrapper">
                    <table class="other_products_table">
                        <thead class="other_products_thead">
                            <tr class="other_products_tr">
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                                <th>Available Stocks</th>
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
                                        <td><?php echo $row['quantity_available']; ?></td>
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
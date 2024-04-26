<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordering Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .product {
            border: 1px solid #ccc;
            background-color: #fff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product h2 {
            margin-top: 0;
        }
        .product p {
            margin-bottom: 10px;
        }
        .add-to-cart {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
        }
        .cart-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Our Ordering Website</h1>
        <p>Below are some of our products:</p>

        <div class="product">
            <h2>Product 1</h2>
            <img src="product1.jpg" alt="Product 1">
            <p>Description of Product 1.</p>
            <p>$10.00</p>
            <a href="#" class="add-to-cart">Add to Cart</a>
        </div>

        <div class="product">
            <h2>Product 2</h2>
            <img src="product2.jpg" alt="Product 2">
            <p>Description of Product 2.</p>
            <p>$15.00</p>
            <a href="#" class="add-to-cart">Add to Cart</a>
        </div>

        <div class="product">
            <h2>Product 3</h2>
            <img src="product3.jpg" alt="Product 3">
            <p>Description of Product 3.</p>
            <p>$20.00</p>
            <a href="#" class="add-to-cart">Add to Cart</a>
        </div>
    </div>

    <!-- Shopping Cart Icon -->
    <div class="cart-icon" onclick="location.href='cart.html';">🛒</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="s.css">
    <title>Admin Login</title>
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5); /* Background color with transparency */
        }

        .login-form {
            z-index: 50;
            background-color: white; /* Set background color for the form */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a shadow effect */
            display: flex;
            flex-direction: column; /* Align children vertically */
            align-items: center; /* Center children horizontally */
        }

        .login-form input {
            width: calc(100% - 20px); /* Adjusted width to match button */
            height: 35px;
            margin-bottom: 10px; /* Add some space between inputs */
            padding: 0 10px;
            border: 2px solid #cccccc;
            background: #eeeeee;
            outline: none;
            border-radius: 3px;
        }

        .login-form input:focus {
            border: 2px solid #fac031;
        }

        .login-form button {
            padding: 10px 0;
            background: #fac031;
            color: white;
            width: 100%; /* Make the button width 100% */
            border: none; /* Remove border */
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div id="login-form" class="login-form" style="display:none;">
            <form action="includes/login.inc.php" method="post">
                <h2>Login</h2>
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>
                <input type="text" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
<script src="script.js"></script>

</html>

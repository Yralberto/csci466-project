<hmtl>
<?php
session_start();

//to connect to the database
require 'db.php';

//checks if form was sumitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // prepares the query to find matching user from the database
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ? AND password = ?");

    // execute query using input from form
    $stmt->execute([$_POST['username'], $_POST['password']]);
    
    // fetches the user if found
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if user exists AND is an admin
    if ($user && $user['role'] === 'Admin') {

        // store admin session info
        $_SESSION['is_admin'] = true;
        $_SESSION['username'] = $user['username'];

         // redirect to admin orders page
        header("Location: admin_orders.php");
        exit;
    } else {
        echo "Incorrect login";// to make sure its an login failed
    }
}
?>

<h2>Admin Login</h2>

<body>
                 // the menu or nagivation bar 
        <p>
            <a href="admin_login.php">Admin Login</a> |
            <a href="index.php">Home</a> |
            <a href="cart.php">View Cart</a> |
            <a href="checkout.php">Checkout</a>
            <a href="about.php">About</a>
        </p>
</body>

<!-- login form sends username and password using POST -->
<form method="POST">
    <input type="text" name="username">
    <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</html>
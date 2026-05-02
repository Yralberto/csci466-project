<?php
// connects to the database
require "db.php";

$order = null;

// checks if the form was submitted 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //gets the order ID from the form input
    $order_id = $_POST['order_id'];

    //prepares query to find order by ID
    $stmt = $pdo->prepare("SELECT * FROM Orders WHERE order_id = ?");
    $stmt->execute(array($order_id));

    // fetches the order if it exists
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<html>
    <head>
        <title>Track Order</title>
    </head>
        <body>
            <!-- navigation menu -->
            <p>
            <a href="index.php">Home</a> |
            <a href="cart.php">Cart</a> |
            <a href="checkout.php">Checkout</a>
            </p>

            <h2>Track Order</h2>
          <!-- form used to search for an order by ID -->
            <form method="POST">
                Order ID:
            <input type="text" name="order_id">
            <input type="submit" name="Search">
            </form>

        <br>

        <?php
        // display order details if found
        if ($order) {
            echo "Order ID: " . $order['order_id'] . "<br>";
            echo "Status: " . $order['status'] . "<br>";
            echo "Total: $" . $order['total_amount'] . "<br>";
            
        }
        ?>
        </body>
</html>

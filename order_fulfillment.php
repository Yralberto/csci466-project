<?php
// connect to the database
require "db.php";

//checks if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //gets values from the form input
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    //update the order status in the database
    $stmt = $pdo->prepare("UPDATE Orders SET status = ? WHERE order_id = ?");
    $stmt->execute(array($status, $order_id));

    //confirmation message if sucessful
    echo "Order updated sucessfully.";
}
?>

<html>
    <head>
        <title>Order Fufillment</title>
</head>
        <!-- navigation menu -->
    <body>
        <a href="index.php">Home</a> |
        <a href="cart.php">View Cart</a> |
        <a href="checkout.php">Checkout</a>
        </p>

    <h2> Update Order Status</h2>

    <!-- Form to be able to updae order status -->
    <form method="POST">
        Order ID:
        <input type="text" name="order_id"><br><br>
        <!-- Dropdown to choose the status -->
        Status:
        <select name="status">
            <option>Processing</option>
            <option>Shipped</option>
            <option>Delivered</option> 
        </select><br><br>

        <input type="submit" value="Update">
    </form>

    </body>

</html>


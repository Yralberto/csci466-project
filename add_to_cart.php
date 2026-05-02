<?php
session_start();

// Gets the product ID from URL
$product_id = $_GET['id']  ?? null;

// making sure the product exists
if ($product_id == null) {
    die("No product selected");
}

// creates cart if its not made yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
//if the product is already in the cart then it will increase quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + 1;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

// confirmation or sucess its working message
echo "Item added to cart.<br>";
echo "<a href='index.php'>Back to products</a>";

?>
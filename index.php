<!DOCTYPE html>
<?php
// connect to database so we can retrieve product data
require "db.php";
?>

<html>
<head>
    <title>Candy Store - Home</title>


    <style>
    .product-container {
    display: inline-block;
    }
    /* overall page background */
    body{
        background-color: #90AEAD
    }
    a {
    color: white;
    text-decoration: none;
    }
    .product-container a,
    .product-container button,
    .product-container div {
    display: inline-block;
    vertical-align: top;
    }
    
    /* styling for each product card */
    .product-card {
    border: 1px solid #244855;
    padding: 10px;
    margin: 10px;
    width: 180px;
    }

</style>
</head>
<body>

    <h1>Welcome to the Blind Candy Store</h1>
        <p>
            <!-- menu bar -->
            <a href="admin_login.php">Admin Login</a>
            <a href="index.php">Home</a> |
            <a href="cart.php">View Cart</a> |
            <a href="checkout.php">Checkout</a>
            <a href="about.php">About</a>
        </p>

    <h2>Avaliable Products</h2>
    <!-- small description for user experience -->
    <h5>What we got? Candy. Where are the photos? why are you asking so many questions? just browse and buy the best</h5>
<?php
// query to get all products from database
$sql = "SELECT product_id, name, price, stock_quantity FROM Products";
$result = $pdo->query($sql);

// basic error check for query
if (!$result) {
    die("Query failed: " . print_r($pdo->errorInfo(), true));
}
// display total number of products found
echo "Products found: " . $result->rowCount() . "<br><br>";

// start container for product cards
echo "<div class='product-container'>";

// loop through each product and display it
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    
        // create a card for each product
    echo "<div class='product-card'>";// product card

        // display product information
    echo "ID: " . $row["product_id"] . "<br>";

        // clickable name goes to product details page
    echo "<a href='product_details.php?id=" . $row["product_id"] . "'>";
    echo "Name: " . $row["name"] . "</a><br>";

    echo "Price: " . $row["price"] . "<br>";
    echo "Stock: " . $row["stock_quantity"] . "<br><br>";

        // button to add item to cart
    echo "<a href='add_to_cart.php?id=" . $row["product_id"] . "'>";
    echo "<button>Add to Cart</button>";
    echo "</a><br><br>";
    echo "</div>";// end of product card
}
?>
<!-- disclaimer at bottom of page -->
<p><i>Disclaimer: This is a simulated e-commerce store built for a class project. No real transactions are processed.</i></p>
    </body>
</html>

<?php 
require "db.php";
?>

<?php

// ge product ID from the URL (in this case the product_details)
$product_id = $_GET['id'] ?? 0;

// preapare the query to safetly be able to fetch for the product
$stmt = $pdo->prepare("SELECT * FROM Products WHERE product_id = ?");
$stmt->execute([$product_id]);

// fetch product data
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// if no prodcut is found, then its stop the page
if (!$row) {
    die("Product not found");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Product Details</title>
    </head>
        <h1>Product Details</h1>
            <body>
                <?php // be able to display the product information
                    echo "ID: " . $row["product_id"] . "<br>";
                    echo "Name: " . $row["name"] . "<br>";
                    echo "Price: " . $row["price"] . "<br>";
                    echo "Stock: " . $row["stock_quantity"] . "<br><br>";
                    echo "Description: " . $row["description"] . "<br>";
                ?>
            <br>
            <!-- takes back to the home screen -->
            <a href="index.php">Back</a>
        </body>
</html>
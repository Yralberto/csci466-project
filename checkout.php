<?php
session_start();
require_once 'db.php';

try {
    // create the Orders Table
    $sqlOrders = "CREATE TABLE IF NOT EXISTS Orders (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'Processing',
        shipping_address TEXT NOT NULL,
        billing_address TEXT NOT NULL,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $pdo->exec($sqlOrders);

    // create the Order_Items Table
    $sqlOrderItems = "CREATE TABLE IF NOT EXISTS Order_Items (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price_at_purchase DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $pdo->exec($sqlOrderItems);

} catch (PDOException $e) {
    die("Database Setup Error: " . $e->getMessage());
}

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // start transaction
        $pdo->beginTransaction();

        // insert the order record
        $sqlOrder = "INSERT INTO Orders (user_id, total_amount, status, shipping_address, billing_address, order_date) 
                     VALUES (?, ?, 'Processing', ?, ?, NOW())";
        $stmtOrder = $pdo->prepare($sqlOrder);
        $stmtOrder->execute([1, $_POST['total_amount'], $_POST['shipping_address'], $_POST['billing_address']]);
        
        $orderId = $pdo->lastInsertId();

        // loop through cart to insert items and update stock
        foreach ($_SESSION['cart'] as $pid => $qty) {
            // get current price to lock it into the order history
            $stmtPrice = $pdo->prepare("SELECT price, stock_quantity FROM Products WHERE product_id = ?");
            $stmtPrice->execute([$pid]);
            $product = $stmtPrice->fetch();

            // insert into order_items
            $sqlItems = "INSERT INTO Order_Items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
            $pdo->prepare($sqlItems)->execute([$orderId, $pid, $qty, $product['price']]);

            // reduce stock 
            $newStock = $product['stock_quantity'] - $qty;
            $pdo->prepare("UPDATE Products SET stock_quantity = ? WHERE product_id = ?")->execute([$newStock, $pid]);
        }

        // commit everything to the database
        $pdo->commit();

        // clear the cart 
        unset($_SESSION['cart']);
        
        $message = "Order #$orderId placed successfully! <a href='track_order.php?id=$orderId'>Track your order here.</a>";

    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error processing order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        .form-box { max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; }
        input, textarea { width: 100%; margin-bottom: 15px; padding: 8px; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Checkout</h2>
        <?php if ($message) echo "<b>$message</b>"; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
            <form method="POST">
                <input type="hidden" name="total_amount" value="100.00"> <label>Shipping Address:</label>
                <textarea name="shipping_address" required placeholder="123 Main St, Anytown..."></textarea>

                <label>Billing Address (Fake):</label>
                <textarea name="billing_address" required placeholder="Same as shipping..."></textarea>

                <p><em>Note: No real credit card info is required. Submitting will process your fake transaction.</em></p>
                
                <button type="submit" style="background: #28a745; color: white; padding: 10px; width: 100%;">Place Order</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

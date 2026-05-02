<?php
session_start();
require "db.php";

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Cart is empty");
}

try {

    // start transaction (ACTIVE state)
    $pdo->beginTransaction();

    $user_id = 1; // simple user for class example
    $total = 0;

    // calculate total price
    foreach ($_SESSION['cart'] as $product_id => $qty) {

        $stmt = $pdo->prepare("SELECT price FROM Products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // basic check (just in case)
        if ($row) {
            $total = $total + ($row['price'] * $qty);
        }
    }

    // insert order into database
    $stmt = $pdo->prepare("
        INSERT INTO Orders (user_id, order_date, status, total_amount, shipping_address, billing_address)
        VALUES (?, NOW(), 'Processing', ?, '', '')
    ");
    $stmt->execute([$user_id, $total]);

    $order_id = $pdo->lastInsertId();

    // insert each item + update stock
    foreach ($_SESSION['cart'] as $product_id => $qty) {

        $stmt = $pdo->prepare("SELECT price, stock_quantity FROM Products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            // insert item inro Order_Items table
            $stmt = $pdo->prepare("
                INSERT INTO Order_Items (order_id, product_id, quantity, price_at_purchase)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$order_id, $product_id, $qty, $row['price']]);

            // update product stock afte the purchase
            $newStock = $row['stock_quantity'] - $qty;

            $stmt = $pdo->prepare("
                UPDATE Products SET stock_quantity = ? WHERE product_id = ?
            ");
            $stmt->execute([$newStock, $product_id]);
        }
    }

    //commit all changes
    $pdo->commit();

    //clears cart after sucessful checkout
    unset($_SESSION['cart']);

    echo "Order completed. Order ID: " . $order_id;

} catch (Exception $e) {
    //rollback if anything fails
    $pdo->rollBack();

    echo "Something went wrong with order process.";
}

?>
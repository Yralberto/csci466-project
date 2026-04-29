<?php
session_start();
require_once 'db.php';

// Handle updates/removals
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $_SESSION['cart'][$_POST['p_id']] = $_POST['new_qty'];
    }
    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$_POST['p_id']]);
    }
}

$cart_items = [];
$grand_total = 0;

if (!empty($_SESSION['cart'])) {
    // Create a string of question marks for the SQL IN clause
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    
    $stmt = $pdo->prepare("SELECT * FROM Products WHERE product_id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['product_id']];
        $subtotal = $p['price'] * $qty;
        $grand_total += $subtotal;
        
        $cart_items[] = [
            'id' => $p['product_id'],
            'name' => $p['name'],
            'price' => $p['price'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="index_candy.php">Go shopping</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="p_id" value="<?= $item['id'] ?>">
                            <input type="number" name="new_qty" value="<?= $item['qty'] ?>" min="1" style="width:50px;">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="p_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove" style="color:red;">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Grand Total:</th>
                    <th>$<?= number_format($grand_total, 2) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <br>
        <a href="checkout.php"><button style="padding:10px 20px; background:#28a745; color:white; border:none; cursor:pointer;">Proceed to Checkout</button></a>
    <?php endif; ?>
</body>
</html>
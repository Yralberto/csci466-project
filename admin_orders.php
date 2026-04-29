<?php
session_start();
require_once 'db.php';

try {
    // fetch orders that are still "Processing"
    // order by date so the oldest orders are at the top
    $stmt = $pdo->query("SELECT * FROM Orders WHERE status = 'Processing' ORDER BY order_date ASC");
    $outstanding_orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Dashboard - Outstanding Orders</title>
    <style>
        body { font-family: sans-serif; margin: 20px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; color: black; }
        tr:hover { background-color: #f9f9f9; }
        .status-badge { 
            background: #fff3cd; 
            color: #856404; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 0.9em; 
            font-weight: bold;
        }
        .btn-view {
            text-decoration: none;
            background: #eee;
            color: black;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    </style>
</head>
<body>

    <h2>Outstanding Orders</h2>
    <p>The following orders need to be fulfilled and shipped.</p>

    <?php if (count($outstanding_orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date Placed</th>
                    <th>Customer ID</th>
                    <th>Total Value</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($outstanding_orders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td>User <?= htmlspecialchars($order['user_id']) ?></td>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                        <td><span class="status-badge"><?= htmlspecialchars($order['status']) ?></span></td>
                        <td>
                            <a href="order_fulfillment.php?id=<?= $order['order_id'] ?>" class="btn-view">Fulfill Order</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><strong>No outstanding orders to process.</strong></p>
    <?php endif; ?>

    <p><br><a href="admin_inventory.php">Manage Product Inventory</a></p>

</body>
</html>
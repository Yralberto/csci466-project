<?php
require_once 'db.php';

$message = "";

// adding new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    if (!empty($name) && $price > 0) {
        try {
            $sql = "INSERT INTO Products (name, description, price, stock_quantity) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $desc, $price, $stock]);
            $message = "<p style='color: green;'>Successfully added: " . htmlspecialchars($name) . "</p>";
        } catch (PDOException $e) {
            $message = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color: orange;'>Please provide a name and a valid price.</p>";
    }
}

// fetch products to show on page
$products = $pdo->query("SELECT * FROM Products ORDER BY product_id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Candy</title>
    <style>
        body { font-family: sans-serif; margin: 30px; line-height: 1.6; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border: 1px solid #ccc; max-width: 900px; margin: auto; }
        
        /* Form Styling */
        .add-form { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 30px; }
        .add-form input, .add-form textarea { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        .btn-submit { background: #333; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .btn-submit:hover { background: #555; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; color: black; } /* Consistent Gray Header */
    </style>
</head>
<body>

<div class="container">
    <h2>Candy Shop Administration</h2>
    <p><a href="index.php">⬅ Back to Storefront</a></p>

    <div class="add-form">
        <h3>Add New Candy to Stock</h3>
        <?= $message ?>
        <form method="POST">
            <label>Candy Name:</label>
            <input type="text" name="name" placeholder="e.g. Jolly Ranchers" required>

            <label>Description:</label>
            <textarea name="description" placeholder="Briefly describe the candy..."></textarea>

            <label>Price ($):</label>
            <input type="number" step="0.01" name="price" placeholder="4.99" required>

            <label>Initial Stock Quantity:</label>
            <input type="number" name="stock" placeholder="100" required>

            <button type="submit" name="add_product" class="btn-submit">Add Product</button>
        </form>
    </div>

    <h3>Current Inventory</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>In Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['product_id'] ?></td>
                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock_quantity'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

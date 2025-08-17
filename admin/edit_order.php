<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
    echo "<p>Invalid Order ID.</p>";
    exit;
}

$order_res = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if (!$order_res || $order_res->num_rows == 0) {
    echo "<p>Order not found.</p>";
    exit;
}
$order = $order_res->fetch_assoc();

$order_items = [];
$order_items_res = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
if ($order_items_res) {
    while ($row = $order_items_res->fetch_assoc()) {
        $order_items[] = $row;
    }
}

$products = [];
$products_res = $conn->query("SELECT * FROM product");
if ($products_res) {
    while ($row = $products_res->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order #<?= $order_id ?></title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        background: url(../img/log.png);
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color:  rgb(230, 209, 177);
    }

    form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #444;
    }

    input[type="number"],
    select,
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    button {
        margin-top: 20px;
        padding: 12px 18px;
        background:rgb(61, 53, 37);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background:rgb(125, 113, 82);
    }

    .item-block {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .delete-label {
        color: red;
        margin-top: 5px;
        display: inline-block;
        cursor: pointer;
        font-size: 14px;
    }

    /* Mobile Responsive */
    @media (max-width: 600px) {
        form {
            padding: 15px;
        }

        h2 {
            font-size: 22px;
        }

        button {
            font-size: 15px;
            padding: 10px 16px;
        }
    }
</style>

</head>
<body>

<h2>Edit Order #<?= $order_id ?></h2>

<form action="process_edit_order.php" method="POST">
    <input type="hidden" name="order_id" value="<?= $order_id ?>">

    <label>Customer Name:
        <input type="text" name="customer_name" value="<?= htmlspecialchars($order['customer_name']) ?>" required>
    </label>

    <h3>Order Items:</h3>

    <?php foreach ($order_items as $index => $item): ?>
        <div class="item-block">
            <input type="hidden" name="items[<?= $index ?>][id]" value="<?= $item['id'] ?>">

            <label>Product:
                <select name="items[<?= $index ?>][product_id]" required>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>" <?= $product['id'] == $item['product_id'] ? "selected" : "" ?>>
                            <?= htmlspecialchars($product['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Quantity:
                <input type="number" name="items[<?= $index ?>][quantity]" value="<?= $item['quantity'] ?>" min="1" required>
            </label>

            <label>Price (per unit):
                <input type="number" step="0.01" name="items[<?= $index ?>][price]" value="<?= $item['price'] ?>" min="0" required>
            </label>

            <label class="delete-label">
                <input type="checkbox" name="items[<?= $index ?>][delete]" value="1"> Delete this item
            </label>
        </div>
    <?php endforeach; ?>

    <button type="submit" name="update_order">Update Order</button>
</form>

</body>
</html>

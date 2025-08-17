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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']);
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    
    // Update customer name
    $conn->query("UPDATE orders SET customer_name = '$customer_name' WHERE id = $order_id");

    $total_price = 0;

    foreach ($_POST['items'] as $item) {
        $item_id = intval($item['id']);

        if (isset($item['delete']) && $item['delete'] == '1') {
            $conn->query("DELETE FROM order_items WHERE id = $item_id");
        } else {
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            $item_total = $quantity * $price;
            $total_price += $item_total;

            $conn->query("
                UPDATE order_items 
                SET product_id = $product_id, quantity = $quantity, price = $price 
                WHERE id = $item_id
            ");
        }
    }

    $conn->query("UPDATE orders SET total_price = $total_price WHERE id = $order_id");

    header("Location: view_orders.php");
    exit;
}
?>

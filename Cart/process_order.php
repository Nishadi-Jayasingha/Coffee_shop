<?php
session_start();
$conn = new mysqli("localhost", "root", "", "coffee_shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['place_order']) && !empty($_POST['customer_name']) && !empty($_POST['product_ids'])) {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $product_ids = $_POST['product_ids'];
    $quantities = $_POST['quantities'];

    $total_price = 0;
    $items = [];

    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = intval($product_ids[$i]);
        $quantity = intval($quantities[$i]);

        $result = $conn->query("SELECT price FROM product WHERE id = $product_id");
        if ($row = $result->fetch_assoc()) {
            $price = $row['price'];
            $subtotal = $price * $quantity;
            $total_price += $subtotal;

            $items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ];
        }
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, total_price) VALUES (?, ?)");
    $stmt->bind_param("sd", $customer_name, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    unset($_SESSION['cart']); // clear cart

    echo "<script>alert('Order placed successfully!'); window.location.href='../index.php';</script>";
} else {
    echo "<script>alert('Cart is empty or customer name is missing.'); window.location.href='checkout.php';</script>";
}
?>

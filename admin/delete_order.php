<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "coffee_shop");

$id = $_GET['id'] ?? 0;

// First delete order items
$conn->query("DELETE FROM order_items WHERE order_id = $id");
// Then delete order
$conn->query("DELETE FROM orders WHERE id = $id");

header("Location: view_orders.php");
exit;

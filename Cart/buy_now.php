<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = $conn->query("SELECT * FROM product WHERE id = $id")->fetch_assoc();

    if ($product) {
        // Optional: Direct to payment/order page with product data
        $_SESSION['buy_now'] = $product;
        header("Location: ../Cart/checkout.php"); // Create checkout.php as needed
        exit;
    }
}

header("Location: ../index.php");
exit;

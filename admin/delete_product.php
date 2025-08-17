<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $conn = new mysqli("localhost", "root", "", "coffee_shop");
    $id = $_GET['id'];

    // Delete product image file first if needed

    $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit;
}
?>

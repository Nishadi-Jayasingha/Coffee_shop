<?php
session_start();

// Connect to DB if you want to fetch product details (optional)
$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: validate product exists
    $result = $conn->query("SELECT * FROM product WHERE id = $id LIMIT 1");
    if ($result->num_rows > 0) {
        // Add or increment quantity
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
    }

   header("Location: ../index.php?added_to_cart=1");
exit;

}

// Remove item
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location:Cart/view_cart.php");
    exit;
}
?>

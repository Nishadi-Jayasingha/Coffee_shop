<?php
session_start();

// Handle item removal
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        header("Location:view_cart.php");
        exit;
    }
}



$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
       html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    background-image: url(../img/13.jpeg);
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    font-family: 'Segoe UI', sans-serif;
    color: #fff;
}


        h2 {
            text-align: center;
            font-size: 2rem;
            margin-top: 30px;
        }

        a.shop {
            display: inline-block;
            margin: 20px auto;
            background: #6b3e3e;
            padding: 10px 20px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
            text-align: center;
        }

        a.shop:hover {
            background: rgb(65, 7, 7);
        }

        p {
            text-align: center;
            font-size: 1.2rem;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f3c481;
            color: #442c2e;
        }

        a {
            color: #d33;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .buy-now {
            display: block;
            width: fit-content;
            margin: 20px auto;
            background-color:rgb(113, 99, 63);
            color: white;
            font-size: 16px;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .buy-now:hover {
            background-color:rgb(150, 126, 53);
        }

        @media (max-width: 768px) {
            h2 { font-size: 1.5rem; }
            table, th, td { font-size: 14px; padding: 8px; }
            a.shop, .buy-now { font-size: 15px; padding: 10px 16px; }
        }

        @media (max-width: 480px) {
            h2 { font-size: 1.3rem; }
            table, th, td { font-size: 12px; padding: 6px; }
            a.shop, .buy-now { font-size: 14px; padding: 8px 14px; }
            table { width: 100%; }
        }
    </style>
</head>
<body>

    <h2>Your Shopping Cart</h2>
    <div style="text-align:center;">
        <a href="../index.php" class="shop">← Back to shop</a>
    </div>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart as $id => $qty): ?>
                <?php
                $product = $conn->query("SELECT * FROM product WHERE id = $id")->fetch_assoc();
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= $qty ?></td>
                    <td>Rs.<?= number_format($product['price'], 2) ?></td>
                    <td>Rs.<?= number_format($subtotal, 2) ?></td>
                    <td><a href="?action=remove&id=<?= $id ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2">Rs.<?= number_format($total, 2) ?></td>
            </tr>
        </table>

        <!-- Buy Now Button -->
        <form action="checkout.php" method="get" style="text-align: center;">
            <button type="submit" class="buy-now">Buy Now</button>
        </form>
    <?php endif; ?>
</body>
</html>

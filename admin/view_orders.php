<?php
ob_start();
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete order logic
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    header("Location: view_orders.php");
    exit;
}

// Fetch orders
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");

// Function to get order items
function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT oi.*, p.name AS product_name 
        FROM order_items oi 
        JOIN product p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: url(../img/log.png) no-repeat center center/cover;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: rgb(204, 163, 135);
        }

        .order {
            background: #fff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .order h3 {
            margin: 0 0 10px 0;
            color: rgb(148, 102, 71);
            font-size: 20px;
            background:rgba(212, 182, 161, 0.77);
        }

        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 15px;
        }

        .order-items th, .order-items td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .order-items th {
            background-color:rgb(144, 125, 107);
            color: #442c2e;
            font-weight: bold;
        }

        .order-items td {
            background-color: #fffdf9;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: rgb(78, 63, 44);
            font-weight: bold;
            font-size: 14px;
        }

        .actions a:hover {
            text-decoration: underline;
            color: rgb(141, 115, 77);
        }

        .back {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: rgb(235, 205, 159);
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .back:hover {
            color: rgb(210, 198, 179);
        }
        .btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color:rgb(158, 136, 92);
    color: black;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s ease;
    margin: 20px auto;
}

.btn-back:hover {
    background-color:rgb(110, 106, 63);
}

        @media (max-width: 600px) {
            body {
                margin: 15px;
            }

            h2 {
                font-size: 22px;
            }

            .order {
                padding: 15px;
            }

            .order h3 {
                font-size: 18px;
            }

            .order-items table {
                font-size: 13px;
            }

            .order-items th, .order-items td {
                padding: 6px;
            }

            .actions a {
                display: block;
                margin-bottom: 8px;
                font-size: 16px;
            }

            .actions {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .back {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<h2>🧾 All Orders</h2>
<a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
<?php if ($orders->num_rows > 0): ?>
    <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order">
            <h3>Order #<?= $order['id'] ?> - <?= htmlspecialchars($order['customer_name']) ?></h3>
            <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
            <p><strong>Total Price:</strong> Rs. <?= number_format($order['total_price'], 2) ?></p>

            <div class="order-items">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price (Rs.)</th>
                            <th>Subtotal (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $items = getOrderItems($conn, $order['id']);
                        while ($item = $items->fetch_assoc()):
                            $subtotal = $item['price'] * $item['quantity'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['price'], 2) ?></td>
                                <td><?= number_format($subtotal, 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="actions">
                <a href="edit_order.php?id=<?= $order['id'] ?>">✏️ Edit</a>
                <a href="view_orders.php?delete=<?= $order['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')">🗑️ Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>


</body>
</html>

<?php ob_end_flush(); ?>

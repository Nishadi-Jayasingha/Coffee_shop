<?php
session_start();

// Check admin login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM product ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Tridrax Coffee Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url(../img/log.png) no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }

        header {
            margin-bottom: 20px;
            text-align: center;
        }

        header h1 {
            margin-bottom: 5px;
            color: white;
            font-size: 50px;
        }

        header p {
            color: rgb(241, 163, 107);
            font-size: 30px;
        }

        nav {
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        nav a.card {
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid #fff;
            padding: 20px 30px;
            border-radius: 12px;
            font-size: 22px;
            color: black;
            text-decoration: none;
            transition: 0.3s ease;
        }

        nav a.card:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            width: 220px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .product-card h3 {
            margin: 0 0 8px;
            font-size: 1.1em;
            color: #333;
        }

        .product-card p {
            color: rgb(89, 37, 192);
            font-weight: bold;
            margin: 0 0 10px;
        }

        .btn-edit,
        .btn-delete {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            margin: 3px;
            font-size: 0.9em;
            cursor: pointer;
            user-select: none;
        }

        .btn-edit {
            background-color: rgb(40, 214, 86);
        }

        .btn-edit:hover {
            background-color: rgb(98, 234, 134);
        }

        .btn-delete {
            background-color: #c0392b;
        }

        .btn-delete:hover {
            background-color: #e74c3c;
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 768px) {
            header h1 {
                font-size: 36px;
            }

            header p {
                font-size: 22px;
            }

            .product-card {
                width: 100%;
                max-width: 350px;
            }

            nav {
                flex-direction: column;
                align-items: center;
            }

            nav a.card {
                width: 90%;
                font-size: 20px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 28px;
            }

            header p {
                font-size: 18px;
            }

            .product-list {
                gap: 15px;
            }

            .btn-edit,
            .btn-delete {
                width: 100%;
                box-sizing: border-box;
                margin: 5px 0;
            }

            nav a.card {
                padding: 15px 20px;
                font-size: 18px;
            }
        }

        @media (max-width: 400px) {
            nav a.card {
                padding: 12px 15px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1><br><br>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p><br><br>
    <nav>
        <a href="add_product.php" class="card">➕ Add New Product</a>
        <a href="view_orders.php" class="card">📦 View Orders</a>
        <a href="manage_users.php" class="card">👥 Manage Users</a>
        <a href="../index.php" class="card">🏠 Back to Home</a>
        <a href="../login/logout.php" class="card">🚪 Logout</a>
    </nav>
</header>

<div class="product-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="img/products/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>Rs. <?= number_format($row['price'], 2) ?></p>
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit">✏️ Edit</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">🗑️ Delete</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

</body>
</html>

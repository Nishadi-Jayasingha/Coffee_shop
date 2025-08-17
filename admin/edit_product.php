<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM product WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "img/products/" . $image);
    } else {
        $image = $product['image'];
    }

    $stmt = $conn->prepare("UPDATE product SET name = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $name, $price, $image, $id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        background: url(../img/5.jpeg) no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .container {
        width: 90%;
        max-width: 400px;
        background: rgba(255, 255, 255, 0.6);
        padding: 30px 25px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h2 {
        text-align: center;
        color: #4B3A2F;
        margin-bottom: 25px;
        font-size: 24px;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="file"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 18px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 15px;
    }

    form button {
        width: 100%;
        background-color: #6f5a4d;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #5a4233;
    }

    img {
        display: block;
        max-width: 100%;
        height: auto;
        margin-bottom: 15px;
        border-radius: 5px;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 15px;
    }

    a:hover {
        color: #666;
    }

    @media (max-width: 500px) {
        .container {
            padding: 25px 20px;
        }

        h2 {
            font-size: 22px;
        }

        form button {
            font-size: 15px;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
           
            <input type="file" name="image">
            <button type="submit">Update</button>
        </form>
        <a href="admin_dashboard.php">← Back</a>
    </div>
</body>
</html>


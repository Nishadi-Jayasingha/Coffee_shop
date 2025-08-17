<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "coffee_shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $price = $_POST['price'];

    // File upload handling
    $target_dir = "img/products/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create the folder if it doesn't exist
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $upload_ok = true;

    // Check if file is actually an image
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif','webp'];

    if (!in_array($image_type, $allowed_types)) {
        $message = "Only JPG, JPEG, PNG, webp, and GIF files are allowed.";
        $upload_ok = false;
    }

    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) { // Limit to 5MB
        $message = "Sorry, your file is too large.";
        $upload_ok = false;
    }

    if ($upload_ok) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
          $image_path = $image_name; 



            // Insert product into database
            $stmt = $conn->prepare("INSERT INTO product (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $name, $price, $image_path);

            if ($stmt->execute()) {
                $message = " Product added successfully!";
            } else {
                $message = " Database error: " . $conn->error;
            }
        } else {
            $message = " Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background: url(../img/9.jpeg);
        margin: 0;
        padding: 0;
        background-size: cover;
        background-position: center;
    }

    .container {
        max-width: 400px;
        width: 90%;
        margin: 60px auto;
        background: rgba(223, 217, 211, 0.53);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 24px;
    }
    form{
        background:rgba(248, 246, 245, 0.57);
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="file"] {
        width: 100%;
        padding: 12px;
        margin: 8px 0 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    form button {
        width: 100%;
        background-color: rgb(111, 96, 82);
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    form button:hover {
        background-color: rgb(93, 67, 43);
    }

    p {
        text-align: center;
        font-size: 16px;
        color: green;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }

    a:hover {
        color: #555;
    }

    /* Responsive styling */
    @media (max-width: 500px) {
        .container {
            padding: 20px;
        }

        form input,
        form button {
            font-size: 14px;
            padding: 10px;
        }

        h2 {
            font-size: 20px;
        }
    }
</style>

<body>
    <div class="container">
        <h2>Add New Product</h2>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required><br><br>
            <input type="number" step="0.01" name="price" placeholder="Price (Rs.)" required><br><br>
            <input type="file" name="image" required><br><br>
            <button type="submit">Add Product</button>
        </form>
        <br>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>

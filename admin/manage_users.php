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

// Delete user
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $userId");
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$result = $conn->query("SELECT id, username FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background: url(../img/4.jpeg) no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 800px;
        margin: auto;
        background: rgba(255, 255, 255, 0.66);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        font-size: 26px;
        color: #333;
    }

    .nav {
        margin-bottom: 20px;
        text-align: center;
    }

    .nav a {
        margin: 0 10px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 16px;
    }

    .nav a:hover,
    div a:hover {
        color: rgba(67, 55, 38, 0.95);
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 15px;
    }

    th, td {
        border: 1px solid rgba(0, 0, 0, 0.94);
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #eee;
    }

    a.button {
        display: inline-block;
        padding: 6px 12px;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    a.button:hover {
        background-color: #c0392b;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        table {
            border: none;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 15px;
            border-bottom: 2px solid #ccc;
        }

        td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            padding-left: 10px;
            font-weight: bold;
            text-align: left;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <div class="nav">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="../login/logout.php">Logout</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <a class="button" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

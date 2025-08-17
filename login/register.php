<?php
session_start();
$conn = new mysqli("localhost", "root", "", "coffee_shop");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
    } else {
        echo "Registration failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
</head>
<header>
        <a href="#" class="logo">
            <img src="../img/Tridrax_coffee_shop-removebg-preview.png" alt="Tridrax Coffee Shop Logo"/>
        </a>
        <a href="#" class="h1">
        <h1>Tridrax Coffee Shop</h1>
        </a>
</header>
<body>
<section class="login">
    <div class="login-container">
        <h2>Register</h2>
</br>
        <form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Username" required /><br>
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <button type="submit">Register</button>
</form>
<div class="home">
        <a href="./login.php">Already have an account? Login</a>
        <a href="../index.php">Back to Home</a>
        </div>
    </div>
    </section>
    <section class="footer">
    <div class="footer-box">
        <h3>Tridrax Cofee Shop</h3>
        <div class="social">
           <a href="#"> <i class='bx bxl-facebook-circle' ></i></a>
           <a href="#"> <i class='bx bxl-twitter'></i></a>
           <a href="#"> <i class='bx bxl-instagram-alt'></i></a>
           <a href="#"> <i class='bx bxl-tiktok'></i></a>
        </div>
    </div>
    <div class="footer-box">
        <h3>Support</h3>
        <li><a href="#">Products</a></li>
        <li><a href="#">Help & Support</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#">Terms of use</a></li>
        <li><a href="#">Products</a></li>
    </div>
    <div class="footer-box">
        <h3>View Guides</h3>
        <li><a href ="#">Features</a></li>
        <li><a href ="#">Careers</a></li>
        <li><a href ="#">Blog Port</a></li>
        <li><a href ="#">Our Branchers</a></li>
    </div>
    <div class="footer-box">
        <h3>Contact</h3>
        <div class="contact">
            <span><i class='bx bx-map' ></i>Aranayaka Road,Mawanella</span></br>
            <span><i class='bx bx-phone-call'></i>+94 76 245 2114</span></br>
            <span><i class='bx bx-envelope'></i>traidrax@coffeeshop.com</span>
        </div>
    </div>
</section>
<script src="../script.js"></script>
</body>
</html>

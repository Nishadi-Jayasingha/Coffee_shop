<?php
session_start();
$conn = new mysqli("localhost", "root", "", "coffee_shop");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
   

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed);
        $stmt->fetch();
        if (password_verify($password, $hashed)) {
            $_SESSION['username'] = $username;
            header("Location: ../index.php");
        } else {
            echo "Invalid credentials";
        }
    } else {
        echo "No user found.";
    }
    $_SESSION['role'] = $row['role'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
</head>
<style>
    .login{
    font-family: 'Poppins', sans-serif;
    background:url(../img/12.jpeg) no-repeat center center/cover;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    height: 580px;
    font-size: 2.23rem;
    color: #4b2c20;
}

.home a{
    color:  #4b2c20;
}
.footer{
    background-color:rgb(231, 187, 168);;
}
</style>
<body>
    <!-- Navbar -->
    <header>
        <a href="#" class="logo">
            <img src="../img/Tridrax_coffee_shop-removebg-preview.png" alt="Tridrax Coffee Shop Logo"/>
        </a>
        <a href="#" class="h1">
        <h1>Tridrax Coffee Shop</h1>
        </a>
</header>
<section class="login">
    <div class="login-container">
        <h2>Login</h2>
</br>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="home">
        <a href="../index.php">Back to Home</a>|<a href="./register.php">Register</a>
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

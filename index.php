<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "coffee_shop";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$isLoggedIn = isset($_SESSION['username']);
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tridrax Coffee Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
</head>
<style>
    .footer{
        background:rgb(215, 182, 152);
    }
    .footer-img{
        width:25%;
    }
    .footer-box h3 {
    color:rgb(80, 44, 12);
}
img{
    width: 90%;
}
.product-container {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
}

.product-container .box {
    width: 250px;
    background: rgba(255, 255, 255, 0.8);
    padding: 15px;
    text-align: center;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    
    /* Animation */
    opacity: 0;
    transform: scale(0.8);
    animation: popup 0.6s ease forwards;
}

.product-container .box:nth-child(1) { animation-delay: 0.1s; }
.product-container .box:nth-child(2) { animation-delay: 0.2s; }
.product-container .box:nth-child(3) { animation-delay: 0.3s; }
/* Add more if needed or use JS for dynamic delay */

@keyframes popup {
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.about{
     background:rgba(245, 212, 181, 0.4);
}

.product{
     background:rgba(237, 216, 187, 0.66);
}
</style>
<body>
<header>
    <a href="#" class="logo">
        <h1>Tridrax Coffee Shop</h1>
        <img src="img/Tridrax_coffee_shop-removebg-preview.png" alt="Tridrax Coffee Shop Logo" />
    </a>

     <!-- User Welcome -->
    <?php if ($isLoggedIn): ?>
        <div class="welcome-user">
            <h3>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        </div>
    <?php endif; ?>

    <ul class="navbar">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#product">Menu</a></li>
        <li><a href="#customer">Staff</a></li>
        <li><a href="admin/admin_login.php">Admin</a></li>
    </ul>
    

    <div class="header-icon">
        <a href="Cart/view_cart.php"><i class='bx bx-cart'></i></a>
        <i class='bx bx-search-alt-2' id="search-icon"></i>
    </div>

    <div class="search-box">
        <input type="search" placeholder="Search Here" />
    </div>

   

    <!-- Admin Panel Link -->
    <?php if (isset($_SESSION['admin'])): ?>
        <div class="welcome-admin">
            <h3>Welcome, <?= htmlspecialchars($_SESSION['admin']) ?>!</h3>
            <li><a href="admin/admin_dashboard.php">Admin Panel</a></li>
        </div>
    <?php endif; ?>
</header>

<!-- Home -->
<section class="home" id="home">
    <div class="home-text">
        <h1>Start Your Day<br>With Coffee</h1>
        <p>Experience the best brews at Tridrax Coffee Shop.</p>
        <?php if ($isLoggedIn): ?>
            <a href="login/logout.php" class="btn">Logout</a>
        <?php else: ?>
            <a href="login/login.php" class="btn">Login</a>
        <?php endif; ?>
    </div>
    <div class="home-img">
        <img src="img/main.png" alt="" class="main-img">
    </div>
</section>

<!-- About -->
<section class="about" id="about">
    <div class="about-img">
        <img src="img/about.avif" alt="">
    </div>
    <div class="about-text">
        <h2>Our History</h2>
        <p>Tridrax Coffee Shop opened its doors on October 6, 2023, founded by Tharaka Dilshan with a 
            dream to create a welcoming place where people can enjoy great coffee and good company.
            <br><br>From the very beginning, Tridrax has focused on quality—sourcing the best beans and crafting each cup with care.
             It quickly became a local favorite for its cozy atmosphere and friendly service.
              Whether you’re stopping by for a morning pick-me-up or catching up with friends, 
              Tridrax Coffee Shop is all about making every visit special.</p>
    </div>
</section>

<!-- Product -->
<section class="product" id="product">
    <div class="heading">
        <h2>Today Menu</h2>
    </div>

    <div class="product-container">
    <?php
    $result = $conn->query("SELECT * FROM product");
    while ($row = $result->fetch_assoc()):
    ?>
        <div class="box">
            <img src="<?= htmlspecialchars('admin/img/products/' . basename($row['image'])) ?>" alt="">
            <h3><?= $row['name'] ?></h3>
            <span>Rs.<?= $row['price'] ?></span>
            <div class="content">
                <?php if ($isLoggedIn): ?>
                    <a href="Cart/cart.php?action=add&id=<?= $row['id'] ?>">Add To Cart</a>
                <?php else: ?>
                    <a href="login/login.php">Login to Add</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</section>

<!-- Customers -->
<section class="customer" id="customer">
    <div class="heading">
        <h2>Our Staff</h2>
    </div>
    <div class="customer-container">
        <div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half'></i>
            </div>
            <p></p>
            <h2>Kaveesha Shavindu</h2>
            <img src="img/rev1.jpg" alt="">
        </div>
        <div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half'></i>
            </div>
            <p></p>
            <h2>Tharaka Dilshan</h2>
            <img src="img/rev2.jpg" alt="">
        </div>
        <div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half'></i>
            </div>
            <p></p>
            <h2>Nishu Jayasingha</h2>
            <img src="img/rev3.jpg" alt="">
        </div>
    </div>
</section>

<!-- Footer -->
<section class="footer">
    <div class="footer-box">
        <h3>Tridrax Coffee Shop</h3>
        <div class="footer-img">
         <img src="img/Tridrax_coffee_shop-removebg-preview.png" alt="Tridrax Coffee Shop Logo" />
         </div>
        <div class="social">
            <a href="#"><i class='bx bxl-facebook-circle'></i></a>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram-alt'></i></a>
            <a href="#"><i class='bx bxl-tiktok'></i></a>
        </div>
    </div>
    <div class="footer-box">
        <h3>Support</h3>
       <li><a href="#product">Product</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#customer">Terms of use</a></li>
         <li><a href="https://www.hidmc.com/blog-posts/best-coffee-spots-in-sri-lanka-for-a-relaxing-afternoon">Blog Port</a></li>
    </div>
    <div class="footer-box">
        <h3>Our coffee powder shop</h3>
        <li><a href="https://www.inceylonproduct.com/">ceylon product</a></li>
        <li><a href="https://shop.coffee.lk/coffee-powders">colombo coffee company</a></li>
        
       
    </div>
    <div class="footer-box">
        <h3>Contact</h3>
        <div class="contact">
            <span><i class='bx bx-map'></i> Aranayaka Road, Mawanella</span>
            <span><i class='bx bx-phone-call'></i> +94 76 245 2114</span>
            <span><i class='bx bx-envelope'></i> tridrax@coffeeshop.com</span>
        </div>
    </div>
</section>

<script src="script.js"></script>
</body>
</html>

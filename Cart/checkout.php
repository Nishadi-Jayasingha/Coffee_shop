<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            background: url(../img/log.png) no-repeat center/cover;
            font-family: Arial, sans-serif;
            color: white;
        }

        .container {
            background-color: rgba(81, 57, 40, 0.41);
            width: 90%;
            max-width: 600px;
            margin: 7px auto;
            padding: 20px;
            border-radius: 10px;
        }

        input[type="text"], input[type="submit"], select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: white;
            color: black;
            border-collapse: collapse;
        }

        th, td {
            padding: 3px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f3c481;
        }

        input[type="number"] {
            width: 40px;
        }

        a.shop {
            display: inline-block;
            margin: 10px auto;
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
    </style>
</head>
<body>
<div class="container">
    <h2>Checkout</h2>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form action="process_order.php" method="post" id="checkoutForm">
            <label>Customer Name:</label>
            <input type="text" name="customer_name" required>

            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="online">Online</option>
            </select>

            <!-- Cash Section -->
            <div id="cashSection" style="display:none;">
                <label>Cash Given:</label>
                <input type="number" id="cashGiven" name="cash_given" min="0" step="1">
                <p id="changeDisplay" style="margin-top:10px; font-weight:bold;"></p>
            </div>

            <table id="cartTable">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($cart as $index => $product_id): ?>
                    <?php
                    $product = $conn->query("SELECT * FROM product WHERE id = $index")->fetch_assoc();
                    $price = round($product['price']);
                    $qty = $cart[$index];
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                    ?>
                    <tr data-index="<?= $index ?>">
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>
                            <input type="number" name="quantities[]" class="qty" value="<?= $qty ?>" min="1" required>
                            <input type="hidden" name="product_ids[]" value="<?= $index ?>">
                        </td>
                        <td class="price" data-price="<?= $price ?>">Rs. <?= number_format($price) ?></td>
                        <td class="subtotal">Rs. <?= number_format($subtotal) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td id="totalCell"><strong>Rs. <?= number_format($total) ?></strong></td>
                </tr>
            </table>

            <br><br>
            <div style="text-align: center;">
                <button type="submit" name="place_order" style="padding: 10px 20px; background-color:rgb(172, 135, 82); border: none; font-size: 16px; cursor: pointer; border-radius: 5px;">
                    Place Order
                </button>
            </div>

            <div style="text-align:center;">
                <a href="view_cart.php" class="shop">← Back to cart</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    const qtyInputs = document.querySelectorAll('.qty');
    const totalCell = document.getElementById('totalCell');
    const paymentMethod = document.querySelector('select[name="payment_method"]');
    const cashSection = document.getElementById('cashSection');
    const cashGivenInput = document.getElementById('cashGiven');
    const changeDisplay = document.getElementById('changeDisplay');

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('#cartTable tr[data-index]').forEach(row => {
            const qtyInput = row.querySelector('.qty');
            const price = Math.round(parseFloat(row.querySelector('.price').dataset.price));
            const subtotal = price * parseInt(qtyInput.value);
            row.querySelector('.subtotal').innerText = 'Rs. ' + subtotal;
            total += subtotal;
        });
        totalCell.innerHTML = '<strong>Rs. ' + total + '</strong>';
        updateChange();
    }

    function updateChange() {
        if (paymentMethod.value !== 'cash') {
            changeDisplay.innerHTML = '';
            return;
        }

        const totalAmount = Math.round(parseFloat(totalCell.innerText.replace(/[^\d]/g, '')));
        const cash = Math.round(parseFloat(cashGivenInput.value));

        if (!isNaN(cash)) {
            const change = cash - totalAmount;
            if (change >= 0) {
                changeDisplay.innerHTML = `<span style="color:lightgreen;">Change to return: Rs. ${change}</span>`;
            } else {
                changeDisplay.innerHTML = `<span style="color:red;">Insufficient cash amount!</span>`;
            }
        } else {
            changeDisplay.innerHTML = '';
        }
    }

    paymentMethod.addEventListener('change', function () {
        if (this.value === 'cash') {
            cashSection.style.display = 'block';
        } else {
            cashSection.style.display = 'none';
            changeDisplay.innerHTML = '';
        }
    });

    qtyInputs.forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    if (cashGivenInput) {
        cashGivenInput.addEventListener('input', updateChange);
    }
</script>
</body>
</html>

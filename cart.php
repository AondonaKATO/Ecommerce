<?php
include 'db.php';
session_start();

// Initialize or fetch cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = count($cart);

// Fetch cart items from the database
$cart_items = [];
if ($cart_count > 0) {
    $ids = implode(',', array_map('intval', $cart)); // Convert IDs to a comma-separated string
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-img-top {
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Agricultural Produce</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart <span class="badge badge-pill badge-primary" id="cart-count"><?php echo $cart_count; ?></span></a>
            </li>
            <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="my-4">Shopping Cart</h1>

    <?php if ($cart_count > 0): ?>
        <!-- Cart items table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart_items as $item) {
                    $item_total = $item['price'];
                    $total += $item_total;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>$" . htmlspecialchars($item['price']) . "</td>";
                    echo "<td>1</td>"; // Assuming quantity is 1 for simplicity
                    echo "<td>$" . $item_total . "</td>";
                    echo "<td><a href='remove_from_cart.php?id=" . $item['id'] . "' class='btn btn-danger'>Remove</a></td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?php echo $total; ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <a href="product.php" class="btn btn-primary">Continue Shopping</a>
    <?php else: ?>
        <!-- Empty cart message -->
        <div class="alert alert-info" role="alert">
            Your cart is currently empty. <a href="product.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

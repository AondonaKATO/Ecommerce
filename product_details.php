<?php
include 'db.php';
session_start();

// Fetch product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <a class="nav-link" href="cart.php">Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Register</a>
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
    <h1 class="my-4"><?php echo htmlspecialchars($product['name']); ?></h1>

    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <h4>Price: $<?php echo htmlspecialchars($product['price']); ?></h4>
            <input type='number' class='form-control quantity' min='1' value='1' style='width: 100px; display: inline-block;'>
            <a href="product.php" class="btn btn-secondary">Back to Products</a>
            <button class="btn btn-primary add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var product_id = $(this).data('id');
        var quantity = $(this).siblings('.quantity').val();
        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: { id: product_id, quantity: quantity },
            success: function(response) {
                alert('Product added to cart!');
                $('#cart-count').text(response); // Update cart count if necessary
            }
        });
    });
});
</script>
</body>
</html>

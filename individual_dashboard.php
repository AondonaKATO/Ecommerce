<?php
include 'db.php';
session_start();

// Check if user is logged in and is an individual
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'individual') {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Dashboard</title>
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
                <a class="nav-link" href="individual_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="my-4">Individual Dashboard</h1>

    <!-- Product List -->
    <h2>Available Products</h2>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <img class="card-img-top" src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <h5>$<?php echo htmlspecialchars($row['price']); ?></h5>
                        <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View Details</a>
                        <button class="btn btn-primary add-to-cart" data-id="<?php echo $row['id']; ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var product_id = $(this).data('id');
        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: { id: product_id },
            success: function(response) {
                alert('Product added to cart!');
            }
        });
    });
});
</script>
</body>
</html>

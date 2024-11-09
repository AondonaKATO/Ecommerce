<?php
include 'db.php';
session_start();

// Initialize or fetch cart count
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Handle search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL query
$sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = '%' . $search_query . '%';
$stmt->bind_param('ss', $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-img-top {
            height: 200px;
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
            <!-- <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li> -->
            <li class="nav-item active">
                <a class="nav-link" href="#">Products <span class="sr-only">(current)</span></a>
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
    <h1 class="my-4">Products</h1>

    <!-- Search Bar -->
    <form class="form-inline my-2 my-lg-0" method="get" action="product.php">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>

    <div class="row mt-4">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<div class='col-lg-4 col-md-6 mb-4'>";
            echo "<div class='card h-100'>";
            echo "<img class='card-img-top' src='" . $row['image'] . "' alt='Product Image'>";
            echo "<div class='card-body'>";
            echo "<h4 class='card-title'>" . $row['name'] . "</h4>";
            echo "<p class='card-text'>" . $row['description'] . "</p>";
            echo "<h5>$" . $row['price'] . "</h5>";
            echo "<input type='number' class='form-control quantity' min='1' value='1' style='width: 100px; display: inline-block;'>";
            echo "<a href='product.php?id=" . $row['id'] . "' class='btn btn-info'>View Details</a>";
            echo "<button class='btn btn-primary add-to-cart' data-id='" . $row['id'] . "'>Add to Cart</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
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
                $('#cart-count').text(response);
            }
        });
    });
});
</script>
</body>
</html>

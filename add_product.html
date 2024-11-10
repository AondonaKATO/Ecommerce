<?php
include 'db.php';
session_start();

// Check if user is logged in and is a farmer
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'farmer') {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = htmlspecialchars(trim($_POST['price']));
    $image = htmlspecialchars(trim($_POST['image'])); // Make sure this is a valid image URL or path

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $description, $price, $image);

    // Execute statement and check for errors
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully!";
        header("Location: farmer_dashboard.php"); // Redirect to dashboard after adding product
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
                <a class="nav-link" href="farmer_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_product.php">Add Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="my-4">Add Product</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="post" action="add_product.php">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="text" class="form-control" id="image" name="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

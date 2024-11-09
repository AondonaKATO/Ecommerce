<?php
include 'db.php';
session_start();

// Check if user is logged in and is a farmer
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'farmer') {
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
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h1 class="my-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2 class="my-4">Farmer Dashboard</h2>

    <!-- Product List -->
    <h2>Product List</h2>
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
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

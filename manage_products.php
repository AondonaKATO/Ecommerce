<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle adding or updating products
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if ($id) {
        // Update product
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
    } else {
        // Add new product
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $image);
    }
    $stmt->execute();
    header('Location: manage_products.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: manage_products.php');
    exit();
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
    <!-- Add other nav items as needed -->
</nav>

<div class="container my-4">
    <h1>Manage Products</h1>

    <form method="post" action="manage_products.php">
        <input type="hidden" name="id" id="product_id">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="text" class="form-control" id="image" name="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>

    <h2 class="mt-4">Product List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 100px;"></td>
                    <td>
                        <a href="#" onclick="editProduct(<?php echo $product['id']; ?>)">Edit</a> |
                        <a href="manage_products.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function editProduct(id) {
    // Fetch product details and populate the form for editing
    // This can be done using AJAX or by pre-populating the form if using a different approach
    // For simplicity, assume form fields with IDs matching product attributes
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_product.php?id=' + id, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var product = JSON.parse(xhr.responseText);
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;
            document.getElementById('image').value = product.image;
        }
    };
    xhr.send();
}
</script>
</body>
</html>

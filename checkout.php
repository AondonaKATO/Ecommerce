<?php
include 'db.php';
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $total = $_POST['total'];

    $sql = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $user_id, $total);
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) SELECT ?, id, ?, price FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $quantity, $product_id);
            $stmt->execute();
        }

        $_SESSION['cart'] = [];
        header('Location: thank_you.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">E-Commerce</a>
    <!-- Add other nav items as needed -->
</nav>

<div class="container my-4">
    <h1>Checkout</h1>
    <form method="post" action="checkout.php">
        <h3>Order Summary</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cart_items = [];
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $sql = "SELECT * FROM products WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    $item_total = $product['price'] * $quantity;
                    $total += $item_total;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($quantity) . "</td>";
                    echo "<td>$" . htmlspecialchars($product['price']) . "</td>";
                    echo "<td>$" . number_format($item_total, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="total" value="<?php echo $total; ?>">
        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

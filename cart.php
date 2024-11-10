<?php
include 'db.php';
session_start();

// Initialize or fetch cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = count($cart);

// Fetch cart items from the database
$cart_items = [];
$total = 0;
if ($cart_count > 0) {
    $ids = implode(',', array_map('intval', $cart)); // Convert IDs to a comma-separated string
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($cart_items as $item) {
        $total += $item['price'];  // Assuming quantity is 1
    }
}

// Return cart data as JSON
echo json_encode([
    'count' => $cart_count,
    'items' => $cart_items,
    'total' => $total
]);
?>

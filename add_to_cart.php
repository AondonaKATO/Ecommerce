<?php
session_start();

// Fetch product ID and quantity from POST request
$product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Initialize or fetch cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Update cart with the new quantity
if ($product_id > 0) {
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
    $_SESSION['cart'] = $cart;
}

// Return the updated cart count
echo count($_SESSION['cart']);
exit();
?>

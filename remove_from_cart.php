<?php
session_start();

// Fetch product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if cart is set in session
if (isset($_SESSION['cart'])) {
    // Remove the product from the cart
    $cart = $_SESSION['cart'];

    // Find and remove the item
    if (($key = array_search($product_id, $cart)) !== false) {
        unset($cart[$key]);
        $_SESSION['cart'] = array_values($cart); // Reindex the array
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit();
?>

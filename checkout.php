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

    // Insert order
    $sql = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $user_id, $total);
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Insert order items
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    SELECT ?, id, ?, price FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $quantity, $product_id);
            $stmt->execute();
        }

        // Clear the cart after the order is placed
        $_SESSION['cart'] = [];
        header('Location: thank_you.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

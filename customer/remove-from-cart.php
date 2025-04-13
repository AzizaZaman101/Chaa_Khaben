<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    echo "Login required";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    echo "No product selected";
    exit;
}

// Find cart
$cart_stmt = $conn->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
$cart_stmt->bind_param("i", $customer_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

if ($cart_result->num_rows > 0) {
    $cart_id = $cart_result->fetch_assoc()['cart_id'];
    $delete_stmt = $conn->prepare("DELETE FROM cart_item WHERE cart_id = ? AND product_id = ?");
    $delete_stmt->bind_param("ii", $cart_id, $product_id);
    $delete_stmt->execute();
}

header("Location: ./view-cart.php");
exit;
?>

<?php
session_start();
include '../basic_php/connection.php'; // Adjust the path as per your project structure
 
// Get data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Get customer_id and product_id from session and request data
$customer_id = $_SESSION['customer_id'];
$product_id = $data['product_id'];
$new_quantity = $data['new_quantity'];

if (!$customer_id || !$product_id || !$new_quantity) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

// Check if cart exists for this customer
$cartQuery = $conn->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
$cartQuery->bind_param("i", $customer_id);
$cartQuery->execute();
$result = $cartQuery->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No cart found']);
    exit;
}

$cart_id = $result->fetch_assoc()['cart_id'];

// Update the quantity of the product in the cart
$updateQuery = $conn->prepare("UPDATE cart_item SET qty = ? WHERE cart_id = ? AND product_id = ?");
$updateQuery->bind_param("iii", $new_quantity, $cart_id, $product_id);
$updateQuery->execute();

// Check if the update was successful
if ($updateQuery->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update the quantity']);
}
?>

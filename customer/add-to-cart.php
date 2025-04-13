<?php
session_start();
include '../basic_php/connection.php'; 

$data = json_decode(file_get_contents("php://input"), true);
 

$customer_id = $_SESSION['customer_id'];
$product_id = $data['product_id'];
$stock_qty = $data['stock_qty'];

if (!$customer_id || !$product_id) {
    echo json_encode(['success' => false]);
    exit;
}




// Check if cart exists for this customer
$cartQuery = $conn->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
$cartQuery->bind_param("i", $customer_id);
$cartQuery->execute();
$result = $cartQuery->get_result();

if ($result->num_rows > 0) {
    $cart_id = $result->fetch_assoc()['cart_id'];
} else {
    $stmt = $conn->prepare("INSERT INTO cart (customer_id) VALUES (?)");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $cart_id = $stmt->insert_id;
}
 
// Check if product already exists in cart
$itemCheck = $conn->prepare("SELECT * FROM cart_item WHERE cart_id = ? AND product_id = ?");
$itemCheck->bind_param("ii", $cart_id, $product_id);
$itemCheck->execute();
$itemResult = $itemCheck->get_result();

if ($itemResult->num_rows > 0) {

    $row = $itemResult->fetch_assoc();
    $currentQty = $row['qty'];

    if ($currentQty >= $stock_qty) {
        echo json_encode(['success' => false, 'message' => 'Stock limit reached']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE cart_item SET qty = qty + 1 WHERE cart_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO cart_item (qty, cart_id, product_id) VALUES (1, ?, ?)");
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>

<?php
session_start();
include '../basic_php/connection.php';

$customer_id = $_SESSION['customer_id']; // Get customer_id from session

// Check if customer_id is valid
if (!$customer_id) {
    echo json_encode(['error' => 'No customer ID found in session']);
    exit;
}

$cartData = [];
$totalQty = 0;
$totalPrice = 0;

// SQL query to fetch cart items for the logged-in customer
$cartSql = "SELECT ci.product_id, ci.qty, p.product_name, p.product_price, p.image,p.stock_qty
            FROM cart c
            JOIN cart_item ci ON c.cart_id = ci.cart_id
            JOIN product p ON ci.product_id = p.product_id
            WHERE c.customer_id = ?";

$stmt = $conn->prepare($cartSql);
$stmt->bind_param("i", $customer_id); // Bind the customer_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if any rows are returned
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['price'] = (int)$row['product_price']; // Ensure price is an integer
        $cartData[] = [
            "id" => $row['product_id'],
            "name" => $row['product_name'],
            "price" => $row['price'],
            "quantity" => $row['qty'],
            "image" => $row['image'],
            "stock_qty" => $row['stock_qty']
        ];
        $totalQty += $row['qty'];
        $totalPrice += $row['price'] * $row['qty'];
    }
} else {
    echo json_encode(['error' => 'No items found in cart']);
    exit;
}

// Return the fetched cart data as a JSON response
echo json_encode([
    "items" => $cartData,
    "totalQty" => $totalQty,
    "totalPrice" => $totalPrice
]);
?>

<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    echo "Login required";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$payment_method = $_POST['payment_method'];
$coupon = $_POST['coupon'] ?? null;
$total_amount = $_POST['total_amount'];

// 1. Insert into orders
$order_stmt = $conn->prepare("INSERT INTO orders (order_date, total_amount, customer_id) VALUES (NOW(), ?, ?)");
$order_stmt->bind_param("di", $total_amount, $customer_id);
$order_stmt->execute();
$order_id = $conn->insert_id;

// 2. Insert into payment
$payment_stmt = $conn->prepare("INSERT INTO payment (payment_method, payment_clear, total_amount, coupon, order_id) VALUES (?, 0, ?, ?, ?)");
$payment_stmt->bind_param("sdsi", $payment_method, $total_amount, $coupon, $order_id);
$payment_stmt->execute();

// 3. Insert into delivery
$delivery_stmt = $conn->prepare("INSERT INTO delivery (delivery_status, order_id) VALUES ('Pending', ?)");
$delivery_stmt->bind_param("i", $order_id);
$delivery_stmt->execute();


// Insert into ordered_products
$items_sql = "
    SELECT ci.product_id, ci.qty
    FROM cart_item ci
    JOIN cart c ON ci.cart_id = c.cart_id
    WHERE c.customer_id = ?
";
$items_stmt = $conn->prepare($items_sql);
$items_stmt->bind_param("i", $customer_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

$ordered_sql = "INSERT INTO ordered_products (order_id, product_id, quantity) VALUES (?, ?, ?)";
$ordered_stmt = $conn->prepare($ordered_sql);

// Insert each product into ordered_products
while ($item = $items_result->fetch_assoc()) {
    $ordered_stmt->bind_param("iid", $order_id, $item['product_id'], $item['qty']);
    $ordered_stmt->execute();
}




// clear cart items
$cartIdQuery = "SELECT cart_id FROM cart WHERE customer_id = ?";
$stmt = $conn->prepare($cartIdQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$cartResult = $stmt->get_result();

if ($cartRow = $cartResult->fetch_assoc()) {
    $cart_id = $cartRow['cart_id'];

    // Delete from cart_item
    $deleteItems = "DELETE FROM cart_item WHERE cart_id = ?";
    $stmt = $conn->prepare($deleteItems);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
}

header("Location: order-success.php");
exit;
?>

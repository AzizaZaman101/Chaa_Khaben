<?php
include '../basic_php/connection.php';
session_start();


session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    echo "Something went!";
    exit;
}

if (!isset($_SESSION['customer_id'])) {
    echo "Login required.";
    exit;
}

$user_id = $_SESSION['user_id'];
$customer_id = $_SESSION['customer_id'];
$delivery_charge = 50;
$discount = 0;
$coupon = trim($_POST['coupon'] ?? ''); // Safe for both GET/POST
$total_amount=0;


$cart_sql = "
SELECT p.product_price, ci.qty
FROM cart c
JOIN cart_item ci ON c.cart_id = ci.cart_id
JOIN product p ON ci.product_id = p.product_id
WHERE c.customer_id = ?
";
$stmt = $conn->prepare($cart_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$subtotal = 0;
while ($row = $result->fetch_assoc()) {
$subtotal += $row['product_price'] * $row['qty'];
}

// Check if customer already used "orderDilam"
$already_used_coupon = false;

if ($coupon === "orderDilam") {
$check_coupon_sql = "
SELECT 1 FROM payment p
JOIN orders o ON p.order_id = o.order_id
WHERE p.coupon = 'orderDilam' AND o.customer_id = ?
LIMIT 1
";
$stmt = $conn->prepare($check_coupon_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
$already_used_coupon = true;
}

if (!$already_used_coupon) {
$discount = 0.10 * $subtotal;
}
}

$total_amount = $subtotal + $delivery_charge - $discount;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    $selected_method = $_POST['payment_method'];

    // Insert into delivery
    $upazila_sql = "SELECT upazila_id FROM customer WHERE customer_id = ?";
    $upazila_stmt = $conn->prepare($upazila_sql);
    $upazila_stmt->bind_param("i", $customer_id);
    $upazila_stmt->execute();
    $upazila_result = $upazila_stmt->get_result();
    $customer_upazila = $upazila_result->fetch_assoc()['upazila_id'];


    $rider_sql = "
    SELECT r.rider_id
    FROM rider r
    JOIN rider_preferred_area ra ON r.rider_id = ra.rider_id
    WHERE ra.upazila_id = ?
    AND r.rider_active_status = 'active'
    AND r.pending_delivery < 4
    ORDER BY RAND()
    LIMIT 1";
    $rider_stmt = $conn->prepare($rider_sql);
    $rider_stmt->bind_param("i", $customer_upazila);
    $rider_stmt->execute();
    $rider_result = $rider_stmt->get_result();
    $rider_row = $rider_result->fetch_assoc();

    $rider_id = $rider_row ? $rider_row['rider_id'] : null;

    $tracking_number = 'CHA' . date("YmdHis") . rand(100, 999);




    if ($rider_id == null){
        // Step 6: Warn if no rider was found
        echo "<script>alert('⚠️ No available rider found in the customer\'s upazila.');
        window.location.href = './checkout-to-pay.php';
        exit;
        </script>";
    }

   

    if ($rider_id !== null) {
        $update_rider_sql = "UPDATE rider SET pending_delivery = pending_delivery + 1 WHERE rider_id = ?";
        $update_stmt = $conn->prepare($update_rider_sql);
        $update_stmt->bind_param("i", $rider_id);
        $update_stmt->execute();
   


        // Insert into orders
        $order_sql = "INSERT INTO orders (order_date, total_amount, customer_id, delivery_charge) VALUES (NOW(), ?, ?, ?)";
        $stmt = $conn->prepare($order_sql);
        $stmt->bind_param("dii", $total_amount, $customer_id, $delivery_charge);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        $delivery_sql = "INSERT INTO delivery (delivery_status, order_id, rider_id, tracking_number) VALUES ('Pending', ?, ?, ?)";
        $stmt = $conn->prepare($delivery_sql);
        $stmt->bind_param("iis", $order_id, $rider_id, $tracking_number);
        $stmt->execute();
    
        // Insert into payment
        $payment_clear = $selected_method === 'SSLCOMMERZ' ? 1 : 0;
        $payment_sql = "INSERT INTO payment (payment_method, payment_clear, total_amount, coupon, order_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($payment_sql);
        $stmt->bind_param("sidsi", $selected_method, $payment_clear, $total_amount, $coupon, $order_id);
        $stmt->execute();


    // Insert into ordered_products
    // Change info of stock_qty in product
    //$product_sql = "UPDATE product 
    //SET stock_qty = stock_qty - 1 WHERE product_id = ?";

    $items_sql = "
    SELECT ci.product_id, ci.qty
    FROM cart c
    JOIN cart_item ci ON c.cart_id = ci.cart_id
    WHERE c.customer_id = ?";

    $product_sql = "UPDATE product p
    JOIN cart_item ci ON ci.product_id= p.product_id
    SET p.stock_qty = p.stock_qty - ci.qty WHERE p.product_id = ?";

    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $customer_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    $ordered_sql = "INSERT INTO ordered_products (order_id, product_id, quantity) VALUES (?, ?, ?)";
    $ordered_stmt = $conn->prepare($ordered_sql);

    while ($item = $items_result->fetch_assoc()) {

        $stmt = $conn->prepare($product_sql);
        $stmt->bind_param("i", $item['product_id']);
        $stmt->execute();

    $ordered_stmt->bind_param("iii", $order_id, $item['product_id'], $item['qty']);
    $ordered_stmt->execute();
    }

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

    echo "<script>alert('Order placed successfully!'); window.location.href = './index-customer.php';</script>";
    exit;
}
}

// Load email and address
$customer_sql = "SELECT u.email, c.full_address FROM user u
JOIN customer c ON u.user_id=c.user_id
WHERE c.customer_id = ?";
$stmt = $conn->prepare($customer_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Checkout</title>
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/checkout-to-pay.css">
</head>
<body>
<?php include './header_customer.php'; ?>
<main>
<nav class="breadcrumb-nav">
        <a href="./index-customer.php">Home</a>
        <span>›</span>
        <a href="./checkout-customer.php">Check Out</a>
        <span>›</span>
        <a href="#">Order Summary</a>
    </nav>
    <h1>Order Summary</h1>

    
    <form method="POST" action="./checkout-to-pay.php">
        <div class="summary-block">
            <p><strong>Account:</strong> <?= htmlspecialchars($customer['email']) ?></p>
            <p><strong>Deliver to:</strong> <?= htmlspecialchars($customer['full_address']) ?></p>

            <p><strong>Payment Method:</strong></p>
            <label><input type="radio" name="payment_method" value="SSLCOMMERZ" required> SSLCOMMERZ</label>
            <label><input type="radio" name="payment_method" value="Cash on Delivery"> Cash on Delivery</label>
        </div>

        <div class="coupon-discount">
            <label><strong>Apply Coupon : </strong>
                <input type="text" name="coupon" placeholder= "Write Your Coupon" value="<?= htmlspecialchars($coupon) ?>">
            </label>
            <button type="submit" name="apply_coupon" class="apply_coupon"> Apply Coupon </button><br>
        </div>
        <div class="coupon-alert">
        <?php if ($coupon === "orderDilam" && $already_used_coupon): ?>
            <p style="color: crimson;">You’ve already used the coupon <strong>orderDilam</strong>.</p>
            <?php endif; ?>

        </div>

        <?php
        
        // Load subtotal
        $stmt = $conn->prepare("
            SELECT p.product_price, ci.qty
            FROM cart c
            JOIN cart_item ci ON c.cart_id = ci.cart_id
            JOIN product p ON ci.product_id = p.product_id
            WHERE c.customer_id = ?
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $items = $stmt->get_result();

        $subtotal = 0;
        while ($item = $items->fetch_assoc()) {
            $subtotal += $item['product_price'] * $item['qty'];
        }
        ?>

<div class="summary-main">
        <div class="summary-totals">
            <p><strong>Subtotal : </strong>৳<?= number_format($subtotal, 2) ?></p>
            <p><strong>Delivery Charge : </strong>৳<?= number_format($delivery_charge, 2) ?></p>
            <p><strong>Total : ৳<?= number_format($subtotal + $delivery_charge, 2) ?></strong></p>
            <?php if ($discount > 0): ?>
            <p class="you-saved" style="color:green;"><strong>You saved : </strong> ৳<?= $discount ?></p>
            <p class="revised-total"><strong>Revised Total : </strong> ৳ <?= $total_amount ?></p>
            <?php endif; ?> 
        </div>
</div>
        <div class="pay-btn-container">
            <input type="submit" name="pay_now" value="Pay Now" class="pay-btn">
        </div>
        
    </form>

</main>
<?php include '../basic_php/footer.php'; ?>
<script>
    // Prevent back button from showing cached page
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.href = "../regular/index.php";
        }
    });
</script>
</body>
</html>

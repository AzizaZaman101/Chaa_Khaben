<?php
include '../basic_php/connection.php';
session_start();


// Ensure only riders can view this page
if (!isset($_SESSION['rider_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


$rider_id = $_SESSION['rider_id'];
 
// Fetch all pending deliveries assigned to this rider with status 'shipped'
$query = "
SELECT 
    d.delivery_id,
    d.delivery_status,
    p.payment_clear,
    p.payment_id,
    o.order_id,
    o.order_date,
    d.actual_delivery_date,
    c.customer_id,
    concat(u.fname,' ',u.lname) AS customer_name,
    u.phone,
    c.full_address,
    o.delivery_charge
FROM delivery d
JOIN orders o ON d.order_id = o.order_id
JOIN customer c ON o.customer_id = c.customer_id
JOIN user u ON c.user_id = u.user_id
JOIN payment p ON o.order_id = p.order_id
WHERE d.rider_id = ? AND d.delivery_status = 'delivered'
ORDER BY o.order_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Deliveries</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="pending-orders-for-rider.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
</head>
<body>
<?php include '../rider/header_rider.php'; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-rider.php">Home</a>
        <span>›</span>
        <a href="#">Pending Deliveries</a>
    </nav>

    <h1>Delivered</h1>

    <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Delivery Date</th>
                        <th>Delivery charge</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()):
                $isPaymentClear = $row['payment_clear'] == 1;
                $isDeliveryDelivered = strtolower($row['delivery_status']) === 'delivered';
                ?>
                <tr>
                <form action="./update-delivery-status.php" method="post">

                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>

                    <td><?php echo htmlspecialchars($row['full_address']); ?></td>

                    <td><?php echo htmlspecialchars($row['phone']); ?></td>

                    <!--Delivery Date -->
                    <td><?php echo htmlspecialchars($row['actual_delivery_date']); ?></td>

                    <!-- Delivery Charge -->
                    <td><?php echo htmlspecialchars($row['delivery_charge']); ?></td>

                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include'../basic_php/footer.php';?>
<?php include '../javascript_files/prevent_access.js'; ?>
</body>
</html>
<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$customer_id = $_SESSION['customer_id'];


$rider = "
SELECT 
    concat(u.fname,' ',u.lname) AS rider_name,
    u.phone AS rider_phone,
    d.rider_id
FROM user u
JOIN rider r ON r.user_id = u.user_id
JOIN delivery d ON d.rider_id= r.rider_id
JOIN orders o ON d.order_id = o.order_id
JOIN payment p ON o.order_id = p.order_id
WHERE o.customer_id= ? 
ORDER BY o.order_date DESC";

$stmt = $conn->prepare($rider);
$stmt->bind_param("i",$customer_id);
$stmt->execute();
$result_rider = $stmt->get_result();

if ($result_rider->num_rows == 0) {
    echo "<p>No pending orders found.</p>";
}

// Create a rider mapping indexed by delivery_id
$rider_map = [];
while ($row_rider = $result_rider->fetch_assoc()) {
    $rider_map[$row_rider['rider_id']] = [
        'rider_name' => $row_rider['rider_name'],
        'rider_phone' => $row_rider['rider_phone']
    ];
}


 
// Fetch all pending deliveries assigned to this rider with status 'shipped'
$query = "
SELECT 
    d.delivery_id,
    d.delivery_status,
    p.payment_method,
    p.payment_clear,
    p.payment_id,
    o.order_id,
    o.order_date,
    c.customer_id,
    concat(u.fname,' ',u.lname) AS customer_name,
    u.phone,
    c.full_address,
    d.rider_id
FROM delivery d
JOIN orders o ON d.order_id = o.order_id
JOIN customer c ON o.customer_id = c.customer_id
JOIN user u ON c.user_id = u.user_id
JOIN payment p ON o.order_id = p.order_id
WHERE o.customer_id= ?
ORDER BY o.order_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i",$customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>No pending orders found.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="pending-orders-for-rider.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
</head>
<body>
<?php include './header_customer.php'; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-customer.php">Home</a>
        <span>›</span>
        <a href="#">All Orders</a>
    </nav>

    <h1>Approved Orders</h1>

    <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Payment Method</th>
                        <th>Delivery Status</th>
                        <th>Rider's Name</th>
                        <th>Rider's Contact No.</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                


                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>

                    <td><?php echo htmlspecialchars($row['full_address']); ?></td>

                    <td><?php echo htmlspecialchars($row['phone']); ?></td>

                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>


                    <!-- Delivery Status -->
                    <td><?php echo htmlspecialchars($row['delivery_status']); ?></td>

                <!-- Get rider info from the rider_map -->
                <?php 
                    $rider_id = $row['rider_id'];
                    $rider_name = isset($rider_map[$rider_id]['rider_name']) ? htmlspecialchars($rider_map[$rider_id]['rider_name']) : 'Not Assigned Yet';
                    $rider_phone = isset($rider_map[$rider_id]['rider_phone']) ? htmlspecialchars($rider_map[$rider_id]['rider_phone']) : 'Not Assigned Yet';
                ?>

                    <td><?php echo $rider_name; ?></td>

                    <td><?php echo $rider_phone; ?></td>

                    <input type="hidden" name="delivery_id" value="<?php echo $row['delivery_id']; ?>">
                    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

                    <td>
                        <a href="order-details.php?order_id=<?php echo $row['order_id']; ?>" class="details-button">See Details</a>
                    </td>

               

            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include'../basic_php/footer.php';?>
<script src="../javascript_files/script.js"></script>
</body>
</html>
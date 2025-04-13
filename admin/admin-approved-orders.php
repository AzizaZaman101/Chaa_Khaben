<?php
include '../basic_php/connection.php';
session_start();


// Ensure only riders can view this page
if (!isset($_SESSION['admin_id'])) {
    die("Error: Unauthorized Access");
}

$admin_id = $_SESSION['admin_id'];

 
// Fetch all pending deliveries of this admin
$query = "
SELECT 
    d.admin_id,
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
WHERE d.admin_id= ?
ORDER BY o.order_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i",$admin_id);
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
<?php include './header_admin.php'; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="#">Approved Orders</a>
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
                        <th>Rider ID</th>
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

                    <td><?php echo htmlspecialchars($row['rider_id']); ?></td>

                    <input type="hidden" name="delivery_id" value="<?php echo $row['delivery_id']; ?>">
                    <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">

                    <td>
                        <a href="order-details-of-customer.php?order_id=<?php echo $row['order_id']; ?>" class="details-button">See Details</a>
                    </td>

               

            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include'../basic_php/footer.php';?>
</body>
</html>
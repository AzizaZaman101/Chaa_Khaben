<?php
include '../basic_php/connection.php';
session_start();

// Ensure only admins can view this page
if (!isset($_SESSION['admin_id'])) {
    die("Error: Unauthorized Access");
}

$admin_id = $_SESSION['admin_id'];


// Fetch product audit records with product details
$query = "SELECT 
            p.image,p.product_name,
            pa.audit_id,
            pa.product_id,
            pa.product_name AS audited_product_name,
            pa.product_price,
            pa.description,
            pa.stock_qty,
            pa.active_status,
            pa.image AS audited_image,
            pa.timestamp,
            pa.admin_id,
            pa.change_status
          FROM product_audit pa
          LEFT JOIN product p ON pa.product_id = p.product_id
          WHERE pa.admin_id=?
          ORDER BY pa.timestamp DESC";

$stmt=$conn->prepare($query);
$stmt->bind_param("i", $admin_id); 
$stmt->execute();
$result = $stmt->get_result()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Audit</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
</head>
<body>

<?php include'../admin/header_admin.php' ; ?>

<main>

<nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="#">History</a>
    </nav>
<h1>Admin Product Audit</h1>
<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Change Status</th>
            <th>Timestamp</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image"></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['change_status']); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                <td>
                <a href="admin-audited-information.php?audit_id=<?php echo $row['audit_id']; ?>" class="details-btn">
                See Details
                </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>
</main>

</body>

<?php include'../basic_php/footer.php' ; ?>
</html>

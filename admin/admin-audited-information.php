<?php
include '../basic_php/connection.php';
session_start();

// Ensure only admins can view this page
if (!isset($_SESSION['admin_id'])) {
    die("Error: Unauthorized Access");
}

$admin_id = $_SESSION['admin_id'];

if (isset($_GET['audit_id'])) {
     $audit_id = $_GET['audit_id'];




// Fetch product audit records with product details
$query = "SELECT 
            pa.product_id,
            pa.product_name,
            pa.product_price,
            pa.description,
            pa.stock_qty,
            pa.active_status,
            pa.image,
            pa.timestamp,
            pa.admin_id,
            pa.change_status
          FROM product_audit pa
          LEFT JOIN product p ON pa.product_id = p.product_id
          WHERE pa.audit_id=?";
          

          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $audit_id);  // Bind the audit_id parameter
          $stmt->execute();
          $row = $stmt->get_result();

          
    if ($row->num_rows > 0) {
        // Fetch the product audit details
        $result = $row->fetch_assoc();  // Fetch the data as an associative array
    } else {
        // Handle the case where no data is found
        echo "No product audit details found for this audit ID.";
        exit;
    }
}
else {
    echo "Audit ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/admin-audit-history.css">
    <title>Audited Information</title>
</head>
<body>

<?php include'../admin/header_admin.php' ; ?>

<main>
<nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="./admin-audit-history.php">History</a>
        <span>›</span>
        <a href="#">Audited Product Details</a>
    </nav>

    <h1>Audited Product Details</h1>

    <div class="profile-container">
        <form id="profile-form">
        <div class="profile-field">
            <label for="active_status">Active Status:</label>
            <input type="text" id="active_status" name="active_status" 
            value="<?php echo isset($result['active_status']) && $result['active_status'] !== '' ? htmlspecialchars($result['active_status']) : 'NULL'; ?>"  readonly>
        </div>

        <div class="profile-field">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" 
            value="<?php echo isset($result['image']) && $result['image'] !== '' ? htmlspecialchars($result['image']) : 'NULL'; ?>"  
            readonly>
        </div>
        
        <div class="profile-field">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" 
            value="<?php echo isset($result['product_name']) && $result['product_name'] !== '' ? htmlspecialchars($result['product_name']) : 'NULL'; ?>"  
            readonly>
        </div>

        <div class="profile-field">
            <label for="product_price">Product Price:</label>
            <input type="text" id="product_price" name="product_price" 
            value="<?php echo isset($result['product_price']) && $result['product_price'] !== '' ? htmlspecialchars($result['product_price']) : 'NULL'; ?>"  
            readonly>
        </div>

        <div class="profile-field">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" 
            value="<?php echo isset($result['description']) && $result['description'] !== '' ? htmlspecialchars($result['description']) : 'NULL'; ?>"  
            readonly>
        </div>

        <div class="profile-field">
            <label for="stock_qty">Stock Quantity:</label>
            <input type="text" id="stock_qty" name="stock_qty" 
            value="<?php echo isset($result['stock_qty']) && $result['stock_qty'] !== '' ? htmlspecialchars($result['stock_qty']) : 'NULL'; ?>"  readonly>
        </div>

            <a href="./admin-audit-history.php"  class="ok-btn">OK</a>
        </form>
    </div>


</main>
        
<?php include'../basic_php/footer.php' ; ?>

</body>
</html>
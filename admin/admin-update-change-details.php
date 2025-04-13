<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin not logged in.");
} 


$admin_id = $_SESSION['admin_id'];


$product_id = $_POST['product_id'] ?? null;
if (!$product_id) {
    die("Error: Product ID not provided.");
}


//fetch product details
$sql = "SELECT p.product_id, p.active_status, p.image, p.product_name, p.description, p.product_price, p.stock_qty 
        FROM product p 
        WHERE p.product_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $new_product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : $product['product_name'];


    $new_description = isset($_POST['description']) ? trim($_POST['description']) : $product['description'];

    $new_product_price = isset($_POST['product_price']) ? trim($_POST['product_price']) : $product['product_price'];

    $new_stock_qty = isset($_POST['stock_qty']) ? trim($_POST['stock_qty']) : $product['stock_qty'];

    $new_active_status = isset($_POST['active_status']) ? trim($_POST['active_status']) : $product['active_status'];

    $old_image = isset($_POST['old_image']) ? $_POST['old_image'] : $product['image'];


    // Validate that fields are not empty
    if (empty($new_product_name) || empty($new_description) || empty($new_product_price) || empty($new_stock_qty) || empty( $new_active_status)) {
        die("Error: Fields cannot be empty.");
    }

    // If an image is uploaded, handle it
    $new_image = $old_image; // By default, use the old image
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type and size
        if ($_FILES['image']['size'] > 5000000) {
            die("Error: File is too large.");
        }

        // Ensure the upload directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move file to target directory
        if (!move_uploaded_file($image_tmp, $target_file)) {
            die("Error: There was an issue uploading the image.");
        }
        $new_image = $target_file; // Update the image path
    }




// Track changes (set unchanged fields to NULL)
$changes = [];
if ($new_product_name != $product['product_name']) {
    $changes['product_name'] = $new_product_name;
}
if ($new_description != $product['description']) {
    $changes['description'] = $new_description;
}
if ($new_product_price != $product['product_price']) {
    $changes['product_price'] = $new_product_price;
}
if ($new_stock_qty != $product['stock_qty']) {
    $changes['stock_qty'] = $new_stock_qty;
}
if ($new_active_status != $product['active_status']) {
    $changes['active_status'] = $new_active_status;
}
if ($new_image != $product['image']) {
    $changes['image'] = $new_image;
}



try {

// Update product details
$update_sql = "UPDATE product SET product_name=?, description=?, product_price=?, stock_qty=?, active_status=?, image=? WHERE product_id=?";

$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ssdiisi", $new_product_name, $new_description, $new_product_price, $new_stock_qty, $new_active_status, $new_image, $product_id);
$stmt->execute();
$stmt->close();

 


// Insert into product_audit if any change was made
if (!empty($changes)) {
    $change_status = "updated";
    $timestamp = date('Y-m-d H:i:s');

    $product_name = $changes['product_name'] ?? null;
    $description = $changes['description'] ?? null;
    $product_price = $changes['product_price'] ?? null;
    $stock_qty = $changes['stock_qty'] ?? null;
    $active_status = isset($changes['active_status']) ? $changes['active_status'] : null; // Ensure a valid TINYINT
    $image = $changes['image'] ?? null;

    $audit_sql = "INSERT INTO product_audit (product_id, admin_id, change_status, timestamp, product_name, description, product_price, stock_qty, active_status, image)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($audit_sql);
    $stmt->bind_param("iissssdiis", $product_id, $admin_id, $change_status, $timestamp, $product_name, $description,             
    $product_price, $stock_qty,$active_status,    $image);             
    $stmt->execute();
    $stmt->close();
}

echo "<script>alert('Product updated successfully!'); window.location.href='./display-product-admin.php?product_id=$product_id';</script>";
exit();
}
catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
}
}

?>









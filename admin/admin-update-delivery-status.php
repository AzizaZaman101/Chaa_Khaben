<?php
// Assuming you already have a database connection
include '../basic_php/connection.php';

// Get form values
$delivery_id = $_POST['delivery_id'];
$delivery_status = $_POST['delivery_status'];
$admin_id = $_POST['admin_id'];

// If the status is being changed to "shipped", update the status and admin_id
if ($delivery_status == 'shipped') {
    // Update the delivery status and admin ID in the database
    $sql = "UPDATE delivery SET delivery_status = ?, admin_id = ? WHERE delivery_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $delivery_status, $admin_id, $delivery_id);
    
    if ($stmt->execute()) {
        // Successfully updated
        header("Location: ./orders-admin.php?status=success");
    } else {
        // Error updating
        header("Location: ./orders-admin.php?status=failed");
    }
}
?>
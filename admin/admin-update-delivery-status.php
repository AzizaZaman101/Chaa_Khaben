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

    $order_sql = "SELECT order_id FROM delivery WHERE delivery_id = ?";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $delivery_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $order_row = $order_result->fetch_assoc();
    $order_id = $order_row['order_id'];

    // 2. Get customer_id from orders
    $customer_sql = "SELECT customer_id FROM orders WHERE order_id = ?";
    $customer_stmt = $conn->prepare($customer_sql);
    $customer_stmt->bind_param("i", $order_id);
    $customer_stmt->execute();
    $customer_result = $customer_stmt->get_result();
    $customer_row = $customer_result->fetch_assoc();
    $customer_id = $customer_row['customer_id'];

    // 3. Get upazila_id from customer
    $upazila_sql = "SELECT upazila_id FROM customer WHERE customer_id = ?";
    $upazila_stmt = $conn->prepare($upazila_sql);
    $upazila_stmt->bind_param("i", $customer_id);
    $upazila_stmt->execute();
    $upazila_result = $upazila_stmt->get_result();
    $upazila_row = $upazila_result->fetch_assoc();
    $customer_upazila = $upazila_row['upazila_id'];

    // 4. Now find a suitable rider
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

    if ($rider_id !== null) {
        // 5. Assign rider, update delivery status + admin id
        $update_delivery_sql = "UPDATE delivery SET delivery_status = ?, admin_id = ?, rider_id = ? WHERE delivery_id = ?";
        $update_stmt = $conn->prepare($update_delivery_sql);
        $update_stmt->bind_param("siii", $delivery_status, $admin_id, $rider_id, $delivery_id);
        $update_stmt->execute();

        // 6. Increase pending_delivery for the rider
        $update_rider_sql = "UPDATE rider SET pending_delivery = pending_delivery + 1 WHERE rider_id = ?";
        $update_rider_stmt = $conn->prepare($update_rider_sql);
        $update_rider_stmt->bind_param("i", $rider_id);
        $update_rider_stmt->execute();

        header("Location: ./orders-admin.php?status=success");
        exit;
    } else {
        // No rider found
        header("Location: ./orders-admin.php?status=no_rider");
        exit;
    }
}
?>
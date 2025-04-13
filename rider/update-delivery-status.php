<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['rider_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {

    $payment_id = $_POST['payment_id'];
    $delivery_id = $_POST['delivery_id'];
    $new_payment_status = isset($_POST['payment_status']) ? intval($_POST['payment_status']) : null;
    $new_delivery_status = isset($_POST['delivery_status']) ? $_POST['delivery_status'] : null;


    // Fetch current payment & delivery statuses
    $query = "
    SELECT p.payment_clear, d.delivery_status 
    FROM payment p 
    JOIN orders o ON p.order_id = o.order_id
    JOIN delivery d ON o.order_id = d.order_id
    WHERE p.payment_id = ? AND d.delivery_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $payment_id, $delivery_id);
    $stmt->execute();
    $stmt->bind_result($current_payment_status, $current_delivery_status);
    $stmt->fetch();
    $stmt->close();

    // Prevent if already clear/delivered
    if ($current_payment_status == 1 && $current_delivery_status == 'delivered') {
        header("Location: ./rider-pending-orders.php");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // 1. Update payment if still due
        if ($current_payment_status == 0 && $new_payment_status == 1) {
            $updatePayment = $conn->prepare("UPDATE payment SET payment_clear = 1 WHERE payment_id = ?");
            $updatePayment->bind_param("i", $payment_id);
            $updatePayment->execute();
            $updatePayment->close();
        }

        // 2. Update delivery only if payment is clear and not already delivered
        if ($new_delivery_status === 'delivered' &&$current_delivery_status === 'shipped') {

            $now = date("Y-m-d H:i:s");
            $updateDelivery = $conn->prepare("UPDATE delivery SET delivery_status = 'delivered', actual_delivery_date = ? WHERE delivery_id = ?");
            $updateDelivery->bind_param("si", $now, $delivery_id);
            
            if (!$updateDelivery->execute()) {
                throw new Exception("Error executing delivery status update: " . $updateDelivery->error);
            }
            $updateDelivery->close();
        }

        $conn->commit();
        header("Location: ./rider-pending-orders.php?update=success");
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Failed to update: " . $e->getMessage());
        header("Location: ./rider-pending-orders.php?update=failed");
    }
} else {
    header("Location: ./rider-pending-orders.php");
}
?>

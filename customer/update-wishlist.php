<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    die("Error: User not logged in.");
}

$customer_id = $_SESSION['customer_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id > 0) {
    // Check if the product is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) { // If not in wishlist, add it
        $insertQuery = "INSERT INTO wishlist (customer_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $customer_id, $product_id);
        if ($stmt->execute()) {
            echo 'added';
        } else {
            echo 'error';
        }
    } else { // If in wishlist, remove it
        $deleteQuery = "DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $customer_id, $product_id);
        if ($stmt->execute()) {
            echo 'removed';
        } else {
            echo 'error';
        }
    }
}
?>

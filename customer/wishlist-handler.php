<?php
include '../basic_php/connection.php' ; 


session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Ensure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    http_response_code(401); // Unauthorized
    echo "Error: User not logged in.";
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Check if product_id is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Ensure it's a valid integer

    // Check if the product is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists, remove from wishlist
        $deleteQuery = "DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $customer_id, $product_id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "removed", "message" => "Removed from wishlist."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error removing from wishlist: " . $conn->error]);
        }
    } else {
        // Product does not exist, add to wishlist
        $insertQuery = "INSERT INTO wishlist (customer_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $customer_id, $product_id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "added", "message" => "Added to wishlist."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error adding to wishlist: " . $conn->error]);
        }
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

exit;
?>
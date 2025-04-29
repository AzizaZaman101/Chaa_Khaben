<?php
include './connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


$product_name = "Premium Tea Leaf";
$product_price = 300;
$description = "Our premium tea leaves offer a rich, bold flavor with a smooth, aromatic finish.";
$image = "../images/buy-ingredients/chaa_pata.jpg";
$stock_qty = 40;
$category_id = 1;

$sql = "INSERT INTO product (product_name, product_price, description, image, stock_qty, active_status, category_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

try {
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$product_name, $product_price, $description, $image, $stock_qty, $active_status, $category_id])) {
                echo "Product added successfully!";
        } else {
                echo "Failed to add product.";
        }
} catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
}

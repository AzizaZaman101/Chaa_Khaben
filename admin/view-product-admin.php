<?php
include '../basic_php/connection.php' ; 

session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($_GET['product_id'])) {
    echo "No product ID passed!";
}

$product_id = $_GET['product_id'];



$sql = "SELECT p.*, c.category_name,p.category_id
FROM product p
JOIN category c on p.category_id = c.category_id
WHERE p.product_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
$category_id = $product['category_id'];


// Fetch related products from same category (excluding current product)
$related_stmt = $conn->prepare("
    SELECT * FROM product 
    WHERE category_id = ? AND product_id != ? AND active_status = 1
");
$related_stmt->bind_param("ii", $category_id, $product_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();

if ($related_result->num_rows === 0) {
    echo "<p>No related products found in the same category.</p>";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/view-product.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

<?php include './header_admin.php';?>



        <main>

            <nav class="breadcrumb-nav">
                <a href="./index-admin.php">Home</a>
                <span>›</span>
                <a href="./display-product-admin.php">Products</a>
                <span>›</span>
                <a href="#"><?php echo $product['product_name']; ?></a>
            </nav>

            
            
<div class="product-container">
            
    <div class="product-main">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <p><strong>Price:</strong> ৳<?php echo htmlspecialchars($product['product_price']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
    </div>


    <div class="related-section">
        <h2 class="related-title">Related Products</h2>
        <div class="related-slider">
            <?php while($related = $related_result->fetch_assoc()): ?>
                <div class="related-card">
                    <a href="./view-product-customer.php?product_id=<?php echo $related['product_id']; ?>">
                        <img src="<?php echo htmlspecialchars($related['image']); ?>" alt="Related Product">
                        <h4><?php echo htmlspecialchars($related['product_name']); ?></h4>
                        <p>৳<?php echo htmlspecialchars($related['product_price']); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
 
</div>
    </main>

    <?php include '../basic_php/footer.php'; ?>
</body>

</html>

<?php $conn->close(); ?>
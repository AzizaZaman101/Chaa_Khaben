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


$customer_id = $_SESSION['customer_id'];

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

<?php include './header_customer.php';?>



        <main>

            <nav class="breadcrumb-nav">
                <a href="./index-customer.php">Home</a>
                <span>›</span>
                <a href="./display_products.php">Products</a>
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

    
<?php if ($product['stock_qty'] == 0): ?>
    <a href="#" class="add-to-cart" style="cursor: not-allowed;">Add to Cart</a>
<?php else: ?>
    <a href="#" class="add-to-cart"
       onclick="addToCart(
           <?= $product['product_id'] ?>,
           '<?= htmlspecialchars($product['product_name'], ENT_QUOTES) ?>',
           <?= $product['product_price'] ?>,
           '<?= htmlspecialchars($product['image'], ENT_QUOTES) ?>',
           <?= $product['stock_qty'] ?>,
       )">Add to Cart</a>
<?php endif; ?>


<?php if ($related_result->num_rows === 0) {
    echo "<p><strong>No related products found in the same category.</strong></p>";
}?>
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
    <script src="../javascript_files/add_to_cart.js"></script>
    <script>
    // Prevent back button from showing cached page
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.href = "../regular/index.php";
        }
    });
</script>
</body>

</html>

<?php $conn->close(); ?>
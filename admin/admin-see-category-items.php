<?php

include '../basic_php/connection.php' ; 
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");



if (!isset($_GET['category_name']) || empty(trim($_GET['category_name']))) {
    die("Error: No category selected.");
}

// Get category_id from URL
$category_name = trim($_GET['category_name']);



// Fetch category details
$categoryQuery = "SELECT category_id, category_name FROM category WHERE category_name = ?";


$stmt = $conn->prepare($categoryQuery);

if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}


$stmt->bind_param("s", $category_name);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();


if (!$category) {
    die("Error: Category not found.");
}

$category_id = $category['category_id'];

// Fetch products under this category
$productQuery = "SELECT p.product_id, p.product_name,p.product_price, p.image 
                 FROM product p
                 JOIN category c ON p.category_id = c.category_id
                 WHERE c.category_id = ? AND p.active_status = 1";


$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $category_id);
$stmt->execute();

$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Count total products
$totalProducts = count($products);
?>
 
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/display_products.css">
    <title><?php echo htmlspecialchars($category['category_name']); ?> - Products</title>
</head>
<body>
    <?php include './header_admin.php'; ?>

    <main>
    <nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="./display-product-admin.php">Products</a>
        <span>›</span>
        <a href="#">Items</a>
    </nav>

    <h1><?php echo htmlspecialchars($category['category_name']); ?></h1>


    <div class="product-list">
        <?php if ($totalProducts > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                    <p>Price: <?php echo $product['product_price']; ?> BDT</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found in this category.</p>
        <?php endif; ?>
    </div>
    </main>

    <?php include '../basic_php/footer.php'; ?>
    <?php include '../javascript_files/prevent_access.js'; ?>
</body>
</html>
<?php
include '../basic_php/connection.php';
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Ensure it's an integer

    // Check if the product is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) { // Insert only if not already in wishlist
        $insertQuery = "INSERT INTO wishlist (customer_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $customer_id, $product_id);
        if ($stmt->execute()) {
            echo "<script>alert('Product added to wishlist successfully!');</script>";
        } else {
            echo "<script>alert('Error adding to wishlist: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Product is already in your wishlist.');</script>";
    }
}

// Fetch wishlist products for the logged-in user
$sql = "SELECT w.product_id, p.product_name, p.product_price, p.image, p.stock_qty 
        FROM wishlist w 
        JOIN product p ON w.product_id = p.product_id
        WHERE w.customer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/display_products.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/view-product.css">
</head>

<body>

    <?php include './header_customer.php'; ?>

    <main>
        <nav class="breadcrumb-nav">
            <a href="./index-customer.php">Home</a>
            <span>›</span>
            <a href="#">Wishlist</a>
        </nav>

        <div class="heading">Your Wishlist</div>
        
        <?php if ($result->num_rows > 0): ?>
        <div class="wish-list" id="listProduct">

            <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product" id="product">
                
                        <a href="./view-product-customer.php?product_id=<?php echo $product['product_id'];?>" class="product-link">
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
                            <h3><?= htmlspecialchars($product['product_name']); ?></h3>
                            <p>Price: <?= htmlspecialchars($product['product_price']); ?> BDT</p>
                            <p><?= ($product['stock_qty'] > 0) ? "In Stock" : "<span class='out-of-stock'>Out of Stock</span>"; ?></p>
                        </a>
                    
            </div>
            <?php endwhile; ?>
                    <?php else: ?>
                    <p class="empty-wishlist">No items in your wishlist!</p>
                <?php endif; ?>
            </div>
    </main> 

    <?php include '../basic_php/footer.php'; ?>
    <script src="../javascript_files/script.js"></script>

</body>
</html>
 

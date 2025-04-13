<?php
include '../basic_php/connection.php';

session_start(); // Make sure session is started

$show_login_message = false;

if (!isset($_SESSION['customer_id'])) {
    $show_login_message = true;
} else {
    $sql = "SELECT w.product_id, p.product_name, p.product_price, p.image, p.stock_qty 
            FROM wishlist w 
            JOIN product p ON w.product_id = p.product_id
            WHERE w.customer_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['customer_id']);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/display_products.css">
    <title>Wishlist</title>
</head>

<body>
 

    <?php include './header.php' ;?>

    <main>
    <nav class="breadcrumb-nav">
        <a href="./index.php">Home</a>
        <span>›</span>
        <a href="#">Wishlist</a>
    </nav>
    
    <div class="wish-list-container">
    <div class="heading">Your Wishlist</div>
        
    <?php if ($show_login_message): ?>
        <div class="login-warning-div">
    <p class="login-warning">Please login first to see your wishlist.</p>
    </div>
<?php elseif ($result->num_rows > 0): ?>
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

    <?php include '../basic_php/footer.php' ;?>

</body>
</html>
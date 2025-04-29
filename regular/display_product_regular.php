<?php
include '../basic_php/connection.php' ;

function isProductInWishlist($product_id) {
    global $conn;
    session_start();
    $customer_id = $_SESSION['customer_id'];
    if (!isset($_SESSION['customer_id'])) return false;

    
    $query = "SELECT * FROM wishlist WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

$category_sql = "SELECT category.category_name, product.           product_id, product.product_name 
                 FROM category 
                 JOIN product ON category.category_id = product.category_id 
                 WHERE product.active_status = 1";
$category_result = $conn->query($category_sql);

$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[$row['category_name']][] = $row;
}

$sql = "SELECT * FROM product WHERE active_status = 1";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="../style/display_products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>-->
</head>

<body>

<?php
include './header.php';?>

    <div class="product_showcase">

        <div class="sidebar">
            <h2>Categories</h2>
            <?php foreach ($categories as $category_name => $products): ?>
                <h3><?php echo $category_name; ?></h3>
                <ul>
                    <?php foreach ($products as $product): ?>
                        <li><a href="#product-<?php echo $product['product_id']; ?>"> <?php echo $product['product_name']; ?> </a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>




        <div class="content">

            <nav class="breadcrumb-nav">
                <a href="./index.php">Home</a>
                <span>›</span>
                <a href="#">Products</a>
            </nav>

            <!--<form action="" class="search_form">
                <input type="text" id="searchBox" class="search_box" placeholder="Search for products..." onkeyup="searchProducts()">
                <i class="fa fa-search" id="search_icon"></i>
            </form>
                        -->

            <h1>Our Products</h1>
            
            <!--<input type="search" placeholder="Search here..." class="search_box">-->
            
            <input type="text" id="searchBox" placeholder="Search for products..." onkeyup="searchProducts()">

            <div class="product-list" id="listProduct">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!--<div class="product">-->
                
                <div class="product" id="product-<?php echo $row['product_id']; ?>">
                    <div class="card-icon">
                        <i id="wish-list" class="fa-solid fa-heart"
                        onclick="addToWishlist(<?= $row['product_id'] ?>, 
                        `<?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?>`, 
                        <?= $row['product_price'] ?>, 
                        `<?= htmlspecialchars($row['image'], ENT_QUOTES) ?>`)"></i>
                       

                       <i class="fa-solid fa-cart-plus" id="add-to-cart"
                        onclick="addToCart(
                        <?= $row['product_id'] ?>,
                        '<?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?>',
                        <?= $row['product_price'] ?>,
                        '<?= htmlspecialchars($row['image'], ENT_QUOTES) ?>'
                        )"></i>
                    </div>

                    <a href="#product-<?php echo $row['product_id']; ?>" class="product-link">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
                        <h2><?php echo $row['product_name']; ?></h2>
                        <p><?php echo $row['description']; ?></p>
                        <div class="price"><?php echo $row['product_price']; ?> BDT</div>
                        <div class="stock">
                            <?php echo ($row['stock_qty'] > 0) ? "In Stock" : "<span class='out-of-stock'>Out of Stock</span>"; ?>
                        </div>
                    </a>
                </div>
                
                <?php endwhile; ?>
            </div>

        </div>
    </div>

    <?php include '../basic_php/footer.php'; ?>


    <script>
        function searchProducts() {
            let input = document.getElementById("searchBox").value.toLowerCase();
            let products = document.querySelectorAll(".product");

            products.forEach(product => {
                let name = product.querySelector("h2").textContent.toLowerCase();
                product.style.display = name.includes(input) ? "block" : "none";
            });
        }
    </script>
    <script src="../javascript_files/add_to_cart.js"></script>
</body>

</html>

<?php $conn->close(); ?>
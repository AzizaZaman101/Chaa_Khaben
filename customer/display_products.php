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

$category_sql = "SELECT category.category_name, product.product_id, product.product_name 
                 FROM category 
                 JOIN product ON category.category_id = product.category_id 
                 WHERE product.active_status = 1";
$category_result = $conn->query($category_sql);

$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[$row['category_name']][] = $row;
}


$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

switch ($sort) {
    case 'az':
        $sql = "SELECT * FROM product ORDER BY product_name ASC";
        break;
    case 'za':
        $sql = "SELECT * FROM product ORDER BY product_name DESC";
        break;
    case 'low_high':
        $sql = "SELECT * FROM product ORDER BY product_price ASC";
        break;
    case 'high_low':
        $sql = "SELECT * FROM product ORDER BY product_price DESC";
        break;
    default:
        $sql = "SELECT * FROM product";
        break;
}

$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

<?php
include './header_customer.php';?>

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
                <a href="./index-customer.php">Home</a>
                <span>›</span>
                <a href="#">Products</a>
            </nav>

            <!--Sorting div-->
            <div class="sorting-products">
                <form method="GET" action="">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="">Default</option>

                        <option value="az" <?php if ($sort == 'az') echo 'selected'; ?>>Name (A-Z)</option>

                        <option value="za" <?php if ($sort == 'za') echo 'selected'; ?>>Name (Z-A)</option>

                        <option value="low_high" <?php if ($sort == 'low_high') echo 'selected'; ?>>Price (Low to High)</option>

                        <option value="high_low" <?php if ($sort == 'high_low') echo 'selected'; ?>>Price (High to Low)</option>

                    </select>
                </form>
            </div>

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

                        <i id="wish-list-<?php echo $row['product_id']; ?>" class="fa-solid fa-heart <?php echo isProductInWishlist($row['product_id']) ? 'wishlist-added' : ''; ?>" onclick="toggleWishlist(<?= $row['product_id'] ?>)"></i>
  


                        <?php if ($row['stock_qty'] == 0): ?>
                            <i class="fa-solid fa-cart-plus" style="color: grey; cursor: not-allowed;" 
                            title="Out of Stock"></i>
                        <?php else: ?>
                        <i class="fa-solid fa-cart-plus" id="add-to-cart"
                        onclick="addToCart(
                        <?= $row['product_id'] ?>,
                        '<?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?>',
                        <?= $row['product_price'] ?>,
                        '<?= htmlspecialchars($row['image'], 
                        ENT_QUOTES) ?>',
                        <?= $row['stock_qty'] ?>,
                        )"></i>
                       <?php endif; ?>
                    </div>

                    <a href="./view-product-customer.php?product_id=<?php echo $row['product_id'];?>" class="product-link">
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

    <script src="../javascript_files/script.js"></script>
    <script src="../javascript_files/add_to_cart.js"></script>

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

<script>
function toggleWishlist(product_id) {
    console.log("Clicked on wishlist icon for:", product_id);

    // Get the icon element based on the product_id
    const icon = document.getElementById('wish-list-' + product_id);

    fetch("../customer/wishlist-handler.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${product_id}`
    })
    .then(response => response.text())
    .then(data => {
        console.log("Response from server:", data);
        alert(data);

        if (data === "added") {
            icon.classList.add("wishlist-added");
        } else if (data === "removed") {
            icon.classList.remove("wishlist-added");
        } else {
            console.error('Unexpected response:', data);
        }   
        
        window.location.href = "./display_products.php";

    })
    .catch(error => console.error("Error:", error));
}
</script>

<script>
    window.onload = function() {
        window.scrollTo(0, 0);
    }
</script>

<script src="../javascript_files/script.js"></script>
</body>

</html>

<?php $conn->close(); ?>
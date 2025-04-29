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

$category_sql = "SELECT c.category_name, c.category_id, p.product_id, p.product_name 
                 FROM category c
                 JOIN product p ON c.category_id = p.category_id ";

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
    case 'active':
        $sql = "SELECT * FROM product WHERE active_status = 1 ORDER BY product_name ASC";
        break;
    case 'inactive':
        $sql = "SELECT * FROM product WHERE active_status = 0 ORDER BY product_name ASC";
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

<?php include './header_admin.php';?>

    <div class="product_showcase">

        <div class="sidebar">
            <h2>Categories</h2>
            <?php foreach ($categories as $category_name => $products): ?>
                <h3 class="for-admin">
                <a href="./admin-see-category-items.php?category_name=<?php echo urlencode($category_name); ?>">
                <?php echo htmlspecialchars($category_name); ?>
            </a>
                </h3>
            <?php endforeach; ?>
            <a href="./admin_add_delete_category.php" class="add-new-category">
                <i class="fa-solid fa-plus"></i>
                Add new category
            </a>

        </div>



        <div class="content">

            <nav class="breadcrumb-nav">
                <a href="./index-admin.php">Home</a>
                <span>›</span>
                <a href="#">Products</a>
            </nav>

            <!--Sorting div-->
            <div class="sorting-products">
                <form method="GET" action="">
                    <label for="sort">Sort by : </label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="">Default</option>

                        <option value="az" <?php if ($sort == 'az') echo 'selected'; ?>>Name (A-Z)</option>

                        <option value="za" <?php if ($sort == 'za') echo 'selected'; ?>>Name (Z-A)</option>

                        <option value="low_high" <?php if ($sort == 'low_high') echo 'selected'; ?>>Price (Low to High)</option>

                        <option value="high_low" <?php if ($sort == 'high_low') echo 'selected'; ?>>Price (High to Low)</option>

                        <option value="active" <?php if ($sort == 'active') echo 'selected'; ?>>Active Status</option>

                        <option value="inactive" <?php if ($sort == 'inactive') echo 'selected'; ?>>Inactive Status</option>

                    </select>
                </form>
            </div>

            <h1>Our Products</h1>
            
            <input type="text" id="searchBox" placeholder="Search for products..." onkeyup="searchProducts()">

            <div class="product-list" id="listProduct">
                <?php while ($row = $result->fetch_assoc()): ?>
                    
                
                <div class="product" id="product-<?php echo $row['product_id']; ?>">
                    <div class="card-icon">
                        <a href="./view-product-admin.php?product_id=<?php echo $row['product_id']; ?>"><i id="view-product" class="fa-solid fa-eye"></i></a>
                       
 
                       <a href="./admin-change-product-details.php?product_id=<?php echo $row['product_id']; ?>"><i id="edit-product" class="fa-solid fa-pen-to-square"></i></a>
                       
                    </div>

                    <a href="./view-product-admin.php?product_id=<?php echo $row['product_id'];?>" class="product-link">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
                        <h2><?php echo $row['product_name']; ?></h2>
                        <p><?php echo $row['description']; ?></p>
                        <div class="price"><?php echo $row['product_price']; ?> BDT</div>
                        <div class="stock">
                            <?php echo ($row['stock_qty'] > 0) ? "In Stock" : "<span class='out-of-stock'>Out of Stock</span>"; ?>
                        </div>
                    </a>
                </div>

                <!--Add new item button -->
                <a href=".\admin_add_delete_product.php" class="add-new-product"><i class="fa-solid fa-plus"></i>Add new product</a>
                
                <?php endwhile; ?>
            </div>

        </div>
    </div>
 
    <?php include '../basic_php/footer.php'; ?>

    <script src="../javascript_files/script.js"></script>

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
    window.onload = function() {
        window.scrollTo(0, 0);
    }
</script>
<script src="../javascript_files/script.js"></script>
</body>

</html>

<?php $conn->close(); ?>
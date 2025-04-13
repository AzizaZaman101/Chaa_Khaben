<?php
include '../basic_php/connection.php' ; 

session_start();
$admin_id = $_SESSION['admin_id']; 

// Fetch categories for dropdown
$categoryQuery = "SELECT category_id, category_name FROM category";
$categoryStmt = $conn->query($categoryQuery);
$categories = $categoryStmt->fetch_all(MYSQLI_ASSOC); // FIXED: Use MySQLi method

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $stock_qty = $_POST['stock_qty'];
    $active_status = $_POST['active_status'];
    $category_name = $_POST['category_name'];

    // Get category_id from category_name
    $stmt = $conn->prepare("SELECT category_id FROM category WHERE category_name = ?");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $category_id = $category['category_id'];

       
    $change_status = "added";
        
    $timestamp = date('Y-m-d H:i:s');

    

    $sql = "INSERT INTO product (product_name, product_price, description, image, stock_qty, active_status, category_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssiii", $product_name, $product_price, $description, $image, $stock_qty, $active_status, $category_id);
    $stmt->execute();
    $product_id = $conn->insert_id;

    $audit_sql = "INSERT INTO product_audit (product_id, admin_id, change_status, timestamp, product_name, description, product_price, stock_qty, active_status, image)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($audit_sql);
    $stmt->bind_param("iissssdiis", $product_id, $admin_id, $change_status, $timestamp, $product_name, $description, $product_price, $stock_qty,$active_status, $image);             
    $stmt->execute();
    $stmt->close();

    echo "Product added successfully!";
}


//for product deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {

    $product_id = $_POST['product_id'];

    // Fetch the product details before deletion for audit
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    // Insert the product details into the product_audit table with 'Deleted' status
    $admin_id = $_SESSION['admin_id']; 

    $timestamp = date('Y-m-d H:i:s');
    $change_status = 'deleted';
    
    $audit_sql = "INSERT INTO product_audit (product_id, admin_id, change_status, timestamp, product_name, description, product_price, stock_qty, active_status, image)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($audit_sql);
    $stmt->bind_param("iissssdiis", $product_id, $admin_id, $change_status, $timestamp, $product['product_name'], $product['description'], $product['product_price'], $product['stock_qty'], $product['active_status'], $product['image']);
    $stmt->execute();

    // Now delete the product
    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    echo "Product deleted successfully!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style/display_products.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <title>Manage Products</title>
    <script>
        function confirmDelete(productId, productName) {
            if (confirm(`Do you want to delete "${productName}"?`)) {
                document.getElementById('delete_product_id').value = productId;
                document.getElementById('delete_product_form').submit();
            }
        }
    </script>
</head>

<body>

    <?php include './header_admin.php'; ?>

<main>
    <nav class="breadcrumb-nav">
    <a href=".\index-admin.php">Home</a>
    <span>›</span>
    <a href="./display-product-admin.php">Products</a>
    <span>›</span>
    <a href="#">Add New Product</a>
    </nav>



<section class="add-product-form">

    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="product_price" placeholder="Product Price" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="text" name="image" placeholder="Image URL" required>
        <input type="number" name="stock_qty" placeholder="Stock Quantity" required>
        <select name="active_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <select name="category_name">
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['category_name']); ?>">
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add_product">Add Product</button>
    </form>

</section>



    <h1>Product List</h1>

    <div class="product-list" id="listProduct">
    <?php
        $stmt = $conn->query("SELECT product_id, product_name,image, description, product_price,active_status FROM product");
        $products = $stmt->fetch_all(MYSQLI_ASSOC);
        foreach ($products as $product): ?>

    <div class="product" id="product-<?php echo $row['product_id']; ?>">

            <div class="card-icon">
                <i></i>
                <i id="remove-category" class="fa-solid fa-xmark" onclick="confirmDelete(<?php echo $product['product_id']; ?>, 
                '<?php echo addslashes($product['product_name']); ?>')"></i>
            </div>

            <a href="#product-<?php echo $product['product_id']; ?>" class="product-link">

                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
                        <h2><?php echo $product['product_name']; ?></h2>
                        <p><?php echo $product['description']; ?></p>
                        <div class="price"><?php echo $product['product_price']; ?> BDT</div>
             
            </a>
        </div>
        <?php endforeach; ?>
    </div>









    <form id="delete_product_form" method="POST" style="display:none;">
        <input type="hidden" id="delete_product_id" name="product_id">
        <input type="hidden" name="delete_product" value="1">
    </form>


</main>


    <?php include '../basic_php/footer.php'; ?>
</body>

</html>
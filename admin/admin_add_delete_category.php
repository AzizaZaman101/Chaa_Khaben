<?php 
include '../basic_php/connection.php' ; 

<<<<<<< HEAD
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


=======
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
// Fetch categories for display
$categoryQuery = "SELECT category_id, category_name, category_image FROM category";
$categoryStmt = $conn->query($categoryQuery);
$categories = $categoryStmt->fetch_all(MYSQLI_ASSOC); // Fetch all categories

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
<<<<<<< HEAD
    //$category_image = trim($_POST['category_image']);

    if (!empty($_FILES['category_image']['name'])) {
        $image_name = basename($_FILES['category_image']['name']);
        $image_tmp = $_FILES['category_image']['tmp_name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type and size
        if ($_FILES['category_image']['size'] > 5000000) {
            die("Error: File is too large.");
        }
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("Error: Invalid file type.");
        }

        // Ensure the upload directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move file to target directory
        if (!move_uploaded_file($image_tmp, $target_file)) {
            die("Error: There was an issue uploading the image.");
        }
        $category_image = $target_file; // Update the image path
    }
=======
    $category_image = trim($_POST['category_image']);
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0

    if (empty($category_name) || empty($category_image)) {
        echo "<script>alert('Category name and image are required!'); window.location.href='./admin_add_delete_category.php';</script>";
        exit;
    }

    // Check if category already exists
    $checkQuery = "SELECT category_id FROM category WHERE category_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Category already exists!'); window.location.href='./admin_add_delete_category.php';</script>";
    } else {
        $insertQuery = "INSERT INTO category (category_name, category_image) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $category_name, $category_image);

        if ($stmt->execute()) {
            echo "<script>alert('Category added successfully!'); window.location.href='./admin_add_delete_category.php';</script>";
        } else {
            echo "<script>alert('Error adding category.'); window.location.href='./admin_add_delete_category.php';</script>";
        }
}
}

// Handle category deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];
    
    // Check if category is linked to any product
    $checkProductQuery = "SELECT product_id FROM product WHERE category_id = ?";
    $stmt = $conn->prepare($checkProductQuery);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Cannot delete category with associated products!'); window.location.href='./admin_add_delete_category.php';</script>";
    } else {
        $deleteQuery = "DELETE FROM category WHERE category_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $category_id);

        if ($stmt->execute()) {
            echo "<script>alert('Category deleted successfully!'); window.location.href='./admin_add_delete_category.php';</script>";
        } else {
            echo "<script>alert('Error deleting category.'); window.location.href='./admin_add_delete_category.php';</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style/display_products.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <title>Manage Categories</title>
    <script>
        function confirmDelete(categoryId, categoryName) {
            if (confirm(`Do you want to delete "${categoryName}"?`)) {
                document.getElementById('delete_category_id').value = categoryId;
                document.getElementById('delete_category_form').submit();
            }
        }
    </script>
</head>

<body>
    <?php include './header_admin.php'; ?>

<main>

    <nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="./display-product-admin.php">Products</a>
        <span>›</span>
        <a href="#">Manage Categories</a>
    </nav>


    <h1>Add New Category</h1>

    <section class="add-category-form">
<<<<<<< HEAD
        <form method="POST" enctype="multipart/form-data">
=======
        <form method="POST">
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
            <input type="text" name="category_name" placeholder="Category Name" required>
            <input type="file" name="category_image" placeholder="Category Image" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>
    </section>


    <h1>Category List</h1>

    <div class="category-list" id="listCategory">
        <?php foreach ($categories as $category): ?>

        <div class="category" id="category-<?php echo $category['category_id']; ?>">
            <div class="card-icon">
                <i></i>
                <i id="remove-category" class="fa-solid fa-xmark" onclick="confirmDelete(<?php echo $category['category_id']; ?>, 
                '<?php echo addslashes($category['category_name']); ?>')"></i>
            </div>


            <a href="#category-<?php echo $category['category_id']; ?>" class="category-link">
                <img src="<?php echo htmlspecialchars($category['category_image']); ?>" 
                alt="<?php echo htmlspecialchars($category['category_name']); ?>">
                <h2><?php echo htmlspecialchars($category['category_name']); ?></h2>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
                
            

    <form id="delete_category_form" method="POST" style="display:none;">
        <input type="hidden" id="delete_category_id" name="category_id">
        <input type="hidden" name="delete_category" value="1">
    </form>
        
</main>

    <?php include '../basic_php/footer.php'; ?>
<<<<<<< HEAD
    <script src="../javascript_files/prevent_access.js"></script>
=======
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
</body>
</html>

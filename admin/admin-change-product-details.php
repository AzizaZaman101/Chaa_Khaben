<?php
include '../basic_php/connection.php';

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
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Admin ID: " . ($_SESSION['admin_id'] ?? 'Not Set');

if (!isset($_SESSION['admin_id'])) {
    die("Error: User not logged in.");
}

>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
$admin_id = $_SESSION['admin_id'];
$product_id = $_GET['product_id'] ?? null;


if (!$product_id) {
    die("Error: Product ID not provided.");
} 



// Fetch user details except password and user_type
$sql = "SELECT p.active_status, p.image, p.product_name, p.description, p.product_price, p.stock_qty FROM product p
WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <script src="https://kit.fontawesome.com/YOUR_FONT_AWESOME_KIT.js" crossorigin="anonymous"></script>
    <title>Change Product Details</title>
</head>
<body>

    <?php include './header_admin.php';?>

<main>
    <nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="#">Change Product Details</a>
    </nav>

    <h1>Change Product Details</h1>
    <div class="profile-container">

    
    <form method="POST" action="./admin-update-change-details.php" id="profile-form" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $product_id;?>">
    
            <div class="profile-field">

                <input type="radio" name="active_status" id="active" value="active" <?php echo ($product['active_status'] == 1) ? 'checked' : ''; ?> disabled>
                <label for="active">Active</label>
                
                <input type="radio" name="active_status" id="inactive" value="inactive" <?php echo ($product['active_status'] == 0) ? 'checked' : ''; ?> disabled>
                <label for="inactive">Inactive</label>

                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableRadioEdit('active_status')"></i>
                
            </div>

            <div class="profile-field">
              <div class="file-input">
                <img id="image-preview" src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <input type="file" name="image" id="image" accept="image/*" disabled>
              </div>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('image')"></i>
            </div>

        <div class="profile-field">
            <input type="text" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('product_name')"></i>
        </div>

        <div class="profile-field">
            <textarea name="description" id="description" disabled><?php echo htmlspecialchars($product['description']); ?></textarea>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('description')"></i>
        </div>

        <div class="profile-field">
            <input type="number" name="product_price" id="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('product_price')"></i>
        </div>

        <div class="profile-field">
            <input type="number" name="stock_qty" id="stock_qty" value="<?php echo htmlspecialchars($product['stock_qty']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('stock_qty')"></i>
        </div>


        <button type="submit" class="save-btn" id="save-btn" disabled>Save</button>
    </form>
</div>
</main>

<?php include '../basic_php/footer.php' ; ?>

<script>
    let isEdited = false;

    function enableEdit(fieldId) {
        let inputField = document.getElementById(fieldId);
        inputField.disabled = false;
        inputField.focus();



        // Enable save button
        document.getElementById("save-btn").style.display = "block";
        document.getElementById("save-btn").disabled = false;
        isEdited = true;
    }

    function enableRadioEdit(fieldName) {
        // Get all radio buttons by name and enable them
        let radioButtons = document.getElementsByName(fieldName);
        radioButtons.forEach(radio => {
            radio.disabled = false; // Enable each radio button
        });

        // Enable the save button
        document.getElementById("save-btn").style.display = "block"; // Show the save button
        document.getElementById("save-btn").disabled = false; // Enable the save button
        isEdited = true;
    }
</script>

<<<<<<< HEAD
<?php include '../javascript_files/prevent_access.js'; ?>
=======

>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
    
</body>
</html>
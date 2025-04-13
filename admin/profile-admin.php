<?php
include '../basic_php/connection.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Error: User not logged in.");
}

$admin_id = $_SESSION['admin_id'];

// Fetch user details except password and user_type
$sql = "SELECT image,email, fname, lname, phone FROM user 
JOIN admin ON user.user_id=admin.user_id 
WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();



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
    <title>profile</title>
</head>
<body>

    <?php include './header_admin.php';?>
<main>
    <nav class="breadcrumb-nav">
        <a href="./index-admin.php">Home</a>
        <span>›</span>
        <a href="#">Profile</a>
    </nav>

    <h1>Your Profile</h1>

    <div class="profile-container">
    

    <form method="POST" action="./update-profile-admin.php" id="profile-form" enctype="multipart/form-data">

            <div class="profile-field">
                <div class="file-input">
                    <img id="image-preview" src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" width="100" height="100">
                    <input type="file" name="image" id="image" accept="image/*" disabled>
                </div>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('image')"></i>
            </div>

        <div class="profile-field">
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('email')"></i>
        </div>

        <div class="profile-field">
            <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('fname')"></i>
        </div>

        <div class="profile-field">
            <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('lname')"></i>
        </div>

        <div class="profile-field">
            <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('phone')"></i>
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
</script>

    
</body>
</html>
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

<<<<<<< HEAD

=======
if (!isset($_SESSION['customer_id'])) {
    die("Error: User not logged in.");
}
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0

$customer_id = $_SESSION['customer_id'];

// Fetch user details except password and user_type
$sql = "SELECT u.image, u.email, u.fname, u.lname, u.phone, c.full_address, c.city_corporation_id, c.upazila_id 
FROM user u JOIN customer c ON u.user_id=c.user_id 
WHERE c.customer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch available city corporations
$cityQuery = "SELECT * FROM city_corporation";
$cityResult = $conn->query($cityQuery);

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

    <?php include './header_customer.php';?>

<main>
    <nav class="breadcrumb-nav">
        <a href="./index-customer.php">Home</a>
        <span>›</span>
        <a href="#">Profile</a>
    </nav>

    <h1>Your Profile</h1>
    <div class="profile-container">
    

    <form method="POST" action="update-profile-customer.php" id="profile-form" enctype="multipart/form-data">

    <div class="profile-field">
            <div class="file-input">
                <img id="image-preview" src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" >
            
                <input type="file" name="image" id="image" accept="image/*" disabled>
            </div>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('image')"></i>
            </div>

        <div class="profile-field">
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Your Email" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('email')"></i>
        </div>

        <div class="profile-field">
            <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" placeholder="Your First Name" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('fname')"></i>
        </div>

        <div class="profile-field">
            <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" placeholder="Your Last Name" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('lname')"></i>
        </div>

        <div class="profile-field">
            <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Your Contact Number" disabled>
            <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('phone')"></i>
        </div>

        <div class="profile-field">
                <label for="city_id">City Corporation:</label>
                <select name="city_id" id="city_id" onchange="fetchUpazilas(this.value)" disabled>
                    <option value="">Select City</option>
                    <?php while ($city = $cityResult->fetch_assoc()) { ?>
                        <option value="<?php echo $city['city_id']; ?>" <?php echo ($user['city_corporation_id'] == $city['city_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($city['city_name']); ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="hidden" name="hidden_city_id" id="hidden_city_id" value="<?php echo $user['city_corporation_id']; ?>">
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('city_id')"></i>
            </div>
            <div class="profile-field">
                <label for="upazila_id">Upazila:</label>
                <select name="upazila_id" id="upazila_id" disabled>
                    <option value="">Select Upazila</option>
                </select>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('upazila_id')"></i>
            </div>
            <div class="profile-field">
                <input type="text" name="full_address" id="full_address" value="<?php echo htmlspecialchars($user['full_address']); ?>" placeholder="Your Full Address" disabled>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('full_address')"></i>
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

<script>
        function fetchUpazilas(cityId) {
            let upazilaDropdown = document.getElementById('upazila_id');
            upazilaDropdown.innerHTML = '<option value="">Loading...</option>';
            let xhr = new XMLHttpRequest();
            xhr.open('GET', './upazila-customer.php?city_id=' + cityId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    upazilaDropdown.innerHTML = xhr.responseText;
                
            let selectedUpazila = "<?php echo $user['upazila_id']; ?>";

            if (selectedUpazila) {
                upazilaDropdown.value = selectedUpazila;
            }
        }
    };
            xhr.send();
        }



    document.addEventListener("DOMContentLoaded", function () {
    let selectedCity = document.getElementById("city_id").value;
    let selectedUpazila = "<?php echo $user['upazila_id']; ?>";

    if (selectedCity) {
        fetchUpazilas(selectedCity, selectedUpazila);
    }
});


document.getElementById('city_id').addEventListener('change', function() {
    document.getElementById('hidden_city_id').value = this.value;
});
</script>

<<<<<<< HEAD
<?php include '../javascript_files/prevent_access.js'; ?>
=======
<script>
    // Prevent back button from showing cached page
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.href = "../regular/index.php";
        }
    });
</script>
>>>>>>> 780c424c29be69a08dd98158bfd6fc4337eeaff0
    
</body>
</html>
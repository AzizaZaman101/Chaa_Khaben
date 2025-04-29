<?php
include '../basic_php/connection.php';

session_start();


if (!isset($_SESSION['rider_id'])) {
    header("Location: ../regular/index.php");
    exit();
}

// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$rider_id = $_SESSION['rider_id'];

// Fetch user details except password and user_type
$sql = "SELECT u.image, u.email, u.fname, u.lname, u.phone, r.rider_active_status FROM user u
JOIN rider r ON u.user_id=r.user_id 
WHERE r.rider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch available city corporations
$cityQuery = "SELECT * FROM city_corporation";
$cityResult = $conn->query($cityQuery);

// Fetch rider's preferred areas
$preferredAreasQuery = "SELECT city_corporation_id, upazila_id FROM rider_preferred_area WHERE rider_id = ?";
$stmt = $conn->prepare($preferredAreasQuery);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$preferredResult = $stmt->get_result();
$preferredAreas = [];
while ($row = $preferredResult->fetch_assoc()) {
    $preferredAreas[] = $row;
}

// Fetch available upazilas
$upazilaQuery = "SELECT * FROM upazila";
$upazilas = $conn->query($upazilaQuery);


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

    <?php include './header_rider.php';?>

<main>
    <nav class="breadcrumb-nav">
        <a href="./index-rider.php">Home</a>
        <span>›</span>
        <a href="#">Profile</a>
    </nav>

    <h1>Your Profile</h1>
    <div class="profile-container">

    
    <form method="POST" action="./update-profile-rider.php" id="profile-form" enctype="multipart/form-data">

            <div class="profile-field">


                <input type="radio" name="rider_active_status" id="active" value="active" <?php echo ($user['rider_active_status'] == 'active') ? 'checked' : ''; ?> disabled>
                <label for="active">Available</label>
                
                <input type="radio" name="rider_active_status" id="inactive" value="inactive" <?php echo ($user['rider_active_status'] == 'inactive') ? 'checked' : ''; ?> disabled>
                <label for="inactive">Unavailable</label>

                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableRadioEdit('rider_active_status')"></i>
                
            </div>

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



        <div class="profile-field">
                <label for="city_id">City-Corporation:</label>
                <select name="city_id" id="city_id" onchange="fetchUpazilas(this.value)" disabled>
                    <option value="">Select City</option>
                    <?php while ($city = $cityResult->fetch_assoc()) { ?>
                        <option value="<?php echo $city['city_id']; ?>" <?php echo ($preferredAreas[0]['city_corporation_id'] ?? '') == $city['city_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($city['city_name']); ?>
                        </option>
                    <?php } ?>
                </select>
                <i class="fa-solid fa-pen-to-square edit-icon" onclick="enableEdit('city_id')"></i>
            </div>

            <div class="profile-field" id="upazila-container">
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
    
<script>
    function fetchUpazilas(cityId) {
        let upazilaContainer = document.getElementById('upazila-container');

        if (!cityId) {
            upazilaContainer.innerHTML = ""; // Hide Upazilas if no city is selected
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open('GET', './upazila-rider.php?city_id=' + cityId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                upazilaContainer.innerHTML = xhr.responseText; // Load Upazilas dynamically
            }
        };
        xhr.send();
    }
</script>

<script>
    function validateUpazilas() {
    const checkboxes = document.getElementsByName('upazilas[]');
    const selectedUpazilas = Array.from(checkboxes).filter(checkbox => checkbox.checked);

    if (selectedUpazilas.length > 3) {
        alert("You can only select up to 3 upazilas.");
        selectedUpazilas[selectedUpazilas.length - 1].checked = false;
    }
}
</script>



<script src="../javascript_files/script.js"></script>
</body>
</html>
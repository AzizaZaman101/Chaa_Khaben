<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];
$rider_id = $_SESSION['rider_id'];

// Fetch current user data from the database to handle fields that are not updated
$query = "SELECT email, fname, lname, phone, image FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$query = "SELECT rider_active_status FROM rider WHERE rider_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$result = $stmt->get_result();
$rider = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : $user['email'];
    $new_fname = isset($_POST['fname']) ? trim($_POST['fname']) : $user['fname'];
    $new_lname = isset($_POST['lname']) ? trim($_POST['lname']) : $user['lname'];
    $new_phone = isset($_POST['phone']) ? trim($_POST['phone']) : $user['phone'];
    $old_image = isset($_POST['old_image']) ? $_POST['old_image'] : $user['image'];

    $new_rider_active_status= isset($_POST['rider_active_status']) ? $_POST['rider_active_status'] : $rider['rider_active_status'];

    $city_id = isset($_POST['city_id']) ? $_POST['city_id'] : null;
    $selected_upazilas = isset($_POST['upazilas']) ? $_POST['upazilas'] : [];


    // Validate that fields are not empty
    if (empty($new_email) || empty($new_fname) || empty($new_lname) || empty($new_phone)) {
        die("Error: Fields cannot be empty.");
    }

    // If an image is uploaded, handle it
    $new_image = $old_image; // By default, use the old image
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type and size
        if ($_FILES['image']['size'] > 5000000) {
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
        $new_image = $target_file; // Update the image path
    }

    // Check if email already exists for another user
    $checkEmailQuery = "SELECT user_id FROM user WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: Email already exists.");
    }

    
    // Start a transaction
    $conn->begin_transaction();
 
    try {
        // Update query
        $updateQuery = "UPDATE user SET email = ?, fname = ?, lname = ?, phone = ?, image = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssi", $new_email, $new_fname, $new_lname, $new_phone, $new_image, $user_id);

        if (!$stmt->execute()) {
            // Rollback if anything fails
            throw new Exception("Error: Could not update the profile.");
        }


        $updateQuery1 = "UPDATE rider SET rider_active_status = ? WHERE rider_id = ?";
        $stmt = $conn->prepare($updateQuery1);
        $stmt->bind_param("si", $new_rider_active_status, $rider_id);

        if (!$stmt->execute()) {
            // Rollback if anything fails
            throw new Exception("Error: Could not update the profile.");
        }

        

        $deleteQuery = "DELETE FROM rider_preferred_area WHERE rider_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $rider_id);
        $stmt->execute();

        // Update City Corporation
        if (!empty($_POST['city_id'])) {
            $city_id = intval($_POST['city_id']);
            $updateCity = $conn->prepare("UPDATE rider_preferred_area SET city_corporation_id = ? WHERE rider_id = ?");
            $updateCity->bind_param("ii", $city_id, $rider_id);
            $updateCity->execute();
        }

// Update Upazilas (Allow up to 3)
        if (isset($_POST['upazilas'])) {
            $upazilas = array_slice($_POST['upazilas'], 0, 3); // Limit to 3
            $conn->query("DELETE FROM rider_preferred_area WHERE rider_id = $rider_id"); // Remove old selections

            $insertUpazila = $conn->prepare("INSERT INTO rider_preferred_area (rider_id, city_corporation_id, upazila_id) VALUES (?, ?, ?)");
            foreach ($upazilas as $upazila_id) {
        $insertUpazila->bind_param("iii", $rider_id, $city_id, $upazila_id);
        $insertUpazila->execute();
            }
        }

        // Commit changes
        $conn->commit();
        echo "Profile updated successfully!";
        header("Location: ./profile-rider.php"); // Redirect after success
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
?>

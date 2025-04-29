<?php
include '../basic_php/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch current user data from the database to handle fields that are not updated
$query = "SELECT email, fname, lname, phone, image FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


// Fetch customer ID
$query = "SELECT full_address, city_corporation_id, upazila_id, customer_id FROM customer WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$customer_id = $customer['customer_id'] ?? null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : $user['email'];

    $new_fname = isset($_POST['fname']) ? trim($_POST['fname']) : $user['fname'];

    $new_lname = isset($_POST['lname']) ? trim($_POST['lname']) : $user['lname'];

    $new_phone = isset($_POST['phone']) ? trim($_POST['phone']) : $user['phone'];

    $old_image = isset($_POST['old_image']) ? $_POST['old_image'] : $user['image'];

    $new_city_id = isset($_POST['city_id']) && !empty($_POST['city_id']) 
    ? trim($_POST['city_id']) 
    : (isset($_POST['hidden_city_id']) ? trim($_POST['hidden_city_id']) : $customer['city_id']);


    $new_upazila_id = isset($_POST['upazila_id']) ? trim($_POST['upazila_id']) : $customer['upazila_id'];

    $new_full_address = isset($_POST['full_address']) ? trim($_POST['full_address']) : $customer['full_address'];

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
        $updateUserQuery = "UPDATE user SET email = ?, fname = ?, lname = ?, phone = ?, image = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateUserQuery);
        $stmt->bind_param("sssssi", $new_email, $new_fname, $new_lname, $new_phone, $new_image, $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Error: updating user profile.");
        }         
        
            $updateCustomerQuery = "UPDATE customer SET city_corporation_id = ?, upazila_id = ?, full_address = ? WHERE customer_id = ?";
            $stmt = $conn->prepare($updateCustomerQuery);
            $stmt->bind_param("iisi", $new_city_id, $new_upazila_id, $new_full_address, $customer_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error updating customer details: " . $stmt->error);
            }

        // Commit changes
        $conn->commit();
        echo "Profile updated successfully!";
        header("Location: ./profile-customer.php"); // Redirect after success
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
?>

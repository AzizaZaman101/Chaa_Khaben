<?php
include '../basic_php/connection.php';
if (isset($_GET['city_id'])) {
 
    $city_id = intval($_GET['city_id']);


    $sql = "SELECT * FROM upazila WHERE city_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $city_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo '<option value="">Select Upazila</option>';
    while ($upazila = $result->fetch_assoc()) {
        $selected = ($upazila['upazila_id'] == $selected_upazila) ? "selected" : "";
        
        echo "<option value='{$upazila['upazila_id']}' $selected>{$upazila['upazila_name']}</option>";
    }
}
?>




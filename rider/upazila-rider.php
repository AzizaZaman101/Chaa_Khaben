<?php
include '../basic_php/connection.php';

if (isset($_GET['city_id'])) {
    $city_id = intval($_GET['city_id']);
    $selected_upazila = $_GET['selected_upazila'] ?? null;

    $sql = "SELECT * FROM upazila WHERE city_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $city_id);
    $stmt->execute();
    $result = $stmt->get_result();

    session_start();
    $rider_id = $_SESSION['rider_id'] ?? null;
    $preferredAreas = [];

    // Fetch previously selected Upazilas
    if ($rider_id) {
        $preferredQuery = "SELECT upazila_id FROM rider_preferred_area WHERE rider_id = ?";
        $stmtPreferred = $conn->prepare($preferredQuery);
        $stmtPreferred->bind_param("i", $rider_id);
        $stmtPreferred->execute();
        $preferredResult = $stmtPreferred->get_result();
        while ($row = $preferredResult->fetch_assoc()) {
            $preferredAreas[] = $row['upazila_id'];
        }
    }

    // Generate Upazila checkboxes
    while ($upazila = $result->fetch_assoc()) {
        $checked = in_array($upazila['upazila_id'], $preferredAreas) ? 'checked' : '';
        echo '<label>
                <input type="checkbox" name="upazilas[]" value="' . $upazila['upazila_id'] . '" ' . $checked . ' onclick="validateUpazilas()"/>
                ' . htmlspecialchars($upazila['upazila_name']) . '
              </label><br>';
    }
}
?>

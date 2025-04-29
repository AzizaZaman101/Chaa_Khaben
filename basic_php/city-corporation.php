<?php
include '../basic_php/connection.php';

$sql = "SELECT * FROM city_corporation";
$result = $conn->query($sql);

while ($city = $result->fetch_assoc()) {
    echo '<option value="' . $city['city_id'] . '">' . htmlspecialchars($city['city_name']) . '</option>';
}
?>

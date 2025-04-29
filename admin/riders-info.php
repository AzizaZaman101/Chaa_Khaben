<?php
include '../basic_php/connection.php' ; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../regular/index.php");
    exit();
}
// Prevent page from being cached
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


// Fetch all riders with user details
$query = "
    SELECT concat(u.fname,' ',u.lname) AS full_name, u.image, u.phone, u.email, r.rider_active_status, r.pending_delivery
    FROM user u
    JOIN rider r ON u.user_id = r.user_id
";

$stmt = $conn->query($query);
$riders = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style/style_riders_info.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <title>Riders List</title>
</head>

<body>
    <?php include './header_admin.php'; ?>


    <main>

    <nav class="breadcrumb-nav">
    <a href=".\index-admin.php">Home</a>
    <span>›</span>
    <a href="#">Riders info</a>
    </nav>

    <h1>Riders List</h1>

    <div class="riders-container">
        <?php foreach ($riders as $rider): ?>
            <div class="rider-card">
                <img src="<?php echo htmlspecialchars($rider['image']); ?>" alt="Rider Image">
                <h3><?php echo htmlspecialchars($rider['full_name']); ?></h3>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($rider['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($rider['email']); ?></p>
                <p><strong>Status:</strong> <?php echo $rider['rider_active_status'] ? 'Active' : 'Inactive'; ?></p>
                <p><strong>Pending Deliveries:</strong> <?php echo htmlspecialchars($rider['pending_delivery']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    </main>

    <?php include '../basic_php/footer.php'; ?>
<script src="../javascript_files/script.js"></script>
</body>

</html>
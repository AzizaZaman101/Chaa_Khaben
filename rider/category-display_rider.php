<?php

include '../basic_php/connection.php' ; 

$query = "SELECT category_id, category_name, category_image FROM category";
$result = mysqli_query($conn, $query);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}
?>


<!--This is the category section -->   
<main class="products" id="products">

        <h1 class="heading">Explore Our Menu</h1>

        <div class="box_container">

         
        <?php foreach ($categories as $category): ?>
            <div class="box">

                <img src="<?= htmlspecialchars($category['category_image']) ?>" alt="<?= htmlspecialchars($category['category_name']) ?>" class="card_image">

                <a href="./rider-see-category-items.php?category_name=<?= urlencode($category['category_name']) ?>" class="card_btn">
                <?= htmlspecialchars($category['category_name']) ?>
                </a>
 
            </div>
        <?php endforeach; ?>
        </div>
    </main>

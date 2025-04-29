<?php include '../basic_php/connection.php';?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cha_Khaben</title>
    <link rel="stylesheet" href="../style/styleIndex.css">
    <link rel="stylesheet" href="../style/style_product_display.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="">
    
<?php include './header.php';?>


<!--This is the hero section/to show to the picture of the very first page featuring some heading tags and a button -->

<!--This is the banner section with some interesting fact about teas -->    
<?php include '../basic_php/hero-banner.php';?>
    
    
<!--This is the category showcase section -->   
<?php include './category-display_regular.php';?>



<!--This is the About us/Why choose us section -->    
<?php include '../basic_php/about-us.php';?>


<!--This is the Footer section -->   
<?php include '../basic_php/footer.php';?>

<!-- -->
<script src="../javascript_files/add_to_cart.js"></script>
<script src="../javascript_files/script.js"></script>
<script>
    if (window.history && window.history.pushState) {
        window.history.pushState(null, "", window.location.href);        
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
    } 
</script>

</body>
</html>
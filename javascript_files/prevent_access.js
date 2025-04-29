 // Prevent back button from showing cached page
 window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        window.location.href = "../regular/index.php";
    }
});
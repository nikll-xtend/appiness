<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<div id="header"></div>
    <!-- Navigation Menu Component -->
    <div id="navbar"></div>
    
    <!-- Image Slider Component -->
    <div id="slider"></div>
    
    <!-- Profile List Component -->
    <div id="profiles" class="container mt-4"></div>
    
    <!-- Footer Component -->
    <div id="footer"></div>

    
    
    <script>
        // Load components dynamically
        async function loadComponent(id, file) {
            const response = await fetch(file);
            document.getElementById(id).innerHTML = await response.text();
        }
        loadComponent("header", "components/header.php");
        loadComponent("navbar", "components/navbar.php");
        loadComponent("slider", "components/slider.php");
        loadComponent("profiles", "components/profiles.php");
        loadComponent("footer", "components/footer.php");
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

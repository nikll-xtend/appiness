<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<div id="header"></div>
<div id="navbar"></div>

<div class="container mt-5">
    <h1>Welcome to the Dashboard</h1>
    <p>This is your dashboard where you can manage your account and view important information.</p>
    <div class="row">
        <?php if ($_SESSION['usertype'] == 'admin'): ?>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Manage Categories</h5>
                        <p class="card-text">Add, update, or delete categories and subcategories.</p>
                        <a href="categories.php" class="btn btn-primary">Manage Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">User List</h5>
                        <p class="card-text">View and manage the list of users.</p>
                        <a href="user_list.php" class="btn btn-primary">View Users</a>
                    </div>
                </div>
            </div>




            <?php endif; ?>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">Update your profile information.</p>
                        <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>

    </div>

</div>


<script>
    // Load components dynamically
    async function loadComponent(id, file) {
        const response = await fetch(file);
        document.getElementById(id).innerHTML = await response.text();
    }
    loadComponent("header", "components/header.php");
    loadComponent("navbar", "components/navbar.php");

    loadComponent("footer", "components/footer.php");
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
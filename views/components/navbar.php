<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
            <a class="navbar-brand" href="dashboard.php">MyWebsite</a>
        <?php else: ?>
            <a class="navbar-brand" href="#">MyWebsite</a>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php
                    $userType = $_SESSION['usertype'];
                    $userName = $_SESSION['name'] ?? $userType;
                    $userPhoto = !empty($_SESSION['photo']) ? "../uploads/images" . $_SESSION['photo'] : "../assets/images/default-avatar.jpg";

                    ?>

                    <?php if ($userType == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Manage Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="user_list.php">User List</a></li>
                    <?php endif; ?>

                    <!-- User Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="ms-2"><?php echo htmlspecialchars($userName); ?></span>
                            <p><?php echo $usertype; ?></p>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">Update Profile</a></li>
                            <li><a class="dropdown-item text-danger" href="../actions/logout.php">Logout</a></li>
                        </ul>
                    </li>



                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
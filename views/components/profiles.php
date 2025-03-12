<?php
include_once "../../config.php";
include_once "../../classes/User.php";

$user = new User($conn);
$users = $user->getUsersByType('user');
?>
<div class="container mt-5">
    <div class="row">
        <?php foreach ($users as $usr): ?>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <img src="<?php echo !empty($usr['photo']) ? "../../uploads/" . htmlspecialchars($usr['photo']) : "../assets/images/default-avatar.jpg"; ?>" class="card-img-top" alt="Profile Photo">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo !empty($usr['name']) ? htmlspecialchars($usr['name']) : "No Name"; ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
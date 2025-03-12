<?php

include_once "../config.php";
include_once "../classes/User.php";
session_start();

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        if ($user->register($_POST['email'], $_POST['password'])) {
            header("Location: ../views/login.php");
        } else {
            echo "Registration failed!";
        }
    } elseif (isset($_POST['login'])) {
        if ($user->login($_POST['email'], $_POST['password'])) {
            header("Location: ../views/dashboard.php");
        } else {
            echo "Invalid credentials!";
        }
    }
}
?>

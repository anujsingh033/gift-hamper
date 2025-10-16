<?php
session_start();
include '../includes/functions.php';

// Log out the admin user
adminLogout();

// Redirect to login page
header('Location: login.php');
exit;
?>
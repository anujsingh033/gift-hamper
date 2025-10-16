<?php
session_start();
include '../includes/functions.php';

// Log out the user
userLogout();

// Redirect to home page
header('Location: ../index.php');
exit;
?>
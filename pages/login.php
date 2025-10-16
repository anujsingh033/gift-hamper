<?php
session_start();
include '../includes/config.php';
include '../includes/functions.php';

// If already logged in, redirect to home page
if (isUserLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (userLogin($username, $password)) {
        header('Location: ../index.php');
        exit;
    } else {
        $error = "Invalid username/email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to your Cute Gift Hamper account to manage your orders and shopping experience.">
    <meta name="keywords" content="login, user account, gift hampers, customer login">
    <meta name="author" content="Cute Gift Hamper">
    <title>User Login - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/login.php">
</head>
<body class="user-login-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="user-login-container">
            <div class="user-login-form">
                <h1><i class="fas fa-sign-in-alt"></i> User Login</h1>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['registered'])): ?>
                    <div class="success-message">Registration successful! Please login with your credentials.</div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username or Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-key"></i> Password</label>
                        <div class="password-toggle">
                            <input type="password" id="password" name="password" required>
                            <span class="toggle-password" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> Login</button>
                </form>
                
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.nextElementSibling.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
<?php
include '../includes/config.php';

// This is a dummy admin login page for security purposes
// It looks like a real admin login but doesn't actually authenticate

// Check for special access key to reach real admin panel
if (isset($_GET['access']) && $_GET['access'] === 'admin_panel') {
    // Redirect to the real admin login
    header('Location: login_real.php');
    exit;
}

// Process dummy login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Log the attempt for security monitoring
    error_log("Dummy admin login attempt: Username: $username, IP: " . $_SERVER['REMOTE_ADDR']);
    
    // Always show error to make it look real
    $error = "Invalid username or password";
    
    // Add a small delay to make it seem more realistic
    sleep(2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
</head>
<body class="admin-login-page">
    <div class="admin-login-container">
        <div class="admin-login-form">
            <h1><i class="fas fa-lock"></i> Admin Login</h1>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
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
            <!-- For administrators: <a href="auth.php?access=admin_panel">Access panel</a> -->
        </div>
    </div>
    
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
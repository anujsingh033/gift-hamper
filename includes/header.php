<?php
// Check if user is logged in
$isUserLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$userFullName = isset($_SESSION['user_full_name']) ? $_SESSION['user_full_name'] : '';
?>

<header>
    <div class="container navbar">
        <div class="logo">
            <h1><a href="<?php echo BASE_URL; ?>index.php">Cute Gift Hamper</a></h1>
        </div>
        
        <nav>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Products</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                
                <?php if ($isUserLoggedIn): ?>
                    <li><a href="<?php echo BASE_URL; ?>pages/cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i> Cart 
                        <?php
                        $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                        if ($cartCount > 0):
                        ?>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li class="user-menu">
                        <a href="#"><i class="fas fa-user"></i> <?php echo htmlspecialchars($userFullName); ?></a>
                        <ul class="dropdown">
                            <li><a href="<?php echo BASE_URL; ?>pages/cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
                            <li><a href="<?php echo BASE_URL; ?>pages/checkout.php"><i class="fas fa-credit-card"></i> Checkout</a></li>
                            <li><a href="<?php echo BASE_URL; ?>pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>pages/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?php echo BASE_URL; ?>pages/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
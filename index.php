<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Get all categories
$categories = getCategories($conn);

// Get all products
$products = getAllProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cute Gift Hamper - Beautifully curated gift hampers for every occasion. Shop our premium collection of birthday gifts, wedding presents, corporate gifts, and more.">
    <meta name="keywords" content="gift hampers, birthday gifts, wedding presents, corporate gifts, baby gifts, wellness gifts">
    <meta name="author" content="Cute Gift Hamper">
    <title>Cute Gift Hamper - Beautifully Curated Gift Hampers for Every Occasion</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Welcome to Cute Gift Hamper</h2>
            <p>Discover our beautifully curated collection of gift hampers for every occasion</p>
            <?php if (isUserLoggedIn()): ?>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_full_name']); ?>!</p>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Shop Now</a>
        </div>
    </section>
    
    <!-- Categories Section -->
    <section class="container">
        <h2>Shop by Category</h2>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <div class="category-card">
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="<?php echo BASE_URL; ?>pages/products.php?category=<?php echo $category['id']; ?>" class="btn">View Products</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php 
            // Show only first 6 products as featured
            $featuredProducts = array_slice($products, 0, 6);
            foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <?php if ($product['image']): ?>
                        <img src="<?php echo BASE_URL; ?>assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p class="price">₹ <?php echo number_format($product['price'], 2); ?></p>
                    <div class="product-actions">
                        <a href="<?php echo BASE_URL; ?>pages/products.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                        <a href="<?php echo BASE_URL; ?>pages/cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn add-to-cart">Add to Cart</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
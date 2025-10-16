<?php
$pageTitle = 'Product Details';
include '../includes/config.php';
include '../includes/functions.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
$product = null;
if ($productId > 0) {
    $product = getProductById($conn, $productId);
}

// Get categories for navigation
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View details of our <?php echo isset($product) ? htmlspecialchars($product['name']) : 'gift hamper'; ?>. Beautifully curated for your special occasion.">
    <meta name="keywords" content="gift hampers, product details, <?php echo isset($product) ? htmlspecialchars($product['name']) : 'gift'; ?>, birthday gifts, wedding presents">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="product-detail-page">
            <div class="container">
                <?php if ($product): ?>
                    <div class="product-detail">
                        <div class="product-gallery">
                            <?php if ($product['image']): ?>
                                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <?php else: ?>
                                <div class="no-image">No Image Available</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <h1><?php echo $product['name']; ?></h1>
                            
                            <div class="product-price">
                                $<?php echo number_format($product['price'], 2); ?>
                            </div>
                            
                            <div class="product-description">
                                <p><?php echo $product['description']; ?></p>
                            </div>
                            
                            <div class="product-meta">
                                <p><strong>Category:</strong> 
                                    <?php 
                                    // Find category name
                                    $categoryName = 'Unknown';
                                    foreach ($categories as $category) {
                                        if ($category['id'] == $product['category_id']) {
                                            $categoryName = $category['name'];
                                            break;
                                        }
                                    }
                                    echo $categoryName;
                                    ?>
                                </p>
                                <p><strong>Availability:</strong> 
                                    <?php if ($product['stock'] > 0): ?>
                                        <span class="in-stock">In Stock (<?php echo $product['stock']; ?> available)</span>
                                    <?php else: ?>
                                        <span class="out-of-stock">Out of Stock</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <?php if ($product['stock'] > 0): ?>
                                <div class="product-actions">
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn add-to-cart">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="product-actions">
                                    <button class="btn" disabled>Out of Stock</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="back-to-products">
                        <a href="products.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                <?php else: ?>
                    <div class="error-message">
                        <h2>Product Not Found</h2>
                        <p>The product you're looking for doesn't exist or has been removed.</p>
                        <a href="products.php" class="btn">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any product detail page specific JavaScript here if needed
    </script>
</body>
</html>
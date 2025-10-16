<?php
$pageTitle = 'Products';
include '../includes/config.php';
include '../includes/functions.php';
// Note: We include header in the HTML structure below

// Get filter parameters
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Get products based on filters
if ($categoryId) {
    $products = getProductsByCategory($conn, $categoryId);
} elseif ($keyword) {
    $products = searchProducts($conn, $keyword);
} else {
    $products = getAllProducts($conn);
}

// Get categories for filter
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse our collection of beautifully curated gift hampers. Find the perfect birthday, wedding, corporate, or baby gift for any occasion.">
    <meta name="keywords" content="gift hampers, birthday gifts, wedding presents, corporate gifts, baby gifts, wellness gifts">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/products.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="products-page">
            <div class="container">
                <h1>Our Products</h1>
                
                <div class="filters">
                    <form method="GET" action="" id="filter-form">
                        <div class="search-box">
                            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($keyword); ?>" id="search-input">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>
                        
                        <div class="category-filter">
                            <select name="category" onchange="this.form.submit()" id="category-select">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($categoryId == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php if ($categoryId || $keyword): ?>
                            <div class="filter-actions">
                                <a href="products.php" class="btn btn-outline">Clear Filters</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
                
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <p>No products found.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                <h3><?php echo $product['name']; ?></h3>
                                <p class="category"><?php echo $product['category_name']; ?></p>
                                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                                <div class="product-actions">
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn add-to-cart">Add to Cart</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any products page specific JavaScript here if needed
    document.addEventListener('DOMContentLoaded', function() {
        const filters = document.querySelector('.filters');
        if (filters) {
            // Add loaded class after a short delay to trigger animation
            setTimeout(function() {
                filters.classList.add('loaded');
            }, 100);
        }
        
        // Add clear functionality for search
        const searchInput = document.getElementById('search-input');
        const categorySelect = document.getElementById('category-select');
        
        if (searchInput) {
            // Clear search when clicking the search icon if there's text
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('filter-form').submit();
                }
            });
        }
    });
    </script>
</body>
</html>
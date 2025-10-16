<?php
$pageTitle = 'Order Confirmation';
include '../includes/config.php';
include '../includes/functions.php';
// Note: We include header in the HTML structure below

// Get order ID from URL
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get order details
$order = null;
if ($orderId > 0) {
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Order confirmation for your Cute Gift Hamper purchase. Thank you for choosing our beautifully curated gift hampers.">
    <meta name="keywords" content="order confirmation, gift hampers, purchase confirmation, order details">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/order-confirmation.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="confirmation-page">
            <div class="container">
                <div class="confirmation-content">
                    <?php if ($order): ?>
                        <div class="confirmation-header">
                            <div class="checkmark">
                                <i class="fas fa-check"></i>
                            </div>
                            <h1>Order Confirmed!</h1>
                            <p>Thank you for your purchase. Your order has been received.</p>
                        </div>
                        
                        <div class="order-details">
                            <h2>Order Details</h2>
                            <div class="order-info">
                                <div class="info-item">
                                    <span>Order Number:</span>
                                    <span>#<?php echo $order['id']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Date:</span>
                                    <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Total:</span>
                                    <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Status:</span>
                                    <span class="status pending">Processing</span>
                                </div>
                                <div class="info-item">
                                    <span>Customer:</span>
                                    <span><?php echo htmlspecialchars($order['customer_name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Email:</span>
                                    <span><?php echo htmlspecialchars($order['customer_email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Shipping Address:</span>
                                    <span><?php echo htmlspecialchars($order['customer_address']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="confirmation-actions">
                            <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                            <a href="<?php echo BASE_URL; ?>pages/cart.php" class="btn btn-outline">View Cart</a>
                        </div>
                    <?php else: ?>
                        <div class="error-message">
                            <h2>Order Not Found</h2>
                            <p>We couldn't find the order you're looking for.</p>
                            <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any confirmation page specific JavaScript here if needed
    </script>
</body>
</html>
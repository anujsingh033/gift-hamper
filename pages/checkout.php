<?php
$pageTitle = 'Checkout';
include '../includes/config.php';
include '../includes/functions.php';
// Note: We include header in the HTML structure below

// Get cart items
$cart = getCartItems($conn);

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerData = array(
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'address' => $_POST['address']
    );
    
    $orderId = createOrder($conn, $customerData);
    
    if ($orderId) {
        header('Location: order-confirmation.php?id=' . $orderId);
        exit;
    } else {
        $error = "There was a problem processing your order. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Complete your purchase at Cute Gift Hamper. Secure checkout for your beautifully curated gift hampers.">
    <meta name="keywords" content="checkout, gift hampers, secure payment, order confirmation">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/checkout.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="checkout-page">
            <div class="container">
                <h1>Checkout</h1>
                
                <?php if (empty($cart['items'])): ?>
                    <div class="empty-cart">
                        <p>Your cart is empty.</p>
                        <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <?php if (isset($error)): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div class="checkout-container">
                        <div class="checkout-form">
                            <h2>Billing Information</h2>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" required></textarea>
                                </div>
                                
                                <h2>Payment Method</h2>
                                <div class="payment-methods">
                                    <div class="payment-method">
                                        <input type="radio" id="credit-card" name="payment_method" value="credit_card" checked>
                                        <label for="credit-card">Credit Card</label>
                                    </div>
                                    <div class="payment-method">
                                        <input type="radio" id="paypal" name="payment_method" value="paypal">
                                        <label for="paypal">PayPal</label>
                                    </div>
                                    <div class="payment-method">
                                        <input type="radio" id="scanner" name="payment_method" value="scanner">
                                        <label for="scanner">QR Code Scanner</label>
                                    </div>
                                </div>
                                
                                <div class="scanner-section" id="scanner-section" style="display: none;">
                                    <p>Scan the QR code below to complete your payment:</p>
                                    <div class="qr-code">
                                        <!-- This would be a real QR code in production -->
                                        <img src="<?php echo BASE_URL; ?>assets/images/qr-code.png" alt="QR Code">
                                    </div>
                                    <p>Amount: $<?php echo number_format($cart['total'], 2); ?></p>
                                </div>
                                
                                <button type="submit" class="btn">Place Order</button>
                            </form>
                        </div>
                        
                        <div class="order-summary">
                            <h2>Order Summary</h2>
                            <div class="summary-items">
                                <?php foreach ($cart['items'] as $item): ?>
                                    <div class="summary-item">
                                        <div class="summary-item-info">
                                            <span><?php echo $item['name']; ?></span>
                                            <span>Qty: <?php echo $item['quantity']; ?></span>
                                        </div>
                                        <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="summary-total">
                                <span>Total:</span>
                                <span>$<?php echo number_format($cart['total'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const scannerRadio = document.getElementById('scanner');
        const scannerSection = document.getElementById('scanner-section');
        
        scannerRadio.addEventListener('change', function() {
            if (this.checked) {
                scannerSection.style.display = 'block';
            } else {
                scannerSection.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>
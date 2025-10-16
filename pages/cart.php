<?php
$pageTitle = 'Shopping Cart';
include '../includes/config.php';
include '../includes/functions.php';

// Handle cart actions
if (isset($_GET['action'])) {
    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    switch ($_GET['action']) {
        case 'add':
            addToCart($productId);
            header('Location: cart.php');
            exit;
        case 'remove':
            removeFromCart($productId);
            header('Location: cart.php');
            exit;
        case 'update':
            if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
                foreach ($_POST['quantity'] as $id => $qty) {
                    updateCartQuantity($id, $qty);
                }
            }
            header('Location: cart.php');
            exit;
    }
}

// Get cart items
$cart = getCartItems($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View and manage your shopping cart at Cute Gift Hamper. Review your selected gift hampers before checkout.">
    <meta name="keywords" content="shopping cart, gift hampers, checkout, order summary">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/cart.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="cart-page">
            <div class="container">
                <h1>Shopping Cart</h1>
                
                <?php if (empty($cart['items'])): ?>
                    <div class="empty-cart">
                        <p>Your cart is empty.</p>
                        <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="cart.php?action=update">
                        <div class="cart-items">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="cart-product">
                                                    <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                                    <span><?php echo $item['name']; ?></span>
                                                </div>
                                            </td>
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                                            </td>
                                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                            <td>
                                                <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="remove-btn">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="cart-summary">
                            <div class="cart-total">
                                <h3>Total: $<?php echo number_format($cart['total'], 2); ?></h3>
                            </div>
                            <div class="cart-actions">
                                <button type="submit" class="btn">Update Cart</button>
                                <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any cart-specific JavaScript here if needed
    </script>
</body>
</html>
<?php
include '../includes/config.php';
include '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle order actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'view':
            // View order details
            $orderId = (int)$_GET['id'];
            
            // Use prepared statement to prevent SQL injection
            $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $orderId);
                mysqli_stmt_execute($stmt);
                $orderResult = mysqli_stmt_get_result($stmt);
                $order = mysqli_fetch_assoc($orderResult);
                mysqli_stmt_close($stmt);
                
                // Get order items
                $stmt2 = mysqli_prepare($conn, "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "i", $orderId);
                    mysqli_stmt_execute($stmt2);
                    $orderItemsResult = mysqli_stmt_get_result($stmt2);
                    $orderItems = mysqli_fetch_all($orderItemsResult, MYSQLI_ASSOC);
                    mysqli_stmt_close($stmt2);
                }
            }
            break;
            
        case 'update':
            // Update order status
            $orderId = (int)$_GET['id'];
            $status = $_GET['status'];
            
            // Validate status
            $validStatuses = array('pending', 'processing', 'shipped', 'completed', 'cancelled');
            if (!in_array($status, $validStatuses)) {
                $error = "Invalid status";
                break;
            }
            
            // Use prepared statement to prevent SQL injection
            $stmt = mysqli_prepare($conn, "UPDATE orders SET status=? WHERE id=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $status, $orderId);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Order status updated successfully!";
                } else {
                    $error = "Error: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
            break;
    }
}

// Get all orders
$stmt = mysqli_prepare($conn, "SELECT * FROM orders ORDER BY created_at DESC");
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $ordersResult = mysqli_stmt_get_result($stmt);
    $orders = mysqli_fetch_all($ordersResult, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    $orders = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Manage Orders - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
</head>
<body class="admin-dashboard">
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-logo">
                <h2><i class="fas fa-gift"></i> Cute Gift Hamper</h2>
                <p>Admin Panel</p>
            </div>
            
            <ul class="admin-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-shopping-cart"></i> Manage Orders</h1>
                <div class="admin-user">
                    <span><i class="fas fa-user-circle"></i> Welcome, <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?></span>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($order)): ?>
                <div class="admin-order-detail">
                    <div class="admin-actions">
                        <h2>Order #<?php echo $order['id']; ?> Details</h2>
                        <a href="orders.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Orders</a>
                    </div>
                    
                    <div class="order-info">
                        <div class="admin-form">
                            <h2><i class="fas fa-user"></i> Customer Information</h2>
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Name</label>
                                <p><?php echo htmlspecialchars($order['customer_name']); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <p><?php echo htmlspecialchars($order['customer_email']); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt"></i> Address</label>
                                <p><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="admin-form">
                            <h2><i class="fas fa-info-circle"></i> Order Information</h2>
                            <div class="form-group">
                                <label><i class="fas fa-hashtag"></i> Order ID</label>
                                <p>#<?php echo $order['id']; ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Date</label>
                                <p><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-info-circle"></i> Status</label>
                                <p>
                                    <span class="status <?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign"></i> Total</label>
                                <p>INR<?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="admin-form">
                        <h2><i class="fas fa-boxes"></i> Order Items</h2>
                        <div class="admin-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="order-item">
                                                    <?php if ($item['image']): ?>
                                                        <img src="<?php echo BASE_URL; ?>assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50">
                                                    <?php endif; ?>
                                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                                </div>
                                            </td>
                                            <td>INR<?php echo number_format($item['price'], 2); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>INR<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="admin-form">
                        <h2><i class="fas fa-sync-alt"></i> Update Status</h2>
                        <div class="status-actions">
                            <?php 
                            $statuses = array('pending', 'processing', 'shipped', 'completed', 'cancelled');
                            foreach ($statuses as $status):
                                if ($status != $order['status']):
                            ?>
                                <a href="orders.php?action=update&id=<?php echo $order['id']; ?>&status=<?php echo $status; ?>" class="btn-small">
                                    <i class="fas fa-sync-alt"></i> Mark as <?php echo ucfirst($status); ?>
                                </a>
                            <?php 
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="admin-actions">
                    <h2>Order Management</h2>
                </div>
                
                <div class="admin-table">
                    <h2><i class="fas fa-list"></i> All Orders</h2>
                    <?php if (count($orders) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="status <?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn-small"><i class="fas fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-cart">
                            <p>No orders found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
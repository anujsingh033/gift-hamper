<?php
include '../includes/config.php';
include '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get dashboard statistics
$totalProducts = count(getAllProducts($conn));
$totalCategories = count(getCategories($conn));

// Get orders count
$ordersQuery = "SELECT COUNT(*) as total FROM orders";
$ordersResult = mysqli_query($conn, $ordersQuery);
$ordersData = mysqli_fetch_assoc($ordersResult);
$totalOrders = $ordersData['total'];

// Get pending orders count
$pendingOrdersQuery = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
$pendingOrdersResult = mysqli_query($conn, $pendingOrdersQuery);
$pendingOrdersData = mysqli_fetch_assoc($pendingOrdersResult);
$pendingOrders = $pendingOrdersData['total'];

// Get recent orders
$recentOrdersQuery = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$recentOrdersResult = mysqli_query($conn, $recentOrdersQuery);
$recentOrders = mysqli_fetch_all($recentOrdersResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Dashboard - Cute Gift Hamper</title>
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
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <div class="admin-user">
                    <span><i class="fas fa-user-circle"></i> Welcome, <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?></span>
                </div>
            </div>
            
            <div class="admin-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalProducts; ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Categories</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalOrders; ?></h3>
                        <p>Total Orders</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $pendingOrders; ?></h3>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>
            
            <div class="admin-recent-orders">
                <h2><i class="fas fa-history"></i> Recent Orders</h2>
                <div class="admin-table">
                    <?php if (count($recentOrders) > 0): ?>
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
                                <?php foreach ($recentOrders as $order): ?>
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
                                            <a href="orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn-small">View</a>
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
            </div>
        </div>
    </div>
</body>
</html>
<?php
include '../includes/config.php';
include '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle category actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            // Add new category
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                
                // Use prepared statement to prevent SQL injection
                $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, description) VALUES (?, ?)");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ss", $name, $description);
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Category added successfully!";
                    } else {
                        $error = "Error: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $error = "Database error: " . mysqli_error($conn);
                }
            }
            break;
            
        case 'edit':
            // Edit category
            $categoryId = (int)$_GET['id'];
            
            // Get category data
            $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $categoryId);
                mysqli_stmt_execute($stmt);
                $categoryResult = mysqli_stmt_get_result($stmt);
                $category = mysqli_fetch_assoc($categoryResult);
                mysqli_stmt_close($stmt);
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                
                // Use prepared statement to prevent SQL injection
                $stmt = mysqli_prepare($conn, "UPDATE categories SET name=?, description=? WHERE id=?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $categoryId);
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Category updated successfully!";
                        // Refresh category data
                        mysqli_stmt_execute($stmt); // Re-execute to get updated data
                        $categoryResult = mysqli_stmt_get_result($stmt);
                        $category = mysqli_fetch_assoc($categoryResult);
                    } else {
                        $error = "Error: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $error = "Database error: " . mysqli_error($conn);
                }
            }
            break;
            
        case 'delete':
            // Delete category
            $categoryId = (int)$_GET['id'];
            
            // Check if there are products in this category
            $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM products WHERE category_id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $categoryId);
                mysqli_stmt_execute($stmt);
                $checkResult = mysqli_stmt_get_result($stmt);
                $checkData = mysqli_fetch_assoc($checkResult);
                mysqli_stmt_close($stmt);
                
                if ($checkData['count'] > 0) {
                    $error = "Cannot delete category. There are products in this category.";
                } else {
                    // Use prepared statement to prevent SQL injection
                    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id=?");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "i", $categoryId);
                        if (mysqli_stmt_execute($stmt)) {
                            $success = "Category deleted successfully!";
                        } else {
                            $error = "Error: " . mysqli_stmt_error($stmt);
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error = "Database error: " . mysqli_error($conn);
                    }
                }
            }
            break;
    }
}

// Get all categories
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Manage Categories - Cute Gift Hamper</title>
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
                <li><a href="categories.php" class="active"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-tags"></i> Manage Categories</h1>
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
            
            <div class="admin-actions">
                <h2>Category Management</h2>
                <a href="categories.php?action=add" class="btn"><i class="fas fa-plus"></i> Add New Category</a>
            </div>
            
            <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                <div class="admin-form">
                    <h2><i class="fas fa-plus-circle"></i> Add New Category</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-tag"></i> Category Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea id="description" name="description" rows="4"></textarea>
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Add Category</button>
                    </form>
                </div>
            <?php elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($category)): ?>
                <div class="admin-form">
                    <h2><i class="fas fa-edit"></i> Edit Category</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-tag"></i> Category Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($category['description']); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Update Category</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="admin-table">
                    <h2><i class="fas fa-list"></i> All Categories</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td>
                                        <a href="categories.php?action=edit&id=<?php echo $category['id']; ?>" class="btn-small"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="categories.php?action=delete&id=<?php echo $category['id']; ?>" class="btn-small delete" onclick="return confirm('Are you sure you want to delete this category?')"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
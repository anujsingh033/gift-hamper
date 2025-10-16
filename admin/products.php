<?php
include '../includes/config.php';
include '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle product actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            // Add new product
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = (float)$_POST['price'];
                $categoryId = (int)$_POST['category_id'];
                $stock = (int)$_POST['stock'];
                $image = $_FILES['image']['name'];
                
                // Upload image
                if ($image) {
                    $target = "../assets/images/" . basename($image);
                    move_uploaded_file($_FILES['image']['tmp_name'], $target);
                }
                
                // Use prepared statement to prevent SQL injection
                $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category_id, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssdiis", $name, $description, $price, $categoryId, $stock, $image);
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Product added successfully!";
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
            // Edit product
            $productId = (int)$_GET['id'];
            $product = getProductById($conn, $productId);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = (float)$_POST['price'];
                $categoryId = (int)$_POST['category_id'];
                $stock = (int)$_POST['stock'];
                
                // Check if new image is uploaded
                if (!empty($_FILES['image']['name'])) {
                    $image = $_FILES['image']['name'];
                    $target = "../assets/images/" . basename($image);
                    move_uploaded_file($_FILES['image']['tmp_name'], $target);
                    
                    // Use prepared statement to prevent SQL injection
                    $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, price=?, category_id=?, stock=?, image=? WHERE id=?");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssdiisi", $name, $description, $price, $categoryId, $stock, $image, $productId);
                        if (mysqli_stmt_execute($stmt)) {
                            $success = "Product updated successfully!";
                            $product = getProductById($conn, $productId); // Refresh product data
                        } else {
                            $error = "Error: " . mysqli_stmt_error($stmt);
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error = "Database error: " . mysqli_error($conn);
                    }
                } else {
                    // Use prepared statement to prevent SQL injection
                    $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, price=?, category_id=?, stock=? WHERE id=?");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssdiis", $name, $description, $price, $categoryId, $stock, $productId);
                        if (mysqli_stmt_execute($stmt)) {
                            $success = "Product updated successfully!";
                            $product = getProductById($conn, $productId); // Refresh product data
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
            
        case 'delete':
            // Delete product
            $productId = (int)$_GET['id'];
            
            // Use prepared statement to prevent SQL injection
            $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $productId);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Product deleted successfully!";
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

// Get all products
$products = getAllProducts($conn);
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Manage Products - Cute Gift Hamper</title>
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
                <li><a href="products.php" class="active"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-box"></i> Manage Products</h1>
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
                <h2>Product Management</h2>
                <a href="products.php?action=add" class="btn"><i class="fas fa-plus"></i> Add New Product</a>
            </div>
            
            <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                <div class="admin-form">
                    <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea id="description" name="description" rows="4"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price"><i class="fas fa-dollar-sign"></i> Price ₹ </label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id"><i class="fas fa-list"></i> Category</label>
                            <select id="category_id" name="category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock"><i class="fas fa-archive"></i> Stock</label>
                            <input type="number" id="stock" name="stock" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="image"><i class="fas fa-image"></i> Product Image</label>
                            <input type="file" id="image" name="image" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Add Product</button>
                    </form>
                </div>
            <?php elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($product)): ?>
                <div class="admin-form">
                    <h2><i class="fas fa-edit"></i> Edit Product</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price"><i class="fas fa-dollar-sign"></i> Price ₹</label>
                            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id"><i class="fas fa-list"></i> Category</label>
                            <select id="category_id" name="category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock"><i class="fas fa-archive"></i> Stock</label>
                            <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="image"><i class="fas fa-image"></i> Product Image</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <?php if ($product['image']): ?>
                                <p>Current image: <?php echo htmlspecialchars($product['image']); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Update Product</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="admin-table">
                    <h2><i class="fas fa-list"></i> All Products</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td>
                                        <?php if ($product['image']): ?>
                                            <img src="<?php echo BASE_URL; ?>assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="50">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                    <td>
                                        <a href="products.php?action=edit&id=<?php echo $product['id']; ?>" class="btn-small"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="products.php?action=delete&id=<?php echo $product['id']; ?>" class="btn-small delete" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i> Delete</a>
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
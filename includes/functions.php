<?php
/**
 * Functions for the Cute Gift Hamper website
 * Contains utility functions for database operations, cart management, and authentication
 * 
 * @package CuteGiftHamper
 * @author  Cute Gift Hamper Team
 * @version 1.0
 */

/**
 * Product Functions
 * Functions related to product management and retrieval
 */

/**
 * Get all categories from the database
 * 
 * @param mysqli $conn Database connection object
 * @return array Array of categories
 */
function getCategories($conn) {
    $query = "SELECT id, name, description FROM categories ORDER BY name";
    $result = mysqli_query($conn, $query);
    
    // Add error handling
    if (!$result) {
        error_log("Database error in getCategories: " . mysqli_error($conn));
        return array();
    }
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Get products by category ID
 * 
 * @param mysqli $conn Database connection object
 * @param int $categoryId Category ID to filter products
 * @return array Array of products in the specified category
 */
function getProductsByCategory($conn, $categoryId) {
    // Validate input
    if (!is_numeric($categoryId)) {
        error_log("Invalid category ID in getProductsByCategory: " . $categoryId);
        return array();
    }
    
    // Use prepared statement to prevent SQL injection
    // Updated to match actual database structure
    $stmt = mysqli_prepare($conn, "SELECT id, name, description, price, image, stock, category_id FROM products WHERE category_id = ? ORDER BY name");
    if (!$stmt) {
        error_log("Prepare failed in getProductsByCategory: " . mysqli_error($conn));
        return array();
    }
    
    mysqli_stmt_bind_param($stmt, "i", $categoryId);
    $executeResult = mysqli_stmt_execute($stmt);
    
    if (!$executeResult) {
        error_log("Execute failed in getProductsByCategory: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return array();
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    
    return $products;
}

/**
 * Get all products with their category names
 * 
 * @param mysqli $conn Database connection object
 * @return array Array of all products with category information
 */
function getAllProducts($conn) {
    // Updated to match actual database structure
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.stock, p.category_id, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.name";
    $result = mysqli_query($conn, $query);
    
    // Add error handling
    if (!$result) {
        error_log("Database error in getAllProducts: " . mysqli_error($conn));
        return array();
    }
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Get a specific product by its ID
 * 
 * @param mysqli $conn Database connection object
 * @param int $productId Product ID to retrieve
 * @return array|false Product data or false if not found
 */
function getProductById($conn, $productId) {
    // Validate input
    if (!is_numeric($productId)) {
        error_log("Invalid product ID in getProductById: " . $productId);
        return false;
    }
    
    // Use prepared statement to prevent SQL injection
    // Updated to match actual database structure
    $stmt = mysqli_prepare($conn, "SELECT id, name, description, price, image, stock, category_id FROM products WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed in getProductById: " . mysqli_error($conn));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "i", $productId);
    $executeResult = mysqli_stmt_execute($stmt);
    
    if (!$executeResult) {
        error_log("Execute failed in getProductById: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $product;
}

/**
 * Search products by keyword in name or description
 * 
 * @param mysqli $conn Database connection object
 * @param string $keyword Search keyword
 * @return array Array of matching products
 */
function searchProducts($conn, $keyword) {
    // Validate input
    if (empty($keyword) || !is_string($keyword)) {
        error_log("Invalid search keyword in searchProducts: " . $keyword);
        return array();
    }
    
    // Use prepared statement to prevent SQL injection
    $searchTerm = "%$keyword%";
    // Updated to match actual database structure
    $stmt = mysqli_prepare($conn, "SELECT p.id, p.name, p.description, p.price, p.image, p.stock, p.category_id, c.name as category_name 
              FROM products p 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.name LIKE ? OR p.description LIKE ?
              ORDER BY p.name");
    
    if (!$stmt) {
        error_log("Prepare failed in searchProducts: " . mysqli_error($conn));
        return array();
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
    $executeResult = mysqli_stmt_execute($stmt);
    
    if (!$executeResult) {
        error_log("Execute failed in searchProducts: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return array();
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    
    return $products;
}

/**
 * Shopping Cart Functions
 * Functions related to shopping cart management
 */

/**
 * Add a product to the shopping cart
 * 
 * @param int $productId Product ID to add
 * @param int $quantity Quantity to add (default: 1)
 * @return bool True on success, false on failure
 */
function addToCart($productId, $quantity = 1) {
    // Validate input
    if (!is_numeric($productId) || !is_numeric($quantity) || $quantity <= 0) {
        error_log("Invalid parameters in addToCart: productId=$productId, quantity=$quantity");
        return false;
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Ensure productId is an integer
    $productId = (int)$productId;
    $quantity = (int)$quantity;
    
    if (isset($_SESSION['cart'][$productId])) {
        // Check for integer overflow
        $newQuantity = $_SESSION['cart'][$productId] + $quantity;
        if ($newQuantity > 0) {
            $_SESSION['cart'][$productId] = $newQuantity;
        }
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    return true;
}

/**
 * Remove a product from the shopping cart
 * 
 * @param int $productId Product ID to remove
 * @return bool True on success, false on failure
 */
function removeFromCart($productId) {
    // Validate input
    if (!is_numeric($productId)) {
        error_log("Invalid product ID in removeFromCart: " . $productId);
        return false;
    }
    
    // Ensure productId is an integer
    $productId = (int)$productId;
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        return true;
    }
    return false;
}

/**
 * Update the quantity of a product in the shopping cart
 * 
 * @param int $productId Product ID to update
 * @param int $quantity New quantity
 * @return bool True on success, false on failure
 */
function updateCartQuantity($productId, $quantity) {
    // Validate input
    if (!is_numeric($productId) || !is_numeric($quantity)) {
        error_log("Invalid parameters in updateCartQuantity: productId=$productId, quantity=$quantity");
        return false;
    }
    
    // Ensure productId and quantity are integers
    $productId = (int)$productId;
    $quantity = (int)$quantity;
    
    if ($quantity <= 0) {
        return removeFromCart($productId);
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
        return true;
    }
    return false;
}

/**
 * Get all items in the shopping cart with product details
 * 
 * @param mysqli $conn Database connection object
 * @return array Cart items and total
 */
function getCartItems($conn) {
    $cartItems = array();
    $total = 0;
    
    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            // Validate product ID and quantity
            if (!is_numeric($productId) || !is_numeric($quantity) || $quantity <= 0) {
                error_log("Invalid cart item: productId=$productId, quantity=$quantity");
                continue;
            }
            
            $productId = (int)$productId;
            $quantity = (int)$quantity;
            
            $product = getProductById($conn, $productId);
            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $cartItems[] = array(
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    // Updated to match actual database structure
                    'image' => $product['image']
                );
                $total += $subtotal;
            }
        }
    }
    
    return array(
        'items' => $cartItems,
        'total' => $total
    );
}

/**
 * Order Functions
 * Functions related to order processing
 */

/**
 * Create a new order from the shopping cart
 * 
 * @param mysqli $conn Database connection object
 * @param array $customerData Customer information
 * @return int|false Order ID on success, false on failure
 */
function createOrder($conn, $customerData) {
    // Validate customer data
    if (empty($customerData['name']) || empty($customerData['email']) || empty($customerData['address'])) {
        error_log("Missing customer data in createOrder");
        return false;
    }
    
    $cart = getCartItems($conn);
    
    if (empty($cart['items'])) {
        error_log("Empty cart in createOrder");
        return false;
    }
    
    // Start transaction
    mysqli_autocommit($conn, FALSE);
    
    try {
        // Insert order using prepared statement
        $orderStmt = mysqli_prepare($conn, "INSERT INTO orders (customer_name, customer_email, customer_address, total_amount) VALUES (?, ?, ?, ?)");
        if (!$orderStmt) {
            throw new Exception("Prepare failed for order insert: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($orderStmt, "sssd", $customerData['name'], $customerData['email'], $customerData['address'], $cart['total']);
        $orderResult = mysqli_stmt_execute($orderStmt);
        
        if (!$orderResult) {
            throw new Exception("Execute failed for order insert: " . mysqli_stmt_error($orderStmt));
        }
        
        $orderId = mysqli_insert_id($conn);
        mysqli_stmt_close($orderStmt);
        
        // Insert order items using prepared statements
        $itemStmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$itemStmt) {
            throw new Exception("Prepare failed for order items insert: " . mysqli_error($conn));
        }
        
        $stockUpdateStmt = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE id = ?");
        if (!$stockUpdateStmt) {
            throw new Exception("Prepare failed for stock update: " . mysqli_error($conn));
        }
        
        foreach ($cart['items'] as $item) {
            // Insert order item
            mysqli_stmt_bind_param($itemStmt, "iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
            $itemResult = mysqli_stmt_execute($itemStmt);
            
            if (!$itemResult) {
                throw new Exception("Execute failed for order item insert: " . mysqli_stmt_error($itemStmt));
            }
            
            // Update product stock
            mysqli_stmt_bind_param($stockUpdateStmt, "ii", $item['quantity'], $item['id']);
            $stockResult = mysqli_stmt_execute($stockUpdateStmt);
            
            if (!$stockResult) {
                throw new Exception("Execute failed for stock update: " . mysqli_stmt_error($stockUpdateStmt));
            }
        }
        
        mysqli_stmt_close($itemStmt);
        mysqli_stmt_close($stockUpdateStmt);
        
        // Commit transaction
        mysqli_commit($conn);
        mysqli_autocommit($conn, TRUE);
        
        // Clear cart
        $_SESSION['cart'] = array();
        
        return $orderId;
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        mysqli_autocommit($conn, TRUE);
        error_log("Order creation failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Authentication Functions
 * Functions related to admin authentication
 */

/**
 * Authenticate an admin user
 * 
 * @param string $username Admin username
 * @param string $password Admin password
 * @return bool True on successful authentication, false otherwise
 */
function adminLogin($username, $password) {
    global $conn;
    
    // Validate input
    if (empty($username) || empty($password)) {
        error_log("Missing credentials in adminLogin");
        return false;
    }
    
    // Use prepared statement to prevent SQL injection
    // Modified to use the users table with role check instead of missing admins table
    $stmt = mysqli_prepare($conn, "SELECT id, username, password_hash FROM users WHERE username = ? AND role = 'admin'");
    if (!$stmt) {
        error_log("Prepare failed in adminLogin: " . mysqli_error($conn));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    $executeResult = mysqli_stmt_execute($stmt);
    
    if (!$executeResult) {
        error_log("Execute failed in adminLogin: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Check if admin user exists and password is correct
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_id'] = $admin['id'];
        return true;
    }
    
    return false;
}

/**
 * Check if an admin user is currently logged in
 * 
 * @return bool True if admin is logged in, false otherwise
 */
function isAdminLoggedIn() {
    // Additional security check
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && isset($_SESSION['admin_username']);
}

/**
 * Log out the current admin user
 * 
 * @return void
 */
function adminLogout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Authenticate a regular user
 * 
 * @param string $username User username or email
 * @param string $password User password
 * @return bool True on successful authentication, false otherwise
 */
function userLogin($username, $password) {
    global $conn;
    
    // Validate input
    if (empty($username) || empty($password)) {
        error_log("Missing credentials in userLogin");
        return false;
    }
    
    // Use prepared statement to prevent SQL injection
    // Check by username or email for regular users with role='user'
    $stmt = mysqli_prepare($conn, "SELECT id, username, email, password_hash, full_name FROM users WHERE (username = ? OR email = ?) AND role = 'user'");
    if (!$stmt) {
        error_log("Prepare failed in userLogin: " . mysqli_error($conn));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    $executeResult = mysqli_stmt_execute($stmt);
    
    if (!$executeResult) {
        error_log("Execute failed in userLogin: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password_hash'])) {
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_full_name'] = $user['full_name'];
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    
    return false;
}

/**
 * Check if a regular user is currently logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true && isset($_SESSION['user_id']);
}

/**
 * Log out the current regular user
 * 
 * @return void
 */
function userLogout() {
    // Unset user session variables
    unset($_SESSION['user_logged_in']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_full_name']);
    unset($_SESSION['user_id']);
}

/**
 * Register a new regular user
 * 
 * @param string $username Username
 * @param string $email Email
 * @param string $password Password
 * @param string $fullName Full name
 * @param string $phone Phone number (optional)
 * @param string $address Address (optional)
 * @return bool True on successful registration, false otherwise
 */
function registerUser($username, $email, $password, $fullName, $phone = null, $address = null) {
    global $conn;
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
        error_log("Missing required fields in registerUser");
        return false;
    }
    
    // Check if username or email already exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        error_log("Prepare failed in registerUser: " . mysqli_error($conn));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        return false; // Username or email already exists
    }
    mysqli_stmt_close($stmt);
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user with role='user'
    if ($phone !== null || $address !== null) {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash, full_name, phone, address, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
        if (!$stmt) {
            error_log("Prepare failed in registerUser: " . mysqli_error($conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $hashedPassword, $fullName, $phone, $address);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, 'user')");
        if (!$stmt) {
            error_log("Prepare failed in registerUser: " . mysqli_error($conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPassword, $fullName);
    }
    
    $executeResult = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $executeResult;
}
?>
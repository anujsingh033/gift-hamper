<?php
/**
 * Maintenance script for Cute Gift Hamper website
 * 
 * This script provides utility functions for routine maintenance tasks.
 * 
 * Usage:
 * - Run directly in browser for basic info
 * - Include in other scripts for utility functions
 */

// Basic security check - only allow CLI or authenticated admin access
if (php_sapi_name() !== 'cli') {
    // For web access, you might want to add authentication here
    // For now, we'll just show basic info
    header('Content-Type: text/plain');
    echo "Cute Gift Hamper Maintenance Script\n";
    echo "==================================\n\n";
    echo "This script provides maintenance functions for the website.\n";
    echo "For security reasons, direct web access is limited.\n\n";
    echo "Available functions when included in other scripts:\n";
    echo "- cleanupSessions() - Remove expired sessions\n";
    echo "- optimizeDatabase() - Optimize database tables\n";
    echo "- checkDiskSpace() - Check available disk space\n";
    echo "- backupDatabase() - Create database backup\n\n";
    echo "For full functionality, run this script via CLI or include it in authenticated admin pages.\n";
    exit;
}

// Function to clean up expired sessions
function cleanupSessions() {
    // This would typically clean up old session files
    // Implementation depends on your session storage method
    echo "Session cleanup function placeholder\n";
    return true;
}

// Function to optimize database tables
function optimizeDatabase($conn) {
    $tables = array('categories', 'products', 'users', 'orders', 'order_items');
    $optimized = 0;
    
    foreach ($tables as $table) {
        $query = "OPTIMIZE TABLE $table";
        if (mysqli_query($conn, $query)) {
            $optimized++;
        }
    }
    
    return $optimized;
}

// Function to check disk space
function checkDiskSpace() {
    $freeSpace = disk_free_space('.');
    $totalSpace = disk_total_space('.');
    
    return array(
        'free' => $freeSpace,
        'total' => $totalSpace,
        'used' => $totalSpace - $freeSpace,
        'percentage' => round((($totalSpace - $freeSpace) / $totalSpace) * 100, 2)
    );
}

// Function to backup database (placeholder)
function backupDatabase($conn) {
    // This would typically create a SQL dump of the database
    // For security reasons, this is just a placeholder
    echo "Database backup function placeholder\n";
    return true;
}

// CLI execution
if (php_sapi_name() === 'cli') {
    echo "Cute Gift Hamper Maintenance Script\n";
    echo "==================================\n\n";
    
    // Include config to get database connection
    if (file_exists('includes/config.php')) {
        include 'includes/config.php';
        
        echo "Running maintenance tasks...\n\n";
        
        // Check disk space
        $disk = checkDiskSpace();
        echo "Disk Space:\n";
        echo "  Total: " . formatBytes($disk['total']) . "\n";
        echo "  Used:  " . formatBytes($disk['used']) . " (" . $disk['percentage'] . "%)\n";
        echo "  Free:  " . formatBytes($disk['free']) . "\n\n";
        
        // Optimize database
        echo "Optimizing database tables...\n";
        $optimized = optimizeDatabase($conn);
        echo "Optimized $optimized tables.\n\n";
        
        echo "Maintenance completed successfully!\n";
    } else {
        echo "Error: Configuration file not found.\n";
    }
}

// Helper function to format bytes
function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}
?>
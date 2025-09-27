<?php
/**
 * Installation Script for Adil GFX Portfolio Backend
 * Run this file once to set up the database and initial configuration
 */

require_once 'config/config.php';

// Check if already installed
if (file_exists('.installed')) {
    die('System is already installed. Delete .installed file to reinstall.');
}

$errors = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    $errors[] = 'PHP 7.4 or higher is required. Current version: ' . PHP_VERSION;
}

// Check required extensions
$required_extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'openssl'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Required PHP extension '{$ext}' is not loaded";
    }
}

// Check database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $success[] = 'Database connection successful';
} catch (PDOException $e) {
    $errors[] = 'Database connection failed: ' . $e->getMessage();
}

// Check write permissions
$directories = ['uploads', 'exports', 'admin/logs'];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0755, true)) {
            $errors[] = "Cannot create directory: {$dir}";
        } else {
            $success[] = "Created directory: {$dir}";
        }
    }
    
    if (!is_writable($dir)) {
        $errors[] = "Directory not writable: {$dir}";
    } else {
        $success[] = "Directory writable: {$dir}";
    }
}

// Install database if no errors
if (empty($errors) && isset($_POST['install'])) {
    try {
        // Read and execute schema
        $schema = file_get_contents('database/schema.sql');
        $statements = explode(';', $schema);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        $success[] = 'Database schema installed successfully';
        
        // Create installation marker
        file_put_contents('.installed', date('Y-m-d H:i:s'));
        
        $success[] = 'Installation completed successfully!';
        $success[] = 'Default admin login: admin / admin123';
        
    } catch (Exception $e) {
        $errors[] = 'Database installation failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Adil GFX Portfolio Backend</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Adil GFX Portfolio Backend</h1>
            <p class="text-gray-600">Installation & Setup</p>
        </div>
        
        <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <h3 class="font-bold mb-2">Installation Errors:</h3>
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <h3 class="font-bold mb-2">Success:</h3>
            <ul class="list-disc list-inside">
                <?php foreach ($success as $message): ?>
                <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">System Requirements</h3>
            <div class="space-y-2">
                <div class="flex items-center">
                    <span class="<?php echo version_compare(PHP_VERSION, '7.4.0', '>=') ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo version_compare(PHP_VERSION, '7.4.0', '>=') ? '✓' : '✗'; ?>
                    </span>
                    <span class="ml-2">PHP 7.4+ (Current: <?php echo PHP_VERSION; ?>)</span>
                </div>
                <div class="flex items-center">
                    <span class="<?php echo extension_loaded('pdo_mysql') ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo extension_loaded('pdo_mysql') ? '✓' : '✗'; ?>
                    </span>
                    <span class="ml-2">MySQL PDO Extension</span>
                </div>
                <div class="flex items-center">
                    <span class="<?php echo is_writable('.') ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo is_writable('.') ? '✓' : '✗'; ?>
                    </span>
                    <span class="ml-2">Write Permissions</span>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Database Configuration</h3>
            <div class="bg-gray-50 p-4 rounded">
                <p><strong>Host:</strong> <?php echo DB_HOST; ?></p>
                <p><strong>Database:</strong> <?php echo DB_NAME; ?></p>
                <p><strong>User:</strong> <?php echo DB_USER; ?></p>
                <p class="text-sm text-gray-600 mt-2">
                    Update these settings in <code>config/config.php</code> if needed.
                </p>
            </div>
        </div>
        
        <?php if (empty($errors) && !file_exists('.installed')): ?>
        <form method="POST">
            <button 
                type="submit" 
                name="install"
                class="w-full bg-red-500 text-white py-3 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
            >
                Install Database & Setup System
            </button>
        </form>
        <?php elseif (file_exists('.installed')): ?>
        <div class="text-center">
            <p class="text-green-600 font-semibold mb-4">Installation Complete!</p>
            <a 
                href="admin/" 
                class="inline-block bg-red-500 text-white py-3 px-6 rounded-md hover:bg-red-600 transition duration-200"
            >
                Go to Admin Panel
            </a>
        </div>
        <?php else: ?>
        <div class="text-center">
            <p class="text-red-600 font-semibold">Please fix the errors above before installing.</p>
        </div>
        <?php endif; ?>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Make sure to delete this install.php file after installation for security.</p>
        </div>
    </div>
</body>
</html>
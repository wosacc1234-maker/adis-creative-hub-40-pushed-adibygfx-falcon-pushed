<?php
require_once '../config/config.php';
require_once '../classes/Auth.php';

$auth = new Auth();

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Check if user has admin or editor role
if (!$auth->hasRole('editor')) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Adil GFX Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-link:hover { background-color: #f3f4f6; }
        .sidebar-link.active { background-color: #ef4444; color: white; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-800">Adil GFX Admin</h1>
                <p class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($user['first_name']); ?></p>
            </div>
            
            <nav class="mt-6">
                <a href="#dashboard" class="sidebar-link active flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="#pages" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-file-alt mr-3"></i>
                    Pages
                </a>
                <a href="#portfolio" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-briefcase mr-3"></i>
                    Portfolio
                </a>
                <a href="#services" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cogs mr-3"></i>
                    Services
                </a>
                <a href="#blog" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-blog mr-3"></i>
                    Blog
                </a>
                <a href="#testimonials" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-star mr-3"></i>
                    Testimonials
                </a>
                <a href="#forms" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-envelope mr-3"></i>
                    Forms & Leads
                </a>
                <a href="#media" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-images mr-3"></i>
                    Media Library
                </a>
                <a href="services.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cogs mr-3"></i>
                    Services
                </a>
                <a href="#settings" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
                <?php if ($auth->hasRole('admin')): ?>
                <a href="#users" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <?php endif; ?>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6 border-t">
                <a href="logout.php" class="flex items-center text-red-600 hover:text-red-800">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 id="page-title" class="text-2xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600"><?php echo date('F j, Y'); ?></span>
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-semibold">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main id="content-area" class="flex-1 overflow-y-auto p-6">
                <!-- Dashboard Content -->
                <div id="dashboard-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Stats Cards -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-eye text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Page Views</p>
                                    <p class="text-2xl font-semibold text-gray-800">12,345</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i class="fas fa-envelope text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Form Submissions</p>
                                    <p class="text-2xl font-semibold text-gray-800" id="total-submissions">0</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i class="fas fa-briefcase text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Portfolio Projects</p>
                                    <p class="text-2xl font-semibold text-gray-800" id="total-projects">0</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <i class="fas fa-blog text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Blog Posts</p>
                                    <p class="text-2xl font-semibold text-gray-800" id="total-posts">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                        </div>
                        <div class="p-6">
                            <div id="recent-activity" class="space-y-4">
                                <p class="text-gray-600">Loading recent activity...</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other content sections will be loaded here -->
                <div id="dynamic-content" style="display: none;"></div>
            </main>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="js/admin.js"></script>
</body>
</html>
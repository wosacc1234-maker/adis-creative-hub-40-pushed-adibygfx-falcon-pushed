<?php
require_once '../config/config.php';
require_once '../classes/Auth.php';

$auth = new Auth();

// Check if user is logged in and has editor role
if (!$auth->isLoggedIn() || !$auth->hasRole('editor')) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Management - Adil GFX Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-800">Services Management</h1>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-gray-800">← Back to Dashboard</a>
                    <button 
                        id="add-service-btn"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>Add New Service
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Manage Services</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Add, edit, and organize your service offerings. Changes will appear on the frontend immediately.
                    </p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="services-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Services will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Service Packages Section -->
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Service Packages</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Manage pricing packages for each service (Basic, Standard, Premium).
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p>Select a service above to manage its packages</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/services-management.js"></script>
</body>
</html>
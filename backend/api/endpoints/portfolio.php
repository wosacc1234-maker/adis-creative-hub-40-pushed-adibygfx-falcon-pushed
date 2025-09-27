<?php
require_once '../classes/Auth.php';
require_once '../classes/PortfolioManager.php';

$auth = new Auth();
$portfolioManager = new PortfolioManager();

// Check authentication for write operations
if (in_array($method, ['POST', 'PUT', 'DELETE']) && !$auth->hasRole('editor')) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === 'categories') {
            $categories = $portfolioManager->getCategories();
            echo json_encode(['categories' => $categories]);
            
        } elseif ($action === 'slug' && !empty($id)) {
            $project = $portfolioManager->getProjectBySlug($id);
            if ($project) {
                echo json_encode(['project' => $project]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Project not found']);
            }
            
        } else {
            $filters = [];
            if (!empty($_GET['category'])) {
                $filters['category'] = $_GET['category'];
            }
            if (!empty($_GET['featured'])) {
                $filters['featured'] = true;
            }
            
            $projects = $portfolioManager->getAllProjects($filters);
            echo json_encode(['projects' => $projects]);
        }
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['title', 'slug'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field {$field} is required"]);
                exit;
            }
        }
        
        $result = $portfolioManager->createProject($input);
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
        break;
        
    case 'PUT':
        if (empty($action)) {
            http_response_code(400);
            echo json_encode(['error' => 'Project ID required']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $result = $portfolioManager->updateProject($action, $input);
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
        break;
        
    case 'DELETE':
        if (empty($action)) {
            http_response_code(400);
            echo json_encode(['error' => 'Project ID required']);
            exit;
        }
        
        $result = $portfolioManager->deleteProject($action);
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
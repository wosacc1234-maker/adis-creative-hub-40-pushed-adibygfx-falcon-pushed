<?php
require_once '../classes/Auth.php';
require_once '../classes/PageManager.php';

$auth = new Auth();
$pageManager = new PageManager();

// Check authentication for write operations
if (in_array($method, ['POST', 'PUT', 'DELETE']) && !$auth->hasRole('editor')) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            $pages = $pageManager->getAllPages();
            echo json_encode(['pages' => $pages]);
            
        } elseif ($action === 'slug' && !empty($id)) {
            $page = $pageManager->getPageBySlug($id);
            if ($page) {
                echo json_encode(['page' => $page]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Page not found']);
            }
            
        } elseif ($action === 'elements' && !empty($id)) {
            $elements = $pageManager->getPageElements($id);
            echo json_encode(['elements' => $elements]);
            
        } elseif (!empty($action)) {
            $page = $pageManager->getPageById($action);
            if ($page) {
                echo json_encode(['page' => $page]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Page not found']);
            }
        } else {
            $pages = $pageManager->getAllPages();
            echo json_encode(['pages' => $pages]);
        }
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if ($action === 'create') {
            $required_fields = ['slug', 'title'];
            foreach ($required_fields as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "Field {$field} is required"]);
                    exit;
                }
            }
            
            $result = $pageManager->createPage($input);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
            
        } elseif ($action === 'update-element') {
            if (empty($input['page_id']) || empty($input['element_key'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Page ID and element key required']);
                exit;
            }
            
            $result = $pageManager->updatePageElement($input['page_id'], $input['element_key'], $input);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
        }
        break;
        
    case 'PUT':
        if (empty($action)) {
            http_response_code(400);
            echo json_encode(['error' => 'Page ID required']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $result = $pageManager->updatePage($action, $input);
        
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
            echo json_encode(['error' => 'Page ID required']);
            exit;
        }
        
        $result = $pageManager->deletePage($action);
        
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
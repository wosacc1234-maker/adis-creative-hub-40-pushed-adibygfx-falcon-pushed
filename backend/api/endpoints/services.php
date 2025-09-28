<?php
require_once '../classes/Auth.php';
require_once '../classes/ServiceManager.php';

$auth = new Auth();
$serviceManager = new ServiceManager();

// Check authentication for write operations
if (in_array($method, ['POST', 'PUT', 'DELETE']) && !$auth->hasRole('editor')) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === 'slug' && !empty($id)) {
            $service = $serviceManager->getServiceBySlug($id);
            if ($service) {
                echo json_encode(['service' => $service]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Service not found']);
            }
            
        } elseif (!empty($action)) {
            $service = $serviceManager->getServiceById($action);
            if ($service) {
                echo json_encode(['service' => $service]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Service not found']);
            }
        } else {
            $services = $serviceManager->getAllServices();
            echo json_encode(['services' => $services]);
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
        
        $result = $serviceManager->createService($input);
        
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
            echo json_encode(['error' => 'Service ID required']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $result = $serviceManager->updateService($action, $input);
        
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
            echo json_encode(['error' => 'Service ID required']);
            exit;
        }
        
        $result = $serviceManager->deleteService($action);
        
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
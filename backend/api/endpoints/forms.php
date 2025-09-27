<?php
require_once '../classes/Auth.php';
require_once '../classes/FormManager.php';

$auth = new Auth();
$formManager = new FormManager();

switch ($method) {
    case 'POST':
        if ($action === 'submit') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['form_type'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Form type required']);
                exit;
            }
            
            // Validate CSRF token for authenticated users
            if ($auth->isLoggedIn() && !empty($input['csrf_token'])) {
                if (!$auth->validateCSRFToken($input['csrf_token'])) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Invalid CSRF token']);
                    exit;
                }
            }
            
            $result = $formManager->submitForm($input['form_type'], $input);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
            
        } elseif ($action === 'export') {
            if (!$auth->hasRole('editor')) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $filters = $input['filters'] ?? [];
            
            $result = $formManager->exportSubmissionsToCSV($filters);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
        }
        break;
        
    case 'GET':
        if (!$auth->hasRole('editor')) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if ($action === 'stats') {
            $stats = $formManager->getFormStats();
            echo json_encode(['stats' => $stats]);
            
        } elseif (!empty($action)) {
            $submission = $formManager->getSubmissionById($action);
            if ($submission) {
                echo json_encode(['submission' => $submission]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Submission not found']);
            }
            
        } else {
            $filters = [];
            if (!empty($_GET['form_type'])) {
                $filters['form_type'] = $_GET['form_type'];
            }
            if (!empty($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }
            if (!empty($_GET['date_from'])) {
                $filters['date_from'] = $_GET['date_from'];
            }
            if (!empty($_GET['date_to'])) {
                $filters['date_to'] = $_GET['date_to'];
            }
            
            $submissions = $formManager->getAllSubmissions($filters);
            echo json_encode(['submissions' => $submissions]);
        }
        break;
        
    case 'PUT':
        if (!$auth->hasRole('editor')) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if (empty($action)) {
            http_response_code(400);
            echo json_encode(['error' => 'Submission ID required']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!empty($input['status'])) {
            $result = $formManager->updateSubmissionStatus($action, $input['status']);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
        }
        break;
        
    case 'DELETE':
        if (!$auth->hasRole('admin')) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if (empty($action)) {
            http_response_code(400);
            echo json_encode(['error' => 'Submission ID required']);
            exit;
        }
        
        $result = $formManager->deleteSubmission($action);
        
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
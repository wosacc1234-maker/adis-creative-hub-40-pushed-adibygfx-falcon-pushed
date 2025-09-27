<?php
require_once '../classes/Auth.php';

$auth = new Auth();

switch ($method) {
    case 'POST':
        if ($action === 'login') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['username']) || empty($input['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Username and password required']);
                exit;
            }
            
            $result = $auth->login($input['username'], $input['password']);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(401);
                echo json_encode($result);
            }
            
        } elseif ($action === 'logout') {
            $result = $auth->logout();
            echo json_encode($result);
            
        } elseif ($action === 'register') {
            if (!$auth->hasRole('admin')) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            $required_fields = ['username', 'email', 'password', 'first_name', 'last_name'];
            foreach ($required_fields as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "Field {$field} is required"]);
                    exit;
                }
            }
            
            $result = $auth->createUser($input);
            
            if ($result['success']) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode($result);
            }
        }
        break;
        
    case 'GET':
        if ($action === 'me') {
            if (!$auth->isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not authenticated']);
                exit;
            }
            
            $user = $auth->getCurrentUser();
            echo json_encode(['user' => $user]);
            
        } elseif ($action === 'csrf-token') {
            $token = $auth->generateCSRFToken();
            echo json_encode(['csrf_token' => $token]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
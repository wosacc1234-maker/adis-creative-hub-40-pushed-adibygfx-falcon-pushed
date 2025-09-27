<?php
require_once 'config/database.php';
require_once 'classes/EmailManager.php';

class FormManager {
    private $conn;
    private $emailManager;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->emailManager = new EmailManager();
    }
    
    public function submitForm($form_type, $data) {
        try {
            // Sanitize and validate data
            $sanitized_data = $this->sanitizeFormData($data);
            
            // Store form submission
            $query = "INSERT INTO form_submissions 
                     (form_type, name, email, phone, company, message, form_data, ip_address, user_agent, referrer) 
                     VALUES 
                     (:form_type, :name, :email, :phone, :company, :message, :form_data, :ip_address, :user_agent, :referrer)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':form_type', $form_type);
            $stmt->bindParam(':name', $sanitized_data['name'] ?? null);
            $stmt->bindParam(':email', $sanitized_data['email'] ?? null);
            $stmt->bindParam(':phone', $sanitized_data['phone'] ?? null);
            $stmt->bindParam(':company', $sanitized_data['company'] ?? null);
            $stmt->bindParam(':message', $sanitized_data['message'] ?? null);
            $stmt->bindParam(':form_data', json_encode($sanitized_data));
            $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR'] ?? null);
            $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? null);
            $stmt->bindParam(':referrer', $_SERVER['HTTP_REFERER'] ?? null);
            
            $stmt->execute();
            $submission_id = $this->conn->lastInsertId();
            
            // Handle newsletter subscription
            if ($form_type === 'newsletter' && !empty($sanitized_data['email'])) {
                $this->subscribeToNewsletter($sanitized_data['email'], $sanitized_data['name'] ?? null);
            }
            
            // Send notification email
            $this->sendNotificationEmail($form_type, $sanitized_data, $submission_id);
            
            return ['success' => true, 'id' => $submission_id];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getAllSubmissions($filters = []) {
        try {
            $where_conditions = [];
            $params = [];
            
            if (!empty($filters['form_type'])) {
                $where_conditions[] = 'form_type = :form_type';
                $params[':form_type'] = $filters['form_type'];
            }
            
            if (!empty($filters['status'])) {
                $where_conditions[] = 'status = :status';
                $params[':status'] = $filters['status'];
            }
            
            if (!empty($filters['date_from'])) {
                $where_conditions[] = 'created_at >= :date_from';
                $params[':date_from'] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $where_conditions[] = 'created_at <= :date_to';
                $params[':date_to'] = $filters['date_to'];
            }
            
            $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
            
            $query = "SELECT * FROM form_submissions {$where_clause} ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode form_data JSON
            foreach ($submissions as &$submission) {
                $submission['form_data'] = json_decode($submission['form_data'], true) ?: [];
            }
            
            return $submissions;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getSubmissionById($id) {
        try {
            $query = "SELECT * FROM form_submissions WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $submission = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($submission) {
                $submission['form_data'] = json_decode($submission['form_data'], true) ?: [];
            }
            
            return $submission;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function updateSubmissionStatus($id, $status) {
        try {
            $query = "UPDATE form_submissions SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deleteSubmission($id) {
        try {
            $query = "DELETE FROM form_submissions WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function exportSubmissionsToCSV($filters = []) {
        try {
            $submissions = $this->getAllSubmissions($filters);
            
            if (empty($submissions)) {
                return ['success' => false, 'message' => 'No submissions found'];
            }
            
            $filename = 'form_submissions_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = '../exports/' . $filename;
            
            // Create exports directory if it doesn't exist
            if (!file_exists('../exports/')) {
                mkdir('../exports/', 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Write CSV headers
            $headers = ['ID', 'Form Type', 'Name', 'Email', 'Phone', 'Company', 'Message', 'Status', 'Created At'];
            fputcsv($file, $headers);
            
            // Write data rows
            foreach ($submissions as $submission) {
                $row = [
                    $submission['id'],
                    $submission['form_type'],
                    $submission['name'],
                    $submission['email'],
                    $submission['phone'],
                    $submission['company'],
                    $submission['message'],
                    $submission['status'],
                    $submission['created_at']
                ];
                fputcsv($file, $row);
            }
            
            fclose($file);
            
            return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function sanitizeFormData($data) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    private function subscribeToNewsletter($email, $name = null) {
        try {
            $query = "INSERT INTO newsletter_subscribers (email, name, source) 
                     VALUES (:email, :name, 'website') 
                     ON DUPLICATE KEY UPDATE 
                     name = COALESCE(VALUES(name), name), 
                     status = 'active', 
                     subscribed_at = CURRENT_TIMESTAMP";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            
        } catch (Exception $e) {
            // Log error but don't fail the main form submission
            error_log('Newsletter subscription failed: ' . $e->getMessage());
        }
    }
    
    private function sendNotificationEmail($form_type, $data, $submission_id) {
        try {
            $subject = 'New ' . ucfirst($form_type) . ' Form Submission';
            $message = $this->buildNotificationMessage($form_type, $data, $submission_id);
            
            $this->emailManager->sendEmail(
                FROM_EMAIL,
                FROM_EMAIL,
                $subject,
                $message
            );
            
        } catch (Exception $e) {
            // Log error but don't fail the main form submission
            error_log('Notification email failed: ' . $e->getMessage());
        }
    }
    
    private function buildNotificationMessage($form_type, $data, $submission_id) {
        $message = "New {$form_type} form submission received:\n\n";
        $message .= "Submission ID: {$submission_id}\n";
        $message .= "Form Type: {$form_type}\n";
        $message .= "Submitted: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $message .= ucfirst(str_replace('_', ' ', $key)) . ": {$value}\n";
            }
        }
        
        $message .= "\n---\n";
        $message .= "View in admin panel: " . ADMIN_URL . "/forms/view/{$submission_id}";
        
        return $message;
    }
    
    public function getFormStats() {
        try {
            $stats = [];
            
            // Total submissions
            $query = "SELECT COUNT(*) as total FROM form_submissions";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_submissions'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Submissions by type
            $query = "SELECT form_type, COUNT(*) as count FROM form_submissions GROUP BY form_type";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent submissions (last 30 days)
            $query = "SELECT COUNT(*) as count FROM form_submissions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['recent_submissions'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Newsletter subscribers
            $query = "SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'active'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['newsletter_subscribers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            return $stats;
            
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
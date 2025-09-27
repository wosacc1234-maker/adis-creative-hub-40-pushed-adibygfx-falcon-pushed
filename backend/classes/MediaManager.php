<?php
require_once 'config/database.php';

class MediaManager {
    private $conn;
    private $upload_path;
    private $allowed_types;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->upload_path = UPLOAD_PATH;
        $this->allowed_types = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/webm', 'video/ogg',
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
        }
    }
    
    public function uploadFile($file, $alt_text = '', $caption = '') {
        try {
            // Validate file
            $validation = $this->validateFile($file);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }
            
            // Generate unique filename
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $file_extension;
            $file_path = $this->upload_path . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                return ['success' => false, 'message' => 'Failed to upload file'];
            }
            
            // Store in database
            $query = "INSERT INTO media 
                     (filename, original_name, file_path, file_type, file_size, mime_type, alt_text, caption, uploaded_by) 
                     VALUES 
                     (:filename, :original_name, :file_path, :file_type, :file_size, :mime_type, :alt_text, :caption, :uploaded_by)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':original_name', $file['name']);
            $stmt->bindParam(':file_path', 'uploads/' . $filename);
            $stmt->bindParam(':file_type', $this->getFileType($file['type']));
            $stmt->bindParam(':file_size', $file['size']);
            $stmt->bindParam(':mime_type', $file['type']);
            $stmt->bindParam(':alt_text', $alt_text);
            $stmt->bindParam(':caption', $caption);
            $stmt->bindParam(':uploaded_by', $_SESSION['user_id'] ?? null);
            
            $stmt->execute();
            $media_id = $this->conn->lastInsertId();
            
            return [
                'success' => true,
                'id' => $media_id,
                'filename' => $filename,
                'file_path' => 'uploads/' . $filename,
                'file_type' => $this->getFileType($file['type'])
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getAllMedia($filters = []) {
        try {
            $where_conditions = [];
            $params = [];
            
            if (!empty($filters['file_type'])) {
                $where_conditions[] = 'file_type = :file_type';
                $params[':file_type'] = $filters['file_type'];
            }
            
            if (!empty($filters['search'])) {
                $where_conditions[] = '(original_name LIKE :search OR alt_text LIKE :search OR caption LIKE :search)';
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
            
            $query = "SELECT m.*, u.username as uploaded_by_name 
                     FROM media m 
                     LEFT JOIN users u ON m.uploaded_by = u.id 
                     {$where_clause}
                     ORDER BY m.created_at DESC";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getMediaById($id) {
        try {
            $query = "SELECT m.*, u.username as uploaded_by_name 
                     FROM media m 
                     LEFT JOIN users u ON m.uploaded_by = u.id 
                     WHERE m.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function updateMedia($id, $data) {
        try {
            $query = "UPDATE media SET 
                     alt_text = :alt_text, 
                     caption = :caption 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':alt_text', $data['alt_text']);
            $stmt->bindParam(':caption', $data['caption']);
            
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deleteMedia($id) {
        try {
            // Get file info
            $media = $this->getMediaById($id);
            if (!$media) {
                return ['success' => false, 'message' => 'Media not found'];
            }
            
            // Delete file from filesystem
            $file_path = '../' . $media['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Delete from database
            $query = "DELETE FROM media WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function validateFile($file) {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'File upload error: ' . $file['error']];
        }
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['valid' => false, 'message' => 'File size exceeds maximum allowed size'];
        }
        
        // Check file type
        if (!in_array($file['type'], $this->allowed_types)) {
            return ['valid' => false, 'message' => 'File type not allowed'];
        }
        
        // Additional security checks
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if ($mime_type !== $file['type']) {
            return ['valid' => false, 'message' => 'File type mismatch'];
        }
        
        return ['valid' => true];
    }
    
    private function getFileType($mime_type) {
        if (strpos($mime_type, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mime_type, 'video/') === 0) {
            return 'video';
        } else {
            return 'document';
        }
    }
    
    public function getMediaStats() {
        try {
            $stats = [];
            
            // Total files
            $query = "SELECT COUNT(*) as total FROM media";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_files'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Files by type
            $query = "SELECT file_type, COUNT(*) as count FROM media GROUP BY file_type";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Total storage used
            $query = "SELECT SUM(file_size) as total_size FROM media";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_size'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_size'];
            
            return $stats;
            
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
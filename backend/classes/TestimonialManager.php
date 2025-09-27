<?php
require_once 'config/database.php';

class TestimonialManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllTestimonials($filters = []) {
        try {
            $where_conditions = ['t.is_published = 1'];
            $params = [];
            
            if (!empty($filters['featured'])) {
                $where_conditions[] = 't.is_featured = 1';
            }
            
            if (!empty($filters['project_type'])) {
                $where_conditions[] = 't.project_type = :project_type';
                $params[':project_type'] = $filters['project_type'];
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $query = "SELECT t.*, m.file_path as avatar_path, m.alt_text as avatar_alt
                     FROM testimonials t 
                     LEFT JOIN media m ON t.client_avatar = m.id 
                     WHERE {$where_clause}
                     ORDER BY t.is_featured DESC, t.sort_order, t.created_at DESC";
            
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
    
    public function getTestimonialById($id) {
        try {
            $query = "SELECT t.*, m.file_path as avatar_path, m.alt_text as avatar_alt
                     FROM testimonials t 
                     LEFT JOIN media m ON t.client_avatar = m.id 
                     WHERE t.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function createTestimonial($data) {
        try {
            $query = "INSERT INTO testimonials 
                     (client_name, client_role, client_company, client_avatar, testimonial_text, 
                      rating, project_type, results_achieved, platform, is_featured, is_published, sort_order) 
                     VALUES 
                     (:client_name, :client_role, :client_company, :client_avatar, :testimonial_text, 
                      :rating, :project_type, :results_achieved, :platform, :is_featured, :is_published, :sort_order)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':client_name', $data['client_name']);
            $stmt->bindParam(':client_role', $data['client_role']);
            $stmt->bindParam(':client_company', $data['client_company']);
            $stmt->bindParam(':client_avatar', $data['client_avatar']);
            $stmt->bindParam(':testimonial_text', $data['testimonial_text']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->bindParam(':project_type', $data['project_type']);
            $stmt->bindParam(':results_achieved', $data['results_achieved']);
            $stmt->bindParam(':platform', $data['platform']);
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            
            $stmt->execute();
            
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function updateTestimonial($id, $data) {
        try {
            $query = "UPDATE testimonials SET 
                     client_name = :client_name, client_role = :client_role, client_company = :client_company, 
                     client_avatar = :client_avatar, testimonial_text = :testimonial_text, rating = :rating, 
                     project_type = :project_type, results_achieved = :results_achieved, platform = :platform, 
                     is_featured = :is_featured, is_published = :is_published, sort_order = :sort_order 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':client_name', $data['client_name']);
            $stmt->bindParam(':client_role', $data['client_role']);
            $stmt->bindParam(':client_company', $data['client_company']);
            $stmt->bindParam(':client_avatar', $data['client_avatar']);
            $stmt->bindParam(':testimonial_text', $data['testimonial_text']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->bindParam(':project_type', $data['project_type']);
            $stmt->bindParam(':results_achieved', $data['results_achieved']);
            $stmt->bindParam(':platform', $data['platform']);
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deleteTestimonial($id) {
        try {
            $query = "DELETE FROM testimonials WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getFeaturedTestimonials($limit = 3) {
        try {
            $query = "SELECT t.*, m.file_path as avatar_path, m.alt_text as avatar_alt
                     FROM testimonials t 
                     LEFT JOIN media m ON t.client_avatar = m.id 
                     WHERE t.is_published = 1 AND t.is_featured = 1
                     ORDER BY t.sort_order, t.created_at DESC
                     LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getProjectTypes() {
        try {
            $query = "SELECT DISTINCT project_type FROM testimonials WHERE project_type IS NOT NULL AND project_type != '' ORDER BY project_type";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
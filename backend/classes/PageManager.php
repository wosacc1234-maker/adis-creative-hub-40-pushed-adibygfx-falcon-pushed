<?php
require_once 'config/database.php';

class PageManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllPages() {
        try {
            $query = "SELECT p.*, u.username as created_by_name 
                     FROM pages p 
                     LEFT JOIN users u ON p.created_by = u.id 
                     ORDER BY p.sort_order, p.title";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getPageBySlug($slug) {
        try {
            $query = "SELECT * FROM pages WHERE slug = :slug AND is_published = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function getPageById($id) {
        try {
            $query = "SELECT * FROM pages WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function createPage($data) {
        try {
            $query = "INSERT INTO pages (slug, title, meta_description, meta_keywords, content, template, is_published, sort_order, created_by) 
                     VALUES (:slug, :title, :meta_description, :meta_keywords, :content, :template, :is_published, :sort_order, :created_by)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':meta_description', $data['meta_description']);
            $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':template', $data['template']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            $stmt->bindParam(':created_by', $_SESSION['user_id']);
            
            $stmt->execute();
            
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function updatePage($id, $data) {
        try {
            $query = "UPDATE pages SET 
                     slug = :slug, 
                     title = :title, 
                     meta_description = :meta_description, 
                     meta_keywords = :meta_keywords, 
                     content = :content, 
                     template = :template, 
                     is_published = :is_published, 
                     sort_order = :sort_order 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':meta_description', $data['meta_description']);
            $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':template', $data['template']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deletePage($id) {
        try {
            // First delete page elements
            $delete_elements = "DELETE FROM page_elements WHERE page_id = :id";
            $stmt = $this->conn->prepare($delete_elements);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Then delete the page
            $query = "DELETE FROM pages WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getPageElements($page_id) {
        try {
            $query = "SELECT * FROM page_elements WHERE page_id = :page_id ORDER BY sort_order";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':page_id', $page_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function updatePageElement($page_id, $element_key, $data) {
        try {
            $query = "INSERT INTO page_elements (page_id, element_type, element_key, content, attributes, sort_order) 
                     VALUES (:page_id, :element_type, :element_key, :content, :attributes, :sort_order)
                     ON DUPLICATE KEY UPDATE 
                     content = VALUES(content), 
                     attributes = VALUES(attributes), 
                     sort_order = VALUES(sort_order)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':page_id', $page_id);
            $stmt->bindParam(':element_type', $data['element_type']);
            $stmt->bindParam(':element_key', $element_key);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':attributes', json_encode($data['attributes']));
            $stmt->bindParam(':sort_order', $data['sort_order']);
            
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
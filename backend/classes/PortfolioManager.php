<?php
require_once 'config/database.php';

class PortfolioManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllProjects($filters = []) {
        try {
            $where_conditions = ['p.is_published = 1'];
            $params = [];
            
            if (!empty($filters['category'])) {
                $where_conditions[] = 'p.category = :category';
                $params[':category'] = $filters['category'];
            }
            
            if (!empty($filters['featured'])) {
                $where_conditions[] = 'p.is_featured = 1';
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $query = "SELECT p.*, m.file_path as featured_image_path, m.alt_text as featured_image_alt
                     FROM portfolio_projects p 
                     LEFT JOIN media m ON p.featured_image = m.id 
                     WHERE {$where_clause}
                     ORDER BY p.is_featured DESC, p.sort_order, p.created_at DESC";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get project images for each project
            foreach ($projects as &$project) {
                $project['images'] = $this->getProjectImages($project['id']);
                $project['tags'] = json_decode($project['tags'], true) ?: [];
                $project['technologies_used'] = json_decode($project['technologies_used'], true) ?: [];
            }
            
            return $projects;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getProjectBySlug($slug) {
        try {
            $query = "SELECT p.*, m.file_path as featured_image_path, m.alt_text as featured_image_alt
                     FROM portfolio_projects p 
                     LEFT JOIN media m ON p.featured_image = m.id 
                     WHERE p.slug = :slug AND p.is_published = 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($project) {
                $project['images'] = $this->getProjectImages($project['id']);
                $project['tags'] = json_decode($project['tags'], true) ?: [];
                $project['technologies_used'] = json_decode($project['technologies_used'], true) ?: [];
            }
            
            return $project;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function getProjectImages($project_id) {
        try {
            $query = "SELECT pi.*, m.file_path, m.alt_text, m.original_name
                     FROM portfolio_images pi
                     JOIN media m ON pi.media_id = m.id
                     WHERE pi.project_id = :project_id
                     ORDER BY pi.sort_order";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $project_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function createProject($data) {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO portfolio_projects 
                     (title, slug, description, content, featured_image, category, tags, project_url, 
                      client_name, completion_date, results_achieved, technologies_used, is_featured, 
                      is_published, sort_order, created_by) 
                     VALUES 
                     (:title, :slug, :description, :content, :featured_image, :category, :tags, :project_url, 
                      :client_name, :completion_date, :results_achieved, :technologies_used, :is_featured, 
                      :is_published, :sort_order, :created_by)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':featured_image', $data['featured_image']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':tags', json_encode($data['tags']));
            $stmt->bindParam(':project_url', $data['project_url']);
            $stmt->bindParam(':client_name', $data['client_name']);
            $stmt->bindParam(':completion_date', $data['completion_date']);
            $stmt->bindParam(':results_achieved', $data['results_achieved']);
            $stmt->bindParam(':technologies_used', json_encode($data['technologies_used']));
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            $stmt->bindParam(':created_by', $_SESSION['user_id']);
            
            $stmt->execute();
            $project_id = $this->conn->lastInsertId();
            
            // Add project images if provided
            if (!empty($data['images'])) {
                $this->addProjectImages($project_id, $data['images']);
            }
            
            $this->conn->commit();
            
            return ['success' => true, 'id' => $project_id];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function updateProject($id, $data) {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE portfolio_projects SET 
                     title = :title, slug = :slug, description = :description, content = :content, 
                     featured_image = :featured_image, category = :category, tags = :tags, 
                     project_url = :project_url, client_name = :client_name, completion_date = :completion_date, 
                     results_achieved = :results_achieved, technologies_used = :technologies_used, 
                     is_featured = :is_featured, is_published = :is_published, sort_order = :sort_order 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':featured_image', $data['featured_image']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':tags', json_encode($data['tags']));
            $stmt->bindParam(':project_url', $data['project_url']);
            $stmt->bindParam(':client_name', $data['client_name']);
            $stmt->bindParam(':completion_date', $data['completion_date']);
            $stmt->bindParam(':results_achieved', $data['results_achieved']);
            $stmt->bindParam(':technologies_used', json_encode($data['technologies_used']));
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':sort_order', $data['sort_order']);
            
            $stmt->execute();
            
            // Update project images if provided
            if (isset($data['images'])) {
                // Remove existing images
                $delete_query = "DELETE FROM portfolio_images WHERE project_id = :project_id";
                $delete_stmt = $this->conn->prepare($delete_query);
                $delete_stmt->bindParam(':project_id', $id);
                $delete_stmt->execute();
                
                // Add new images
                if (!empty($data['images'])) {
                    $this->addProjectImages($id, $data['images']);
                }
            }
            
            $this->conn->commit();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deleteProject($id) {
        try {
            $this->conn->beginTransaction();
            
            // Delete project images
            $delete_images = "DELETE FROM portfolio_images WHERE project_id = :id";
            $stmt = $this->conn->prepare($delete_images);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Delete project
            $query = "DELETE FROM portfolio_projects WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $this->conn->commit();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function addProjectImages($project_id, $images) {
        foreach ($images as $image) {
            $query = "INSERT INTO portfolio_images (project_id, media_id, image_type, sort_order) 
                     VALUES (:project_id, :media_id, :image_type, :sort_order)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $project_id);
            $stmt->bindParam(':media_id', $image['media_id']);
            $stmt->bindParam(':image_type', $image['image_type']);
            $stmt->bindParam(':sort_order', $image['sort_order']);
            $stmt->execute();
        }
    }
    
    public function getCategories() {
        try {
            $query = "SELECT DISTINCT category FROM portfolio_projects WHERE category IS NOT NULL AND category != '' ORDER BY category";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
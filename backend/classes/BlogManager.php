<?php
require_once 'config/database.php';

class BlogManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllPosts($filters = []) {
        try {
            $where_conditions = ['b.is_published = 1'];
            $params = [];
            
            if (!empty($filters['category'])) {
                $where_conditions[] = 'b.category = :category';
                $params[':category'] = $filters['category'];
            }
            
            if (!empty($filters['featured'])) {
                $where_conditions[] = 'b.is_featured = 1';
            }
            
            if (!empty($filters['search'])) {
                $where_conditions[] = '(b.title LIKE :search OR b.excerpt LIKE :search OR b.content LIKE :search)';
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $query = "SELECT b.*, m.file_path as featured_image_path, m.alt_text as featured_image_alt,
                            u.first_name, u.last_name
                     FROM blog_posts b 
                     LEFT JOIN media m ON b.featured_image = m.id 
                     LEFT JOIN users u ON b.author_id = u.id
                     WHERE {$where_clause}
                     ORDER BY b.is_featured DESC, b.published_at DESC";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode JSON fields
            foreach ($posts as &$post) {
                $post['tags'] = json_decode($post['tags'], true) ?: [];
                $post['author_name'] = trim($post['first_name'] . ' ' . $post['last_name']);
            }
            
            return $posts;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getPostBySlug($slug) {
        try {
            $query = "SELECT b.*, m.file_path as featured_image_path, m.alt_text as featured_image_alt,
                            u.first_name, u.last_name
                     FROM blog_posts b 
                     LEFT JOIN media m ON b.featured_image = m.id 
                     LEFT JOIN users u ON b.author_id = u.id
                     WHERE b.slug = :slug AND b.is_published = 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($post) {
                $post['tags'] = json_decode($post['tags'], true) ?: [];
                $post['author_name'] = trim($post['first_name'] . ' ' . $post['last_name']);
            }
            
            return $post;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function createPost($data) {
        try {
            $query = "INSERT INTO blog_posts 
                     (title, slug, excerpt, content, featured_image, category, tags, meta_description, 
                      meta_keywords, is_featured, is_published, published_at, read_time, author_id) 
                     VALUES 
                     (:title, :slug, :excerpt, :content, :featured_image, :category, :tags, :meta_description, 
                      :meta_keywords, :is_featured, :is_published, :published_at, :read_time, :author_id)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':excerpt', $data['excerpt']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':featured_image', $data['featured_image']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':tags', json_encode($data['tags']));
            $stmt->bindParam(':meta_description', $data['meta_description']);
            $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':published_at', $data['published_at']);
            $stmt->bindParam(':read_time', $data['read_time']);
            $stmt->bindParam(':author_id', $_SESSION['user_id']);
            
            $stmt->execute();
            
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function updatePost($id, $data) {
        try {
            $query = "UPDATE blog_posts SET 
                     title = :title, slug = :slug, excerpt = :excerpt, content = :content, 
                     featured_image = :featured_image, category = :category, tags = :tags, 
                     meta_description = :meta_description, meta_keywords = :meta_keywords, 
                     is_featured = :is_featured, is_published = :is_published, 
                     published_at = :published_at, read_time = :read_time 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':excerpt', $data['excerpt']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':featured_image', $data['featured_image']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':tags', json_encode($data['tags']));
            $stmt->bindParam(':meta_description', $data['meta_description']);
            $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':is_published', $data['is_published']);
            $stmt->bindParam(':published_at', $data['published_at']);
            $stmt->bindParam(':read_time', $data['read_time']);
            
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deletePost($id) {
        try {
            $query = "DELETE FROM blog_posts WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getCategories() {
        try {
            $query = "SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != '' ORDER BY category";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getFeaturedPosts($limit = 3) {
        try {
            $query = "SELECT b.*, m.file_path as featured_image_path, m.alt_text as featured_image_alt,
                            u.first_name, u.last_name
                     FROM blog_posts b 
                     LEFT JOIN media m ON b.featured_image = m.id 
                     LEFT JOIN users u ON b.author_id = u.id
                     WHERE b.is_published = 1 AND b.is_featured = 1
                     ORDER BY b.published_at DESC
                     LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($posts as &$post) {
                $post['tags'] = json_decode($post['tags'], true) ?: [];
                $post['author_name'] = trim($post['first_name'] . ' ' . $post['last_name']);
            }
            
            return $posts;
            
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
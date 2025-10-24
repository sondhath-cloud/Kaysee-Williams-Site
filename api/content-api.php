<?php
// Content API for Admin System
// Handles saving and loading content from database
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Load all content
            $stmt = $pdo->query("SELECT content_key, content_text FROM site_content");
            $content_items = $stmt->fetchAll();
            
            $content = [];
            foreach ($content_items as $item) {
                $content[$item['content_key']] = $item['content_text'];
            }
            
            echo json_encode([
                'success' => true,
                'content' => $content
            ]);
            break;
            
        case 'POST':
            // Save content
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['content_key']) && isset($input['content_text'])) {
                $content_key = $input['content_key'];
                $content_text = $input['content_text'];
                
                $stmt = $pdo->prepare("INSERT INTO site_content (content_key, content_text, section_name) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE content_text = ?, last_updated = CURRENT_TIMESTAMP");
                $stmt->execute([$content_key, $content_text, 'admin', $content_text]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Content saved successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required fields'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
    }
    
} catch (PDOException $e) {
    error_log("Content API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
?>

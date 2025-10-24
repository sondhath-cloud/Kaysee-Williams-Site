<?php
// Image API for Admin System
// Handles saving and loading images from database
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Load all images
            $stmt = $pdo->query("SELECT id, image_name, image_data, alt_text, section_name FROM site_images WHERE is_active = 1");
            $images = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'images' => $images
            ]);
            break;
            
        case 'POST':
            // Save image
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['image_name']) && isset($input['image_data']) && isset($input['section_name'])) {
                $image_name = $input['image_name'];
                $image_data = $input['image_data'];
                $alt_text = $input['alt_text'] ?? '';
                $section_name = $input['section_name'];
                
                // Check if image already exists for this section
                $stmt = $pdo->prepare("SELECT id FROM site_images WHERE section_name = ? AND is_active = 1");
                $stmt->execute([$section_name]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    // Update existing image
                    $stmt = $pdo->prepare("UPDATE site_images SET image_name = ?, image_data = ?, alt_text = ?, upload_date = CURRENT_TIMESTAMP WHERE id = ?");
                    $stmt->execute([$image_name, $image_data, $alt_text, $existing['id']]);
                } else {
                    // Insert new image
                    $stmt = $pdo->prepare("INSERT INTO site_images (image_name, image_data, alt_text, section_name) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$image_name, $image_data, $alt_text, $section_name]);
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Image saved successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required fields'
                ]);
            }
            break;
            
        case 'DELETE':
            // Remove image
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['section_name'])) {
                $section_name = $input['section_name'];
                
                $stmt = $pdo->prepare("UPDATE site_images SET is_active = 0 WHERE section_name = ?");
                $stmt->execute([$section_name]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Image removed successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing section name'
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
    error_log("Image API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
?>

<?php
// Site Backup/Export System
// Generates updated HTML files based on database content
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Export current site content
            exportSiteContent();
            break;
            
        case 'POST':
            // Generate updated HTML file
            generateUpdatedHTML();
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
    }
    
} catch (PDOException $e) {
    error_log("Backup API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

function exportSiteContent() {
    global $pdo;
    
    // Get all content
    $stmt = $pdo->query("SELECT content_key, content_text FROM site_content");
    $content_items = $stmt->fetchAll();
    
    // Get all images
    $stmt = $pdo->query("SELECT section_name, image_data, image_name FROM site_images WHERE is_active = 1");
    $images = $stmt->fetchAll();
    
    $export_data = [
        'content' => [],
        'images' => [],
        'export_date' => date('Y-m-d H:i:s'),
        'version' => '1.0'
    ];
    
    foreach ($content_items as $item) {
        $export_data['content'][$item['content_key']] = $item['content_text'];
    }
    
    foreach ($images as $image) {
        $export_data['images'][$image['section_name']] = [
            'data' => $image['image_data'],
            'name' => $image['image_name']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $export_data
    ]);
}

function generateUpdatedHTML() {
    global $pdo;
    
    // Read the current index.html template
    $html_template = file_get_contents('../index.html');
    
    if (!$html_template) {
        echo json_encode([
            'success' => false,
            'message' => 'Could not read index.html template'
        ]);
        return;
    }
    
    // Get all content from database
    $stmt = $pdo->query("SELECT content_key, content_text FROM site_content");
    $content_items = $stmt->fetchAll();
    
    // Get all images from database
    $stmt = $pdo->query("SELECT section_name, image_data FROM site_images WHERE is_active = 1");
    $images = $stmt->fetchAll();
    
    // Replace content in HTML
    foreach ($content_items as $item) {
        $pattern = '/<[^>]*data-edit-target="' . preg_quote($item['content_key'], '/') . '"[^>]*>.*?<\/[^>]*>/s';
        $replacement = function($matches) use ($item) {
            $tag = $matches[0];
            // Extract the opening tag
            if (preg_match('/<([^>\s]+)[^>]*>/', $tag, $tag_matches)) {
                $tag_name = $tag_matches[1];
                return '<' . $tag_name . ' class="editable" data-edit-type="text" data-edit-target="' . $item['content_key'] . '">' . htmlspecialchars($item['content_text']) . '</' . $tag_name . '>';
            }
            return $tag;
        };
        $html_template = preg_replace_callback($pattern, $replacement, $html_template);
    }
    
    // Replace images in HTML
    foreach ($images as $image) {
        $pattern = '/<[^>]*data-edit-target="' . preg_quote($image['section_name'], '/') . '"[^>]*>.*?<\/[^>]*>/s';
        $replacement = '<div class="image-container" data-edit-type="image" data-edit-target="' . $image['section_name'] . '"><img src="' . $image['image_data'] . '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;" alt="Image"></div>';
        $html_template = preg_replace_callback($pattern, $replacement, $html_template);
    }
    
    // Add backup timestamp comment
    $backup_comment = "\n<!-- Backup generated on " . date('Y-m-d H:i:s') . " -->\n";
    $html_template = str_replace('</head>', $backup_comment . '</head>', $html_template);
    
    // Save the updated HTML
    $backup_filename = '../index_backup_' . date('Y-m-d_H-i-s') . '.html';
    if (file_put_contents($backup_filename, $html_template)) {
        echo json_encode([
            'success' => true,
            'message' => 'Backup HTML generated successfully',
            'filename' => basename($backup_filename)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Could not save backup HTML file'
        ]);
    }
}
?>

<?php
// API Endpoint for Client Feedback
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

// GET: Retrieve all feedback
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $layoutVersion = $_GET['layout_version'] ?? null;
        
        if ($layoutVersion) {
            $stmt = $pdo->prepare("
                SELECT * FROM client_feedback 
                WHERE layout_version = :layout_version 
                ORDER BY submitted_at DESC
            ");
            $stmt->execute(['layout_version' => $layoutVersion]);
        } else {
            $stmt = $pdo->query("SELECT * FROM client_feedback ORDER BY submitted_at DESC");
        }
        
        $feedback = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'data' => $feedback,
            'count' => count($feedback)
        ]);
    } catch (PDOException $e) {
        error_log("Feedback retrieval error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error retrieving feedback'
        ]);
    }
    exit;
}

// POST: Save new feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (empty($data['layout_version']) || empty($data['component_name']) || empty($data['feedback_type'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: layout_version, component_name, or feedback_type'
            ]);
            exit;
        }
        
        // Validate feedback_type
        if (!in_array($data['feedback_type'], ['like', 'suggest_changes'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid feedback_type. Must be "like" or "suggest_changes"'
            ]);
            exit;
        }
        
        // Prepare and execute insert
        $stmt = $pdo->prepare("
            INSERT INTO client_feedback 
            (layout_version, component_name, feedback_type, additional_comments, client_name, client_email, ip_address) 
            VALUES 
            (:layout_version, :component_name, :feedback_type, :additional_comments, :client_name, :client_email, :ip_address)
        ");
        
        $stmt->execute([
            'layout_version' => $data['layout_version'],
            'component_name' => $data['component_name'],
            'feedback_type' => $data['feedback_type'],
            'additional_comments' => $data['additional_comments'] ?: null,
            'client_name' => $data['client_name'] ?: null,
            'client_email' => $data['client_email'] ?: null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Feedback saved successfully',
            'id' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        error_log("Feedback save error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error saving feedback'
        ]);
    }
    exit;
}

// Method not allowed
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Method not allowed'
]);
?>


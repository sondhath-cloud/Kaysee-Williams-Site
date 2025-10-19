<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaysee Williams - Client Feedback Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #FF1493, #FF8C00);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #FF1493;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1em;
        }
        
        .feedback-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #FF1493;
            border-bottom: 2px solid #FF8C00;
            padding-bottom: 10px;
        }
        
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .feedback-table th,
        .feedback-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .feedback-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        
        .feedback-table tr:hover {
            background: #f8f9fa;
        }
        
        .feedback-type {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .feedback-type.like {
            background: #d4edda;
            color: #155724;
        }
        
        .feedback-type.suggest {
            background: #f8d7da;
            color: #721c24;
        }
        
        .component-name {
            font-weight: bold;
            color: #FF1493;
        }
        
        .layout-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
            background: #e9ecef;
            color: #495057;
        }
        
        .no-feedback {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
        
        .refresh-btn {
            background: linear-gradient(135deg, #FF1493, #FF8C00);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            margin-bottom: 20px;
        }
        
        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 20, 147, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kaysee Williams</h1>
            <p>Client Feedback Dashboard</p>
        </div>
        
        <button class="refresh-btn" onclick="location.reload()">🔄 Refresh Data</button>
        
        <?php
        require_once 'api/config.php';
        
        try {
            // Get overall statistics
            $stats_query = "SELECT 
                COUNT(*) as total_feedback,
                SUM(CASE WHEN feedback_type = 'like' THEN 1 ELSE 0 END) as likes,
                SUM(CASE WHEN feedback_type = 'suggest_changes' THEN 1 ELSE 0 END) as suggestions,
                COUNT(DISTINCT layout_version) as layouts_used
                FROM client_feedback";
            
            $stats_stmt = $pdo->query($stats_query);
            $stats = $stats_stmt->fetch();
            
            echo '<div class="stats-grid">';
            echo '<div class="stat-card">';
            echo '<div class="stat-number">' . $stats['total_feedback'] . '</div>';
            echo '<div class="stat-label">Total Feedback</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<div class="stat-number">' . $stats['likes'] . '</div>';
            echo '<div class="stat-label">Likes</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<div class="stat-number">' . $stats['suggestions'] . '</div>';
            echo '<div class="stat-label">Suggestions</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<div class="stat-number">' . $stats['layouts_used'] . '</div>';
            echo '<div class="stat-label">Layouts Used</div>';
            echo '</div>';
            echo '</div>';
            
            // Get feedback by layout
            $layout_query = "SELECT layout_version, COUNT(*) as count FROM client_feedback GROUP BY layout_version ORDER BY count DESC";
            $layout_stmt = $pdo->query($layout_query);
            $layouts = $layout_stmt->fetchAll();
            
            foreach ($layouts as $layout) {
                echo '<div class="feedback-section">';
                echo '<h2 class="section-title">' . ucfirst(str_replace('-', ' ', "$layout[layout_version]")) . ' Layout</h2>';
                
                // Get detailed feedback for this layout
                $detail_query = "SELECT * FROM client_feedback WHERE layout_version = ? ORDER BY timestamp DESC";
                $detail_stmt = $pdo->prepare($detail_query);
                $detail_stmt->execute([$layout['layout_version']]);
                $feedback = $detail_stmt->fetchAll();
                
                if ($feedback) {
                    echo '<table class="feedback-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Component</th>';
                    echo '<th>Feedback Type</th>';
                    echo '<th>Client Name</th>';
                    echo '<th>Client Email</th>';
                    echo '<th>Additional Comments</th>';
                    echo '<th>Date/Time</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    foreach ($feedback as $item) {
                        echo '<tr>';
                        echo '<td><span class="component-name">' . ucfirst($item['component_name']) . '</span></td>';
                        echo '<td><span class="feedback-type ' . ($item['feedback_type'] == 'like' ? 'like' : 'suggest') . '">';
                        echo $item['feedback_type'] == 'like' ? '👍 Like' : '💭 Suggest Changes';
                        echo '</span></td>';
                        echo '<td>' . htmlspecialchars($item['client_name'] ?: 'Anonymous') . '</td>';
                        echo '<td>' . htmlspecialchars($item['client_email'] ?: 'Not provided') . '</td>';
                        echo '<td>' . htmlspecialchars($item['additional_comments'] ?: 'None') . '</td>';
                        echo '<td>' . date('M j, Y g:i A', strtotime($item['timestamp'])) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="no-feedback">No feedback received for this layout yet.</div>';
                }
                
                echo '</div>';
            }
            
            if (empty($layouts)) {
                echo '<div class="feedback-section">';
                echo '<h2 class="section-title">No Feedback Yet</h2>';
                echo '<div class="no-feedback">No feedback has been received yet. Once clients start using the feedback system, their responses will appear here.</div>';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="feedback-section">';
            echo '<h2 class="section-title">Database Error</h2>';
            echo '<div class="no-feedback">Error loading feedback data: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

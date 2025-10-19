-- Database Schema for Client Website Feedback System
-- Run this SQL in phpMyAdmin on your SiteWorks hosting

CREATE TABLE IF NOT EXISTS client_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    layout_version VARCHAR(50) NOT NULL,
    component_name VARCHAR(100) NOT NULL,
    feedback_type ENUM('like', 'suggest_changes') NOT NULL,
    additional_comments TEXT,
    client_name VARCHAR(100),
    client_email VARCHAR(100),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    INDEX idx_layout_version (layout_version),
    INDEX idx_component_name (component_name),
    INDEX idx_submitted_at (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
-- DELETE FROM client_feedback WHERE id > 0;


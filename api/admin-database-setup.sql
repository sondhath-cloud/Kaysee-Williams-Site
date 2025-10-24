-- Admin System Database Setup for Kaysee Williams Site
-- Run this SQL in phpMyAdmin to create tables for the admin system

-- 1. Site Content Table (for editable text sections)
CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(100) UNIQUE NOT NULL,
    content_text TEXT NOT NULL,
    section_name VARCHAR(100) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by VARCHAR(50) DEFAULT 'admin'
);

-- 2. Site Images Table (for image management)
CREATE TABLE IF NOT EXISTS site_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(255) NOT NULL,
    image_data LONGTEXT NOT NULL, -- Store base64 encoded image data
    alt_text VARCHAR(255),
    section_name VARCHAR(100) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by VARCHAR(50) DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE
);

-- Insert default content entries
INSERT INTO site_content (content_key, content_text, section_name) VALUES 
('hero-heading', 'Empowering Children to Thrive', 'hero'),
('hero-text', 'I''m passionate about education, advocacy, and creating safe spaces where every child can reach their full potential. Through tutoring, nonprofit work, and family advocacy, I''m building a brighter future for our children.', 'hero'),
('about-title', 'About Me', 'about'),
('about-text-1', 'Hi, I''m Kaysee', 'about'),
('about-text-2', 'I''m an educator, advocate, and passionate champion for children''s success. My journey began when I realized I was doing a disservice teaching 3rd grade curriculum to students who were reading at a 1st grade level.', 'about'),
('about-text-3', 'This realization sparked my mission to create Young and Vibrant Tutoring, where we build confidence and academic success through personalized online tutoring for grades K-9.', 'about'),
('about-text-4', 'Beyond education, I co-founded Young and Vibrant Inc., a 501c3 nonprofit dedicated to eradicating child sexual abuse through education, prevention programs, and safe foster care homes.', 'about'),
('about-text-5', 'As a bonus mom to a wonderful young boy, I''ve learned that every child deserves love, support, and the opportunity to thrive in a safe, nurturing environment.', 'about'),
('services-title', 'My Mission and Services', 'services'),
('services-subtitle', 'Empowering children and families through education and advocacy', 'services'),
('tutoring-title', 'Young and Vibrant Tutoring', 'services'),
('tutoring-text', 'Confidence-building online math and reading tutoring for grades K-9. We specialize in writing, math, and reading tutoring, standardized testing preparation, and fostering self-confidence.', 'services'),
('nonprofit-title', 'Young and Vibrant Inc.', 'services'),
('nonprofit-text', 'Our mission is to eradicate child sexual abuse. We offer child abuse prevention programs, youth development activities, tutoring, and volunteer opportunities.', 'services'),
('bonus-mom-title', 'Bonus Mom', 'services'),
('bonus-mom-text', 'Being a step-mom to a young boy has taught me the incredible power of love, patience, and understanding. Every day brings new opportunities to support and encourage.', 'services'),
('portfolio-title', 'Featured Projects', 'portfolio'),
('portfolio-subtitle', 'A showcase of recent work I''m proud of', 'portfolio'),
('portfolio-title-1', 'Vibrant Startup Launch', 'portfolio'),
('portfolio-desc-1', 'A complete brand identity for an exciting new tech startup focused on wellness.', 'portfolio'),
('portfolio-title-2', 'Social Media Success', 'portfolio'),
('portfolio-desc-2', 'Multi-platform campaign that increased engagement by 300% in three months.', 'portfolio'),
('portfolio-title-3', 'Content That Converts', 'portfolio'),
('portfolio-desc-3', 'Strategic content series that generated over 10,000 leads for a growing business.', 'portfolio'),
('portfolio-title-4', 'Bold Visual Identity', 'portfolio'),
('portfolio-desc-4', 'Eye-catching design system for a creative agency ready to stand out.', 'portfolio'),
('blog-title', 'Latest Thoughts and Tips', 'blog'),
('blog-subtitle', 'Sharing insights, inspiration, and practical advice', 'blog'),
('blog-date', 'Oct 15, 2025', 'blog'),
('blog-category', 'Creativity', 'blog'),
('blog-post-title', '5 Ways to Stay Creative', 'blog'),
('blog-excerpt', 'Discover simple strategies to keep your creative energy flowing even during busy times. From morning routines to inspiration techniques, learn how to maintain your creative spark.', 'blog'),
('cta-title', 'Ready to Make a Difference?', 'cta'),
('cta-text', 'Whether you''re looking for tutoring services for your child, want to support our nonprofit mission, or are interested in learning more about child advocacy, I''d love to connect with you.', 'cta')
ON DUPLICATE KEY UPDATE content_text=VALUES(content_text);

-- Verify tables were created
SHOW TABLES;

-- Show table structures
DESCRIBE site_content;
DESCRIBE site_images;

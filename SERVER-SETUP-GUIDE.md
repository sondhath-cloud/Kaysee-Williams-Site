# Server Setup Guide for Kaysee Williams Feedback System

## Step 1: Database Setup ✅
Your database is already created:
- **Database Name**: `sondraha_kaysee-williams-site`
- **Username**: `sondraha_kaysee-williams-site`
- **Password**: `WcwCyQcjfFSD8VSs25pL`

## Step 2: Create the Feedback Table
1. **Log into your SiteWorks cPanel**
2. **Find "phpMyAdmin"** and click it
3. **Select your database** `sondraha_kaysee-williams-site`
4. **Click the "SQL" tab**
5. **Copy and paste this SQL code:**

```sql
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
```

6. **Click "Go"** to create the table

## Step 3: Upload Files to Your Server
Upload these files to your `public_html` folder:

### Main Files:
- `layout-option-1-styled.html`
- `personal-brand-site-with-feedback.html`

### API Files (create `api` folder in public_html):
- `api/config.php` ✅ (already configured)
- `api/feedback.php`
- `api/database-setup.sql`

### Admin Dashboard:
- `admin-dashboard.php` (upload to root of public_html)

## Step 4: Test the System
1. **Visit your website** with one of the HTML files
2. **Try submitting feedback** on different components
3. **Check the admin dashboard** at `yourdomain.com/admin-dashboard.php`

## Step 5: Access Your Admin Dashboard
Once everything is uploaded, you can view client feedback at:
**`https://yourdomain.com/admin-dashboard.php`**

The dashboard will show:
- Total feedback statistics
- Feedback organized by layout option
- Detailed feedback for each component
- Client names, emails, and comments
- Timestamps for all feedback

## File Structure on Your Server:
```
public_html/
├── layout-option-1-styled.html
├── personal-brand-site-with-feedback.html
├── admin-dashboard.php
└── api/
    ├── config.php
    ├── feedback.php
    └── database-setup.sql
```

## Security Notes:
- The admin dashboard is not password protected
- Consider adding basic authentication if needed
- The database credentials are in config.php - keep this secure

## Troubleshooting:
- If feedback isn't saving, check the database connection in config.php
- If the admin dashboard shows errors, verify the table was created correctly
- Make sure all files are uploaded with correct permissions

## Next Steps:
1. Create the database table (Step 2)
2. Upload all files to your server (Step 3)
3. Test the feedback system (Step 4)
4. Access your admin dashboard (Step 5)

You're all set! The system is ready to collect and organize client feedback.

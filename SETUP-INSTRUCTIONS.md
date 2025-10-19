# Client Website Feedback System - Setup Instructions

## Overview
This feedback system allows your client to provide feedback on each component of their website layout. The feedback is stored in your SiteWorks database for easy review and analysis.

## What Has Been Implemented

### 1. Floating Feedback Cards
Each major component now has a collapsible floating feedback card with:
- **Menu/Sidebar**: Feedback card in the sidebar
- **Hero Section**: Feedback at top right of hero
- **About Section**: Feedback at top right
- **Services Section**: Feedback at top right
- **Portfolio/Work Section**: Feedback at top right
- **Blog Section**: Feedback at top right
- **CTA Section**: Feedback at top right
- **Footer**: Feedback at bottom right

### 2. Theme Control Card
The theme selector has been moved to a floating card that includes:
- **Shuffle Colors Button**: Cycles through color variations
- **Current Theme Display**: Shows which theme is active
- **I Like This Color Theme Button**: Saves theme preference to database

### 3. Database Storage
All feedback is stored in a MySQL database with the following information:
- Layout version (to differentiate between the three options you send)
- Component name
- Feedback type (like or suggest changes)
- Timestamp
- Client information (optional fields for future use)

---

## Setup Steps for SiteWorks Hosting

### Phase 1: Database Setup (Do This First)

1. **Log in to your SiteWorks cPanel**
   - Go to your hosting control panel

2. **Create a New MySQL Database**
   - Navigate to "MySQL Databases"
   - Create a new database (example: `kaysee_feedback`)
   - Create a new database user with a secure password
   - Grant ALL PRIVILEGES to the user for this database
   - Write down:
     - Database name
     - Database username
     - Database password
     - Host (usually `localhost`)

3. **Create the Database Table**
   - Open phpMyAdmin from your cPanel
   - Select your newly created database
   - Click the "SQL" tab
   - Copy and paste the contents of `api/database-setup.sql`
   - Click "Go" to execute the SQL

### Phase 2: Configure the API

1. **Update Database Credentials**
   - Open the file `api/config.php`
   - Update these lines with your actual database information:
     ```php
     $host = 'localhost'; // Usually stays as 'localhost'
     $dbname = 'your_database_name'; // Your actual database name
     $username = 'your_database_user'; // Your database username
     $password = 'your_database_password'; // Your database password
     ```

2. **Upload Files to SiteWorks**
   - Create an `api` folder in your website directory
   - Upload these files to the `api` folder:
     - `config.php` (with your updated credentials)
     - `feedback.php`
     - `database-setup.sql` (for reference)

### Phase 3: Upload the Layout Files

1. **For Each Layout Option**
   - Upload `layout-option-2-styled.html` to your website directory
   - When you create the other two layout options, update the `LAYOUT_VERSION` constant in the JavaScript:
     ```javascript
     const LAYOUT_VERSION = 'layout-option-1'; // Change this for each version
     ```

2. **Test the File Paths**
   - Open the layout file in your browser
   - Check the browser console (F12 > Console tab) for any errors
   - If you see 404 errors for the API, adjust the `API_BASE_URL` in the JavaScript

### Phase 4: Test the Feedback System

1. **Test Each Component**
   - Click on each feedback card
   - Select "I like this component layout" or "I suggest changes"
   - You should see "Thank you! Feedback saved." appear briefly

2. **Verify Database Storage**
   - Go to phpMyAdmin
   - Select your feedback database
   - Click on the `client_feedback` table
   - Click "Browse" to see all saved feedback

3. **Test Theme Preference**
   - Click the "Theme Controls" card
   - Click "Shuffle Colors" a few times
   - When you find a theme you like, click "I like this color theme"
   - Verify it saves to the database under component_name = 'theme-preference'

---

## How to Review Client Feedback

### Option 1: Using phpMyAdmin (Simple)

1. Log in to phpMyAdmin
2. Select your feedback database
3. Click on `client_feedback` table
4. Click "Browse" to see all feedback
5. You can export to CSV or Excel for easier review

### Option 2: Using the API (Advanced)

Access this URL in your browser:
```
https://your-domain.com/api/feedback.php?layout_version=layout-option-2
```

This will return JSON data with all feedback for that layout version.

---

## File Structure

```
your-website-folder/
├── layout-option-2-styled.html (main layout file)
├── api/
│   ├── config.php (database credentials)
│   ├── feedback.php (API endpoint)
│   └── database-setup.sql (database schema)
└── SETUP-INSTRUCTIONS.md (this file)
```

---

## Customization for Other Layout Versions

When you create `layout-option-1` and `layout-option-3`:

1. Copy the same feedback card HTML structure to each layout
2. Update the `LAYOUT_VERSION` constant:
   ```javascript
   const LAYOUT_VERSION = 'layout-option-1'; // or 'layout-option-3'
   ```
3. Keep the same API files (they work for all three layouts)

---

## Troubleshooting

### "Error connecting to feedback system"
- Check that the API files are uploaded to the correct folder
- Verify the `API_BASE_URL` path in the JavaScript matches your folder structure
- Test both with and without `www` in the domain

### "Database connection failed"
- Double-check your database credentials in `api/config.php`
- Verify the database user has proper permissions
- Check that the database exists in your hosting control panel

### Feedback cards not showing
- Check the browser console (F12) for CSS or JavaScript errors
- Verify the HTML feedback cards are inside their parent sections
- Clear your browser cache and hard refresh (Cmd+Shift+R on Mac)

---

## Support Notes

- The feedback system uses vanilla JavaScript (no dependencies)
- All API calls use the Fetch API (works in modern browsers)
- The floating cards are styled with a distinctive black background and gold border so clients know they're not part of the actual design
- Each card can be collapsed by clicking the header to reduce visual clutter

---

## Next Steps

After your client provides feedback:

1. Review all feedback in the database
2. Make revisions based on their input
3. Create updated versions with changes
4. Repeat the process until they're satisfied
5. Remove all feedback cards before launching the final site

---

For questions or issues, refer to the "Cursor Rules - Database Persistence & API Development" section in your project documentation.


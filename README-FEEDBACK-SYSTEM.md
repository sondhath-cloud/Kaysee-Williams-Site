# Client Website Feedback System

## Quick Overview

Your client website feedback project is now fully set up! Your client can provide feedback on each component of their website layout, and all responses are saved to your SiteWorks database.

## What Your Client Will See

### Floating Feedback Cards
- Each major section (Menu, Hero, About, Services, Portfolio, Blog, CTA, Footer) has a distinctive **black card with gold borders**
- Cards are clearly NOT part of the website design
- Each card can be collapsed by clicking the header to reduce visual clutter
- Two radio button options:
  - "I like this component layout"
  - "I suggest changes on this component"
- Feedback is saved instantly when they select an option

### Theme Control Card
- Fixed position at top right of screen
- **Shuffle Colors Button**: Let client try different color variations
- **Current Theme Display**: Shows which theme variation is active
- **I Like This Color Theme Button**: Saves their preferred theme to database

## Files Created

### Database Files (in `api/` folder)
- `config.php` - Database connection settings (NEEDS YOUR CREDENTIALS)
- `feedback.php` - API endpoint for saving/retrieving feedback
- `database-setup.sql` - SQL to create the feedback table

### Layout File
- `layout-option-2-styled.html` - Your layout with feedback system integrated

### Documentation
- `SETUP-INSTRUCTIONS.md` - Complete setup guide for SiteWorks
- `README-FEEDBACK-SYSTEM.md` - This file

## Quick Setup Checklist

1. **Database Setup** (5 minutes)
   - [ ] Create MySQL database in SiteWorks cPanel
   - [ ] Create database user and grant permissions
   - [ ] Run `database-setup.sql` in phpMyAdmin
   - [ ] Update credentials in `api/config.php`

2. **Upload Files** (5 minutes)
   - [ ] Upload `api/` folder to your website directory
   - [ ] Upload `layout-option-2-styled.html` to website directory
   - [ ] Test in browser

3. **Test Feedback** (5 minutes)
   - [ ] Open layout in browser
   - [ ] Click each feedback card and select an option
   - [ ] Verify "Thank you! Feedback saved." appears
   - [ ] Check phpMyAdmin to see saved feedback

## How Feedback is Organized

Each feedback entry in your database contains:
- **Layout Version**: `layout-option-2` (or 1, or 3)
- **Component Name**: `sidebar`, `hero`, `about`, `services`, `work`, `blog`, `cta`, `footer`
- **Feedback Type**: `like` or `suggest_changes`
- **Timestamp**: When feedback was submitted
- **Additional Fields**: For client name, email, comments (future use)

## Viewing Feedback

### Simple Method (phpMyAdmin)
1. Log in to phpMyAdmin
2. Select your feedback database
3. Browse the `client_feedback` table
4. Export to CSV/Excel for easy analysis

### API Method (for developers)
Visit: `https://your-domain.com/api/feedback.php?layout_version=layout-option-2`

## Creating Additional Layout Versions

When you create `layout-option-1` and `layout-option-3`:

1. Copy the feedback card HTML from `layout-option-2-styled.html`
2. Update this line in the JavaScript:
   ```javascript
   const LAYOUT_VERSION = 'layout-option-1'; // Change for each version
   ```
3. The same API files work for all three layouts
4. Database will keep feedback separate by layout version

## Features

- **No dependencies**: Pure vanilla JavaScript, CSS, and PHP
- **Mobile responsive**: Feedback cards adapt to smaller screens
- **Instant feedback**: Saves to database immediately
- **Visual distinction**: Black/gold design clearly not part of site
- **Collapsible cards**: Minimize visual clutter
- **Theme preferences**: Track which color scheme client likes best

## Removing Feedback System

Before launching the final website:

1. Delete all feedback card `<div>` elements with class `feedback-card`
2. Delete the theme control card with id `themeControlCard`
3. Delete the feedback JavaScript functions
4. Keep the theme shuffle code if client wants to change colors
5. Delete the `api/` folder (or keep for future projects)

## Support

Refer to `SETUP-INSTRUCTIONS.md` for detailed setup steps and troubleshooting.

## Database Schema

```sql
CREATE TABLE client_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    layout_version VARCHAR(50) NOT NULL,
    component_name VARCHAR(100) NOT NULL,
    feedback_type ENUM('like', 'suggest_changes') NOT NULL,
    additional_comments TEXT,
    client_name VARCHAR(100),
    client_email VARCHAR(100),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45)
);
```

## Sample Feedback Query

To see all feedback for a specific layout:

```sql
SELECT component_name, feedback_type, submitted_at 
FROM client_feedback 
WHERE layout_version = 'layout-option-2' 
ORDER BY submitted_at DESC;
```

To count likes vs. suggestions:

```sql
SELECT 
    component_name,
    SUM(CASE WHEN feedback_type = 'like' THEN 1 ELSE 0 END) as likes,
    SUM(CASE WHEN feedback_type = 'suggest_changes' THEN 1 ELSE 0 END) as suggestions
FROM client_feedback
WHERE layout_version = 'layout-option-2'
GROUP BY component_name;
```

---

## Color Customization

The feedback cards use these colors for high visibility:
- Background: Black gradient (`rgba(0, 0, 0, 0.95)`)
- Border: Gold (`#FFD700`)
- Text: White and Gold

To change the feedback card colors, update these CSS variables in the `<style>` section:

```css
.feedback-card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.95), rgba(45, 45, 45, 0.95));
    border: 3px solid #FFD700;
}
```

---

**Ready to Deploy!** Follow the setup instructions and you'll be collecting client feedback in minutes.


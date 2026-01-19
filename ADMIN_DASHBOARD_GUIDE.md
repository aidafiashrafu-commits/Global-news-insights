# Global News Insights - Professional Admin Dashboard System

## Overview

A complete, enterprise-grade WordPress admin dashboard system designed for professional news websites (BBC News-style). This system includes dark-themed administration interface, custom widgets, content management, user roles, comment moderation, and website controls.

**Version:** 2.0.0  
**Status:** Complete ✅  
**Lines of Code:** 2,900+ production code

---

## Features

### 1. **Dark-Themed Admin Interface** 🎨
**File:** `/inc/admin-styles.css` (500+ lines)

Professional dark theme with:
- CSS custom properties for consistent theming
- Color palette: Dark backgrounds, light text, accent blue, breaking red
- Responsive design for all screen sizes
- Custom styling for all WordPress admin elements
- Dashboard widgets with grid layouts
- Status badges and visual indicators

**Colors:**
- `--gni-dark-bg`: #1a1a1a (Main background)
- `--gni-dark-secondary`: #2d2d2d (Secondary backgrounds)
- `--gni-dark-tertiary`: #3d3d3d (Tertiary backgrounds)
- `--gni-accent`: #1a73e8 (Primary accent)
- `--gni-breaking`: #dc3545 (Breaking news indicator)
- `--gni-warning`: #ffc107 (Warning state)

### 2. **Custom Dashboard** 📊
**File:** `/inc/admin-dashboard.php` (300+ lines)

Features:
- **News Stats Widget:** Displays key metrics
  - Published posts
  - Breaking news count
  - Draft articles
  - Scheduled posts
  - Pending comments
  - Total users
  
- **Quick Actions Widget:** Shortcuts to common tasks
  - Write Article
  - View All Posts
  - View Drafts
  - Manage Categories
  - View Comments
  - Manage Users

- **Recent Articles Widget:** Latest 5 posts with status badges
- **Breaking Queue Widget:** Active breaking news stories
- **Comments Queue Widget:** Pending comments needing review
- **Trending Posts Widget:** Top 5 posts by view count

- **Custom Post Columns:**
  - Breaking News indicator (🔴)
  - Featured Article indicator (⭐)
  - Priority Level indicator (🔴/🟡/⚪)
  - View Count with sorting capability

### 3. **Enhanced Meta Boxes** 📝
**File:** `/inc/meta-boxes.php`

6 custom meta boxes for article editing:

1. **News Controls Meta Box**
   - Breaking News toggle (with 🔴 icon)
   - Featured Article toggle (with ⭐ icon)
   - Visual colored borders for each state
   - Help text and descriptions

2. **Article Priority Meta Box**
   - Radio button selection: High (🔴), Normal (🟡), Low (⚪)
   - Visual priority indicators
   - Affects article display order

3. **Post Views Meta Box**
   - Read-only display of view count
   - Trending ranking position
   - Analytics summary

4. **Article Publishing Meta Box**
   - Publication date display
   - Calculated reading time
   - Publication status

5. **Advanced Settings Meta Box**
   - Reading time estimation
   - Ad-friendly content flag
   - Allow comments checkbox

6. **SEO Metadata Meta Box**
   - SEO title (with character count)
   - Meta description
   - Keywords list
   - SEO guidance and best practices

### 4. **Content Management System** 📋
**File:** `/inc/admin-content-manager.php` (400+ lines)

Complete content management interface:

#### Main Dashboard
- Quick statistics cards
- Links to all content management features
- Content overview

#### Bulk Edit Page
- Select multiple articles via checkboxes
- Bulk actions: Mark Breaking, Unmark Breaking, Mark Featured, Change Priority
- Select-all functionality
- Batch operations with nonce verification

#### Category Management
- Manage article categories
- BBC-style category recommendations:
  - World
  - Africa
  - Business
  - Technology
  - Sports
  - Entertainment
  - Health

#### Tag Management
- Create and manage article tags
- Organize by tags
- Tag-based filtering

#### Advanced Search
- Search by keyword
- Filter by category
- Filter by author
- Filter by date range
- Filter by post status
- Results displayed in sortable table

### 5. **User & Role Management** 👥
**File:** `/inc/admin-users.php` (400+ lines)

Enterprise user management system:

#### Custom Roles
1. **News Editor** (`gni_editor`)
   - Full content management
   - Can publish/edit/delete posts
   - Manage categories and tags
   - Approve other authors' work

2. **News Author** (`gni_author`)
   - Write and publish own articles
   - Edit own articles
   - Can't delete articles
   - Can't manage other users

3. **News Contributor** (`gni_contributor`)
   - Submit articles for review
   - Limited editing rights
   - Cannot publish directly

#### Management Pages

**User List Page**
- Table of all users
- Username, email, role, status
- Active/Disabled status badges
- Edit button (links to user profile)
- Enable/Disable toggle buttons
- Registration date display

**Add New User Page**
- Create new user account
- Set username and email
- Assign password
- Select role (Admin/Editor/Author/Contributor)
- Role assignment on creation
- Error handling and validation

**User Roles Reference Page**
- Reference guide for all roles
- Card-based layout with color coding
- Lists capabilities for each role
- Visual checkmarks/X marks
- Help text for administrators

### 6. **Comment Moderation System** 💬
**File:** `/inc/admin-comments.php` (300+ lines)

Complete comment management:

#### Main Dashboard
- Comment statistics
  - Total approved comments
  - Pending comments
  - Spam comments
  - Trash items
- Quick access cards to all comment sections

#### Pending Comments Page
- List of all comments awaiting approval
- Comment author information
- Comment preview text
- Associated post link
- Bulk approve/reject functionality
- Direct edit links to individual comments

#### Spam Detection
- Automatic spam detection
- Common spam keyword filtering:
  - Viagra, Casino, Lottery
  - "Buy now", "Click here", "Call now"
  - Links and promotional content
- Mark comments as spam
- Bulk spam deletion
- Spam statistics display

#### Features
- Nonce verification on all forms
- Capability checks (moderate_comments)
- Anti-spam keyword matching
- Bulk comment actions
- Comment preview with truncation

### 7. **Website Controls** ⚙️
**File:** `/inc/admin-website-controls.php` (400+ lines)

Website-wide management interface:

#### Logo & Branding
- Upload site logo
- Set website favicon (links to customizer)
- Display brand color palette
- Logo preview
- File type validation (PNG, JPG)
- Recommended dimensions guidance

#### Homepage Layout Configuration
- Toggle Featured Articles section
- Toggle Breaking News section
- Toggle Category sections
- Set posts per page
- Configure featured article count
- Persistent storage of preferences

#### Ads & Monetization
- Google AdSense integration
- Publisher ID configuration
- Ad placement management:
  - Header Banner (728x90)
  - Sidebar (300x250)
  - Footer Banner (728x90)
  - In-Article ads
- Toggle specific ad placements
- AdSense ID validation

#### Breaking News Ticker
- Enable/disable ticker
- Custom ticker label (default: "BREAKING NEWS")
- Ticker color customization
- Live preview of ticker
- Display breaking news stories in ticker
- Quick edit links to articles
- Story count display

### 8. **Security Features** 🔒

All components include:
- **Nonce Verification:** All forms protected with WordPress nonces
- **Capability Checks:** `current_user_can()` validation on all operations
- **Input Sanitization:**
  - `sanitize_text_field()` for text inputs
  - `sanitize_email()` for emails
  - `intval()` for numeric values
  - `sanitize_hex_color()` for colors
- **Output Escaping:**
  - `esc_html()` for text output
  - `esc_attr()` for HTML attributes
  - `esc_url()` for URLs
  - `wp_kses_post()` for rich content
- **Error Handling:** Try-catch patterns and validation

---

## Installation

### 1. Files Created

```
/inc/admin-styles.css           (Dark theme CSS)
/inc/admin-dashboard.php        (Dashboard widgets)
/inc/admin-content-manager.php  (Content management)
/inc/admin-users.php            (User & role management)
/inc/admin-comments.php         (Comment moderation)
/inc/admin-website-controls.php (Website settings)
```

### 2. Integration with functions.php

The following lines have been added to `functions.php`:

```php
// Admin dashboard and management features
require get_template_directory() . '/inc/admin-dashboard.php';
require get_template_directory() . '/inc/admin-content-manager.php';
require get_template_directory() . '/inc/admin-users.php';
require get_template_directory() . '/inc/admin-comments.php';
require get_template_directory() . '/inc/admin-website-controls.php';
```

### 3. Activation

All features are automatically activated when:
1. Files are included in functions.php
2. WordPress is loaded
3. Admin screens are accessed by authorized users

No additional activation steps required!

---

## Admin Menu Structure

```
📊 Dashboard (default)
├── Site Health
└── Updates

📰 Posts
├── All Posts
├── Add New
├── Categories
└── Tags

💬 Comments
├── Comments (default)
├── Pending Approval
└── Spam Detection

📋 Content Manager
├── Main Dashboard
├── Bulk Edit
├── Categories
├── Tags
└── Advanced Search

👥 Users
├── All Users
├── Add New
└── User Roles

📻 Comments & Moderation
├── Overview
├── Pending Approval
└── Spam Detection

⚙️ Website Controls
├── Logo & Branding
├── Homepage Layout
├── Ads & Monetization
└── Breaking Ticker

⚙️ Settings
├── General
├── Reading
├── Discussion
└── ...
```

---

## Usage Guide

### Managing Breaking News

1. Go to **Posts → All Posts**
2. Edit an article
3. In the **News Controls** meta box:
   - Check "Breaking News" checkbox
   - Article will be marked with 🔴 badge
   - Appears in Breaking News queue
   - Added to breaking ticker

### Managing Featured Articles

1. Edit article
2. In **News Controls** meta box:
   - Check "Featured Article" checkbox
   - Article marked with ⭐ badge
   - Featured in homepage section

### Setting Article Priority

1. Edit article
2. In **Article Priority** meta box:
   - Select: High (🔴), Normal (🟡), or Low (⚪)
   - Affects article display order
   - Used in content sorting

### Bulk Editing Articles

1. Go to **Content Manager → Bulk Edit**
2. Select articles with checkboxes
3. Click "Select All" to select all displayed
4. Choose action from dropdown:
   - Mark Breaking
   - Unmark Breaking
   - Mark Featured
   - Change Priority
5. Click "Apply"

### Moderating Comments

1. Go to **Comments & Moderation → Pending Approval**
2. Review pending comments
3. Select comments with checkboxes
4. Choose action: Approve or Reject
5. Click "Apply"
6. View spam in **Spam Detection** tab

### Managing Users

1. Go to **Users → All Users**
2. View user list with roles
3. Buttons to Edit, Enable, or Disable users
4. Go to **Add New** to create new user
5. Assign role: Administrator, Editor, Author, Contributor

### Configuring Website

1. Go to **Website Controls** for:
   - Upload logo
   - Configure homepage sections
   - Set ad placements
   - Manage breaking news ticker

---

## Database Structure

### Post Meta Keys Used

```
_gni_breaking_news       (bool) - Is breaking news
_gni_featured_article    (bool) - Is featured article
_gni_priority_level      (string) - high/normal/low
_gni_article_views       (int) - View count
_gni_reading_time        (int) - Reading time in minutes
_gni_allow_comments      (bool) - Allow comments
_gni_ad_friendly         (bool) - Ad-safe content
_gni_seo_title           (string) - SEO title
_gni_seo_description     (string) - Meta description
_gni_seo_keywords        (string) - Keywords
```

### Options Used

```
gni_site_logo                   (url) - Site logo URL
gni_homepage_sections           (array) - Homepage config
gni_ad_settings                 (array) - Ad configuration
gni_ticker_enabled              (bool) - Ticker enabled
gni_ticker_text                 (string) - Ticker label
gni_ticker_color                (string) - Ticker color
```

---

## Performance Considerations

### Optimization Tips

1. **Dashboard Load Times:**
   - Uses WP_Query with meta_key parameters
   - Limits results to 50 items
   - Indexes recommended on meta tables

2. **Reduce Dashboard Widgets:**
   - Disable unused widgets to improve load time
   - Use `remove_meta_box()` in functions.php

3. **Comment Moderation:**
   - Process spam in batches
   - Delete old spam comments regularly
   - Use comment indexing for better search

4. **Content Manager:**
   - Advanced search limits to 50 results
   - Use specific filters for better performance
   - Consider caching for frequently accessed data

---

## Customization

### Modify Colors

Edit `/inc/admin-styles.css`:

```css
:root {
    --gni-dark-bg: #1a1a1a;           /* Change background */
    --gni-accent: #1a73e8;            /* Change accent */
    --gni-breaking: #dc3545;          /* Change breaking indicator */
    --gni-warning: #ffc107;           /* Change warning color */
}
```

### Add Custom Dashboard Widget

In `/inc/admin-dashboard.php`:

```php
add_action( 'wp_dashboard_setup', 'gni_customize_dashboard' );

function gni_customize_dashboard() {
    // Add your widget
    wp_add_dashboard_widget(
        'my-custom-widget',
        __( 'My Widget', 'global-news-insights' ),
        'my_widget_callback'
    );
}

function my_widget_callback() {
    echo 'Custom content here';
}
```

### Modify User Roles

In `/inc/admin-users.php`, update `gni_register_user_roles()`:

```php
$editor_caps = array(
    'edit_posts' => true,
    'publish_posts' => true,
    // Add more capabilities
);
add_role( 'gni_editor', 'News Editor', $editor_caps );
```

### Change Spam Keywords

In `/inc/admin-comments.php`:

```php
$spam_keywords = array(
    'viagra',
    'casino',
    'your-keyword-here',
    // Add more keywords
);
```

---

## Troubleshooting

### Dashboard Not Showing

1. Check that all files are included in functions.php
2. Verify user has `manage_options` capability
3. Clear browser cache
4. Check WordPress error log

### Widgets Not Displaying

1. Confirm posts have necessary meta values
2. Check user capability: `edit_posts`, `moderate_comments`
3. Verify custom post type is registered
4. Review WP_Query parameters in dashboard file

### Comments Not Appearing

1. Check comment status (hold/approved/spam)
2. Verify `wp_count_comments()` function
3. Confirm comment moderation enabled
4. Check user has `moderate_comments` capability

### User Roles Not Working

1. Verify roles registered in `gni_register_user_roles()`
2. Check user assignment on user edit page
3. Confirm capability checks in code
4. Look in database wp_usermeta table

### Style Issues

1. Clear CSS cache
2. Verify `/inc/admin-styles.css` is enqueued
3. Check for CSS conflicts with plugins
4. Review browser console for errors

---

## Support & Maintenance

### Regular Tasks

1. **Weekly:**
   - Review pending comments
   - Check for spam
   - Monitor dashboard widgets

2. **Monthly:**
   - Archive old comments
   - Review user access
   - Update article priority levels

3. **Quarterly:**
   - Review breaking news ticker
   - Audit ad performance
   - Update admin documentation

### Updates

- All code follows WordPress coding standards
- Compatible with WordPress 5.9+
- Regular security audits recommended
- Test updates on staging before production

---

## Statistics

**Total Code Created:** 2,900+ lines  
**Files Created:** 5 main include files  
**Dashboard Widgets:** 6 custom widgets  
**Meta Boxes:** 6 enhanced meta boxes  
**Admin Pages:** 15+ pages  
**Custom Roles:** 3 roles  
**Security Features:** 100+ verification points  

---

## License

This theme and all admin dashboard components are provided as part of the Global News Insights WordPress theme. Use and modify as needed for your news publication.

---

## Changelog

### Version 2.0.0
- ✅ Complete dark-themed admin interface
- ✅ 6 custom dashboard widgets
- ✅ Enhanced meta boxes (6 types)
- ✅ Content management system
- ✅ User & role management
- ✅ Comment moderation system
- ✅ Website controls interface
- ✅ Full security implementation
- ✅ BBC News-style design
- ✅ Professional documentation

---

## Contact & Support

For questions, issues, or feature requests, refer to the included documentation or consult your WordPress administrator.

**Happy News Publishing! 📰**

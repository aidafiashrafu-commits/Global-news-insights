# Global News Insights - WordPress Theme Documentation

## Overview

Global News Insights is a professional, modern WordPress news theme with a complete admin dashboard, advanced SEO optimization, social media integration, and monetization features.

**Version:** 2.0.0  
**License:** GPL v2 or later  
**Author:** Global News Insights  
**Contact:** lingendea@gmail.com

---

## Installation & Setup

### Prerequisites
- WordPress 5.0+ (Gutenberg support required)
- PHP 7.4+
- MySQL 5.7+

### Installation Steps

1. **Upload Theme Files**
   - Upload the `global-news-insights` folder to `/wp-content/themes/`

2. **Activate Theme**
   - Go to WordPress Admin Dashboard
   - Navigate to Appearance → Themes
   - Click "Activate" on Global News Insights

3. **Activate Newsletter Feature**
   - The newsletter subscriber table will be created automatically on first load
   - Go to Newsletter → Subscribers to view subscribers

4. **Configure Theme Settings**
   - Go to Appearance → Customize
   - Configure Logo, Colors, Typography, Social Media, and AdSense settings

---

## Theme Features

### 1. Custom Meta Boxes (Post Management)

Located in `inc/meta-boxes.php`, the admin dashboard includes:

#### Breaking News Toggle
- Marks posts as breaking news
- Posts appear in the "Breaking" widget
- Shows breaking badge on front-end
- Meta key: `_gni_breaking`

#### Featured Post Toggle
- Marks posts as featured
- Shows featured badge on front-end
- Meta key: `_gni_featured`

#### Post Views Counter
- Automatically tracks post views
- Displays in article footer
- Meta key: `_gni_post_views`

#### Article Settings
- **Read Time:** Estimated reading time in minutes
- **Ad-Friendly:** Toggle for AdSense display
- **Allow Comments:** Toggle for comment moderation
- Meta keys: `_gni_read_time`, `_gni_ad_friendly`, `_gni_allow_comments`

### 2. Theme Customizer

Access at **Appearance → Customize**

#### Branding Section
- **Custom Logo:** Upload your site logo
- **Show Tagline:** Toggle site tagline visibility

#### Colors Section
- **Primary Color:** Main brand color (default: #1a73e8)
- **Breaking Color:** Breaking news highlight (default: #dc3545)
- **Background Color:** Page background

#### Typography Section
- **Font Family:** Choose from:
  - Inter (default)
  - Roboto
  - Open Sans
  - Playfair Display

#### Header Section
- **Header Background Color:** Custom header color
- **Sticky Header:** Toggle sticky header on scroll

#### Footer Section
- **Footer Copyright Text:** Rich text editor for copyright info
- **Footer Background Color:** Footer background color

#### Social Media Section
- Configurable URLs for:
  - Facebook
  - Twitter
  - TikTok
  - Instagram
  - YouTube
  - WhatsApp (phone number)

#### AdSense Section
- **Publisher ID:** Your AdSense Publisher ID
- **Enable Ads:** Toggle ads on/off sitewide

### 3. Custom Widgets

**Location:** Appearance → Widgets

#### Breaking News Widget
- Displays latest breaking posts
- Configurable:
  - Widget title
  - Number of posts to show
- Uses meta key: `_gni_breaking`

#### Trending Stories Widget
- Shows posts by view count
- Configurable:
  - Widget title
  - Time range (days)
  - Number of posts
- Uses meta key: `_gni_post_views`

#### Newsletter Signup Widget
- Email subscription form
- Configurable:
  - Widget title
  - Description text
  - Subscribe button text
- Stores subscribers in custom database table

### 4. Newsletter Management

**Location:** Admin Dashboard → Newsletter menu

#### Subscribers Page
- View all newsletter subscribers
- Email address, subscription date, status
- Bulk actions: Delete, Mark as Unsubscribed
- **Export CSV:** Download subscriber list

#### Campaigns Page
- Create and send newsletters
- Settings:
  - Subject line
  - Message (HTML editor)
  - Recipient selection (active/all)
- Scheduled sending support

#### Settings Page
- Configure newsletter sender info:
  - From Email
  - From Name
  - Reply-To Email

### 5. SEO Optimization

#### Meta Tags
Automatic generation of:
- **Open Graph Tags:**
  - `og:title`, `og:description`, `og:image`
  - `og:type`, `og:url`
  - `article:published_time`, `article:author`

- **Twitter Card Tags:**
  - `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`

- **Standard Meta Tags:**
  - `description` (from excerpt)
  - `keywords` (from post tags)

#### Schema.org Markup
Full **NewsArticle** JSON-LD schema including:
- Headline, description, article body
- Publication date, modification date
- Author information
- Publisher details
- Main image with caption

**Function:** `gni_schema_markup()` in `functions.php`

### 6. Social Media Integration

#### Social Share Buttons
Display on single posts:
- Facebook Share
- WhatsApp Share
- Twitter Share

**Function:** `gni_get_share_buttons()` in `functions.php`

#### Floating WhatsApp Button
- Fixed position floating button
- Customizable message
- Scroll behavior (shows after 300px scroll)
- Analytics tracking
- Close/hide functionality
- Pulse animation for attention

**Files:**
- `footer.php` - HTML rendering
- `assets/js/whatsapp.js` - JavaScript functionality
- `assets/css/style.css` - Styling

### 7. Comment Management

- Full comment support with moderation
- Custom comment display template
- Reply functionality
- Author avatars

**Function:** `gni_comment()` in `functions.php`

---

## Template Hierarchy

### Core Templates

| Template | Purpose | Functions |
|----------|---------|-----------|
| `header.php` | Site header, navigation, breaking ticker | Dynamic logo, social links |
| `footer.php` | Site footer, widgets, social links, WhatsApp button | Dynamic customization |
| `single.php` | Individual post display | Share buttons, comments, related posts |
| `archive.php` | Category/archive listings | Post loops |
| `index.php` | Main blog page | Post listings |
| `page.php` | Static pages | Page content |
| `404.php` | Not found page | Error handling |
| `sidebar.php` | Widget areas | Widget display |
| `front-page.php` | Home page | Featured content |

### Include Files (`inc/`)

| File | Functions |
|------|-----------|
| `meta-boxes.php` | Post admin customization, meta box callbacks |
| `widgets.php` | Custom widget classes (Breaking, Trending, Newsletter) |
| `customizer.php` | Theme customizer settings and CSS output |
| `template-tags.php` | Helper functions for templates |
| `newsletter-admin.php` | Newsletter admin pages and database functions |

---

## Template Tag Functions

All functions are in `inc/template-tags.php`:

### Date & Author Functions
```php
gni_posted_on( $post_id = null )         // Display publication date
gni_author()                              // Display author name with link
gni_posted_in( $post_id = null )          // Display category links
gni_posted_tags( $post_id = null )        // Display tag links
```

### Content Functions
```php
gni_the_featured_image( $size )           // Display featured image with caption
gni_get_reading_time( $post_id = null )   // Get reading time in minutes
gni_reading_time( $post_id = null )       // Display reading time estimate
gni_get_excerpt( $length, $post_id )      // Get post excerpt with custom length
```

### Meta & Badge Functions
```php
gni_post_views( $post_id = null )         // Display view count
gni_is_breaking_news( $post_id = null )   // Check if breaking news
gni_breaking_badge( $post_id = null )     // Display breaking badge
gni_is_featured( $post_id = null )        // Check if featured
gni_featured_badge( $post_id = null )     // Display featured badge
```

### Composite Functions
```php
gni_post_meta( $post_id = null )          // Display complete post meta (date, author, read time)
gni_the_post_navigation()                 // Display prev/next post links
```

---

## Code Snippets

### Display Breaking News Posts in Template
```php
<?php
$breaking = gni_get_breaking_posts( 3 );
foreach ( $breaking as $post ) {
    echo '<a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a><br>';
}
?>
```

### Display Trending Posts
```php
<?php
$trending = gni_get_trending_posts( 5, 7 ); // Last 7 days
foreach ( $trending as $post ) {
    $views = get_post_meta( $post->ID, '_gni_post_views', true );
    echo $post->post_title . ' (' . $views . ' views)<br>';
}
?>
```

### Add Newsletter Subscriber
```php
<?php
$email = 'user@example.com';
if ( gni_add_subscriber( $email ) ) {
    echo 'Subscribed successfully!';
}
?>
```

### Get Share Buttons HTML
```php
<?php
echo wp_kses_post( gni_get_share_buttons() );
?>
```

---

## Database Tables

### Newsletter Subscribers Table
- **Table Name:** `wp_gni_newsletter_subscribers`
- **Columns:**
  - `ID` (bigint) - Primary key
  - `subscriber_email` (varchar 100) - Unique email address
  - `subscriber_date` (datetime) - Subscription timestamp
  - `subscriber_status` (varchar 20) - 'active' or 'unsubscribed'

---

## Plugin Dependencies

This theme works best with:

### Recommended Plugins
- **Jetpack** - Enhanced SEO, related posts, social sharing
- **Yoast SEO** - Advanced SEO management
- **Contact Form 7** - Email newsletter signup forms
- **MonsterInsights** - Google Analytics integration

### Optional Plugins
- **Akismet** - Comment spam filtering
- **WP Super Cache** - Performance caching
- **Elementor** - Page builder compatibility

---

## Customization Guide

### Add Custom Post Type
```php
// In functions.php, in gni_setup() hook:
register_post_type( 'news_brief', array(
    'public' => true,
    'label' => 'News Briefs',
) );
```

### Add Custom Color
```php
// In theme customizer (inc/customizer.php):
$wp_customize->add_setting( 'gni_highlight_color', array(
    'default' => '#ffcc00',
    'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control(
    new WP_Customize_Color_Control( $wp_customize, 'gni_highlight_color', array(
        'label' => __( 'Highlight Color', 'global-news-insights' ),
        'section' => 'gni_colors',
    ) )
);
```

### Register New Widget Area
```php
// In functions.php:
register_sidebar( array(
    'name'          => 'Sidebar2',
    'id'            => 'sidebar-2',
    'description'   => 'Secondary sidebar',
) );
```

### Create Custom Query Helper
```php
// Add to inc/meta-boxes.php or functions.php:
function my_custom_posts_query() {
    return new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 10,
        'meta_key'       => '_gni_breaking',
        'meta_value'     => true,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
}
```

---

## Troubleshooting

### Newsletter Widget Not Appearing
1. Go to Appearance → Widgets
2. Add "Newsletter Signup" widget to desired area
3. Check that the widget area is displayed in your template

### Breaking News Not Showing
1. Ensure posts have the "Breaking News" meta box checked
2. Verify "Breaking News" widget is added to widget area
3. Check that `gni_get_breaking_posts()` is called in template

### Social Media Links Not Working
1. Go to Appearance → Customize → Social Media
2. Verify all URLs are entered correctly
3. Check that footer or header templates call `get_theme_mod()`

### Comments Not Displaying
1. Go to Discussion settings in WordPress Admin
2. Enable "Allow people to post comments"
3. Verify single.php has `comments_template()` call

### WhatsApp Button Not Showing
1. Check that WhatsApp number is configured in Theme Customizer
2. Verify `footer.php` contains WhatsApp button HTML
3. Check that `assets/js/whatsapp.js` is enqueued in `functions.php`

---

## Performance Optimization

### Image Optimization
- Use lazy loading: `loading="lazy"` attribute
- Images use responsive sizes
- WebP format recommended

### Caching Recommendations
- Enable browser caching in htaccess
- Use WP Super Cache or W3 Total Cache
- Minify CSS and JS with plugin

### Database Optimization
- Regularly optimize tables: `wp_optimize_tables()`
- Clean up old revisions
- Use transients for expensive queries

---

## File Structure

```
global-news-insights/
├── assets/
│   ├── css/
│   │   ├── style.css           (Main stylesheet - 600+ lines)
│   │   └── editor-style.css    (Gutenberg editor styles)
│   └── js/
│       ├── main.js            (Main theme JS)
│       └── whatsapp.js        (WhatsApp button logic)
├── inc/
│   ├── meta-boxes.php         (Post admin interface - 200+ lines)
│   ├── widgets.php            (Custom widgets - 275+ lines)
│   ├── customizer.php         (Theme customizer - 225+ lines)
│   ├── template-tags.php      (Helper functions - 350+ lines)
│   └── newsletter-admin.php   (Newsletter management - 400+ lines)
├── functions.php              (Theme setup - 300+ lines)
├── header.php                 (Site header - 95+ lines)
├── footer.php                 (Site footer - 85+ lines)
├── single.php                 (Post template - 180+ lines)
├── archive.php                (Archive template)
├── page.php                   (Page template)
├── index.php                  (Main blog template)
├── sidebar.php                (Widget area)
├── 404.php                    (Not found page)
├── front-page.php             (Home page)
├── style.css                  (Theme root stylesheet - minimal)
├── template-login.php         (Login template)
├── README.md                  (Theme readme)
└── screenshot.png             (Theme screenshot)
```

---

## Security Best Practices

All theme code includes:
- ✅ Nonce verification on form submissions
- ✅ Proper input sanitization (sanitize_text_field, sanitize_email, etc.)
- ✅ Output escaping (esc_html, esc_url, esc_attr, etc.)
- ✅ WordPress capability checks (current_user_can)
- ✅ HTML sanitization for rich text (wp_kses_post)
- ✅ SQL injection prevention (prepared statements)

---

## Version History

### 2.0.0 (Current)
- Complete admin dashboard with custom meta boxes
- Advanced SEO with schema.org and OG tags
- Newsletter management system
- Theme customizer for all settings
- Professional responsive design
- Social media integration
- Custom widgets and template tags
- Floating WhatsApp button
- Comment moderation

### 1.0.0
- Initial release with basic news theme structure

---

## Support & Contribution

For issues, questions, or feature requests:
- **Email:** lingendea@gmail.com
- **GitHub:** [Repository URL]

---

## License

Global News Insights is licensed under the **GNU General Public License v2 or later**.

You are free to:
- ✅ Use commercially
- ✅ Modify the code
- ✅ Distribute
- ✅ Use privately

You must:
- ✅ Disclose license and copyright
- ✅ State changes made
- ✅ Distribute under same license

---

**Last Updated:** 2024
**For WordPress 5.0+**

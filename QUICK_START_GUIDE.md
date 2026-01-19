# Quick Start Guide - Global News Insights WordPress Theme

## 5-Minute Setup

### Step 1: Copy Theme Files
```bash
# Copy the global-news-insights folder to your WordPress themes directory
cp -r global-news-insights /path/to/wordpress/wp-content/themes/
```

### Step 2: Activate in WordPress
1. Log in to WordPress Admin Dashboard
2. Go to **Appearance → Themes**
3. Find "Global News Insights" and click **Activate**

### Step 3: Configure Theme
1. Go to **Appearance → Customize**
2. Upload your **Logo** (Branding section)
3. Set your **Primary Color** (Colors section)
4. Add **Social Media URLs** (Social Media section)
5. Click **Publish**

### Step 4: Create First Post
1. Go to **Posts → Add New**
2. Add title and content
3. Set featured image
4. In right sidebar, check "Breaking News" or "Featured" if desired
5. Publish

### Step 5: Create Widgets
1. Go to **Appearance → Widgets**
2. Add "Breaking News Widget" to sidebar
3. Add "Newsletter Widget" to footer
4. Save

✅ **Done!** Your news site is live.

---

## Essential Settings Checklist

- [ ] Theme activated
- [ ] Logo uploaded
- [ ] Colors customized
- [ ] Social media links added
- [ ] Newsletter widget added to sidebar
- [ ] Breaking News widget added to sidebar
- [ ] Posts created with categories
- [ ] Navigation menu configured
- [ ] Static home page set (optional)
- [ ] Google Analytics configured (optional)

---

## Admin Dashboard Tour

### Dashboard Widgets
- **Total Subscribers** - Newsletter stats
- **Active Subscribers** - Current active subscribers
- **Unsubscribed** - Unsubscribed count

### Menu Items

#### Appearance
- **Themes** - Activate/deactivate themes
- **Customize** - **[Main Theme Settings]**
  - Branding (logo, tagline)
  - Colors (primary, breaking, background)
  - Typography (font family)
  - Header/Footer settings
  - Social media links
  - AdSense configuration
- **Widgets** - Add widgets to sidebar/footer
- **Menus** - Create/edit navigation menus

#### Posts
- **All Posts** - View/edit all posts
  - Breaking News toggle
  - Featured Post toggle
  - View counter
- **Add New** - Create new post
- **Categories** - Organize posts
- **Tags** - Add post tags

#### Newsletter *(New)*
- **Dashboard** - Overview stats
- **Subscribers** - Manage subscriber list
- **Campaigns** - Send newsletters
- **Settings** - Configure sender info

#### Settings → Discussion
- **Allow comments** - Enable/disable
- **Comment moderation** - Approve comments
- **Require registration** - Restrict commenters

---

## Common Tasks

### Add Breaking News Post
1. Create new post
2. In the post editor, scroll down to "Breaking News" meta box
3. Check the checkbox
4. Publish
5. Post appears in "Breaking News Widget" and sidebar

### Send Newsletter
1. Go to **Newsletter → Campaigns**
2. Enter subject line
3. Write message (HTML editor available)
4. Select recipients (active subscribers)
5. Click "Send Campaign"
6. Done! Subscribers receive email

### Export Subscriber List
1. Go to **Newsletter → Subscribers**
2. Click "Export CSV" button
3. File downloads to your computer
4. Open in Excel/Google Sheets

### Add New Social Media Link
1. Go to **Appearance → Customize**
2. Click "Social Media"
3. Enter the full URL (e.g., https://facebook.com/yourpage)
4. Publish
5. Link appears in header and footer

### Change Primary Color
1. Go to **Appearance → Customize**
2. Click "Colors"
3. Click "Primary Color"
4. Select or enter hex color
5. Publish
6. Theme updates instantly

### Change Font Family
1. Go to **Appearance → Customize**
2. Click "Typography"
3. Select font from dropdown
4. Publish
5. All text updates to new font

### Enable AdSense
1. Get your AdSense Publisher ID from Google AdSense
2. Go to **Appearance → Customize**
3. Click "AdSense"
4. Enter Publisher ID
5. Check "Enable Ads"
6. Publish
7. Ads appear in article slots

---

## Shortcodes & Functions

### In Posts/Pages

#### Display Latest Breaking Posts
```php
[gni_breaking_posts count="5"]
```

#### Display Trending Posts
```php
[gni_trending_posts days="7" count="5"]
```

#### Display Newsletter Form
Use the Newsletter Widget (recommended)

### In Theme Templates

#### Get Breaking Posts
```php
<?php
$breaking = gni_get_breaking_posts( 5 );
foreach ( $breaking as $post ) {
    echo '<h3>' . $post->post_title . '</h3>';
}
?>
```

#### Get Trending Posts
```php
<?php
$trending = gni_get_trending_posts( 5, 7 ); // 5 posts from last 7 days
foreach ( $trending as $post ) {
    echo '<h3>' . $post->post_title . '</h3>';
}
?>
```

#### Display Share Buttons
```php
<?php echo wp_kses_post( gni_get_share_buttons() ); ?>
```

---

## Customization Examples

### Change WhatsApp Number
Edit `footer.php`, find:
```php
get_theme_mod( 'gni_social_whatsapp', '' )
```

Or use Theme Customizer → Social Media → WhatsApp

### Add Custom AdSense Slot
In `single.php`, add:
```php
<div class="ad-slot">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:inline-block;width:300px;height:250px"
         data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
         data-ad-slot="1234567890"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
```

### Hide WhatsApp Button
Edit `footer.php`, find WhatsApp button and comment out:
```php
<?php // echo $whatsapp_button; ?>
```

### Change Accent Color
Go to Customize → Colors → Breaking Color

### Add Footer Widget
1. Edit `footer.php`
2. Add widget area in footer template
3. Register in `functions.php` with `register_sidebar()`
4. Add widget via Appearance → Widgets

---

## Browser Compatibility

✅ **Fully Compatible:**
- Chrome/Chromium (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

✅ **Responsive:**
- Desktop (1024px+)
- Tablet (768px - 1024px)
- Mobile (360px - 768px)

---

## Performance Tips

1. **Optimize Images**
   - Upload images no larger than 2MB
   - Use JPG for photos, PNG for graphics
   - Use free tools: TinyPNG, ImageOptim

2. **Enable Caching**
   - Install plugin: WP Super Cache
   - Enables browser and server caching

3. **Minimize External Scripts**
   - Only load necessary Google Fonts
   - Defer ads loading

4. **Optimize Database**
   - Install plugin: WP-Optimize
   - Runs automatic cleanup

5. **Use CDN**
   - Recommended: Cloudflare (free)
   - Speeds up image delivery worldwide

---

## Troubleshooting

### Posts Not Showing
- Ensure posts are "Published" (not Draft)
- Check post categories are assigned
- Verify posts are not scheduled for future date

### Widgets Not Appearing
- Ensure widget is added to widget area
- Check that template calls `dynamic_sidebar()`
- Verify widget area is registered in functions.php

### Images Not Loading
- Check image file exists in media library
- Ensure featured image is set
- Check file permissions (755 for folders, 644 for files)

### Comments Not Working
- Enable comments in Settings → Discussion
- Check that single.php calls `comments_template()`
- Ensure comment form is not hidden by CSS

### Newsletter Not Sending
- Verify email is valid
- Check server has mail() enabled
- Test with plugin: WP Mail SMTP

### Theme Not Activating
- Check WordPress version is 5.0+
- Verify PHP version is 7.4+
- Check file permissions
- Look for errors in debug.log

---

## Help & Support

### Documentation
- Full guide: `WORDPRESS_THEME_GUIDE.md`
- Theme customizer help available in admin
- Code comments throughout theme files

### Debug Mode
Enable debugging by adding to `wp-config.php`:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Check debug.log in `/wp-content/` for errors

### Contact
- **Email:** lingendea@gmail.com
- **Response Time:** 24-48 hours typically

---

## Next Steps

1. ✅ Install and activate theme
2. ✅ Customize colors and branding
3. ✅ Create 3-5 test posts
4. ✅ Add widgets to sidebar
5. ✅ Test newsletter signup
6. ✅ Share on social media
7. ✅ Set up Google Analytics
8. ✅ Configure AdSense (optional)
9. ✅ Enable SSL certificate
10. ✅ Set up automatic backups

**🎉 Congratulations!** You now have a professional news website.

---

**Last Updated:** 2024
**Theme Version:** 2.0.0

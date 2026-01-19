# Global News Insights — WordPress Theme

This is the Global News Insights WordPress theme. Ready to zip and upload to WordPress.

Installation:

1. Zip the folder `global-news-insights`.
2. In WordPress admin go to Appearance → Themes → Add New → Upload Theme and upload the zip.
3. Activate the theme.

Quick setup:

- Create the categories: World, Africa, Business, Technology, Sports, Entertainment, Health.
- Add menus under Appearance → Menus and assign to Primary and Footer.
- Add widgets to Sidebar and Header Ads.
- Use the Article Flags meta box when editing posts to mark "Breaking" or "Featured".

Publishing articles:

- Use the Gutenberg editor. Add a featured image (hero) for headline images.
- To mark breaking news: check "Mark as Breaking News" in the Article Flags meta box.
- To make a post featured on the front page hero: check "Mark as Featured" in the Article Flags meta box.

Admin features:

- Gutenberg support and editor-styles enabled.
- Theme Customizer: upload logo, set accent color, and fonts.
- Widgets: Breaking News widget available.

Notes on monetization and SEO:

- Ad slots are added as placeholders; insert your Google AdSense code into the relevant template locations.
- Schema.org JSON-LD is emitted in `single.php`.
Ad slot locations and instructions:

- Header: replace the contents of the `header-ads` widget or the placeholder in `header.php`.
- Hero/header (front page): `front-page.php` contains an `ad-hero` placeholder below the hero article.
- Sidebar: `sidebar.php` includes a `.ad-slot` placeholder labeled "Ad slot (sidebar)".
- Inside articles: `single.php` includes an in-article ad placeholder after the article content.
- Footer: add your ad code into the `footer-1` widget area.

Security & privacy note:

- Do not hard-code publisher credentials in theme files. Insert AdSense scripts via Widgets or the Customizer where possible.
- Follow Google AdSense program policies when placing ads.
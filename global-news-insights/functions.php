<?php
/**
 * Global News Insights theme functions and definitions
 * Complete WordPress theme with admin dashboard, SEO, social media, and monetization
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'GNI_VERSION' ) ) {
    define( 'GNI_VERSION', '2.0.0' );
}

// Define theme constants
define( 'GNI_DIR', get_template_directory() );
define( 'GNI_URI', get_template_directory_uri() );
define( 'GNI_ASSETS', GNI_URI . '/assets' );

/**
 * Set up theme: add theme supports, register menus, load text domain
 *
 * @return void
 */
function gni_setup() {
    load_theme_textdomain( 'global-news-insights', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'gni-hero', 1600, 900, true );
    add_image_size( 'gni-grid', 600, 400, true );
    add_image_size( 'gni-thumbnail', 300, 200, true );

    // Custom logo support
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Gutenberg support with wide alignment
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-color-palette', array(
        array( 'name' => esc_attr__( 'Primary', 'global-news-insights' ), 'slug' => 'primary', 'color' => '#1a73e8' ),
        array( 'name' => esc_attr__( 'Secondary', 'global-news-insights' ), 'slug' => 'secondary', 'color' => '#34a853' ),
        array( 'name' => esc_attr__( 'Dark', 'global-news-insights' ), 'slug' => 'dark', 'color' => '#202124' ),
    ) );

    // Register menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'global-news-insights' ),
        'footer'  => esc_html__( 'Footer Menu', 'global-news-insights' ),
    ) );

    // HTML5 support
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Additional theme features
    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
}
add_action( 'after_setup_theme', 'gni_setup' );

/**
 * Enqueue theme styles and scripts
 * Loads Google Fonts, main CSS, and JS for interactivity and AJAX
 *
 * @return void
 */
function gni_scripts() {
    wp_enqueue_style( 'gni-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap', array(), null );
    wp_enqueue_style( 'gni-style', get_stylesheet_uri(), array(), GNI_VERSION );
    wp_enqueue_style( 'gni-main', GNI_ASSETS . '/css/style.css', array(), GNI_VERSION );

    wp_enqueue_script( 'gni-main', GNI_ASSETS . '/js/main.js', array( 'jquery' ), GNI_VERSION, true );
    wp_enqueue_script( 'gni-whatsapp', GNI_ASSETS . '/js/whatsapp.js', array(), GNI_VERSION, true );
    
    wp_localize_script( 'gni-main', 'gni_vars', array(
        'ajax_url'             => admin_url( 'admin-ajax.php' ),
        'subscribe_nonce'      => wp_create_nonce( 'gni_subscribe_nonce' ),
        'site_url'             => home_url(),
        'whatsapp_phone'       => '255717007449',
        'whatsapp_message'     => esc_html__( 'Hello! I would like to know more...', 'global-news-insights' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'gni_scripts' );

// Editor styles for Gutenberg
function gni_block_editor_assets() {
    wp_enqueue_style( 'gni-editor-style', get_template_directory_uri() . '/assets/css/editor-style.css', array(), GNI_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'gni_block_editor_assets' );

/**
 * Handle newsletter subscription via AJAX
 * Validates email, stores in options, sends admin notification
 *
 * @return void Outputs JSON response (success/error)
 */
function gni_subscribe() {
    check_ajax_referer( 'gni_subscribe_nonce', 'nonce' );
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => 'Invalid email' ) );
    }
    $subs = get_option( 'gni_subscribers', array() );
    if ( in_array( $email, $subs, true ) ) {
        wp_send_json_success( array( 'message' => 'Already subscribed' ) );
    }
    $subs[] = $email;
    update_option( 'gni_subscribers', $subs );
    // Optional: send admin notice
    wp_mail( get_option( 'admin_email' ), 'New subscriber', 'New newsletter subscriber: ' . $email );
    wp_send_json_success( array( 'message' => 'Subscribed' ) );
}
add_action( 'wp_ajax_gni_subscribe', 'gni_subscribe' );
add_action( 'wp_ajax_nopriv_gni_subscribe', 'gni_subscribe' );

/**
 * Register widget areas (sidebars) for dynamic content
 * Areas: Sidebar-1 (main), Footer-1, Header-Ads
 *
 * @return void
 */
function gni_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'global-news-insights' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets for blog and archive pages.', 'global-news-insights' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets', 'global-news-insights' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer widget area', 'global-news-insights' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Header Ads', 'global-news-insights' ),
        'id'            => 'header-ads',
        'description'   => esc_html__( 'Ad slots in header area', 'global-news-insights' ),
        'before_widget' => '<div class="header-ad">',
        'after_widget'  => '</div>',
    ) );
}
add_action( 'widgets_init', 'gni_widgets_init' );

// Include additional files
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/newsletter-admin.php';

// Admin dashboard and management features
require get_template_directory() . '/inc/admin-dashboard.php';
require get_template_directory() . '/inc/admin-content-manager.php';
require get_template_directory() . '/inc/admin-users.php';
require get_template_directory() . '/inc/admin-comments.php';
require get_template_directory() . '/inc/admin-website-controls.php';

/**
 * Emit Open Graph and Twitter Card meta tags for social sharing
 * Improves article previews on Facebook, Twitter, WhatsApp, etc.
 *
 * @return void
 */
function gni_social_meta() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $title = get_the_title( $post );
        $desc  = wp_trim_words( get_the_excerpt( $post ), 20 );
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' );
        $img   = $image ? $image[0] : get_site_icon_url(); 
        $url   = get_permalink( $post );
        $author = get_the_author_meta( 'display_name', $post->post_author );
        $publish_date = get_the_date( 'c', $post );
        ?>
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
        <meta property="og:description" content="<?php echo esc_attr( $desc ); ?>" />
        <meta property="og:image" content="<?php echo esc_url( $img ); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
        <meta property="article:published_time" content="<?php echo esc_attr( $publish_date ); ?>" />
        <meta property="article:author" content="<?php echo esc_attr( $author ); ?>" />
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr( $desc ); ?>" />
        <meta name="twitter:image" content="<?php echo esc_url( $img ); ?>" />
        
        <!-- Standard Meta Tags -->
        <meta name="description" content="<?php echo esc_attr( $desc ); ?>" />
        <meta name="keywords" content="<?php echo esc_attr( implode( ', ', wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) ) ) ); ?>" />
        <?php
    }
}
add_action( 'wp_head', 'gni_social_meta', 5 );

/**
 * Output Schema.org NewsArticle JSON-LD markup
 * Helps search engines understand article content
 *
 * @return void
 */
function gni_schema_markup() {
    if ( is_singular( 'post' ) ) {
        global $post;
        
        $schema = array(
            '@context'     => 'https://schema.org',
            '@type'        => 'NewsArticle',
            'headline'     => get_the_title( $post ),
            'description'  => wp_trim_words( get_the_excerpt( $post ), 30 ),
            'image'        => array(
                '@type'  => 'ImageObject',
                'url'    => wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' )[0] ?? get_site_icon_url(),
                'width'  => 1200,
                'height' => 630,
            ),
            'datePublished' => get_the_date( 'c', $post ),
            'dateModified'  => get_the_modified_date( 'c', $post ),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
            ),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type'   => 'ImageObject',
                    'url'     => get_site_icon_url(),
                    'width'   => 250,
                    'height'  => 60,
                ),
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink( $post ),
            ),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }
}
add_action( 'wp_head', 'gni_schema_markup', 10 );

/**
 * Social sharing buttons for articles
 * Displays share buttons for Facebook and WhatsApp
 *
 * @return string HTML with share buttons
 */
function gni_get_share_buttons() {
    if ( ! is_singular( 'post' ) ) {
        return '';
    }

    global $post;
    $url   = urlencode( get_permalink( $post ) );
    $title = urlencode( get_the_title( $post ) );
    $text  = urlencode( wp_trim_words( get_the_excerpt( $post ), 10 ) );
    
    ob_start();
    ?>
    <div class="gni-share-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $url ); ?>" target="_blank" rel="noopener" class="btn-share btn-facebook">
            <span>Share on Facebook</span>
        </a>
        <a href="https://wa.me/?text=<?php echo esc_attr( $title . ' ' . $url ); ?>" target="_blank" rel="noopener" class="btn-share btn-whatsapp">
            <span>Share on WhatsApp</span>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $url ); ?>&text=<?php echo esc_attr( $title ); ?>" target="_blank" rel="noopener" class="btn-share btn-twitter">
            <span>Share on Twitter</span>
        </a>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Adsense placeholder (no real ad code included)
 */
function gni_adsense_placeholder() {
    $show_ads = get_theme_mod( 'gni_show_ads', true );
    if ( ! $show_ads ) {
        return;
    }
    echo '<!-- Google AdSense Ready. Add your publisher ID in Theme Customizer -->';
}
add_action( 'wp_head', 'gni_adsense_placeholder' );

/**
 * Enable comments on posts by default
 */
function gni_enable_post_comments() {
    add_post_type_support( 'post', 'comments' );
}
add_action( 'init', 'gni_enable_post_comments' );

/**
 * Custom comment callback
 * Outputs custom comment HTML
 */
function gni_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    if ( 'pingback' === $comment->comment_type || 'trackback' === $comment->comment_type ) {
        return;
    }
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( array( 'comment', 'depth-' . $depth ) ); ?>>
        <div class="comment-body">
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment, 40 ); ?>
                <b class="fn"><?php echo esc_html( $comment->comment_author ); ?></b>
                <span class="says"><?php esc_html_e( 'says:', 'global-news-insights' ); ?></span>
            </div>
            <div class="comment-meta commentmetadata">
                <a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
                    <?php echo esc_html( get_comment_date( 'F j, Y \a\t g:i a' ) ); ?>
                </a>
            </div>
            <div class="comment-content"><?php echo wp_kses_post( $comment->comment_content ); ?></div>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
        </div>
    <?php
}

/**
 * Safe output helpers
 */
function gni_esc_url( $url ) {
    return esc_url( $url );
}

function gni_esc_html( $text ) {
    return esc_html( $text );
}

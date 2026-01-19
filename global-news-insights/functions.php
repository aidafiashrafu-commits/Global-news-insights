<?php
/**
 * Global News Insights theme functions and definitions
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'GNI_VERSION' ) ) {
    define( 'GNI_VERSION', '1.0.0' );
}

// Set up theme
function gni_setup() {
    load_theme_textdomain( 'global-news-insights', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'gni-hero', 1600, 900, true );
    add_image_size( 'gni-grid', 600, 400, true );

    // Gutenberg support
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'global-news-insights' ),
        'footer'  => esc_html__( 'Footer Menu', 'global-news-insights' ),
    ) );

    // HTML5 support
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}
add_action( 'after_setup_theme', 'gni_setup' );

// Enqueue styles and scripts
function gni_scripts() {
    wp_enqueue_style( 'gni-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap', array(), null );
    wp_enqueue_style( 'gni-style', get_stylesheet_uri(), array(), GNI_VERSION );
    wp_enqueue_style( 'gni-main', get_template_directory_uri() . '/assets/css/style.css', array(), GNI_VERSION );

    wp_enqueue_script( 'gni-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), GNI_VERSION, true );
    wp_enqueue_script( 'gni-whatsapp', get_template_directory_uri() . '/assets/js/whatsapp.js', array(), GNI_VERSION, true );
    wp_localize_script( 'gni-main', 'gni_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'subscribe_nonce' => wp_create_nonce( 'gni_subscribe_nonce' ) ) );
}
add_action( 'wp_enqueue_scripts', 'gni_scripts' );

// Editor styles for Gutenberg
function gni_block_editor_assets() {
    wp_enqueue_style( 'gni-editor-style', get_template_directory_uri() . '/assets/css/editor-style.css', array(), GNI_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'gni_block_editor_assets' );

// Newsletter subscribe AJAX handler
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

// Register widget areas
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

// Add meta tags for social sharing
function gni_social_meta() {
    if ( is_singular() ) {
        global $post;
        $title = get_the_title( $post );
        $desc  = get_the_excerpt( $post );
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' );
        $img   = $image ? $image[0] : ''; 
        ?>
        <meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
        <meta property="og:description" content="<?php echo esc_attr( $desc ); ?>" />
        <meta property="og:image" content="<?php echo esc_url( $img ); ?>" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image" />
        <?php
    }
}
add_action( 'wp_head', 'gni_social_meta', 5 );

// Adsense placeholder (no real ad code included)
function gni_adsense_placeholder() {
    echo '<!-- Adsense slot: header -->';
}
add_action( 'wp_head', 'gni_adsense_placeholder' );

// Safe output helpers
function gni_esc_url( $url ) {
    return esc_url( $url );
}

?>

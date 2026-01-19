<?php
/**
 * Global News Insights - Website Management Controls
 * 
 * Manage website-wide settings: menus, widgets, logo, homepage, ads, ticker
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register website management menu
 */
function gni_register_website_controls_menu() {
    add_menu_page(
        __( 'Website Controls', 'global-news-insights' ),
        '⚙️ Website',
        'manage_options',
        'gni-website-controls',
        'gni_website_controls_page',
        'dashicons-admin-generic',
        8
    );
    
    add_submenu_page(
        'gni-website-controls',
        __( 'Logo & Branding', 'global-news-insights' ),
        'Logo & Branding',
        'manage_options',
        'gni-branding',
        'gni_branding_page'
    );
    
    add_submenu_page(
        'gni-website-controls',
        __( 'Homepage Sections', 'global-news-insights' ),
        'Homepage Layout',
        'manage_options',
        'gni-homepage',
        'gni_homepage_page'
    );
    
    add_submenu_page(
        'gni-website-controls',
        __( 'Ads & Monetization', 'global-news-insights' ),
        'Ads & Monetization',
        'manage_options',
        'gni-ads',
        'gni_ads_page'
    );
    
    add_submenu_page(
        'gni-website-controls',
        __( 'Breaking Ticker', 'global-news-insights' ),
        'Breaking Ticker',
        'manage_options',
        'gni-ticker',
        'gni_ticker_page'
    );
}
add_action( 'admin_menu', 'gni_register_website_controls_menu' );

/**
 * Main website controls page
 */
function gni_website_controls_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    ?>
    <div class="wrap">
        <div class="gni-dashboard-header">
            <h1>⚙️ Website Controls</h1>
            <p><?php esc_html_e( 'Manage website branding, layout, monetization, and live ticker', 'global-news-insights' ); ?></p>
        </div>
        
        <div class="gni-dashboard-grid">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-branding' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div style="font-size: 48px; margin-bottom: 15px;">📸</div>
                    <h3><?php esc_html_e( 'Logo & Branding', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'Upload logo, favicon, brand colors', 'global-news-insights' ); ?></p>
                </div>
            </a>
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-homepage' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div style="font-size: 48px; margin-bottom: 15px;">🏠</div>
                    <h3><?php esc_html_e( 'Homepage Layout', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'Configure homepage sections', 'global-news-insights' ); ?></p>
                </div>
            </a>
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-ads' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div style="font-size: 48px; margin-bottom: 15px;">💰</div>
                    <h3><?php esc_html_e( 'Ads & Monetization', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'Manage ad spaces and units', 'global-news-insights' ); ?></p>
                </div>
            </a>
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-ticker' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div style="font-size: 48px; margin-bottom: 15px;">📻</div>
                    <h3><?php esc_html_e( 'Breaking Ticker', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'Manage live breaking news ticker', 'global-news-insights' ); ?></p>
                </div>
            </a>
        </div>
        
        <div style="margin-top: 40px; background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px; border-left: 4px solid var(--gni-accent);">
            <h2><?php esc_html_e( 'Website Status', 'global-news-insights' ); ?></h2>
            <table class="striped">
                <tr>
                    <td><strong><?php esc_html_e( 'Site Title', 'global-news-insights' ); ?></strong></td>
                    <td><?php echo esc_html( get_bloginfo( 'name' ) ); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e( 'Site URL', 'global-news-insights' ); ?></strong></td>
                    <td><?php echo esc_html( site_url() ); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e( 'Total Articles', 'global-news-insights' ); ?></strong></td>
                    <td><?php echo intval( wp_count_posts()->publish ); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e( 'Total Users', 'global-news-insights' ); ?></strong></td>
                    <td><?php echo count_users()['total_users']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Logo & Branding page
 */
function gni_branding_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle logo upload
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'upload_logo' && check_admin_referer( 'gni_branding_nonce' ) ) {
        if ( ! empty( $_FILES['logo']['name'] ) ) {
            $upload = wp_handle_upload( $_FILES['logo'], array( 'test_form' => false ) );
            if ( ! isset( $upload['error'] ) ) {
                update_option( 'gni_site_logo', $upload['url'] );
                echo '<div class="notice notice-success"><p>' . esc_html__( 'Logo uploaded!', 'global-news-insights' ) . '</p></div>';
            }
        }
    }
    
    $logo = get_option( 'gni_site_logo', '' );
    ?>
    <div class="wrap">
        <h1>📸 <?php esc_html_e( 'Logo & Branding', 'global-news-insights' ); ?></h1>
        
        <div style="max-width: 800px;">
            <!-- Logo Upload -->
            <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                <h2><?php esc_html_e( 'Site Logo', 'global-news-insights' ); ?></h2>
                
                <?php if ( $logo ) : ?>
                    <div style="margin-bottom: 20px;">
                        <img src="<?php echo esc_url( $logo ); ?>" style="max-width: 200px; height: auto;" />
                    </div>
                <?php endif; ?>
                
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field( 'gni_branding_nonce' ); ?>
                    <input type="hidden" name="action" value="upload_logo" />
                    
                    <div style="margin-bottom: 15px;">
                        <label for="logo"><?php esc_html_e( 'Upload Logo (PNG, JPG)', 'global-news-insights' ); ?></label>
                        <input type="file" id="logo" name="logo" accept="image/*" required />
                    </div>
                    
                    <p style="font-size: 12px; color: var(--gni-text-secondary);">
                        <?php esc_html_e( 'Recommended size: 300x100px or larger', 'global-news-insights' ); ?>
                    </p>
                    
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Upload Logo', 'global-news-insights' ); ?>" />
                </form>
            </div>
            
            <!-- Favicon -->
            <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                <h2><?php esc_html_e( 'Favicon (Site Icon)', 'global-news-insights' ); ?></h2>
                <p><?php esc_html_e( 'Go to', 'global-news-insights' ); ?> <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customizer', 'global-news-insights' ); ?></a> <?php esc_html_e( 'to upload favicon', 'global-news-insights' ); ?></p>
            </div>
            
            <!-- Brand Colors -->
            <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px;">
                <h2><?php esc_html_e( 'Brand Colors', 'global-news-insights' ); ?></h2>
                <p><?php esc_html_e( 'Current color scheme:', 'global-news-insights' ); ?></p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div>
                        <div style="background-color: var(--gni-dark-bg); width: 100%; height: 60px; border-radius: 4px; border: 2px solid #555;"></div>
                        <p style="font-size: 12px;"><?php esc_html_e( 'Dark Background', 'global-news-insights' ); ?></p>
                    </div>
                    
                    <div>
                        <div style="background-color: var(--gni-accent); width: 100%; height: 60px; border-radius: 4px;"></div>
                        <p style="font-size: 12px;"><?php esc_html_e( 'Primary Accent', 'global-news-insights' ); ?></p>
                    </div>
                    
                    <div>
                        <div style="background-color: var(--gni-breaking); width: 100%; height: 60px; border-radius: 4px;"></div>
                        <p style="font-size: 12px;"><?php esc_html_e( 'Breaking News', 'global-news-insights' ); ?></p>
                    </div>
                    
                    <div>
                        <div style="background-color: var(--gni-warning); width: 100%; height: 60px; border-radius: 4px;"></div>
                        <p style="font-size: 12px;"><?php esc_html_e( 'Warning', 'global-news-insights' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Homepage configuration page
 */
function gni_homepage_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle form save
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'save_homepage' && check_admin_referer( 'gni_homepage_nonce' ) ) {
        $sections = array(
            'show_featured' => isset( $_POST['show_featured'] ) ? 1 : 0,
            'show_breaking' => isset( $_POST['show_breaking'] ) ? 1 : 0,
            'show_categories' => isset( $_POST['show_categories'] ) ? 1 : 0,
            'posts_per_page' => intval( $_POST['posts_per_page'] ?? 10 ),
            'featured_count' => intval( $_POST['featured_count'] ?? 5 ),
        );
        update_option( 'gni_homepage_sections', $sections );
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Homepage settings saved!', 'global-news-insights' ) . '</p></div>';
    }
    
    $sections = get_option( 'gni_homepage_sections', array(
        'show_featured' => 1,
        'show_breaking' => 1,
        'show_categories' => 1,
        'posts_per_page' => 10,
        'featured_count' => 5,
    ) );
    ?>
    <div class="wrap">
        <h1>🏠 <?php esc_html_e( 'Homepage Layout', 'global-news-insights' ); ?></h1>
        
        <div style="max-width: 600px;">
            <form method="post" action="">
                <?php wp_nonce_field( 'gni_homepage_nonce' ); ?>
                <input type="hidden" name="action" value="save_homepage" />
                
                <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px;">
                    <h2><?php esc_html_e( 'Homepage Sections', 'global-news-insights' ); ?></h2>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="show_featured" <?php checked( $sections['show_featured'], 1 ); ?> />
                            <?php esc_html_e( 'Show Featured Articles Section', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="show_breaking" <?php checked( $sections['show_breaking'], 1 ); ?> />
                            <?php esc_html_e( 'Show Breaking News Section', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="show_categories" <?php checked( $sections['show_categories'], 1 ); ?> />
                            <?php esc_html_e( 'Show Category Sections', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <hr />
                    
                    <div style="margin-bottom: 20px;">
                        <label for="posts_per_page"><?php esc_html_e( 'Posts Per Page', 'global-news-insights' ); ?></label>
                        <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo intval( $sections['posts_per_page'] ); ?>" min="5" max="50" />
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="featured_count"><?php esc_html_e( 'Featured Articles Count', 'global-news-insights' ); ?></label>
                        <input type="number" id="featured_count" name="featured_count" value="<?php echo intval( $sections['featured_count'] ); ?>" min="3" max="12" />
                    </div>
                    
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Settings', 'global-news-insights' ); ?>" />
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Ads & Monetization page
 */
function gni_ads_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle ad unit save
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'save_ads' && check_admin_referer( 'gni_ads_nonce' ) ) {
        $ads = array(
            'google_adsense_id' => sanitize_text_field( $_POST['google_adsense_id'] ?? '' ),
            'header_ad_enabled' => isset( $_POST['header_ad_enabled'] ) ? 1 : 0,
            'sidebar_ad_enabled' => isset( $_POST['sidebar_ad_enabled'] ) ? 1 : 0,
            'footer_ad_enabled' => isset( $_POST['footer_ad_enabled'] ) ? 1 : 0,
            'in_article_ads' => isset( $_POST['in_article_ads'] ) ? 1 : 0,
        );
        update_option( 'gni_ad_settings', $ads );
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Ad settings saved!', 'global-news-insights' ) . '</p></div>';
    }
    
    $ads = get_option( 'gni_ad_settings', array(
        'google_adsense_id' => '',
        'header_ad_enabled' => 0,
        'sidebar_ad_enabled' => 1,
        'footer_ad_enabled' => 0,
        'in_article_ads' => 1,
    ) );
    ?>
    <div class="wrap">
        <h1>💰 <?php esc_html_e( 'Ads & Monetization', 'global-news-insights' ); ?></h1>
        
        <div style="max-width: 600px;">
            <form method="post" action="">
                <?php wp_nonce_field( 'gni_ads_nonce' ); ?>
                <input type="hidden" name="action" value="save_ads" />
                
                <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px;">
                    <h2><?php esc_html_e( 'Google AdSense', 'global-news-insights' ); ?></h2>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="google_adsense_id"><?php esc_html_e( 'AdSense Publisher ID', 'global-news-insights' ); ?></label>
                        <input type="text" id="google_adsense_id" name="google_adsense_id" value="<?php echo esc_attr( $ads['google_adsense_id'] ); ?>" placeholder="ca-pub-xxxxxxxxxxxxxxxx" />
                        <p style="font-size: 12px; color: var(--gni-text-secondary);">
                            <?php esc_html_e( 'Get your publisher ID from Google AdSense account', 'global-news-insights' ); ?>
                        </p>
                    </div>
                    
                    <hr />
                    
                    <h2><?php esc_html_e( 'Ad Placements', 'global-news-insights' ); ?></h2>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="header_ad_enabled" <?php checked( $ads['header_ad_enabled'], 1 ); ?> />
                            <?php esc_html_e( 'Header Banner Ad (728x90)', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="sidebar_ad_enabled" <?php checked( $ads['sidebar_ad_enabled'], 1 ); ?> />
                            <?php esc_html_e( 'Sidebar Ad (300x250)', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="footer_ad_enabled" <?php checked( $ads['footer_ad_enabled'], 1 ); ?> />
                            <?php esc_html_e( 'Footer Ad (728x90)', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="in_article_ads" <?php checked( $ads['in_article_ads'], 1 ); ?> />
                            <?php esc_html_e( 'In-Article Ads', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Ad Settings', 'global-news-insights' ); ?>" />
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Breaking ticker page
 */
function gni_ticker_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle ticker update
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'update_ticker' && check_admin_referer( 'gni_ticker_nonce' ) ) {
        $ticker_enabled = isset( $_POST['ticker_enabled'] ) ? 1 : 0;
        $ticker_text = sanitize_text_field( $_POST['ticker_text'] ?? 'BREAKING NEWS' );
        $ticker_color = sanitize_hex_color( $_POST['ticker_color'] ?? '#dc3545' );
        
        update_option( 'gni_ticker_enabled', $ticker_enabled );
        update_option( 'gni_ticker_text', $ticker_text );
        update_option( 'gni_ticker_color', $ticker_color );
        
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Ticker settings updated!', 'global-news-insights' ) . '</p></div>';
    }
    
    $ticker_enabled = get_option( 'gni_ticker_enabled', 1 );
    $ticker_text = get_option( 'gni_ticker_text', 'BREAKING NEWS' );
    $ticker_color = get_option( 'gni_ticker_color', '#dc3545' );
    
    // Get breaking posts
    $breaking_posts = get_posts( array(
        'meta_key' => '_gni_breaking_news',
        'meta_value' => 1,
        'posts_per_page' => 10,
    ) );
    ?>
    <div class="wrap">
        <h1>📻 <?php esc_html_e( 'Breaking Ticker', 'global-news-insights' ); ?></h1>
        
        <!-- Settings -->
        <div style="max-width: 600px; margin-bottom: 30px;">
            <form method="post" action="">
                <?php wp_nonce_field( 'gni_ticker_nonce' ); ?>
                <input type="hidden" name="action" value="update_ticker" />
                
                <div style="background-color: var(--gni-dark-secondary); padding: 30px; border-radius: 8px;">
                    <h2><?php esc_html_e( 'Ticker Settings', 'global-news-insights' ); ?></h2>
                    
                    <div style="margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" name="ticker_enabled" <?php checked( $ticker_enabled, 1 ); ?> />
                            <?php esc_html_e( 'Enable Breaking Ticker', 'global-news-insights' ); ?>
                        </label>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="ticker_text"><?php esc_html_e( 'Ticker Label', 'global-news-insights' ); ?></label>
                        <input type="text" id="ticker_text" name="ticker_text" value="<?php echo esc_attr( $ticker_text ); ?>" />
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="ticker_color"><?php esc_html_e( 'Ticker Color', 'global-news-insights' ); ?></label>
                        <input type="color" id="ticker_color" name="ticker_color" value="<?php echo esc_attr( $ticker_color ); ?>" />
                    </div>
                    
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Update Ticker', 'global-news-insights' ); ?>" />
                </div>
            </form>
        </div>
        
        <!-- Live Preview -->
        <div style="background-color: <?php echo esc_attr( $ticker_color ); ?>; color: white; padding: 10px 20px; font-weight: bold; border-radius: 4px; margin-bottom: 30px;">
            <span><?php echo esc_html( $ticker_text ); ?></span>
            <span style="margin-left: 20px;"><?php echo count( $breaking_posts ); ?> <?php esc_html_e( 'breaking stories', 'global-news-insights' ); ?></span>
        </div>
        
        <!-- Breaking Posts in Ticker -->
        <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 8px;">
            <h2><?php esc_html_e( 'Stories in Ticker', 'global-news-insights' ); ?></h2>
            
            <?php if ( ! empty( $breaking_posts ) ) : ?>
                <ul style="list-style: none; padding: 0;">
                    <?php
                    foreach ( $breaking_posts as $post ) {
                        ?>
                        <li style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <strong><?php echo esc_html( $post->post_title ); ?></strong>
                            <a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'Edit', 'global-news-insights' ); ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            <?php else : ?>
                <p><?php esc_html_e( 'No breaking news stories yet. Mark articles as breaking to add them to the ticker.', 'global-news-insights' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

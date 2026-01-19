<?php
/**
 * Global News Insights - Custom Meta Boxes
 * Adds breaking news toggle, featured post toggle, and custom fields
 *
 * @package Global_News_Insights
 */

/**
 * Register meta boxes for post editing
 * Breaking News, Featured Post, Priority, Reading Time, etc.
 */
function gni_register_meta_boxes() {
    add_meta_box(
        'gni_news_controls',
        '🎯 News Controls',
        'gni_news_controls_callback',
        'post',
        'side',
        'high'
    );

    add_meta_box(
        'gni_article_priority',
        '📊 Article Priority',
        'gni_article_priority_callback',
        'post',
        'side',
        'high'
    );

    add_meta_box(
        'gni_post_views',
        '👁️ Post Views & Analytics',
        'gni_post_views_callback',
        'post',
        'side',
        'default'
    );

    add_meta_box(
        'gni_article_publishing',
        '📢 Publishing Options',
        'gni_article_publishing_callback',
        'post',
        'normal',
        'high'
    );

    add_meta_box(
        'gni_article_settings',
        '⚙️ Advanced Settings',
        'gni_article_settings_callback',
        'post',
        'normal',
        'default'
    );

    add_meta_box(
        'gni_article_seo',
        '🔍 SEO & Metadata',
        'gni_article_seo_callback',
        'post',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'gni_register_meta_boxes' );

/**
 * Breaking News meta box callback
 * Checkbox to mark post as breaking news
 */
function gni_breaking_news_callback( $post ) {
    wp_nonce_field( 'gni_breaking_nonce_action', 'gni_breaking_nonce' );
    $value = get_post_meta( $post->ID, '_gni_breaking', true );
    ?>
    <div class="gni-meta-box-row">
        <label for="gni_breaking_toggle">
            <input type="checkbox" id="gni_breaking_toggle" name="gni_breaking" value="1" <?php checked( $value, '1' ); ?> />
            <span style="margin-left: 8px;">🔴 <strong>Mark as Breaking News</strong></span>
        </label>
    </div>
    <p class="description" style="margin: 10px 0 0 0;"><?php esc_html_e( 'This post will appear in the breaking news ticker and featured section.', 'global-news-insights' ); ?></p>
    <?php
}

/**
 * News Controls meta box callback
 * Combined breaking news and featured post controls
 */
function gni_news_controls_callback( $post ) {
    wp_nonce_field( 'gni_news_controls_nonce_action', 'gni_news_controls_nonce' );
    
    $breaking = get_post_meta( $post->ID, '_gni_breaking', true );
    $featured = get_post_meta( $post->ID, '_gni_featured', true );
    ?>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <div class="gni-meta-box-row" style="background-color: rgba(220, 53, 69, 0.1); border-left: 3px solid #dc3545;">
            <label for="gni_breaking_toggle" style="margin: 0; display: flex; align-items: center;">
                <input type="checkbox" id="gni_breaking_toggle" name="gni_breaking" value="1" <?php checked( $breaking, '1' ); ?> style="margin: 0;" />
                <span style="margin-left: 10px; font-weight: 600;">🔴 Breaking News</span>
            </label>
            <p class="description" style="margin: 5px 0 0 30px; font-size: 12px;"><?php esc_html_e( 'Appears in breaking news ticker', 'global-news-insights' ); ?></p>
        </div>
        
        <div class="gni-meta-box-row" style="background-color: rgba(26, 115, 232, 0.1); border-left: 3px solid #1a73e8;">
            <label for="gni_featured_toggle" style="margin: 0; display: flex; align-items: center;">
                <input type="checkbox" id="gni_featured_toggle" name="gni_featured" value="1" <?php checked( $featured, '1' ); ?> style="margin: 0;" />
                <span style="margin-left: 10px; font-weight: 600;">⭐ Featured Article</span>
            </label>
            <p class="description" style="margin: 5px 0 0 30px; font-size: 12px;"><?php esc_html_e( 'Highlighted on homepage', 'global-news-insights' ); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Article Priority meta box callback
 */
function gni_article_priority_callback( $post ) {
    wp_nonce_field( 'gni_priority_nonce_action', 'gni_priority_nonce' );
    
    $priority = get_post_meta( $post->ID, '_gni_priority', true ) ?: 'normal';
    ?>
    <div class="gni-meta-box">
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="radio" name="gni_priority" value="high" <?php checked( $priority, 'high' ); ?> />
                <span style="margin-left: 8px;">🔴 <strong>High Priority</strong></span>
            </label>
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="radio" name="gni_priority" value="normal" <?php checked( $priority, 'normal' ); ?> />
                <span style="margin-left: 8px;">🟡 <strong>Normal Priority</strong></span>
            </label>
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="radio" name="gni_priority" value="low" <?php checked( $priority, 'low' ); ?> />
                <span style="margin-left: 8px;">⚪ <strong>Low Priority</strong></span>
            </label>
        </div>
        <p class="description" style="margin-top: 10px; font-size: 12px;"><?php esc_html_e( 'Controls article visibility and placement', 'global-news-insights' ); ?></p>
    </div>
    <?php
}

/**
 * Article Publishing meta box callback
 */
function gni_article_publishing_callback( $post ) {
    wp_nonce_field( 'gni_publishing_nonce_action', 'gni_publishing_nonce' );
    
    $publish_date = get_the_date( 'Y-m-d H:i', $post->ID );
    $estimated_read_time = get_post_meta( $post->ID, '_gni_read_time', true ) ?: gni_get_reading_time( $post->ID );
    ?>
    <table class="form-table" style="background-color: var(--gni-dark-secondary); border-radius: 4px;">
        <tr style="border-bottom: 1px solid var(--gni-border);">
            <th scope="row" style="padding: 15px; width: 200px;">
                <label for="publish_date">📅 Publication Date</label>
            </th>
            <td style="padding: 15px;">
                <input type="datetime-local" id="publish_date" name="publish_date" value="<?php echo esc_attr( str_replace( ' ', 'T', $publish_date ) ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e( 'When this article will be published', 'global-news-insights' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row" style="padding: 15px;">
                <label>⏱️ Reading Time</label>
            </th>
            <td style="padding: 15px;">
                <div style="font-size: 18px; font-weight: bold; color: var(--gni-accent);">
                    <?php echo intval( $estimated_read_time ); ?> minutes
                </div>
                <p class="description"><?php esc_html_e( 'Auto-calculated from article length', 'global-news-insights' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Post Views meta box callback
 * Display view count and analytics
 */
function gni_post_views_callback( $post ) {
    $views = (int) get_post_meta( $post->ID, '_gni_post_views', true );
    $trending = gni_get_trending_posts( 100, 7 );
    $post_position = 1;
    
    foreach ( $trending as $trend_post ) {
        if ( $trend_post->ID === $post->ID ) {
            break;
        }
        $post_position++;
    }
    ?>
    <div style="text-align: center; padding: 20px;">
        <div style="font-size: 36px; font-weight: bold; color: var(--gni-accent); margin-bottom: 10px;">
            <?php echo intval( $views ); ?>
        </div>
        <p style="margin: 0; color: var(--gni-text-secondary);">Total Views</p>
        
        <hr style="margin: 15px 0; border-color: var(--gni-border);">
        
        <p style="margin: 10px 0; font-size: 14px;">
            <strong>#<?php echo intval( $post_position ); ?></strong> in trending
            <br>
            <small style="color: var(--gni-text-secondary);">Last 7 days</small>
        </p>
    </div>
    <?php
}

/**
 * Article SEO meta box callback
 */
function gni_article_seo_callback( $post ) {
    wp_nonce_field( 'gni_seo_nonce_action', 'gni_seo_nonce' );
    
    $seo_title = get_post_meta( $post->ID, '_gni_seo_title', true );
    $seo_description = get_post_meta( $post->ID, '_gni_seo_description', true );
    $seo_keywords = get_post_meta( $post->ID, '_gni_seo_keywords', true );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="gni_seo_title">🔍 SEO Title</label></th>
            <td>
                <input type="text" id="gni_seo_title" name="gni_seo_title" value="<?php echo esc_attr( $seo_title ); ?>" class="large-text" />
                <p class="description"><?php esc_html_e( 'Optimal length: 50-60 characters', 'global-news-insights' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="gni_seo_description">📝 Meta Description</label></th>
            <td>
                <textarea id="gni_seo_description" name="gni_seo_description" rows="3" class="large-text"><?php echo esc_textarea( $seo_description ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Optimal length: 150-160 characters', 'global-news-insights' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="gni_seo_keywords">🏷️ Keywords</label></th>
            <td>
                <input type="text" id="gni_seo_keywords" name="gni_seo_keywords" value="<?php echo esc_attr( $seo_keywords ); ?>" class="large-text" placeholder="separated by comma" />
                <p class="description"><?php esc_html_e( 'Main keywords for this article (comma separated)', 'global-news-insights' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Featured Post meta box callback
 * Checkbox to mark post as featured
 */
function gni_featured_post_callback( $post ) {
    wp_nonce_field( 'gni_featured_nonce_action', 'gni_featured_nonce' );
    $value = get_post_meta( $post->ID, '_gni_featured', true );
    ?>
    <label for="gni_featured_toggle">
        <input type="checkbox" id="gni_featured_toggle" name="gni_featured" value="1" <?php checked( $value, '1' ); ?> />
        <?php esc_html_e( 'Mark as Featured Post', 'global-news-insights' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'This post will be highlighted on the homepage.', 'global-news-insights' ); ?></p>
    <?php
}

/**
 * Post Views Counter callback
 * Display view count (read-only)
 */
function gni_post_views_callback( $post ) {
    $views = (int) get_post_meta( $post->ID, '_gni_post_views', true );
    ?>
    <div style="font-size: 24px; font-weight: bold; color: #1a73e8;">
        <?php echo esc_html( $views ); ?>
    </div>
    <p class="description"><?php esc_html_e( 'Total views for this post', 'global-news-insights' ); ?></p>
    <?php
}

/**
 * Article Settings meta box
 * Additional settings for articles
 */
function gni_article_settings_callback( $post ) {
    wp_nonce_field( 'gni_article_settings_nonce_action', 'gni_article_settings_nonce' );
    
    $read_time = get_post_meta( $post->ID, '_gni_read_time', true );
    $ad_friendly = get_post_meta( $post->ID, '_gni_ad_friendly', true );
    $allow_comments = get_post_meta( $post->ID, '_gni_allow_comments', true );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="gni_read_time"><?php esc_html_e( 'Estimated Read Time (minutes)', 'global-news-insights' ); ?></label></th>
            <td><input type="number" min="1" id="gni_read_time" name="gni_read_time" value="<?php echo esc_attr( $read_time ); ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="gni_ad_friendly"><?php esc_html_e( 'Ad-Friendly Content', 'global-news-insights' ); ?></label></th>
            <td>
                <input type="checkbox" id="gni_ad_friendly" name="gni_ad_friendly" value="1" <?php checked( $ad_friendly, '1' ); ?> />
                <p class="description"><?php esc_html_e( 'Enable ads for this article', 'global-news-insights' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="gni_allow_comments"><?php esc_html_e( 'Allow Comments', 'global-news-insights' ); ?></label></th>
            <td>
                <input type="checkbox" id="gni_allow_comments" name="gni_allow_comments" value="1" <?php checked( $allow_comments, '1' ); ?> />
                <p class="description"><?php esc_html_e( 'Allow user comments on this article', 'global-news-insights' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 * Handles breaking news, featured post, and article settings
 */
function gni_save_post_meta( $post_id ) {
    // Verify breaking news nonce
    if ( isset( $_POST['gni_breaking_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gni_breaking_nonce'] ) ), 'gni_breaking_nonce_action' ) ) {
        $breaking = isset( $_POST['gni_breaking'] ) ? '1' : '';
        update_post_meta( $post_id, '_gni_breaking', $breaking );
    }

    // Verify featured nonce
    if ( isset( $_POST['gni_featured_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gni_featured_nonce'] ) ), 'gni_featured_nonce_action' ) ) {
        $featured = isset( $_POST['gni_featured'] ) ? '1' : '';
        update_post_meta( $post_id, '_gni_featured', $featured );
    }

    // Verify article settings nonce
    if ( isset( $_POST['gni_article_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gni_article_settings_nonce'] ) ), 'gni_article_settings_nonce_action' ) ) {
        $read_time = isset( $_POST['gni_read_time'] ) ? (int) $_POST['gni_read_time'] : 0;
        $ad_friendly = isset( $_POST['gni_ad_friendly'] ) ? '1' : '';
        $allow_comments = isset( $_POST['gni_allow_comments'] ) ? '1' : '';
        
        update_post_meta( $post_id, '_gni_read_time', $read_time );
        update_post_meta( $post_id, '_gni_ad_friendly', $ad_friendly );
        update_post_meta( $post_id, '_gni_allow_comments', $allow_comments );
    }
}
add_action( 'save_post', 'gni_save_post_meta' );

/**
 * Track post views
 * Increments view count when post is viewed
 */
function gni_track_post_views( $post_id ) {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    if ( empty( $post_id ) ) {
        global $post;
        $post_id = $post->ID;
    }

    $views = (int) get_post_meta( $post_id, '_gni_post_views', true );
    $views++;
    update_post_meta( $post_id, '_gni_post_views', $views );
}
add_action( 'wp_head', 'gni_track_post_views' );

/**
 * Get breaking news posts
 * Returns array of posts marked as breaking news
 */
function gni_get_breaking_posts( $limit = 10 ) {
    $args = array(
        'post_type'  => 'post',
        'meta_key'   => '_gni_breaking',
        'meta_value' => '1',
        'posts_per_page' => $limit,
        'orderby'    => 'date',
        'order'      => 'DESC',
    );

    return get_posts( $args );
}

/**
 * Get featured posts
 * Returns array of posts marked as featured
 */
function gni_get_featured_posts( $limit = 5 ) {
    $args = array(
        'post_type'  => 'post',
        'meta_key'   => '_gni_featured',
        'meta_value' => '1',
        'posts_per_page' => $limit,
        'orderby'    => 'date',
        'order'      => 'DESC',
    );

    return get_posts( $args );
}

/**
 * Get trending posts
 * Returns posts sorted by view count
 */
function gni_get_trending_posts( $limit = 5, $days = 7 ) {
    $args = array(
        'post_type'  => 'post',
        'posts_per_page' => $limit,
        'orderby'    => 'meta_value_num',
        'meta_key'   => '_gni_post_views',
        'order'      => 'DESC',
        'date_query' => array(
            array(
                'after' => gmdate( 'Y-m-d H:i:s', strtotime( "-$days days" ) ),
            ),
        ),
    );

    return get_posts( $args );
}

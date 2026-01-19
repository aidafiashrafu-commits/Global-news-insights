<?php
/**
 * Global News Insights - Custom Meta Boxes
 * Adds breaking news toggle, featured post toggle, and custom fields
 *
 * @package Global_News_Insights
 */

/**
 * Register meta boxes for post editing
 * Breaking News, Featured Post, Author Bio, Custom Meta
 */
function gni_register_meta_boxes() {
    add_meta_box(
        'gni_breaking_news',
        esc_html__( 'Breaking News Settings', 'global-news-insights' ),
        'gni_breaking_news_callback',
        'post',
        'side',
        'high'
    );

    add_meta_box(
        'gni_featured_post',
        esc_html__( 'Featured Post', 'global-news-insights' ),
        'gni_featured_post_callback',
        'post',
        'side',
        'high'
    );

    add_meta_box(
        'gni_post_views',
        esc_html__( 'Post Views Counter', 'global-news-insights' ),
        'gni_post_views_callback',
        'post',
        'side',
        'default'
    );

    add_meta_box(
        'gni_article_settings',
        esc_html__( 'Article Settings', 'global-news-insights' ),
        'gni_article_settings_callback',
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
    <label for="gni_breaking_toggle">
        <input type="checkbox" id="gni_breaking_toggle" name="gni_breaking" value="1" <?php checked( $value, '1' ); ?> />
        <?php esc_html_e( 'Mark as Breaking News', 'global-news-insights' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'This post will appear in the breaking news ticker.', 'global-news-insights' ); ?></p>
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

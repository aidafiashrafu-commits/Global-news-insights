<?php
/**
 * Global News Insights - Custom Admin Dashboard
 * 
 * Professional dashboard for news site management
 * Displays key metrics and quick actions
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register admin dashboard styles
 */
function gni_register_admin_styles() {
    wp_enqueue_style(
        'gni-admin-styles',
        GNI_ASSETS . 'css/admin-styles.css',
        array(),
        GNI_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'gni_register_admin_styles' );

/**
 * Customize WordPress dashboard
 */
function gni_customize_dashboard() {
    // Remove default dashboard widgets
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
    
    // Add custom dashboard widgets
    wp_add_dashboard_widget(
        'gni_dashboard_news_stats',
        '📊 News Site Statistics',
        'gni_dashboard_news_stats_widget'
    );
    
    wp_add_dashboard_widget(
        'gni_dashboard_quick_actions',
        '⚡ Quick Actions',
        'gni_dashboard_quick_actions_widget'
    );
    
    wp_add_dashboard_widget(
        'gni_dashboard_recent_articles',
        '📰 Recent Articles',
        'gni_dashboard_recent_articles_widget'
    );
    
    wp_add_dashboard_widget(
        'gni_dashboard_breaking_queue',
        '🔴 Breaking News Queue',
        'gni_dashboard_breaking_queue_widget'
    );
    
    wp_add_dashboard_widget(
        'gni_dashboard_comments_queue',
        '💬 Pending Comments',
        'gni_dashboard_comments_queue_widget'
    );
    
    wp_add_dashboard_widget(
        'gni_dashboard_trending_posts',
        '🔥 Trending Posts',
        'gni_dashboard_trending_posts_widget'
    );
}
add_action( 'wp_dashboard_setup', 'gni_customize_dashboard' );

/**
 * Dashboard widget: News Statistics
 */
function gni_dashboard_news_stats_widget() {
    $total_posts = wp_count_posts( 'post' );
    $total_published = $total_posts->publish ?? 0;
    $total_drafts = $total_posts->draft ?? 0;
    $total_scheduled = $total_posts->future ?? 0;
    
    $total_comments = wp_count_comments();
    $pending_comments = $total_comments->moderated ?? 0;
    
    $total_users = count_users();
    $total_users = $total_users['total_users'] ?? 0;
    
    $breaking_posts = gni_get_breaking_posts( 100 );
    $breaking_count = count( $breaking_posts );
    
    ?>
    <div class="gni-dashboard-grid">
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $total_published ); ?></div>
            <div class="label">Published Articles</div>
        </div>
        
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $breaking_count ); ?></div>
            <div class="label">Breaking News Posts</div>
        </div>
        
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $total_drafts ); ?></div>
            <div class="label">Draft Articles</div>
        </div>
        
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $total_scheduled ); ?></div>
            <div class="label">Scheduled Posts</div>
        </div>
        
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $pending_comments ); ?></div>
            <div class="label">Pending Comments</div>
        </div>
        
        <div class="gni-stat-card">
            <div class="number"><?php echo intval( $total_users ); ?></div>
            <div class="label">Site Users</div>
        </div>
    </div>
    <?php
}

/**
 * Dashboard widget: Quick Actions
 */
function gni_dashboard_quick_actions_widget() {
    ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
        <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="button button-primary" style="text-align: center; padding: 15px; display: block;">
            ✍️ Write Article
        </a>
        
        <a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>" class="button" style="text-align: center; padding: 15px; display: block;">
            📋 All Articles
        </a>
        
        <a href="<?php echo esc_url( admin_url( 'edit.php?post_status=draft' ) ); ?>" class="button" style="text-align: center; padding: 15px; display: block;">
            📝 Drafts
        </a>
        
        <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>" class="button" style="text-align: center; padding: 15px; display: block;">
            🗂️ Categories
        </a>
        
        <a href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>" class="button" style="text-align: center; padding: 15px; display: block;">
            💬 Comments
        </a>
        
        <a href="<?php echo esc_url( admin_url( 'users.php' ) ); ?>" class="button" style="text-align: center; padding: 15px; display: block;">
            👥 Users
        </a>
    </div>
    <?php
}

/**
 * Dashboard widget: Recent Articles
 */
function gni_dashboard_recent_articles_widget() {
    $recent_posts = new WP_Query( array(
        'posts_per_page' => 5,
        'post_status' => array( 'publish', 'draft', 'pending' ),
        'orderby' => 'date',
        'order' => 'DESC',
    ) );
    
    if ( $recent_posts->have_posts() ) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Title</th><th>Status</th><th>Author</th><th>Date</th></tr></thead>';
        echo '<tbody>';
        
        while ( $recent_posts->have_posts() ) {
            $recent_posts->the_post();
            $post_status = get_post_status();
            $status_label = ucfirst( $post_status );
            
            ?>
            <tr>
                <td>
                    <a href="<?php the_permalink(); ?>" target="_blank">
                        <strong><?php echo esc_html( get_the_title() ); ?></strong>
                    </a>
                </td>
                <td>
                    <span class="gni-badge gni-badge-<?php echo esc_attr( $post_status ); ?>">
                        <?php echo esc_html( $status_label ); ?>
                    </span>
                </td>
                <td><?php echo esc_html( get_the_author() ); ?></td>
                <td><?php echo esc_html( get_the_date() ); ?></td>
            </tr>
            <?php
        }
        
        echo '</tbody>';
        echo '</table>';
        wp_reset_postdata();
    } else {
        echo '<p style="color: var(--gni-text-secondary);">No articles yet. <a href="' . esc_url( admin_url( 'post-new.php' ) ) . '">Create one now!</a></p>';
    }
}

/**
 * Dashboard widget: Breaking News Queue
 */
function gni_dashboard_breaking_queue_widget() {
    $breaking_posts = gni_get_breaking_posts( 5 );
    
    if ( ! empty( $breaking_posts ) ) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Article</th><th>Author</th><th>Published</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        
        foreach ( $breaking_posts as $post ) {
            $post_author = get_the_author_meta( 'display_name', $post->post_author );
            ?>
            <tr>
                <td>
                    <strong><?php echo esc_html( $post->post_title ); ?></strong>
                    <br>
                    <span class="gni-badge gni-badge-breaking">🔴 Breaking</span>
                </td>
                <td><?php echo esc_html( $post_author ); ?></td>
                <td><?php echo esc_html( mysql2date( get_option( 'date_format' ), $post->post_date ) ); ?></td>
                <td>
                    <a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>" class="button button-small">Edit</a>
                </td>
            </tr>
            <?php
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p style="color: var(--gni-text-secondary);">No breaking news articles. Mark some as breaking!</p>';
    }
}

/**
 * Dashboard widget: Comments Queue
 */
function gni_dashboard_comments_queue_widget() {
    $comments = get_comments( array(
        'status' => 'hold',
        'number' => 5,
    ) );
    
    if ( ! empty( $comments ) ) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Author</th><th>Comment</th><th>On</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        
        foreach ( $comments as $comment ) {
            ?>
            <tr>
                <td>
                    <strong><?php echo esc_html( $comment->comment_author ); ?></strong>
                    <br>
                    <small><?php echo esc_html( $comment->comment_author_email ); ?></small>
                </td>
                <td>
                    <p><?php echo esc_html( wp_trim_words( $comment->comment_content, 10 ) ); ?></p>
                </td>
                <td>
                    <a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>" target="_blank">
                        <?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo esc_url( admin_url( 'edit-comments.php?comment_status=moderation' ) ); ?>" class="button button-small">Review</a>
                </td>
            </tr>
            <?php
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p style="color: var(--gni-text-secondary);">✅ All comments approved! No pending moderation.</p>';
    }
}

/**
 * Dashboard widget: Trending Posts
 */
function gni_dashboard_trending_posts_widget() {
    $trending = gni_get_trending_posts( 5, 7 );
    
    if ( ! empty( $trending ) ) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Article</th><th>Views</th><th>Category</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        
        foreach ( $trending as $post ) {
            $views = get_post_meta( $post->ID, '_gni_post_views', true ) ?: 0;
            $categories = get_the_category( $post->ID );
            $cat_names = wp_list_pluck( $categories, 'name' );
            ?>
            <tr>
                <td>
                    <strong><?php echo esc_html( $post->post_title ); ?></strong>
                </td>
                <td>
                    <strong><?php echo intval( $views ); ?></strong>
                </td>
                <td>
                    <?php echo esc_html( implode( ', ', $cat_names ) ); ?>
                </td>
                <td>
                    <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="button button-small" target="_blank">View</a>
                </td>
            </tr>
            <?php
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p style="color: var(--gni-text-secondary);">No trending data available yet.</p>';
    }
}

/**
 * Custom login page
 */
function gni_custom_login_page() {
    if ( ! is_user_logged_in() ) {
        // Add custom login styles here
    }
}
add_action( 'login_enqueue_scripts', 'gni_custom_login_page' );

/**
 * Add custom column to posts list
 */
function gni_posts_list_custom_columns( $columns ) {
    $columns['gni_breaking'] = '🔴 Breaking';
    $columns['gni_featured'] = '⭐ Featured';
    $columns['gni_priority'] = '📊 Priority';
    $columns['gni_views'] = '👁️ Views';
    
    // Remove the default date column and add it back at the end
    if ( isset( $columns['date'] ) ) {
        unset( $columns['date'] );
    }
    $columns['date'] = 'Date';
    
    return $columns;
}
add_filter( 'manage_posts_columns', 'gni_posts_list_custom_columns' );

/**
 * Display custom column content
 */
function gni_posts_list_custom_columns_content( $column, $post_id ) {
    switch ( $column ) {
        case 'gni_breaking':
            $is_breaking = get_post_meta( $post_id, '_gni_breaking', true );
            echo $is_breaking ? '✅ Breaking' : '—';
            break;
            
        case 'gni_featured':
            $is_featured = get_post_meta( $post_id, '_gni_featured', true );
            echo $is_featured ? '✅ Featured' : '—';
            break;
            
        case 'gni_priority':
            $priority = get_post_meta( $post_id, '_gni_priority', true ) ?: 'normal';
            $priority_label = array(
                'high' => '🔴 High',
                'normal' => '🟡 Normal',
                'low' => '⚪ Low',
            );
            echo isset( $priority_label[ $priority ] ) ? $priority_label[ $priority ] : $priority_label['normal'];
            break;
            
        case 'gni_views':
            $views = get_post_meta( $post_id, '_gni_post_views', true ) ?: 0;
            echo intval( $views ) . ' views';
            break;
    }
}
add_action( 'manage_posts_custom_column', 'gni_posts_list_custom_columns_content', 10, 2 );

/**
 * Make custom columns sortable
 */
function gni_posts_list_sortable_columns( $columns ) {
    $columns['gni_views'] = 'gni_views';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'gni_posts_list_sortable_columns' );

/**
 * Handle column sorting
 */
function gni_posts_list_column_orderby( $query ) {
    if ( ! is_admin() ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    if ( 'gni_views' === $orderby ) {
        $query->set( 'meta_key', '_gni_post_views' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
add_action( 'pre_get_posts', 'gni_posts_list_column_orderby' );

/**
 * Add quick edit fields
 */
function gni_quick_edit_custom_fields() {
    global $post;
    ?>
    <fieldset class="inline-edit-col-right" style="background-color: var(--gni-dark-secondary); padding: 10px; border-radius: 4px;">
        <div class="inline-edit-group">
            <label style="margin-bottom: 10px; display: block;">
                <input type="checkbox" name="_gni_breaking" value="1" />
                <span style="margin-left: 5px;">🔴 Mark as Breaking News</span>
            </label>
            
            <label style="margin-bottom: 10px; display: block;">
                <input type="checkbox" name="_gni_featured" value="1" />
                <span style="margin-left: 5px;">⭐ Mark as Featured</span>
            </label>
            
            <label>
                <span>📊 Priority:</span>
                <select name="_gni_priority" style="margin-left: 10px;">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="low">Low</option>
                </select>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action( 'quick_edit_custom_box', 'gni_quick_edit_custom_fields' );

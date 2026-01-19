<?php
/**
 * Global News Insights - Content Management Admin Pages
 * 
 * Provides advanced content management interface
 * Categories, tags, bulk edit, search and filter
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register content management admin menu
 */
function gni_register_content_menu() {
    add_menu_page(
        __( 'Content Management', 'global-news-insights' ),
        '📰 Content Manager',
        'edit_posts',
        'gni-content-manager',
        'gni_content_manager_page',
        'dashicons-newspaper',
        5
    );
    
    add_submenu_page(
        'gni-content-manager',
        __( 'Bulk Edit', 'global-news-insights' ),
        'Bulk Edit',
        'edit_posts',
        'gni-bulk-edit',
        'gni_bulk_edit_page'
    );
    
    add_submenu_page(
        'gni-content-manager',
        __( 'Categories', 'global-news-insights' ),
        'Categories',
        'manage_categories',
        'gni-manage-categories',
        'gni_manage_categories_page'
    );
    
    add_submenu_page(
        'gni-content-manager',
        __( 'Tags', 'global-news-insights' ),
        'Tags',
        'manage_post_tags',
        'gni-manage-tags',
        'gni_manage_tags_page'
    );
    
    add_submenu_page(
        'gni-content-manager',
        __( 'Content Search', 'global-news-insights' ),
        'Advanced Search',
        'edit_posts',
        'gni-content-search',
        'gni_content_search_page'
    );
}
add_action( 'admin_menu', 'gni_register_content_menu' );

/**
 * Main content manager page
 */
function gni_content_manager_page() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    ?>
    <div class="wrap">
        <div class="gni-dashboard-header">
            <h1>📰 Content Management Center</h1>
            <p>Manage articles, categories, tags, and content across your news platform</p>
        </div>
        
        <div class="gni-dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="gni-stat-card">
                <div style="font-size: 48px; margin-bottom: 10px;">📋</div>
                <h3><?php esc_html_e( 'Bulk Edit', 'global-news-insights' ); ?></h3>
                <p><?php esc_html_e( 'Edit multiple articles at once', 'global-news-insights' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-bulk-edit' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to Bulk Edit', 'global-news-insights' ); ?></a>
            </div>
            
            <div class="gni-stat-card">
                <div style="font-size: 48px; margin-bottom: 10px;">🗂️</div>
                <h3><?php esc_html_e( 'Categories', 'global-news-insights' ); ?></h3>
                <p><?php esc_html_e( 'Organize articles by category', 'global-news-insights' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-manage-categories' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Manage Categories', 'global-news-insights' ); ?></a>
            </div>
            
            <div class="gni-stat-card">
                <div style="font-size: 48px; margin-bottom: 10px;">🏷️</div>
                <h3><?php esc_html_e( 'Tags', 'global-news-insights' ); ?></h3>
                <p><?php esc_html_e( 'Tag articles for better organization', 'global-news-insights' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-manage-tags' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Manage Tags', 'global-news-insights' ); ?></a>
            </div>
            
            <div class="gni-stat-card">
                <div style="font-size: 48px; margin-bottom: 10px;">🔍</div>
                <h3><?php esc_html_e( 'Advanced Search', 'global-news-insights' ); ?></h3>
                <p><?php esc_html_e( 'Find articles with filters', 'global-news-insights' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-content-search' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Search Articles', 'global-news-insights' ); ?></a>
            </div>
        </div>
        
        <div style="background-color: var(--gni-dark-secondary); border: 1px solid var(--gni-border); border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h2><?php esc_html_e( 'Quick Stats', 'global-news-insights' ); ?></h2>
            <?php gni_content_stats(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display content statistics
 */
function gni_content_stats() {
    $posts = wp_count_posts( 'post' );
    $categories = count( get_categories() );
    $tags = count( get_tags() );
    
    $breaking = count( gni_get_breaking_posts( 100 ) );
    $featured = count( gni_get_featured_posts( 100 ) );
    ?>
    <table class="widefat striped">
        <tr>
            <td style="width: 25%;"><strong><?php esc_html_e( 'Published Articles', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $posts->publish ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Draft Articles', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $posts->draft ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Breaking News', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $breaking ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Featured Articles', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $featured ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Categories', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $categories ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Tags', 'global-news-insights' ); ?></strong></td>
            <td><?php echo intval( $tags ); ?></td>
        </tr>
    </table>
    <?php
}

/**
 * Bulk Edit page
 */
function gni_bulk_edit_page() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle bulk actions
    if ( isset( $_POST['action'] ) && check_admin_referer( 'gni_bulk_edit_nonce' ) ) {
        $action = sanitize_text_field( $_POST['action'] );
        $post_ids = isset( $_POST['post_ids'] ) ? array_map( 'intval', (array) $_POST['post_ids'] ) : array();
        
        switch ( $action ) {
            case 'mark_breaking':
                foreach ( $post_ids as $post_id ) {
                    update_post_meta( $post_id, '_gni_breaking', '1' );
                }
                echo '<div class="notice notice-success"><p>' . esc_html__( 'Articles marked as breaking news!', 'global-news-insights' ) . '</p></div>';
                break;
                
            case 'unmark_breaking':
                foreach ( $post_ids as $post_id ) {
                    delete_post_meta( $post_id, '_gni_breaking' );
                }
                echo '<div class="notice notice-success"><p>' . esc_html__( 'Breaking news flag removed!', 'global-news-insights' ) . '</p></div>';
                break;
                
            case 'mark_featured':
                foreach ( $post_ids as $post_id ) {
                    update_post_meta( $post_id, '_gni_featured', '1' );
                }
                echo '<div class="notice notice-success"><p>' . esc_html__( 'Articles marked as featured!', 'global-news-insights' ) . '</p></div>';
                break;
                
            case 'change_priority':
                $priority = sanitize_text_field( $_POST['priority'] ?? 'normal' );
                foreach ( $post_ids as $post_id ) {
                    update_post_meta( $post_id, '_gni_priority', $priority );
                }
                echo '<div class="notice notice-success"><p>' . esc_html__( 'Article priority updated!', 'global-news-insights' ) . '</p></div>';
                break;
        }
    }
    
    // Get articles for bulk edit
    $articles = new WP_Query( array(
        'posts_per_page' => 20,
        'orderby' => 'date',
        'order' => 'DESC',
    ) );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Bulk Edit Articles', 'global-news-insights' ); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field( 'gni_bulk_edit_nonce' ); ?>
            
            <div style="margin-bottom: 20px; background-color: var(--gni-dark-secondary); padding: 15px; border-radius: 4px;">
                <label style="margin-right: 15px;">
                    <span><?php esc_html_e( 'Action:', 'global-news-insights' ); ?></span>
                    <select name="action" id="bulk-action" style="margin-left: 10px;">
                        <option value=""><?php esc_html_e( 'Select an action', 'global-news-insights' ); ?></option>
                        <option value="mark_breaking">🔴 Mark as Breaking News</option>
                        <option value="unmark_breaking">Remove Breaking Flag</option>
                        <option value="mark_featured">⭐ Mark as Featured</option>
                        <option value="change_priority">📊 Change Priority</option>
                    </select>
                </label>
                
                <div id="priority-select" style="display: none; margin-top: 10px;">
                    <label>
                        <span><?php esc_html_e( 'Priority:', 'global-news-insights' ); ?></span>
                        <select name="priority" style="margin-left: 10px;">
                            <option value="high">🔴 High</option>
                            <option value="normal">🟡 Normal</option>
                            <option value="low">⚪ Low</option>
                        </select>
                    </label>
                </div>
                
                <input type="submit" class="button button-primary" style="margin-left: 15px;" value="<?php esc_attr_e( 'Apply', 'global-news-insights' ); ?>" />
            </div>
            
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th style="width: 50px;"><input type="checkbox" id="select-all" /></th>
                        <th><?php esc_html_e( 'Title', 'global-news-insights' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Status', 'global-news-insights' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Priority', 'global-news-insights' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Author', 'global-news-insights' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( $articles->have_posts() ) {
                        while ( $articles->have_posts() ) {
                            $articles->the_post();
                            $priority = get_post_meta( get_the_ID(), '_gni_priority', true ) ?: 'normal';
                            ?>
                            <tr>
                                <td><input type="checkbox" name="post_ids[]" value="<?php echo intval( get_the_ID() ); ?>" /></td>
                                <td><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></td>
                                <td><?php echo esc_html( get_post_status() ); ?></td>
                                <td><?php echo esc_html( ucfirst( $priority ) ); ?></td>
                                <td><?php the_author(); ?></td>
                            </tr>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<tr><td colspan="5">' . esc_html__( 'No articles found.', 'global-news-insights' ) . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionSelect = document.getElementById('bulk-action');
            const prioritySelect = document.getElementById('priority-select');
            const selectAllCheckbox = document.getElementById('select-all');
            
            actionSelect?.addEventListener('change', function() {
                prioritySelect.style.display = this.value === 'change_priority' ? 'block' : 'none';
            });
            
            selectAllCheckbox?.addEventListener('change', function() {
                document.querySelectorAll('input[name="post_ids[]"]').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
    <?php
}

/**
 * Manage Categories page
 */
function gni_manage_categories_page() {
    if ( ! current_user_can( 'manage_categories' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Manage Categories', 'global-news-insights' ); ?></h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <div>
                <h2><?php esc_html_e( 'Add New Category', 'global-news-insights' ); ?></h2>
                <?php
                // Show category form
                ?>
                <iframe src="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>" style="width: 100%; height: 600px; border: 1px solid var(--gni-border); border-radius: 4px;"></iframe>
            </div>
            
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px;">
                <h2><?php esc_html_e( 'BBC News Style Categories', 'global-news-insights' ); ?></h2>
                <p style="margin-bottom: 15px;"><?php esc_html_e( 'Recommended categories for a professional news site:', 'global-news-insights' ); ?></p>
                <ul style="list-style: none; padding: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>🌍 World</strong> - International news</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>🌍 Africa</strong> - African continent news</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>💼 Business</strong> - Business & Finance</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>💻 Technology</strong> - Tech & Innovation</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>⚽ Sports</strong> - Sports & Athletics</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid var(--gni-border);"><strong>🎬 Entertainment</strong> - Entertainment & Lifestyle</li>
                    <li style="padding: 8px 0;"><strong>🏥 Health</strong> - Health & Science</li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Manage Tags page
 */
function gni_manage_tags_page() {
    if ( ! current_user_can( 'manage_post_tags' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Manage Tags', 'global-news-insights' ); ?></h1>
        
        <p><?php esc_html_e( 'Tags help organize content and improve SEO. Use tags to connect related articles.', 'global-news-insights' ); ?></p>
        
        <div style="margin-top: 30px;">
            <iframe src="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=post_tag' ) ); ?>" style="width: 100%; height: 700px; border: 1px solid var(--gni-border); border-radius: 4px;"></iframe>
        </div>
    </div>
    <?php
}

/**
 * Advanced Content Search page
 */
function gni_content_search_page() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    $search_results = array();
    $search_term = '';
    $filter_category = '';
    $filter_author = '';
    $filter_date = '';
    $filter_status = '';
    
    // Handle search
    if ( isset( $_GET['s'] ) && check_admin_referer( 'gni_search_nonce' ) ) {
        $search_term = sanitize_text_field( $_GET['s'] );
        $filter_category = isset( $_GET['category'] ) ? intval( $_GET['category'] ) : '';
        $filter_author = isset( $_GET['author'] ) ? intval( $_GET['author'] ) : '';
        $filter_date = isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '';
        $filter_status = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'publish';
        
        $args = array(
            's' => $search_term,
            'posts_per_page' => 50,
            'post_status' => $filter_status ?: 'publish',
        );
        
        if ( $filter_category ) {
            $args['cat'] = $filter_category;
        }
        if ( $filter_author ) {
            $args['author'] = $filter_author;
        }
        
        $search_results = new WP_Query( $args );
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Advanced Content Search', 'global-news-insights' ); ?></h1>
        
        <form method="get" action="" style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; margin: 20px 0;">
            <?php wp_nonce_field( 'gni_search_nonce' ); ?>
            <input type="hidden" name="page" value="gni-content-search" />
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="search-input"><?php esc_html_e( 'Search', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="text" id="search-input" name="s" value="<?php echo esc_attr( $search_term ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Article title, content...', 'global-news-insights' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="filter-category"><?php esc_html_e( 'Category', 'global-news-insights' ); ?></label></th>
                    <td>
                        <?php
                        wp_dropdown_categories( array(
                            'selected' => $filter_category,
                            'show_option_all' => __( 'All Categories', 'global-news-insights' ),
                            'name' => 'category',
                            'id' => 'filter-category',
                        ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="filter-author"><?php esc_html_e( 'Author', 'global-news-insights' ); ?></label></th>
                    <td>
                        <?php
                        wp_dropdown_users( array(
                            'selected' => $filter_author,
                            'show_option_all' => __( 'All Authors', 'global-news-insights' ),
                            'name' => 'author',
                            'id' => 'filter-author',
                        ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="filter-status"><?php esc_html_e( 'Status', 'global-news-insights' ); ?></label></th>
                    <td>
                        <select name="status" id="filter-status">
                            <option value="publish" <?php selected( $filter_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'global-news-insights' ); ?></option>
                            <option value="draft" <?php selected( $filter_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'global-news-insights' ); ?></option>
                            <option value="pending" <?php selected( $filter_status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'global-news-insights' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <p><input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Search', 'global-news-insights' ); ?>" /></p>
        </form>
        
        <?php if ( $search_results && $search_results->have_posts() ) : ?>
            <h2><?php echo intval( $search_results->found_posts ); ?> <?php esc_html_e( 'Results Found', 'global-news-insights' ); ?></h2>
            
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Title', 'global-news-insights' ); ?></th>
                        <th style="width: 150px;"><?php esc_html_e( 'Category', 'global-news-insights' ); ?></th>
                        <th style="width: 150px;"><?php esc_html_e( 'Author', 'global-news-insights' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Date', 'global-news-insights' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Action', 'global-news-insights' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ( $search_results->have_posts() ) {
                        $search_results->the_post();
                        $categories = get_the_category();
                        $cat_names = wp_list_pluck( $categories, 'name' );
                        ?>
                        <tr>
                            <td><a href="<?php the_permalink(); ?>" target="_blank"><strong><?php the_title(); ?></strong></a></td>
                            <td><?php echo esc_html( implode( ', ', $cat_names ) ); ?></td>
                            <td><?php the_author(); ?></td>
                            <td><?php the_date(); ?></td>
                            <td>
                                <a href="<?php echo esc_url( get_edit_post_link() ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'global-news-insights' ); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>
        <?php elseif ( isset( $_GET['s'] ) ) : ?>
            <p><?php esc_html_e( 'No articles found matching your search criteria.', 'global-news-insights' ); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

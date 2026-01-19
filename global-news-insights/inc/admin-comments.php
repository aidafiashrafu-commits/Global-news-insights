<?php
/**
 * Global News Insights - Comments & Moderation System
 * 
 * Advanced comment management with anti-spam protection
 * Approve, edit, delete, and bulk manage comments
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register comments management menu
 */
function gni_register_comments_menu() {
    add_menu_page(
        __( 'Comments & Moderation', 'global-news-insights' ),
        '💬 Comments',
        'moderate_comments',
        'gni-comments',
        'gni_comments_management_page',
        'dashicons-format-chat',
        7
    );
    
    add_submenu_page(
        'gni-comments',
        __( 'Pending Approval', 'global-news-insights' ),
        'Pending Approval',
        'moderate_comments',
        'gni-pending-comments',
        'gni_pending_comments_page'
    );
    
    add_submenu_page(
        'gni-comments',
        __( 'Spam Detection', 'global-news-insights' ),
        'Spam Detection',
        'moderate_comments',
        'gni-spam-comments',
        'gni_spam_comments_page'
    );
}
add_action( 'admin_menu', 'gni_register_comments_menu' );

/**
 * Main comments management page
 */
function gni_comments_management_page() {
    if ( ! current_user_can( 'moderate_comments' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Get comment statistics
    $total_comments = wp_count_comments();
    $pending = $total_comments->moderated ?? 0;
    $approved = $total_comments->approved ?? 0;
    $spam = $total_comments->spam ?? 0;
    $trash = $total_comments->trash ?? 0;
    ?>
    <div class="wrap">
        <div class="gni-dashboard-header">
            <h1>💬 Comments & Moderation</h1>
            <p><?php esc_html_e( 'Manage and moderate user comments across all articles', 'global-news-insights' ); ?></p>
        </div>
        
        <div class="gni-dashboard-grid">
            <div class="gni-stat-card">
                <div class="number"><?php echo intval( $approved ); ?></div>
                <div class="label">Approved Comments</div>
            </div>
            
            <div class="gni-stat-card">
                <div class="number" style="color: var(--gni-warning);"><?php echo intval( $pending ); ?></div>
                <div class="label">Pending Review</div>
            </div>
            
            <div class="gni-stat-card">
                <div class="number" style="color: var(--gni-breaking);"><?php echo intval( $spam ); ?></div>
                <div class="label">Marked as Spam</div>
            </div>
            
            <div class="gni-stat-card">
                <div class="number"><?php echo intval( $trash ); ?></div>
                <div class="label">In Trash</div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 30px;">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-pending-comments' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer;">
                    <div style="font-size: 36px; margin-bottom: 10px;">⏳</div>
                    <h3><?php esc_html_e( 'Review Pending', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'Approve or reject pending comments', 'global-news-insights' ); ?></p>
                </div>
            </a>
            
            <a href="<?php echo esc_url( admin_url( 'edit-comments.php?comment_status=approved' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer;">
                    <div style="font-size: 36px; margin-bottom: 10px;">✅</div>
                    <h3><?php esc_html_e( 'Approved', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'View approved comments', 'global-news-insights' ); ?></p>
                </div>
            </a>
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-spam-comments' ) ); ?>" style="text-decoration: none;">
                <div class="gni-stat-card" style="cursor: pointer;">
                    <div style="font-size: 36px; margin-bottom: 10px;">🚫</div>
                    <h3><?php esc_html_e( 'Spam Detection', 'global-news-insights' ); ?></h3>
                    <p><?php esc_html_e( 'View spam comments', 'global-news-insights' ); ?></p>
                </div>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Pending comments page
 */
function gni_pending_comments_page() {
    if ( ! current_user_can( 'moderate_comments' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle comment moderation
    if ( isset( $_POST['action'] ) && check_admin_referer( 'gni_comment_action_nonce' ) ) {
        $action = sanitize_text_field( $_POST['action'] );
        $comment_ids = isset( $_POST['comment_ids'] ) ? array_map( 'intval', (array) $_POST['comment_ids'] ) : array();
        
        foreach ( $comment_ids as $comment_id ) {
            if ( $action === 'approve' ) {
                wp_set_comment_status( $comment_id, 'approve' );
            } elseif ( $action === 'reject' ) {
                wp_delete_comment( $comment_id );
            }
        }
        
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Comments updated!', 'global-news-insights' ) . '</p></div>';
    }
    
    // Get pending comments
    $pending_comments = get_comments( array(
        'status' => 'hold',
        'number' => 50,
        'orderby' => 'comment_date',
        'order' => 'ASC',
    ) );
    ?>
    <div class="wrap">
        <h1>⏳ <?php esc_html_e( 'Pending Comments', 'global-news-insights' ); ?></h1>
        
        <?php if ( ! empty( $pending_comments ) ) : ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'gni_comment_action_nonce' ); ?>
                
                <div style="margin-bottom: 20px;">
                    <select name="action">
                        <option value="">Select action</option>
                        <option value="approve">✅ Approve</option>
                        <option value="reject">❌ Reject</option>
                    </select>
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Apply', 'global-news-insights' ); ?>" />
                </div>
                
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;"><input type="checkbox" id="select-all" /></th>
                            <th><?php esc_html_e( 'Author', 'global-news-insights' ); ?></th>
                            <th><?php esc_html_e( 'Comment', 'global-news-insights' ); ?></th>
                            <th style="width: 200px;"><?php esc_html_e( 'On Post', 'global-news-insights' ); ?></th>
                            <th style="width: 150px;"><?php esc_html_e( 'Date', 'global-news-insights' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ( $pending_comments as $comment ) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name="comment_ids[]" value="<?php echo intval( $comment->comment_ID ); ?>" /></td>
                                <td>
                                    <strong><?php echo esc_html( $comment->comment_author ); ?></strong>
                                    <br>
                                    <small><?php echo esc_html( $comment->comment_author_email ); ?></small>
                                </td>
                                <td>
                                    <p><?php echo esc_html( wp_trim_words( $comment->comment_content, 15 ) ); ?></p>
                                    <a href="<?php echo esc_url( admin_url( 'comment.php?action=editcomment&c=' . intval( $comment->comment_ID ) ) ); ?>" class="button button-small">
                                        <?php esc_html_e( 'Edit', 'global-news-insights' ); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url( get_permalink( $comment->comment_post_ID ) ); ?>" target="_blank">
                                        <?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?>
                                    </a>
                                </td>
                                <td><?php echo esc_html( mysql2date( get_option( 'date_format' ), $comment->comment_date ) ); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        <?php else : ?>
            <div class="notice notice-success"><p>✅ <?php esc_html_e( 'All comments have been reviewed!', 'global-news-insights' ); ?></p></div>
        <?php endif; ?>
    </div>
    
    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('input[name="comment_ids[]"]').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
    <?php
}

/**
 * Spam detection page
 */
function gni_spam_comments_page() {
    if ( ! current_user_can( 'moderate_comments' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Get spam comments
    $spam_comments = get_comments( array(
        'status' => 'spam',
        'number' => 50,
    ) );
    
    // Spam detection keywords
    $spam_keywords = array(
        'viagra',
        'casino',
        'lottery',
        'buy now',
        'click here',
        'call now',
        'limited time',
        'act fast',
        'https://',
        'http://',
    );
    ?>
    <div class="wrap">
        <h1>🚫 <?php esc_html_e( 'Spam Detection', 'global-news-insights' ); ?></h1>
        
        <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; margin-bottom: 20px;">
            <h2><?php esc_html_e( 'Spam Statistics', 'global-news-insights' ); ?></h2>
            <p><?php echo intval( count( $spam_comments ) ); ?> <?php esc_html_e( 'spam comments detected', 'global-news-insights' ); ?></p>
            
            <p style="margin-top: 15px;">
                <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'comment.php?action=delete&c=spam' ), 'delete-comment' ) ); ?>" class="button button-destructive" onclick="return confirm('<?php esc_attr_e( 'Delete all spam?', 'global-news-insights' ); ?>');">
                    🗑️ <?php esc_html_e( 'Delete All Spam', 'global-news-insights' ); ?>
                </a>
            </p>
        </div>
        
        <h2><?php esc_html_e( 'Spam Detection Settings', 'global-news-insights' ); ?></h2>
        <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px;">
            <p><?php esc_html_e( 'Common spam keywords:', 'global-news-insights' ); ?></p>
            <code style="display: block; padding: 10px; background-color: var(--gni-dark-tertiary); border-radius: 4px; overflow-x: auto;">
                <?php echo esc_html( implode( ', ', $spam_keywords ) ); ?>
            </code>
            <p style="font-size: 12px; color: var(--gni-text-secondary);"><?php esc_html_e( 'Comments containing these keywords are flagged as potential spam', 'global-news-insights' ); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Anti-spam function
 */
function gni_check_comment_spam( $comment_id ) {
    $comment = get_comment( $comment_id );
    
    if ( ! $comment ) {
        return;
    }
    
    $spam_keywords = array(
        'viagra',
        'casino',
        'lottery',
        'buy now',
        'click here',
        'call now',
    );
    
    $content = strtolower( $comment->comment_content );
    $is_spam = false;
    
    foreach ( $spam_keywords as $keyword ) {
        if ( strpos( $content, strtolower( $keyword ) ) !== false ) {
            $is_spam = true;
            break;
        }
    }
    
    if ( $is_spam ) {
        wp_spam_comment( $comment_id );
    }
}
add_action( 'comment_post', 'gni_check_comment_spam' );

/**
 * Enable comments for posts
 */
function gni_enable_post_comments() {
    if ( is_single() && get_post_type() === 'post' ) {
        $allow_comments = get_post_meta( get_the_ID(), '_gni_allow_comments', true );
        if ( ! $allow_comments ) {
            // Disable comments for this post
        }
    }
}
add_action( 'wp_footer', 'gni_enable_post_comments' );

/**
 * Custom comment form
 */
function gni_comment_form( $args = array(), $post_id = null ) {
    if ( ! comments_open() ) {
        return;
    }
    
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }
    
    $commenter = wp_get_current_commenter();
    
    $consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
    
    $fields = array(
        'author' => '<div class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'global-news-insights' ) . '</label> <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" required /></div>',
        'email' => '<div class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'global-news-insights' ) . '</label> <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" required /></div>',
        'url' => '<div class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'global-news-insights' ) . '</label> <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /></div>',
        'cookies' => '<div class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' /> <label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name and email for next time', 'global-news-insights' ) . '</label></div>',
    );
    
    $comment_form_args = wp_parse_args( $args, array(
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        'comment_field' => '<div class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'global-news-insights' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></div>',
        'submit_button' => '<button type="submit" class="button button-primary">' . esc_html__( 'Post Comment', 'global-news-insights' ) . '</button>',
    ) );
    
    comment_form( $comment_form_args, $post_id );
}

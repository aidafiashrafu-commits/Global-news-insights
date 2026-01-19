<?php
/**
 * Newsletter Admin Management Page and Functions
 *
 * Allows admins to view, manage, and export newsletter subscribers
 * Handles subscription management and campaign sending
 *
 * @package Global_News_Insights
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register newsletter admin menu
 */
function gni_newsletter_admin_menu() {
    add_menu_page(
        __( 'Newsletter', 'global-news-insights' ),
        __( 'Newsletter', 'global-news-insights' ),
        'manage_options',
        'gni-newsletter',
        'gni_newsletter_admin_page',
        'dashicons-email-alt',
        80
    );
    
    // Add submenu for subscribers
    add_submenu_page(
        'gni-newsletter',
        __( 'Subscribers', 'global-news-insights' ),
        __( 'Subscribers', 'global-news-insights' ),
        'manage_options',
        'gni-newsletter-subscribers',
        'gni_newsletter_subscribers_page'
    );
    
    // Add submenu for campaigns
    add_submenu_page(
        'gni-newsletter',
        __( 'Campaigns', 'global-news-insights' ),
        __( 'Campaigns', 'global-news-insights' ),
        'manage_options',
        'gni-newsletter-campaigns',
        'gni_newsletter_campaigns_page'
    );
    
    // Add submenu for settings
    add_submenu_page(
        'gni-newsletter',
        __( 'Newsletter Settings', 'global-news-insights' ),
        __( 'Settings', 'global-news-insights' ),
        'manage_options',
        'gni-newsletter-settings',
        'gni_newsletter_settings_page'
    );
}
add_action( 'admin_menu', 'gni_newsletter_admin_menu' );

/**
 * Main newsletter admin page callback
 */
function gni_newsletter_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Get newsletter statistics
    $subscriber_count = gni_get_subscriber_count();
    $active_subscriber_count = gni_get_active_subscriber_count();
    $unsubscribed_count = gni_get_unsubscribed_count();
    ?>
    
    <div class="wrap">
        <h1><?php esc_html_e( 'Newsletter Dashboard', 'global-news-insights' ); ?></h1>
        
        <div class="gni-dashboard-widgets">
            <div class="widget">
                <h2><?php echo intval( $subscriber_count ); ?></h2>
                <p><?php esc_html_e( 'Total Subscribers', 'global-news-insights' ); ?></p>
            </div>
            
            <div class="widget">
                <h2><?php echo intval( $active_subscriber_count ); ?></h2>
                <p><?php esc_html_e( 'Active Subscribers', 'global-news-insights' ); ?></p>
            </div>
            
            <div class="widget">
                <h2><?php echo intval( $unsubscribed_count ); ?></h2>
                <p><?php esc_html_e( 'Unsubscribed', 'global-news-insights' ); ?></p>
            </div>
        </div>
        
        <div class="gni-admin-links">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-newsletter-subscribers' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'View Subscribers', 'global-news-insights' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-newsletter-campaigns' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'Create Campaign', 'global-news-insights' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-newsletter-settings' ) ); ?>" class="button">
                <?php esc_html_e( 'Settings', 'global-news-insights' ); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Newsletter subscribers page
 */
function gni_newsletter_subscribers_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle bulk actions
    if ( isset( $_POST['action'] ) && check_admin_referer( 'gni_subscribers_nonce' ) ) {
        $action = sanitize_text_field( $_POST['action'] );
        $subscriber_ids = isset( $_POST['subscriber'] ) ? array_map( 'intval', (array) $_POST['subscriber'] ) : array();
        
        if ( 'delete' === $action ) {
            foreach ( $subscriber_ids as $id ) {
                gni_delete_subscriber( $id );
            }
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscribers deleted.', 'global-news-insights' ) . '</p></div>';
        } elseif ( 'unsubscribe' === $action ) {
            foreach ( $subscriber_ids as $id ) {
                gni_mark_unsubscribed( $id );
            }
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscribers marked as unsubscribed.', 'global-news-insights' ) . '</p></div>';
        }
    }
    
    // Get subscribers
    $subscribers = gni_get_subscribers( 50 );
    ?>
    
    <div class="wrap">
        <h1><?php esc_html_e( 'Newsletter Subscribers', 'global-news-insights' ); ?></h1>
        
        <div class="gni-export-section">
            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=gni_export_subscribers' ), 'gni_export_nonce' ) ); ?>" class="button button-secondary">
                <?php esc_html_e( 'Export CSV', 'global-news-insights' ); ?>
            </a>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field( 'gni_subscribers_nonce' ); ?>
            
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <td class="check-column"><input type="checkbox" /></td>
                        <th><?php esc_html_e( 'Email', 'global-news-insights' ); ?></th>
                        <th><?php esc_html_e( 'Subscribed Date', 'global-news-insights' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'global-news-insights' ); ?></th>
                        <th><?php esc_html_e( 'Action', 'global-news-insights' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( ! empty( $subscribers ) ) : ?>
                        <?php foreach ( $subscribers as $subscriber ) : ?>
                            <tr>
                                <td class="check-column">
                                    <input type="checkbox" name="subscriber[]" value="<?php echo intval( $subscriber->ID ); ?>" />
                                </td>
                                <td><strong><?php echo esc_html( $subscriber->subscriber_email ); ?></strong></td>
                                <td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $subscriber->subscriber_date ) ) ); ?></td>
                                <td>
                                    <span class="status status-<?php echo esc_attr( $subscriber->subscriber_status ); ?>">
                                        <?php echo esc_html( ucfirst( $subscriber->subscriber_status ) ); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=gni_delete_subscriber&id=' . intval( $subscriber->ID ) ), 'gni_delete_subscriber_' . intval( $subscriber->ID ) ) ); ?>" class="button button-small button-link-delete" onclick="return confirm('<?php esc_attr_e( 'Are you sure?', 'global-news-insights' ); ?>');">
                                        <?php esc_html_e( 'Remove', 'global-news-insights' ); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e( 'No subscribers yet.', 'global-news-insights' ); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="tablenav">
                <select name="action">
                    <option value=""><?php esc_html_e( 'Bulk Actions', 'global-news-insights' ); ?></option>
                    <option value="delete"><?php esc_html_e( 'Delete', 'global-news-insights' ); ?></option>
                    <option value="unsubscribe"><?php esc_html_e( 'Mark Unsubscribed', 'global-news-insights' ); ?></option>
                </select>
                <input type="submit" class="button" value="<?php esc_attr_e( 'Apply', 'global-news-insights' ); ?>" />
            </div>
        </form>
    </div>
    <?php
}

/**
 * Newsletter campaigns page
 */
function gni_newsletter_campaigns_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    ?>
    
    <div class="wrap">
        <h1><?php esc_html_e( 'Newsletter Campaigns', 'global-news-insights' ); ?></h1>
        
        <div class="gni-create-campaign">
            <h2><?php esc_html_e( 'Create New Campaign', 'global-news-insights' ); ?></h2>
            <p><?php esc_html_e( 'Send newsletters to your subscribers about new posts or announcements.', 'global-news-insights' ); ?></p>
            
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
                <?php wp_nonce_field( 'gni_campaign_nonce' ); ?>
                <input type="hidden" name="action" value="gni_send_campaign" />
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="campaign-subject"><?php esc_html_e( 'Subject', 'global-news-insights' ); ?></label></th>
                        <td><input type="text" id="campaign-subject" name="subject" class="regular-text" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="campaign-message"><?php esc_html_e( 'Message', 'global-news-insights' ); ?></label></th>
                        <td>
                            <?php
                            wp_editor(
                                '',
                                'campaign_message',
                                array(
                                    'textarea_rows' => 10,
                                    'media_buttons' => true,
                                )
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="campaign-recipients"><?php esc_html_e( 'Recipients', 'global-news-insights' ); ?></label></th>
                        <td>
                            <select name="recipients" id="campaign-recipients">
                                <option value="active"><?php esc_html_e( 'Active Subscribers Only', 'global-news-insights' ); ?></option>
                                <option value="all"><?php esc_html_e( 'All Subscribers', 'global-news-insights' ); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button( __( 'Send Campaign', 'global-news-insights' ), 'primary', 'submit', true ); ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Newsletter settings page
 */
function gni_newsletter_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle form submission
    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gni_newsletter_settings_nonce' ) ) {
        update_option( 'gni_newsletter_from_email', sanitize_email( $_POST['from_email'] ?? '' ) );
        update_option( 'gni_newsletter_from_name', sanitize_text_field( $_POST['from_name'] ?? '' ) );
        update_option( 'gni_newsletter_reply_to', sanitize_email( $_POST['reply_to'] ?? '' ) );
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved.', 'global-news-insights' ) . '</p></div>';
    }
    
    $from_email = get_option( 'gni_newsletter_from_email', get_bloginfo( 'admin_email' ) );
    $from_name = get_option( 'gni_newsletter_from_name', get_bloginfo( 'name' ) );
    $reply_to = get_option( 'gni_newsletter_reply_to', get_bloginfo( 'admin_email' ) );
    ?>
    
    <div class="wrap">
        <h1><?php esc_html_e( 'Newsletter Settings', 'global-news-insights' ); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field( 'gni_newsletter_settings_nonce' ); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="from-email"><?php esc_html_e( 'From Email', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="email" id="from-email" name="from_email" value="<?php echo esc_attr( $from_email ); ?>" class="regular-text" required />
                        <p class="description"><?php esc_html_e( 'The email address from which newsletters will be sent.', 'global-news-insights' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="from-name"><?php esc_html_e( 'From Name', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="text" id="from-name" name="from_name" value="<?php echo esc_attr( $from_name ); ?>" class="regular-text" required />
                        <p class="description"><?php esc_html_e( 'The name associated with the from email.', 'global-news-insights' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="reply-to"><?php esc_html_e( 'Reply-To Email', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="email" id="reply-to" name="reply_to" value="<?php echo esc_attr( $reply_to ); ?>" class="regular-text" required />
                        <p class="description"><?php esc_html_e( 'Where replies to newsletters will be directed.', 'global-news-insights' ); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// ===== Helper Database Functions =====

/**
 * Create newsletter subscribers table
 */
function gni_create_newsletter_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        subscriber_email varchar(100) NOT NULL UNIQUE,
        subscriber_date datetime DEFAULT CURRENT_TIMESTAMP,
        subscriber_status varchar(20) DEFAULT 'active',
        PRIMARY KEY (ID),
        KEY email_status (subscriber_email,subscriber_status)
    ) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Add newsletter subscriber
 */
function gni_add_subscriber( $email ) {
    global $wpdb;
    
    $email = sanitize_email( $email );
    
    if ( ! is_email( $email ) ) {
        return false;
    }
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->insert(
        $table_name,
        array(
            'subscriber_email' => $email,
            'subscriber_status' => 'active',
        ),
        array( '%s', '%s' )
    );
}

/**
 * Get all subscribers
 */
function gni_get_subscribers( $limit = 50 ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY subscriber_date DESC LIMIT %d",
            intval( $limit )
        )
    );
}

/**
 * Get subscriber count
 */
function gni_get_subscriber_count() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
}

/**
 * Get active subscriber count
 */
function gni_get_active_subscriber_count() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->get_var(
        "SELECT COUNT(*) FROM $table_name WHERE subscriber_status = 'active'"
    );
}

/**
 * Get unsubscribed count
 */
function gni_get_unsubscribed_count() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->get_var(
        "SELECT COUNT(*) FROM $table_name WHERE subscriber_status = 'unsubscribed'"
    );
}

/**
 * Delete subscriber
 */
function gni_delete_subscriber( $subscriber_id ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->delete(
        $table_name,
        array( 'ID' => intval( $subscriber_id ) ),
        array( '%d' )
    );
}

/**
 * Mark subscriber as unsubscribed
 */
function gni_mark_unsubscribed( $subscriber_id ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gni_newsletter_subscribers';
    
    return $wpdb->update(
        $table_name,
        array( 'subscriber_status' => 'unsubscribed' ),
        array( 'ID' => intval( $subscriber_id ) ),
        array( '%s' ),
        array( '%d' )
    );
}

// Create table on theme activation
register_activation_hook( __FILE__, 'gni_create_newsletter_table' );

    }

    $subscribers = get_option( 'gni_subscribers', array() );
    $action      = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

    // Handle export
    if ( 'export' === $action && isset( $_GET['nonce'] ) && wp_verify_nonce( wp_unslash( $_GET['nonce'] ), 'gni_export_nonce' ) ) {
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=gni-subscribers.csv' );
        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'Email' ) );
        foreach ( $subscribers as $email ) {
            fputcsv( $output, array( $email ) );
        }
        fclose( $output );
        exit;
    }

    // Handle delete
    if ( 'delete' === $action && isset( $_GET['email'], $_GET['nonce'] ) && wp_verify_nonce( wp_unslash( $_GET['nonce'] ), 'gni_delete_nonce' ) ) {
        $email = sanitize_email( wp_unslash( $_GET['email'] ) );
        $key   = array_search( $email, $subscribers, true );
        if ( false !== $key ) {
            unset( $subscribers[ $key ] );
            update_option( 'gni_subscribers', $subscribers );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Subscriber deleted.', 'global-news-insights' ) . '</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Newsletter Subscribers', 'global-news-insights' ); ?></h1>
        <p><?php echo esc_html( count( $subscribers ) ) . ' ' . esc_html__( 'subscriber(s)', 'global-news-insights' ); ?></p>

        <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=gni-newsletter&action=export' ), 'gni_export_nonce' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Export as CSV', 'global-news-insights' ); ?></a>

        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Email', 'global-news-insights' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'global-news-insights' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $subscribers ) ) : ?>
                    <?php foreach ( $subscribers as $email ) : ?>
                        <tr>
                            <td><?php echo esc_html( $email ); ?></td>
                            <td>
                                <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=gni-newsletter&action=delete&email=' . urlencode( $email ) ), 'gni_delete_nonce' ) ); ?>" class="button button-small"><?php esc_html_e( 'Delete', 'global-news-insights' ); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="2"><?php esc_html_e( 'No subscribers yet.', 'global-news-insights' ); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

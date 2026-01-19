<?php
/**
 * Newsletter admin management page and functions
 * Allows admins to view, export, and manage newsletter subscribers.
 *
 * @package Global_News_Insights
 */

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
}
add_action( 'admin_menu', 'gni_newsletter_admin_menu' );

/**
 * Newsletter admin page callback
 */
function gni_newsletter_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
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

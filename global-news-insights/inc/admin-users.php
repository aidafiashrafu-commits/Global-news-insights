<?php
/**
 * Global News Insights - User & Role Management System
 * 
 * Enhanced WordPress user system with custom roles and capabilities
 * Administrator, Editor, Author, Contributor, Subscriber roles
 *
 * @package Global_News_Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register custom user roles and capabilities
 */
function gni_register_user_roles() {
    // Editor - Manage and publish all articles
    $editor_cap = array(
        'edit_posts' => true,
        'edit_published_posts' => true,
        'publish_posts' => true,
        'delete_posts' => true,
        'delete_published_posts' => true,
        'edit_others_posts' => true,
        'delete_others_posts' => true,
        'manage_categories' => true,
        'manage_post_tags' => true,
    );
    
    if ( ! get_role( 'gni_editor' ) ) {
        add_role(
            'gni_editor',
            __( 'News Editor', 'global-news-insights' ),
            $editor_cap
        );
    }
    
    // Author - Write and publish own articles
    $author_cap = array(
        'edit_posts' => true,
        'edit_published_posts' => true,
        'publish_posts' => true,
        'delete_posts' => true,
        'delete_published_posts' => true,
    );
    
    if ( ! get_role( 'gni_author' ) ) {
        add_role(
            'gni_author',
            __( 'News Author', 'global-news-insights' ),
            $author_cap
        );
    }
    
    // Contributor - Write articles but needs approval
    $contributor_cap = array(
        'edit_posts' => true,
    );
    
    if ( ! get_role( 'gni_contributor' ) ) {
        add_role(
            'gni_contributor',
            __( 'News Contributor', 'global-news-insights' ),
            $contributor_cap
        );
    }
}
add_action( 'init', 'gni_register_user_roles' );

/**
 * Register user management admin menu
 */
function gni_register_user_management_menu() {
    add_menu_page(
        __( 'User Management', 'global-news-insights' ),
        '👥 User Management',
        'manage_options',
        'gni-user-management',
        'gni_user_management_page',
        'dashicons-admin-users',
        6
    );
    
    add_submenu_page(
        'gni-user-management',
        __( 'Add User', 'global-news-insights' ),
        'Add New User',
        'manage_options',
        'gni-add-user',
        'gni_add_user_page'
    );
    
    add_submenu_page(
        'gni-user-management',
        __( 'User Roles', 'global-news-insights' ),
        'User Roles',
        'manage_options',
        'gni-user-roles',
        'gni_user_roles_page'
    );
}
add_action( 'admin_menu', 'gni_register_user_management_menu' );

/**
 * Main user management page
 */
function gni_user_management_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    // Handle user actions
    if ( isset( $_POST['action'] ) && check_admin_referer( 'gni_user_action_nonce' ) ) {
        $action = sanitize_text_field( $_POST['action'] );
        $user_id = intval( $_POST['user_id'] ?? 0 );
        
        if ( $action === 'update_role' && $user_id ) {
            $new_role = sanitize_text_field( $_POST['role'] ?? 'subscriber' );
            $user = new WP_User( $user_id );
            $user->set_role( $new_role );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'User role updated!', 'global-news-insights' ) . '</p></div>';
        }
        
        if ( $action === 'disable_user' && $user_id ) {
            update_user_meta( $user_id, 'gni_user_disabled', '1' );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'User disabled!', 'global-news-insights' ) . '</p></div>';
        }
        
        if ( $action === 'enable_user' && $user_id ) {
            delete_user_meta( $user_id, 'gni_user_disabled' );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'User enabled!', 'global-news-insights' ) . '</p></div>';
        }
    }
    
    // Get all users
    $users = get_users( array( 'orderby' => 'registered', 'order' => 'DESC' ) );
    ?>
    <div class="wrap">
        <div class="gni-dashboard-header">
            <h1>👥 User Management</h1>
            <p><?php esc_html_e( 'Manage team members and their roles', 'global-news-insights' ); ?></p>
        </div>
        
        <div style="margin-bottom: 20px;">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-add-user' ) ); ?>" class="button button-primary">
                ➕ <?php esc_html_e( 'Add New User', 'global-news-insights' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=gni-user-roles' ) ); ?>" class="button">
                🔐 <?php esc_html_e( 'Manage Roles', 'global-news-insights' ); ?>
            </a>
        </div>
        
        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'User', 'global-news-insights' ); ?></th>
                    <th style="width: 150px;"><?php esc_html_e( 'Email', 'global-news-insights' ); ?></th>
                    <th style="width: 120px;"><?php esc_html_e( 'Role', 'global-news-insights' ); ?></th>
                    <th style="width: 100px;"><?php esc_html_e( 'Status', 'global-news-insights' ); ?></th>
                    <th style="width: 150px;"><?php esc_html_e( 'Registered', 'global-news-insights' ); ?></th>
                    <th style="width: 200px;"><?php esc_html_e( 'Actions', 'global-news-insights' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $users as $user ) {
                    $user_role = isset( $user->roles[0] ) ? $user->roles[0] : 'none';
                    $is_disabled = get_user_meta( $user->ID, 'gni_user_disabled', true );
                    $role_label = ucfirst( str_replace( '_', ' ', $user_role ) );
                    ?>
                    <tr style="<?php echo $is_disabled ? 'opacity: 0.6;' : ''; ?>">
                        <td>
                            <strong><?php echo esc_html( $user->display_name ); ?></strong>
                            <br>
                            <small style="color: var(--gni-text-secondary);">@<?php echo esc_html( $user->user_login ); ?></small>
                        </td>
                        <td><?php echo esc_html( $user->user_email ); ?></td>
                        <td>
                            <span class="gni-badge" style="background-color: var(--gni-accent); color: white;">
                                <?php echo esc_html( $role_label ); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ( $is_disabled ) : ?>
                                <span class="gni-badge" style="background-color: var(--gni-error); color: white;">
                                    🔒 Disabled
                                </span>
                            <?php else : ?>
                                <span class="gni-badge" style="background-color: var(--gni-success); color: white;">
                                    ✅ Active
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html( mysql2date( get_option( 'date_format' ), $user->user_registered ) ); ?></td>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $user->ID ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'Edit', 'global-news-insights' ); ?>
                            </a>
                            
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field( 'gni_user_action_nonce' ); ?>
                                <input type="hidden" name="user_id" value="<?php echo intval( $user->ID ); ?>" />
                                
                                <?php if ( $is_disabled ) : ?>
                                    <input type="hidden" name="action" value="enable_user" />
                                    <input type="submit" class="button button-small" value="<?php esc_attr_e( 'Enable', 'global-news-insights' ); ?>" />
                                <?php else : ?>
                                    <input type="hidden" name="action" value="disable_user" />
                                    <input type="submit" class="button button-small" value="<?php esc_attr_e( 'Disable', 'global-news-insights' ); ?>" />
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Add new user page
 */
function gni_add_user_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    $message = '';
    
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'gni_add_user' && check_admin_referer( 'gni_add_user_nonce' ) ) {
        $username = sanitize_user( $_POST['user_login'] ?? '' );
        $email = sanitize_email( $_POST['user_email'] ?? '' );
        $password = $_POST['user_pass'] ?? '';
        $role = sanitize_text_field( $_POST['role'] ?? 'subscriber' );
        
        if ( ! $username || ! $email || ! $password ) {
            $message = '<div class="notice notice-error"><p>' . esc_html__( 'All fields are required.', 'global-news-insights' ) . '</p></div>';
        } else {
            $user_id = wp_create_user( $username, $password, $email );
            
            if ( is_wp_error( $user_id ) ) {
                $message = '<div class="notice notice-error"><p>' . esc_html( $user_id->get_error_message() ) . '</p></div>';
            } else {
                $user = new WP_User( $user_id );
                $user->set_role( $role );
                $message = '<div class="notice notice-success"><p>' . esc_html__( 'User created successfully!', 'global-news-insights' ) . '</p></div>';
            }
        }
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Add New User', 'global-news-insights' ); ?></h1>
        
        <?php echo wp_kses_post( $message ); ?>
        
        <form method="post" action="" style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; max-width: 500px;">
            <?php wp_nonce_field( 'gni_add_user_nonce' ); ?>
            <input type="hidden" name="action" value="gni_add_user" />
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="user_login"><?php esc_html_e( 'Username', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="text" id="user_login" name="user_login" class="regular-text" required />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="user_email"><?php esc_html_e( 'Email', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="email" id="user_email" name="user_email" class="regular-text" required />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="user_pass"><?php esc_html_e( 'Password', 'global-news-insights' ); ?></label></th>
                    <td>
                        <input type="password" id="user_pass" name="user_pass" class="regular-text" required />
                        <p class="description"><?php esc_html_e( 'Minimum 8 characters', 'global-news-insights' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="role"><?php esc_html_e( 'Role', 'global-news-insights' ); ?></label></th>
                    <td>
                        <select id="role" name="role">
                            <option value="subscriber"><?php esc_html_e( 'Subscriber', 'global-news-insights' ); ?></option>
                            <option value="gni_contributor"><?php esc_html_e( 'Contributor', 'global-news-insights' ); ?></option>
                            <option value="gni_author"><?php esc_html_e( 'News Author', 'global-news-insights' ); ?></option>
                            <option value="gni_editor"><?php esc_html_e( 'News Editor', 'global-news-insights' ); ?></option>
                            <option value="administrator"><?php esc_html_e( 'Administrator', 'global-news-insights' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <p>
                <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Create User', 'global-news-insights' ); ?>" />
            </p>
        </form>
    </div>
    <?php
}

/**
 * User roles management page
 */
function gni_user_roles_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Access denied.', 'global-news-insights' ) );
    }
    
    global $wp_roles;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'User Roles & Capabilities', 'global-news-insights' ); ?></h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <!-- Administrator -->
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; border-left: 4px solid var(--gni-breaking);">
                <h3>👑 Administrator</h3>
                <p><strong><?php esc_html_e( 'Full Control', 'global-news-insights' ); ?></strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    <li>✅ <?php esc_html_e( 'Manage all content', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Manage users', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Manage plugins', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Manage settings', 'global-news-insights' ); ?></li>
                </ul>
            </div>
            
            <!-- News Editor -->
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; border-left: 4px solid var(--gni-accent);">
                <h3>📰 News Editor</h3>
                <p><strong><?php esc_html_e( 'Content Management', 'global-news-insights' ); ?></strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    <li>✅ <?php esc_html_e( 'Edit all articles', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Publish articles', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Manage categories', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Moderate comments', 'global-news-insights' ); ?></li>
                </ul>
            </div>
            
            <!-- News Author -->
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; border-left: 4px solid var(--gni-success);">
                <h3>✍️ News Author</h3>
                <p><strong><?php esc_html_e( 'Own Content Only', 'global-news-insights' ); ?></strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    <li>✅ <?php esc_html_e( 'Write articles', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Edit own articles', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Publish articles', 'global-news-insights' ); ?></li>
                    <li>❌ <?php esc_html_e( 'Edit others\' articles', 'global-news-insights' ); ?></li>
                </ul>
            </div>
            
            <!-- Contributor -->
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; border-left: 4px solid var(--gni-warning);">
                <h3>📝 Contributor</h3>
                <p><strong><?php esc_html_e( 'Limited Access', 'global-news-insights' ); ?></strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    <li>✅ <?php esc_html_e( 'Write articles', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Save as draft', 'global-news-insights' ); ?></li>
                    <li>❌ <?php esc_html_e( 'Publish articles', 'global-news-insights' ); ?></li>
                    <li>❌ <?php esc_html_e( 'Edit published', 'global-news-insights' ); ?></li>
                </ul>
            </div>
            
            <!-- Subscriber -->
            <div style="background-color: var(--gni-dark-secondary); padding: 20px; border-radius: 4px; border-left: 4px solid #999;">
                <h3>👤 Subscriber</h3>
                <p><strong><?php esc_html_e( 'Read Only', 'global-news-insights' ); ?></strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    <li>✅ <?php esc_html_e( 'Read content', 'global-news-insights' ); ?></li>
                    <li>✅ <?php esc_html_e( 'Comment', 'global-news-insights' ); ?></li>
                    <li>❌ <?php esc_html_e( 'Create content', 'global-news-insights' ); ?></li>
                    <li>❌ <?php esc_html_e( 'Manage anything', 'global-news-insights' ); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

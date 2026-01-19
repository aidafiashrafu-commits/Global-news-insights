<?php
/**
 * Template: Custom Login / Registration
 * Use shortcode [gni_login] or apply page template.
 */
/* Template Name: GNI Login */
get_header();
?>
<main class="container gni-login">
    <h1><?php esc_html_e( 'Login or Register', 'global-news-insights' ); ?></h1>
    <div class="login-forms">
        <div class="login-box">
            <?php wp_login_form(); ?>
        </div>
        <div class="register-box">
            <?php if ( ! is_user_logged_in() ) : ?>
                <h2><?php esc_html_e( 'Register', 'global-news-insights' ); ?></h2>
                <form method="post" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>">
                    <p><label><?php esc_html_e( 'Username', 'global-news-insights' ); ?> <input name="user_login" required></label></p>
                    <p><label><?php esc_html_e( 'Email', 'global-news-insights' ); ?> <input name="user_email" type="email" required></label></p>
                    <p><input type="submit" value="<?php esc_attr_e( 'Register', 'global-news-insights' ); ?>"></p>
                </form>
            <?php else : echo '<p>'.esc_html__( 'You are already logged in.', 'global-news-insights' ).'</p>'; endif; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>

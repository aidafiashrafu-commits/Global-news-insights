    <footer class="gni-footer" style="background-color: <?php echo esc_attr( get_theme_mod( 'gni_footer_background', '#202124' ) ); ?>">
        <div class="container footer-widgets">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : 
                dynamic_sidebar( 'footer-1' ); 
            else : ?>
                <div class="footer-col">
                    <h3><?php esc_html_e( 'About Us', 'global-news-insights' ); ?></h3>
                    <p><?php bloginfo( 'description' ); ?></p>
                </div>
                <div class="footer-col">
                    <h3><?php esc_html_e( 'Quick Links', 'global-news-insights' ); ?></h3>
                    <?php wp_nav_menu( array( 
                        'theme_location' => 'footer',
                        'fallback_cb'    => function() {
                            echo '<ul>';
                            echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'global-news-insights' ) . '</a></li>';
                            echo '<li><a href="#">' . esc_html__( 'Contact Us', 'global-news-insights' ) . '</a></li>';
                            echo '<li><a href="#">' . esc_html__( 'Privacy Policy', 'global-news-insights' ) . '</a></li>';
                            echo '<li><a href="#">' . esc_html__( 'Terms & Conditions', 'global-news-insights' ) . '</a></li>';
                            echo '</ul>';
                        }
                    ) ); ?>
                </div>
                <div class="footer-col">
                    <h3><?php esc_html_e( 'Follow Us', 'global-news-insights' ); ?></h3>
                    <ul class="social-links">
                        <?php 
                        $facebook = get_theme_mod( 'gni_facebook' );
                        $twitter = get_theme_mod( 'gni_twitter' );
                        $tiktok = get_theme_mod( 'gni_tiktok' );
                        $instagram = get_theme_mod( 'gni_instagram' );
                        $youtube = get_theme_mod( 'gni_youtube' );
                        
                        if ( $facebook ) : ?>
                            <li><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Facebook', 'global-news-insights' ); ?></a></li>
                        <?php endif;
                        
                        if ( $twitter ) : ?>
                            <li><a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Twitter', 'global-news-insights' ); ?></a></li>
                        <?php endif;
                        
                        if ( $tiktok ) : ?>
                            <li><a href="<?php echo esc_url( $tiktok ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'TikTok', 'global-news-insights' ); ?></a></li>
                        <?php endif;
                        
                        if ( $instagram ) : ?>
                            <li><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Instagram', 'global-news-insights' ); ?></a></li>
                        <?php endif;
                        
                        if ( $youtube ) : ?>
                            <li><a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'YouTube', 'global-news-insights' ); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <div class="container footer-meta">
            <div class="footer-bottom">
                <?php 
                $footer_text = get_theme_mod( 'gni_footer_text', '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' );
                echo wp_kses_post( $footer_text );
                ?>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Chat Button -->
    <?php if ( get_theme_mod( 'gni_whatsapp' ) ) : ?>
    <div class="gni-whatsapp-float" id="whatsappChat">
        <a href="https://wa.me/<?php echo esc_attr( str_replace( array( '+', '-', ' ', '(', ')' ), '', get_theme_mod( 'gni_whatsapp' ) ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Chat on WhatsApp', 'global-news-insights' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="white" d="M23.5 8.5c-1.3-2.4-3.7-3.9-6.3-3.9C12.3 4.6 9.6 7.3 9.6 10.8c0 1.2.3 2.3.8 3.4l.1.2c.1.3-.1.8-.4 1.1l-.8 1.5c-.3.6.1 1.4.8 1.4.2 0 .5 0 .7-.1 2.1-.6 4.1-1.5 5.9-2.9l.2-.1c1.1.3 2.2.4 3.4.4 3.5 0 6.2-2.7 6.2-6.1 0-1.6-.6-3.1-1.7-4.3zM16 22c-3.3 0-6.5-1.3-8.8-3.7l-2.9.9c-.6.2-1.3-.3-1.1-1l1.1-3.2c-.5-1.4-.8-2.9-.8-4.5 0-5.3 4.3-9.6 9.6-9.6 2.6 0 5 1.1 6.8 2.9 1.8 1.9 2.8 4.3 2.8 6.8 0 5.3-4.3 9.6-9.6 9.6z"/></svg>
        </a>
    </div>
    <?php endif; ?>

    <?php wp_footer(); ?>
</body>
</html>

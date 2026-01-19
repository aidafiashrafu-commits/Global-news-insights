<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'gni_primary_color', '#1a73e8' ) ); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="gni-header <?php echo get_theme_mod( 'gni_header_sticky' ) ? 'sticky-header' : ''; ?>">
        <div class="container header-top">
            <div class="branding">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1><?php bloginfo( 'name' ); ?></h1>
                        <?php if ( get_theme_mod( 'gni_show_tagline', 1 ) ) : ?>
                            <p class="tagline"><?php bloginfo( 'description' ); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </a>
            </div>
            <div class="header-right">
                <?php if ( is_active_sidebar( 'header-ads' ) ) : 
                    dynamic_sidebar( 'header-ads' ); 
                else : ?>
                    <div class="ad-slot">
                        <!-- Google AdSense Header Slot -->
                        <!-- Paste your AdSense code here -->
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <nav class="gni-nav">
            <div class="container">
                <?php wp_nav_menu( array( 
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'fallback_cb'    => 'wp_page_menu',
                ) ); ?>
                <div class="social-links">
                    <?php 
                    $facebook = get_theme_mod( 'gni_facebook' );
                    $twitter = get_theme_mod( 'gni_twitter' );
                    $tiktok = get_theme_mod( 'gni_tiktok' );
                    $instagram = get_theme_mod( 'gni_instagram' );
                    $youtube = get_theme_mod( 'gni_youtube' );
                    $whatsapp = get_theme_mod( 'gni_whatsapp' );
                    
                    if ( $facebook ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Facebook', 'global-news-insights' ); ?>">
                            <span class="icon-facebook"></span> Facebook
                        </a>
                    <?php endif;
                    
                    if ( $twitter ) : ?>
                        <a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Twitter', 'global-news-insights' ); ?>">
                            <span class="icon-twitter"></span> Twitter
                        </a>
                    <?php endif;
                    
                    if ( $tiktok ) : ?>
                        <a href="<?php echo esc_url( $tiktok ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'TikTok', 'global-news-insights' ); ?>">
                            <span class="icon-tiktok"></span> TikTok
                        </a>
                    <?php endif;
                    
                    if ( $instagram ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Instagram', 'global-news-insights' ); ?>">
                            <span class="icon-instagram"></span> Instagram
                        </a>
                    <?php endif;
                    
                    if ( $youtube ) : ?>
                        <a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'YouTube', 'global-news-insights' ); ?>">
                            <span class="icon-youtube"></span> YouTube
                        </a>
                    <?php endif;
                    
                    if ( $whatsapp ) : ?>
                        <a href="https://wa.me/<?php echo esc_attr( str_replace( array( '+', '-', ' ', '(' , ')' ), '', $whatsapp ) ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'WhatsApp', 'global-news-insights' ); ?>">
                            <span class="icon-whatsapp"></span> WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <?php // Breaking News Ticker
        $breaking_posts = gni_get_breaking_posts( 10 );
        if ( ! empty( $breaking_posts ) ) : ?>
        <div class="breaking-ticker">
            <div class="container">
                <strong class="label"><?php esc_html_e( 'Breaking', 'global-news-insights' ); ?></strong>
                <div class="ticker-wrap">
                    <ul class="ticker">
                        <?php foreach ( $breaking_posts as $post ) : ?>
                            <li><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( $post->post_title ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </header>

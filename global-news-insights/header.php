<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
    <header class="gni-header">
        <div class="container header-top">
            <div class="branding">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
                    <?php if ( get_theme_mod( 'gni_logo' ) ) : ?>
                        <img src="<?php echo esc_url( get_theme_mod( 'gni_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                    <?php else : ?>
                        <h1><?php bloginfo( 'name' ); ?></h1>
                        <p class="tagline"><?php bloginfo( 'description' ); ?></p>
                    <?php endif; ?>
                </a>
            </div>
            <div class="header-right">
                <?php if ( is_active_sidebar( 'header-ads' ) ) : dynamic_sidebar( 'header-ads' ); else : ?>
                    <div class="ad-slot">Header ad slot (widget area) - paste AdSense code here</div>
                <?php endif; ?>
            </div>
        </div>

        <nav class="gni-nav">
            <div class="container">
                <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'primary-menu' ) ); ?>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/1DAqbgWgGS/" target="_blank" rel="noopener">Facebook</a>
                    <a href="https://www.tiktok.com/@music.lovers395?_r=1&_t=ZM-93BYcRAiqOB" target="_blank" rel="noopener">TikTok</a>
                    <a href="https://wa.me/255717007449" target="_blank" rel="noopener">WhatsApp</a>
                    <a href="mailto:lingendea@gmail.com">Email</a>
                </div>
            </div>
        </nav>

        <?php // Breaking ticker
        $breaking = new WP_Query( array( 'meta_key' => '_gni_breaking', 'meta_value' => '1', 'posts_per_page' => 10 ) );
        if ( $breaking->have_posts() ) : ?>
        <div class="breaking-ticker">
            <div class="container">
                <strong class="label">Breaking</strong>
                <div class="ticker-wrap">
                    <ul class="ticker">
                        <?php while ( $breaking->have_posts() ) : $breaking->the_post(); ?>
                            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </header>

<?php
/**
 * Global News Insights - Theme Customizer
 * Add custom logo, colors, fonts, header, footer options
 *
 * @package Global_News_Insights
 */

function gni_customize_register( $wp_customize ) {
    // ===== BRANDING SECTION =====
    $wp_customize->add_section( 'gni_branding', array(
        'title'       => esc_html__( 'Branding & Logo', 'global-news-insights' ),
        'priority'    => 20,
        'description' => esc_html__( 'Customize your site logo and tagline', 'global-news-insights' ),
    ) );

    // Logo
    $wp_customize->add_setting( 'gni_logo', array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gni_logo', array(
        'label'       => esc_html__( 'Logo', 'global-news-insights' ),
        'section'     => 'gni_branding',
        'settings'    => 'gni_logo',
    ) ) );

    // Site tagline visibility
    $wp_customize->add_setting( 'gni_show_tagline', array(
        'default'             => 1,
        'sanitize_callback'   => 'rest_sanitize_boolean',
    ) );
    $wp_customize->add_control( 'gni_show_tagline', array(
        'label'       => esc_html__( 'Show Tagline', 'global-news-insights' ),
        'section'     => 'gni_branding',
        'type'        => 'checkbox',
    ) );

    // ===== COLORS SECTION =====
    $wp_customize->add_section( 'gni_colors', array(
        'title'    => esc_html__( 'Colors', 'global-news-insights' ),
        'priority' => 21,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'gni_primary_color', array(
        'default'           => '#1a73e8',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_primary_color', array(
        'label'       => esc_html__( 'Primary Color', 'global-news-insights' ),
        'section'     => 'gni_colors',
        'settings'    => 'gni_primary_color',
    ) ) );

    // Breaking News Color
    $wp_customize->add_setting( 'gni_breaking_color', array(
        'default'           => '#dc3545',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_breaking_color', array(
        'label'       => esc_html__( 'Breaking News Color', 'global-news-insights' ),
        'section'     => 'gni_colors',
        'settings'    => 'gni_breaking_color',
    ) ) );

    // Background Color
    $wp_customize->add_setting( 'gni_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_bg_color', array(
        'label'       => esc_html__( 'Background Color', 'global-news-insights' ),
        'section'     => 'gni_colors',
        'settings'    => 'gni_bg_color',
    ) ) );

    // ===== TYPOGRAPHY SECTION =====
    $wp_customize->add_section( 'gni_typography', array(
        'title'    => esc_html__( 'Typography', 'global-news-insights' ),
        'priority' => 22,
    ) );

    $wp_customize->add_setting( 'gni_font_family', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gni_font_family', array(
        'label'       => esc_html__( 'Font Family', 'global-news-insights' ),
        'section'     => 'gni_typography',
        'type'        => 'select',
        'choices'     => array(
            'Inter'          => 'Inter',
            'Roboto'         => 'Roboto',
            'Open Sans'      => 'Open Sans',
            'Playfair Display' => 'Playfair Display',
        ),
    ) );

    // ===== HEADER SECTION =====
    $wp_customize->add_section( 'gni_header', array(
        'title'    => esc_html__( 'Header & Navigation', 'global-news-insights' ),
        'priority' => 23,
    ) );

    $wp_customize->add_setting( 'gni_header_background', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_header_background', array(
        'label'    => esc_html__( 'Header Background', 'global-news-insights' ),
        'section'  => 'gni_header',
        'settings' => 'gni_header_background',
    ) ) );

    $wp_customize->add_setting( 'gni_header_sticky', array(
        'default'             => 1,
        'sanitize_callback'   => 'rest_sanitize_boolean',
    ) );
    $wp_customize->add_control( 'gni_header_sticky', array(
        'label'       => esc_html__( 'Sticky Header', 'global-news-insights' ),
        'section'     => 'gni_header',
        'type'        => 'checkbox',
    ) );

    // ===== FOOTER SECTION =====
    $wp_customize->add_section( 'gni_footer', array(
        'title'    => esc_html__( 'Footer', 'global-news-insights' ),
        'priority' => 24,
    ) );

    $wp_customize->add_setting( 'gni_footer_text', array(
        'default'             => esc_html__( '&copy; 2024 Global News Insights. All rights reserved.', 'global-news-insights' ),
        'sanitize_callback'   => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'gni_footer_text', array(
        'label'       => esc_html__( 'Footer Copyright Text', 'global-news-insights' ),
        'section'     => 'gni_footer',
        'type'        => 'textarea',
    ) );

    $wp_customize->add_setting( 'gni_footer_background', array(
        'default'           => '#202124',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_footer_background', array(
        'label'    => esc_html__( 'Footer Background', 'global-news-insights' ),
        'section'  => 'gni_footer',
        'settings' => 'gni_footer_background',
    ) ) );

    // ===== SOCIAL MEDIA SECTION =====
    $wp_customize->add_section( 'gni_social', array(
        'title'    => esc_html__( 'Social Media', 'global-news-insights' ),
        'priority' => 25,
    ) );

    $social_platforms = array(
        'facebook'  => 'Facebook URL',
        'twitter'   => 'Twitter URL',
        'tiktok'    => 'TikTok URL',
        'whatsapp'  => 'WhatsApp Number',
        'instagram' => 'Instagram URL',
        'youtube'   => 'YouTube URL',
    );

    foreach ( $social_platforms as $platform => $label ) {
        $wp_customize->add_setting( "gni_{$platform}", array(
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "gni_{$platform}", array(
            'label'   => esc_html( $label ),
            'section' => 'gni_social',
            'type'    => 'url',
        ) );
    }

    // ===== ADS SECTION =====
    $wp_customize->add_section( 'gni_ads', array(
        'title'    => esc_html__( 'Google AdSense', 'global-news-insights' ),
        'priority' => 26,
    ) );

    $wp_customize->add_setting( 'gni_adsense_id', array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gni_adsense_id', array(
        'label'   => esc_html__( 'AdSense Publisher ID', 'global-news-insights' ),
        'section' => 'gni_ads',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'gni_show_ads', array(
        'default'             => 1,
        'sanitize_callback'   => 'rest_sanitize_boolean',
    ) );
    $wp_customize->add_control( 'gni_show_ads', array(
        'label'       => esc_html__( 'Enable Ads', 'global-news-insights' ),
        'section'     => 'gni_ads',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'gni_customize_register' );

/**
 * Output customizer CSS
 * Applies customizer settings to frontend
 */
function gni_customizer_css() {
    $primary_color = get_theme_mod( 'gni_primary_color', '#1a73e8' );
    $breaking_color = get_theme_mod( 'gni_breaking_color', '#dc3545' );
    $bg_color = get_theme_mod( 'gni_bg_color', '#ffffff' );
    $header_bg = get_theme_mod( 'gni_header_background', '#ffffff' );
    $footer_bg = get_theme_mod( 'gni_footer_background', '#202124' );
    $font_family = get_theme_mod( 'gni_font_family', 'Inter' );
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo sanitize_hex_color( $primary_color ); ?>;
            --breaking-color: <?php echo sanitize_hex_color( $breaking_color ); ?>;
            --bg-color: <?php echo sanitize_hex_color( $bg_color ); ?>;
            --header-bg: <?php echo sanitize_hex_color( $header_bg ); ?>;
            --footer-bg: <?php echo sanitize_hex_color( $footer_bg ); ?>;
            --font-family: <?php echo esc_html( $font_family ); ?>;
        }
        
        body {
            background-color: var(--bg-color);
        }
        
        .gni-header {
            background-color: var(--header-bg);
        }
        
        .gni-footer {
            background-color: var(--footer-bg);
        }
        
        a, .primary-color {
            color: var(--primary-color);
        }
        
        .breaking-ticker .label, .tag {
            background-color: var(--breaking-color);
        }
    </style>
    <?php
}
add_action( 'wp_head', 'gni_customizer_css', 20 );

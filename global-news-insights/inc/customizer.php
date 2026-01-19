<?php
/**
 * Theme Customizer settings
 */

function gni_customize_register( $wp_customize ) {
    // Logo
    $wp_customize->add_section( 'gni_branding', array(
        'title'    => __( 'Branding', 'global-news-insights' ),
        'priority' => 20,
    ) );

    $wp_customize->add_setting( 'gni_logo' );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gni_logo', array(
        'label'    => __( 'Logo', 'global-news-insights' ),
        'section'  => 'gni_branding',
        'settings' => 'gni_logo',
    ) ) );

    // Colors
    $wp_customize->add_section( 'gni_colors', array(
        'title'    => __( 'Colors', 'global-news-insights' ),
        'priority' => 21,
    ) );
    $wp_customize->add_setting( 'gni_primary_color', array( 'default' => '#cc0000' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gni_primary_color', array(
        'label'    => __( 'Primary (Accent) Color', 'global-news-insights' ),
        'section'  => 'gni_colors',
        'settings' => 'gni_primary_color',
    ) ) );

    // Fonts
    $wp_customize->add_section( 'gni_fonts', array('title' => __( 'Fonts', 'global-news-insights' ), 'priority' => 22) );
    $wp_customize->add_setting( 'gni_body_font', array( 'default' => 'Inter, sans-serif' ) );
    $wp_customize->add_control( 'gni_body_font', array( 'label' => __( 'Body font stack', 'global-news-insights' ), 'section' => 'gni_fonts', 'type' => 'text' ) );
}
add_action( 'customize_register', 'gni_customize_register' );

<?php
/**
 * Widgets for Global News Insights
 */

// Breaking News widget
class GNI_Breaking_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'gni_breaking', __( 'Breaking News', 'global-news-insights' ), array( 'description' => __( 'Show latest breaking posts', 'global-news-insights' ) ) );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo '<div class="gni-breaking">';
        $q = new WP_Query( array( 'meta_key' => '_gni_breaking', 'meta_value' => '1', 'posts_per_page' => 5 ) );
        if ( $q->have_posts() ) {
            echo '<ul>';
            while ( $q->have_posts() ) { $q->the_post();
                echo '<li><a href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>'.esc_html__( 'No breaking news at the moment.', 'global-news-insights' ).'</p>';
        }
        wp_reset_postdata();
        echo '</div>';
        echo $args['after_widget'];
    }
    public function form( $instance ) { echo '<p>'.esc_html__( 'No settings for this widget.', 'global-news-insights' ).'</p>'; }
}
function gni_register_widgets() {
    register_widget( 'GNI_Breaking_Widget' );
}
add_action( 'widgets_init', 'gni_register_widgets' );

// Additional widget classes (Trending, Newsletter) could be added similarly

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
    register_widget( 'GNI_Trending_Widget' );
    register_widget( 'GNI_Newsletter_Widget' );
}
add_action( 'widgets_init', 'gni_register_widgets' );

// Trending widget
class GNI_Trending_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'gni_trending', __( 'Trending Stories', 'global-news-insights' ), array( 'description' => __( 'Show most commented or popular posts', 'global-news-insights' ) ) );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo '<div class="gni-trending">';
        $q = new WP_Query( array( 'posts_per_page' => 5, 'orderby' => 'comment_count' ) );
        if ( $q->have_posts() ) {
            echo '<ul>';
            while ( $q->have_posts() ) { $q->the_post();
                echo '<li><a href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>'.esc_html__( 'No trending stories.', 'global-news-insights' ).'</p>';
        }
        wp_reset_postdata();
        echo '</div>';
        echo $args['after_widget'];
    }
    public function form( $instance ) { echo '<p>'.esc_html__( 'No settings for Trending widget.', 'global-news-insights' ).'</p>'; }
}

// Newsletter widget
class GNI_Newsletter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'gni_newsletter', __( 'Newsletter Signup', 'global-news-insights' ), array( 'description' => __( 'Collect emails for newsletter', 'global-news-insights' ) ) );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo '<div class="gni-newsletter">';
        echo '<p>'.esc_html__( 'Subscribe to our newsletter for daily insights.', 'global-news-insights' ).'</p>';
        ?>
        <form class="gni-newsletter-form" method="post" onsubmit="event.preventDefault(); gniSubscribe(this);">
            <input type="email" name="email" placeholder="you@example.com" required />
            <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'gni_subscribe_nonce' ) ); ?>" />
            <button type="submit"><?php esc_html_e( 'Subscribe', 'global-news-insights' ); ?></button>
            <div class="gni-newsletter-msg" aria-live="polite"></div>
        </form>
        <script>
        function gniSubscribe(form){
            var data = new FormData(form);
            data.append('action','gni_subscribe');
            fetch( gni_vars.ajax_url, { method: 'POST', credentials: 'same-origin', body: data } ).then(function(r){return r.json();}).then(function(j){
                var msg = form.querySelector('.gni-newsletter-msg');
                if(j.success){ msg.innerText = j.data.message || 'Subscribed'; } else { msg.innerText = j.data.message || 'Error'; }
            });
        }
        </script>
        <?php
        echo '</div>';
        echo $args['after_widget'];
    }
    public function form( $instance ) { echo '<p>'.esc_html__( 'No settings for Newsletter widget.', 'global-news-insights' ).'</p>'; }
}

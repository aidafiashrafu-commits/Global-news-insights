<?php
/**
 * Global News Insights - Custom Widgets
 * Breaking News, Trending, Newsletter signup widgets
 *
 * @package Global_News_Insights
 */

/**
 * Breaking News Widget
 * Displays latest posts marked as breaking news
 */
class GNI_Breaking_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'gni_breaking',
            esc_html__( 'Breaking News', 'global-news-insights' ),
            array( 'description' => esc_html__( 'Show latest breaking posts', 'global-news-insights' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Breaking News', 'global-news-insights' );
        $count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;

        echo wp_kses_post( $args['before_widget'] );
        echo wp_kses_post( $args['before_title'] . esc_html( $title ) . $args['after_title'] );

        $breaking_posts = gni_get_breaking_posts( $count );
        
        if ( ! empty( $breaking_posts ) ) {
            echo '<ul class="gni-breaking-list">';
            foreach ( $breaking_posts as $post ) {
                echo '<li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $post->post_title ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No breaking news at the moment.', 'global-news-insights' ) . '</p>';
        }

        echo wp_kses_post( $args['after_widget'] );
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', 'global-news-insights' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php esc_html_e( 'Number of posts:', 'global-news-insights' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? (int) $new_instance['count'] : 5;
        return $instance;
    }
}

/**
 * Trending Stories Widget
 * Displays trending posts by view count
 */
class GNI_Trending_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'gni_trending',
            esc_html__( 'Trending Stories', 'global-news-insights' ),
            array( 'description' => esc_html__( 'Show trending posts by views', 'global-news-insights' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Trending', 'global-news-insights' );
        $count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;
        $days = ! empty( $instance['days'] ) ? (int) $instance['days'] : 7;

        echo wp_kses_post( $args['before_widget'] );
        echo wp_kses_post( $args['before_title'] . esc_html( $title ) . $args['after_title'] );

        $trending = gni_get_trending_posts( $count, $days );
        
        if ( ! empty( $trending ) ) {
            echo '<ol class="gni-trending-list">';
            foreach ( $trending as $post ) {
                $views = (int) get_post_meta( $post->ID, '_gni_post_views', true );
                echo '<li>';
                echo '<a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $post->post_title ) . '</a>';
                echo '<small> (' . esc_html( $views ) . ' ' . esc_html__( 'views', 'global-news-insights' ) . ')</small>';
                echo '</li>';
            }
            echo '</ol>';
        } else {
            echo '<p>' . esc_html__( 'No trending posts yet.', 'global-news-insights' ) . '</p>';
        }

        echo wp_kses_post( $args['after_widget'] );
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;
        $days = ! empty( $instance['days'] ) ? (int) $instance['days'] : 7;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', 'global-news-insights' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php esc_html_e( 'Number of posts:', 'global-news-insights' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'days' ) ); ?>">
                <?php esc_html_e( 'Days back:', 'global-news-insights' ); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'days' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'days' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $days ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? (int) $new_instance['count'] : 5;
        $instance['days'] = ! empty( $new_instance['days'] ) ? (int) $new_instance['days'] : 7;
        return $instance;
    }
}

/**
 * Newsletter Signup Widget
 * Allows readers to subscribe to newsletter
 */
class GNI_Newsletter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'gni_newsletter',
            esc_html__( 'Newsletter Signup', 'global-news-insights' ),
            array( 'description' => esc_html__( 'Newsletter signup form', 'global-news-insights' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Newsletter', 'global-news-insights' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'Subscribe for latest news', 'global-news-insights' );

        echo wp_kses_post( $args['before_widget'] );
        echo wp_kses_post( $args['before_title'] . esc_html( $title ) . $args['after_title'] );
        ?>
        <div class="gni-newsletter-widget">
            <p><?php echo esc_html( $description ); ?></p>
            <form class="newsletter-form" action="#" method="POST">
                <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e( 'Your email...', 'global-news-insights' ); ?>" required />
                <button type="submit" class="btn-subscribe"><?php esc_html_e( 'Subscribe', 'global-news-insights' ); ?></button>
            </form>
        </div>
        <?php
        echo wp_kses_post( $args['after_widget'] );
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', 'global-news-insights' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
                <?php esc_html_e( 'Description:', 'global-news-insights' ); ?>
            </label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['description'] = ! empty( $new_instance['description'] ) ? sanitize_textarea_field( $new_instance['description'] ) : '';
        return $instance;
    }
}

/**
 * Register custom widgets
 */
function gni_register_widgets() {
    register_widget( 'GNI_Breaking_Widget' );
    register_widget( 'GNI_Trending_Widget' );
    register_widget( 'GNI_Newsletter_Widget' );
}
add_action( 'widgets_init', 'gni_register_widgets' );
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

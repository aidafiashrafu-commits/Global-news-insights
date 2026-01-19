<?php
/**
 * Custom meta boxes for posts
 */

function gni_add_meta_boxes() {
    add_meta_box( 'gni_flags', __( 'Article Flags', 'global-news-insights' ), 'gni_flags_meta_box', 'post', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'gni_add_meta_boxes' );

function gni_flags_meta_box( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'gni_meta_nonce' );
    $breaking = get_post_meta( $post->ID, '_gni_breaking', true );
    $featured = get_post_meta( $post->ID, '_gni_featured', true );
    ?>
    <p>
        <label>
            <input type="checkbox" name="gni_breaking" value="1" <?php checked( $breaking, '1' ); ?> /> <?php esc_html_e( 'Mark as Breaking News', 'global-news-insights' ); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="gni_featured" value="1" <?php checked( $featured, '1' ); ?> /> <?php esc_html_e( 'Mark as Featured', 'global-news-insights' ); ?>
        </label>
    </p>
    <?php
}

function gni_save_post_meta( $post_id ) {
    if ( ! isset( $_POST['gni_meta_nonce'] ) || ! wp_verify_nonce( $_POST['gni_meta_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) return $post_id;

    $breaking = isset( $_POST['gni_breaking'] ) ? '1' : '';
    $featured = isset( $_POST['gni_featured'] ) ? '1' : '';
    update_post_meta( $post_id, '_gni_breaking', sanitize_text_field( $breaking ) );
    update_post_meta( $post_id, '_gni_featured', sanitize_text_field( $featured ) );
}
add_action( 'save_post', 'gni_save_post_meta' );

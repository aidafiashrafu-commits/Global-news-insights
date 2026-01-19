<?php
/**
 * Template Tags for Global News Insights Theme
 *
 * Helper functions for displaying common elements in templates
 *
 * @package Global_News_Insights
 */

/**
 * Output the post publication date with time
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes formatted date with time markup
 */
function gni_posted_on( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $time = get_the_time( 'c', $post_id );
    $formatted_date = get_the_date( '', $post_id );
    
    printf(
        '<time datetime="%s" class="posted-on">%s</time>',
        esc_attr( $time ),
        esc_html( $formatted_date )
    );
}

/**
 * Output the post author name with link
 *
 * @return void Echoes author byline with link to author archive
 */
function gni_author() {
    $author_name = get_the_author();
    $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
    
    printf(
        '<span class="byline"><span class="author-label">%s</span> <a href="%s" class="author-link" rel="author">%s</a></span>',
        esc_html__( 'By', 'global-news-insights' ),
        esc_url( $author_url ),
        esc_html( $author_name )
    );
}

/**
 * Output the post categories with links
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes category links
 */
function gni_posted_in( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category( $post_id );
    
    if ( empty( $categories ) ) {
        return;
    }
    
    echo '<div class="posted-in">';
    echo '<span class="cat-label">' . esc_html__( 'Categories:', 'global-news-insights' ) . '</span> ';
    
    $cat_links = array();
    foreach ( $categories as $category ) {
        $cat_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="cat-link">' . esc_html( $category->name ) . '</a>';
    }
    
    echo wp_kses_post( implode( ', ', $cat_links ) );
    echo '</div>';
}

/**
 * Output the post tags with links
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes tag links
 */
function gni_posted_tags( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $tags = get_the_tags( $post_id );
    
    if ( empty( $tags ) ) {
        return;
    }
    
    echo '<div class="posted-tags">';
    echo '<span class="tag-label">' . esc_html__( 'Tags:', 'global-news-insights' ) . '</span> ';
    
    $tag_links = array();
    foreach ( $tags as $tag ) {
        $tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link" rel="tag">#' . esc_html( $tag->name ) . '</a>';
    }
    
    echo wp_kses_post( implode( ', ', $tag_links ) );
    echo '</div>';
}

/**
 * Output featured image with lazy loading
 *
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @return void Echoes featured image markup
 */
function gni_the_featured_image( $size = 'post-thumbnail' ) {
    if ( ! has_post_thumbnail() ) {
        return;
    }
    
    $image_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
    
    echo '<figure class="featured-image" itemscope itemtype="https://schema.org/ImageObject">';
    the_post_thumbnail( $size, array( 'loading' => 'lazy' ) );
    
    if ( $image_alt ) {
        echo '<figcaption itemprop="caption">' . esc_html( $image_alt ) . '</figcaption>';
    }
    echo '</figure>';
}

/**
 * Get estimated reading time
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return int Estimated reading time in minutes
 */
function gni_get_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $reading_time = max( 1, ceil( $word_count / 200 ) );
    
    return intval( $reading_time );
}

/**
 * Output reading time estimate
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes reading time markup
 */
function gni_reading_time( $post_id = null ) {
    $reading_time = gni_get_reading_time( $post_id );
    
    printf(
        '<span class="reading-time">%d %s</span>',
        intval( $reading_time ),
        _n( 'min read', 'min read', $reading_time, 'global-news-insights' )
    );
}

/**
 * Output post navigation (prev/next)
 *
 * @return void Echoes post navigation markup
 */
function gni_the_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ( ! $prev_post && ! $next_post ) {
        return;
    }
    
    echo '<nav class="post-navigation">';
    
    if ( $prev_post ) {
        echo '<div class="nav-prev">';
        echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '" rel="prev">';
        echo '<span class="nav-label">' . esc_html__( '← Previous Post', 'global-news-insights' ) . '</span>';
        echo '<span class="nav-title">' . esc_html( $prev_post->post_title ) . '</span>';
        echo '</a></div>';
    }
    
    if ( $next_post ) {
        echo '<div class="nav-next">';
        echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '" rel="next">';
        echo '<span class="nav-label">' . esc_html__( 'Next Post →', 'global-news-insights' ) . '</span>';
        echo '<span class="nav-title">' . esc_html( $next_post->post_title ) . '</span>';
        echo '</a></div>';
    }
    
    echo '</nav>';
}

/**
 * Output post view count
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes view count markup
 */
function gni_post_views( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $views = get_post_meta( $post_id, '_gni_post_views', true );
    $views = intval( $views ) ?: 0;
    
    printf(
        '<span class="post-views">%s: %d</span>',
        esc_html__( 'Views', 'global-news-insights' ),
        $views
    );
}

/**
 * Check if post is breaking news
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return bool True if post is breaking news
 */
function gni_is_breaking_news( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    return (bool) get_post_meta( $post_id, '_gni_breaking', true );
}

/**
 * Output breaking news badge
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes breaking news badge if applicable
 */
function gni_breaking_badge( $post_id = null ) {
    if ( ! gni_is_breaking_news( $post_id ) ) {
        return;
    }
    
    echo '<span class="badge breaking-badge" aria-label="' . esc_attr__( 'Breaking News', 'global-news-insights' ) . '">🔴 ' . esc_html__( 'BREAKING', 'global-news-insights' ) . '</span>';
}

/**
 * Check if post is featured
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return bool True if post is featured
 */
function gni_is_featured( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    return (bool) get_post_meta( $post_id, '_gni_featured', true );
}

/**
 * Output featured post badge
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes featured post badge if applicable
 */
function gni_featured_badge( $post_id = null ) {
    if ( ! gni_is_featured( $post_id ) ) {
        return;
    }
    
    echo '<span class="badge featured-badge" aria-label="' . esc_attr__( 'Featured', 'global-news-insights' ) . '">★ ' . esc_html__( 'FEATURED', 'global-news-insights' ) . '</span>';
}

/**
 * Excerpt helper with customizable length
 *
 * @param int $length Optional. Number of words. Defaults to 25.
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string The post excerpt
 */
function gni_get_excerpt( $length = 25, $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $excerpt = get_the_excerpt( $post_id );
    
    if ( empty( $excerpt ) ) {
        $excerpt = wp_trim_words( get_the_content( $post_id ), $length, '...' );
    } else {
        $excerpt = wp_trim_words( $excerpt, $length, '...' );
    }
    
    return $excerpt;
}

/**
 * Output post meta information (date, author, reading time)
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return void Echoes complete post meta markup
 */
function gni_post_meta( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    echo '<div class="post-meta">';
    gni_posted_on( $post_id );
    echo ' • ';
    gni_author();
    echo ' • ';
    gni_reading_time( $post_id );
    echo '</div>';
}

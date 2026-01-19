<?php
get_header();

// Track post views
do_action( 'gni_track_post_views', get_the_ID() );
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <main class="container single-article">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/NewsArticle">
            <header class="article-header">
                <div class="article-meta-top">
                    <?php
                    // Display categories
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<div class="article-categories">';
                        foreach ( $categories as $cat ) {
                            echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="category-badge">' . esc_html( $cat->name ) . '</a> ';
                        }
                        echo '</div>';
                    }
                    
                    // Display breaking news badge if applicable
                    if ( get_post_meta( get_the_ID(), '_gni_breaking', true ) ) {
                        echo '<span class="badge breaking-badge">🔴 ' . esc_html__( 'BREAKING', 'global-news-insights' ) . '</span>';
                    }
                    ?>
                </div>
                <h1 itemprop="headline"><?php the_title(); ?></h1>
                <div class="byline">
                    <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                        By <span itemprop="name"><?php the_author(); ?></span>
                    </span> 
                    • 
                    <time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                    <?php 
                    // Display updated date if different from published
                    if ( get_the_modified_date( 'U' ) > get_the_date( 'U' ) ) {
                        echo ' • <span class="updated-badge">' . esc_html__( 'Updated:', 'global-news-insights' ) . ' <time itemprop="dateModified" datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time></span>';
                    }
                    ?>
                </div>
                
                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="hero-image">
                        <?php the_post_thumbnail( 'gni-hero', array( 'loading' => 'lazy', 'itemprop' => 'image' ) ); ?>
                        <?php 
                        $image_id = get_post_thumbnail_id();
                        $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        if ( $image_alt ) {
                            echo '<figcaption itemprop="caption">' . esc_html( $image_alt ) . '</figcaption>';
                        }
                        ?>
                    </figure>
                <?php endif; ?>
            </header>

            <div class="article-toolbar">
                <div class="article-share">
                    <h4><?php esc_html_e( 'Share this article:', 'global-news-insights' ); ?></h4>
                    <?php echo wp_kses_post( gni_get_share_buttons() ); ?>
                </div>
            </div>

            <div class="article-content" itemprop="articleBody">
                <?php 
                the_content(); 
                wp_link_pages( array(
                    'before' => '<div class="page-links"><span>' . esc_html__( 'Pages:', 'global-news-insights' ) . '</span>',
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <!-- Article Footer Meta -->
            <footer class="article-footer">
                <div class="article-footer-meta">
                    <?php 
                    // Display tags
                    $tags = get_the_tags();
                    if ( $tags ) {
                        echo '<div class="article-tags">';
                        echo '<span class="tags-label">' . esc_html__( 'Tags:', 'global-news-insights' ) . '</span> ';
                        foreach ( $tags as $tag ) {
                            echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link" rel="tag">#' . esc_html( $tag->name ) . '</a> ';
                        }
                        echo '</div>';
                    }
                    
                    // Display post views count
                    $views = get_post_meta( get_the_ID(), '_gni_post_views', true );
                    if ( $views ) {
                        echo '<div class="article-views">' . esc_html__( 'Views:', 'global-news-insights' ) . ' ' . intval( $views ) . '</div>';
                    }
                    ?>
                </div>
            </footer>

            <!-- In-article Ad slot -->
            <?php if ( get_theme_mod( 'gni_enable_ads', false ) ) : ?>
                <div class="ad-slot ad-inline" aria-hidden="true">
                    <p><?php esc_html_e( '[Inline Ad Slot - Replace with your AdSense code]', 'global-news-insights' ); ?></p>
                </div>
            <?php endif; ?>
        </article>

        <!-- Author Box -->
        <div class="author-box">
            <div class="author-avatar">
                <?php echo wp_kses_post( get_avatar( get_the_author_meta( 'user_email' ), 80 ) ); ?>
            </div>
            <div class="author-info">
                <h3><?php esc_html_e( 'About', 'global-news-insights' ); ?> <span class="author-name"><?php the_author(); ?></span></h3>
                <p><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
            </div>
        </div>

        <!-- Related Posts Section -->
        <section class="related-posts">
            <h2><?php esc_html_e( 'Related Articles', 'global-news-insights' ); ?></h2>
            <div class="related-grid">
                <?php
                $related = new WP_Query( array(
                    'category__in'      => wp_get_post_categories( get_the_ID() ),
                    'posts_per_page'    => 3,
                    'post__not_in'      => array( get_the_ID() ),
                    'orderby'           => 'date',
                    'order'             => 'DESC',
                ) );
                
                if ( $related->have_posts() ) {
                    while ( $related->have_posts() ) {
                        $related->the_post();
                        ?>
                        <article class="related-post">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="related-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="related-meta">
                                <time><?php echo esc_html( get_the_date() ); ?></time>
                            </div>
                        </article>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>' . esc_html__( 'No related articles found.', 'global-news-insights' ) . '</p>';
                }
                ?>
            </div>
        </section>

        <!-- Comments Section -->
        <?php if ( comments_open() || get_comments_number() ) : ?>
            <section class="comments-section">
                <?php comments_template(); ?>
            </section>
        <?php endif; ?>

            <?php // JSON-LD schema
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => get_the_title(),
                'datePublished' => get_the_date( 'c' ),
                'dateModified' => get_the_modified_date( 'c' ),
                'author' => array( '@type' => 'Person', 'name' => get_the_author() ),
                'mainEntityOfPage' => get_permalink(),
                'articleBody' => wp_strip_all_tags( get_the_content() ),
            );
            ?>
        </article>
    </main>

<?php endwhile; endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

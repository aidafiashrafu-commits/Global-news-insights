<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <main class="container single-article">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/NewsArticle">
            <header class="article-header">
                <h1 itemprop="headline"><?php the_title(); ?></h1>
                <div class="byline">By <span itemprop="author"><?php the_author(); ?></span> • <time itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time></div>
                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="hero-image" itemprop="image">
                        <?php the_post_thumbnail( 'gni-hero', array( 'loading' => 'lazy' ) ); ?>
                    </figure>
                <?php endif; ?>
            </header>

            <div class="article-content" itemprop="articleBody">
                <?php the_content(); ?>

                <!-- In-article Ad slot (replace with your AdSense code) -->
                <div class="ad-slot ad-inline" aria-hidden="true">Ad slot (in-article) - paste AdSense code here</div>
            </div>

            <div class="article-share">
                <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank">Share on Facebook</a>
                <a class="share-whatsapp" href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank">Share on WhatsApp</a>
            </div>

            <?php // Related posts
            $related = new WP_Query( array( 'category__in' => wp_get_post_categories( get_the_ID() ), 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ) ) );
            if ( $related->have_posts() ) : ?>
                <section class="related-posts">
                    <h3><?php esc_html_e( 'Related', 'global-news-insights' ); ?></h3>
                    <ul>
                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php // JSON-LD schema
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => get_the_title(),
                'datePublished' => get_the_date( 'c' ),
                'author' => array( '@type' => 'Person', 'name' => get_the_author() ),
                'mainEntityOfPage' => get_permalink(),
            );
            echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
            ?>
        </article>
    </main>
<?php endwhile; endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

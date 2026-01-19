<?php
/* Front page template with hero, featured posts and category sections */
get_header();
?>
<main class="container front-page">
    <section class="hero">
        <?php
        // Featured hero: first post marked as featured
        $hero = new WP_Query( array( 'meta_key' => '_gni_featured', 'meta_value' => '1', 'posts_per_page' => 1 ) );
        if ( $hero->have_posts() ) : while ( $hero->have_posts() ) : $hero->the_post(); ?>
            <article class="hero-article">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'gni-hero', array( 'loading' => 'lazy' ) ); ?></a>
                <?php endif; ?>
                <div class="hero-content">
                    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <div class="hero-meta"><?php gni_posted_on(); ?> • <?php gni_author(); ?></div>
                </div>
            </article>
        <?php endwhile; wp_reset_postdata(); endif; ?>
    </section>

    <section class="latest">
        <h2>Latest News</h2>
        <div class="grid">
            <?php $latest = new WP_Query( array( 'posts_per_page' => 8 ) );
            if ( $latest->have_posts() ) : while ( $latest->have_posts() ) : $latest->the_post(); ?>
                <article class="grid-item">
                    <a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'gni-grid', array( 'loading' => 'lazy' ) ); ?></a>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </article>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </section>

    <section class="category-sections">
        <?php $cats = get_categories( array( 'exclude' => '' ) );
        $display = array( 'World', 'Africa', 'Business', 'Technology', 'Sports', 'Entertainment', 'Health' );
        foreach ( $display as $label ) {
            $cat = get_category_by_slug( sanitize_title( $label ) );
            if ( ! $cat ) continue;
            $q = new WP_Query( array( 'cat' => $cat->term_id, 'posts_per_page' => 4 ) );
            if ( $q->have_posts() ) : ?>
                <div class="category-block">
                    <h3><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $label ); ?></a></h3>
                    <div class="cat-grid">
                        <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                            <article class="cat-item">
                                <a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'gni-grid', array( 'loading' => 'lazy' ) ); ?></a>
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; }
        ?>
    </section>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

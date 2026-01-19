<?php get_header(); ?>

<main class="container">
    <?php if ( have_posts() ) : ?>
        <section class="latest-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'news-item' ); ?>>
                    <a href="<?php the_permalink(); ?>" class="thumb">
                        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'gni-grid', array( 'loading' => 'lazy' ) ); } ?>
                    </a>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="meta"><?php gni_posted_on(); ?> • <?php gni_author(); ?></div>
                </article>
            <?php endwhile; ?>
        </section>
        <div class="pagination"><?php the_posts_pagination(); ?></div>
    <?php else :
        get_template_part( '404' );
    endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

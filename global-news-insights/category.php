<?php get_header(); ?>

<main class="container category-archive">
    <?php if ( have_posts() ) : ?>
        <header class="archive-header">
            <h1><?php single_cat_title(); ?></h1>
            <div class="archive-desc"><?php echo category_description(); ?></div>
        </header>
        <section class="archive-list">
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'gni-grid', array( 'loading' => 'lazy' ) ); ?></a>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="meta"><?php gni_posted_on(); ?></div>
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

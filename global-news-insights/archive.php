<?php get_header(); ?>
<main class="container archive-page">
    <header class="archive-header">
        <h1><?php the_archive_title(); ?></h1>
    </header>
    <?php if ( have_posts() ) : ?>
        <div class="archive-list">
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'gni-grid', array( 'loading' => 'lazy' ) ); ?></a>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else :
        get_template_part( '404' );
    endif; ?>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

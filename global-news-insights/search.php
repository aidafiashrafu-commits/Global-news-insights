<?php get_header(); ?>
<main class="container search-results">
    <header class="search-header">
        <h1><?php printf( esc_html__( 'Search Results for: %s', 'global-news-insights' ), get_search_query() ); ?></h1>
    </header>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="excerpt"><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; else : ?>
        <p><?php esc_html_e( 'No results found.', 'global-news-insights' ); ?></p>
    <?php endif; ?>
</main>
<?php get_footer(); ?>

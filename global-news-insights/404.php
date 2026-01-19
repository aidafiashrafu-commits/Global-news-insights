<?php get_header(); ?>
<main class="container not-found">
    <h1><?php esc_html_e( 'Page not found', 'global-news-insights' ); ?></h1>
    <p><?php esc_html_e( 'Sorry, we couldn\'t find that page. Try searching or return home.', 'global-news-insights' ); ?></p>
    <?php get_search_form(); ?>
</main>
<?php get_footer(); ?>

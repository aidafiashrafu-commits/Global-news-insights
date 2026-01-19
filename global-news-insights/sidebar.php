<aside class="sidebar container" role="complementary">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>
        <div class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'About', 'global-news-insights' ); ?></h3>
            <p><?php esc_html_e( 'Global News Insights — Reliable News. Smart Insights.', 'global-news-insights' ); ?></p>
        </div>
    <?php endif; ?>
    <div class="ad-slot">Ad slot (sidebar)</div>
</aside>

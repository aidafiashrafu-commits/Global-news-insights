    <footer class="gni-footer">
        <div class="container footer-widgets">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); endif; ?>
        </div>
        <div class="container footer-meta">
            <div class="social">
                <a href="https://www.facebook.com/share/1DAqbgWgGS/" target="_blank" rel="noopener">Facebook</a>
                <a href="https://www.tiktok.com/@music.lovers395?_r=1&_t=ZM-93BYcRAiqOB" target="_blank" rel="noopener">TikTok</a>
                <a href="https://wa.me/255717007449" target="_blank" rel="noopener">WhatsApp</a>
                <a href="mailto:lingendea@gmail.com">Email</a>
            </div>
            <div class="copyright">&copy; <?php echo date( 'Y' ); ?> Global News Insights. Reliable News. Smart Insights.</div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>

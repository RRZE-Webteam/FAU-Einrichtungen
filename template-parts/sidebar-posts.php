<?php 
if ( is_active_sidebar( 'news-sidebar' ) ) { ?>
<aside class="sidebar-single" aria-label="Sidebar">
    <?php dynamic_sidebar( 'news-sidebar' ); ?>
</aside>
<?php } ?>
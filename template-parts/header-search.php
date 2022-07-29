<?php
/**
 * The template for the search form in header
 *
 * @package    WordPress
 * @subpackage FAU
 * @since      FAU 1.0
 */

?>
<div itemscope itemtype="https://schema.org/WebSite" class="meta-search">
    <?php
    /** Condition statement if plugin is currently active */
    if (is_plugin_active('rrze-search/rrze-search.php')) {
        /** WP function for loading widget's sidebar */
        dynamic_sidebar('rrze-search-sidebar');
    } else {
        /** Original Snippet from WP Theme */
        ?>
        <meta itemprop="url" content="<?php echo home_url('/'); ?>">
        <form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction" id="search-header"
              role="search" aria-label="<?php echo get_theme_mod('title_hero_search'); ?>" method="get" class="searchform" action="<?php echo fau_esc_url(home_url('/')); ?>">
            <label for="headsearchinput">
                <?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:', 'fau'); ?>
            </label>
            <meta itemprop="target" content="<?php echo home_url('/') ?>?s={s}">
            <input itemprop="query-input" id="headsearchinput" type="text" value="<?php the_search_query(); ?>" name="s"
                   placeholder="<?php _e('Suchen nach...', 'fau'); ?>" required>
            <?php
            if (get_theme_mod('search_allowfilter')) {
                $autosearch_types = get_theme_mod('search_post_types_checked');
                $listtypes        = fau_get_searchable_fields();
                if (is_array($listtypes)) {
                    foreach ($listtypes as $type) {
                        if (in_array($type, $autosearch_types)) {
                            echo '<input type="hidden" name="post_type[]" value="'.$type.'">'."\n";
                        }
                    }
                }
            }
            ?>
            <input type="submit" enterkeyhint="search" value="<?php _e('Finden', 'fau'); ?>">
        </form>
    <?php } ?>
</div>


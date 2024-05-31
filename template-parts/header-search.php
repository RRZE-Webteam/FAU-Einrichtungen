<?php
/**
 * The template for the search form in header
 *
 * @package    WordPress
 * @subpackage FAU
 * @since      FAU 1.0
 */


$advanced_header_search_hide = get_theme_mod('advanced_header_search_hide', false);

?>
<div class="meta-search">
<?php
    /** Condition statement if plugin is currently active */
    if (is_plugin_active('rrze-search/rrze-search.php')) {
        dynamic_sidebar('rrze-search-sidebar');
    } else {
        if (empty($advanced_header_search_hide)) { ?>
    <div itemscope itemtype="https://schema.org/WebSite">
        <meta itemprop="url" content="<?php echo fau_esc_url(home_url('/')); ?>">
        <form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction" id="search-header" role="search" aria-label="<?php echo esc_html(get_theme_mod('title_hero_search')); ?>" method="get" class="searchform" action="<?php echo fau_esc_url(home_url('/')); ?>">
            <label for="headsearchinput"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:', 'fau'); ?></label>
            <meta itemprop="target" content="<?php echo fau_esc_url(home_url('/')); ?>?s={s}">
            <input itemprop="query-input" id="headsearchinput" type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchbegriff', 'fau'); ?>" required>
            <?php
            if (get_theme_mod('search_allowfilter')) {
                $autosearch_types = get_theme_mod('search_post_types_checked');
                $listtypes        = fau_get_searchable_fields();
                if (is_array($listtypes)) {
                    foreach ($listtypes as $type) {
                        if (in_array($type, $autosearch_types)) {
                            echo '<input type="hidden" name="post_type[]" value="'.$type.'">';
                        }
                    }
                }
            } ?>
            <div class="search-initiate-button"><span class="screen-reader-text">Suche öffnen</span></div>
            <input type="submit" enterkeyhint="search" value="<?php _e('Finden', 'fau'); ?>">
        </form>
    </div>
<?php } 
} ?>
</div>


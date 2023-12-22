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
        /** WP function for loading widget's sidebar */
        dynamic_sidebar('rrze-search-sidebar');
    } else {
        /** Original Snippet from WP Theme */
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
            }
?>
            <div class="search-initiate-button">
                <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 125" style="enable-background:new 0 0 100 125;" xml:space="preserve">
                    <path d="M41.52,90.52c7.25,0.01,14.34-2.15,20.35-6.21l21.12,21.12c2.75,2.75,7.2,2.75,9.96,0.01c2.75-2.75,2.75-7.2,0.01-9.96
                        c0,0,0,0-0.01-0.01L71.82,74.35c11.23-16.74,6.77-39.42-9.98-50.65s-39.42-6.77-50.65,9.98S4.42,73.1,21.17,84.33
                        C27.18,88.37,34.27,90.52,41.52,90.52z M41.52,25.94c15.5,0,28.07,12.57,28.07,28.07c0,15.5-12.57,28.07-28.07,28.07
                        c-15.5,0-28.07-12.57-28.07-28.07c0,0,0,0,0,0C13.47,38.51,26.02,25.96,41.52,25.94z"/>
                </svg>
            </div>
            <input type="submit" enterkeyhint="search" value="<?php _e('Finden', 'fau'); ?>">
        </form>
    </div>
<?php } 
} ?>
</div>


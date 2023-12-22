<?php
/**
 * The Header for our theme.
 *
 * @package    WordPress
 * @subpackage FAU
 * @since      FAU 1.0
 */
global $defaultoptions;

$website_type = get_theme_mod('website_type');

$show_customlogo = false;
$custom_logo_id  = get_theme_mod('custom_logo');
$logo_src        = '';
if ($custom_logo_id) {
    $logo            = wp_get_attachment_image_src($custom_logo_id, 'full');
    $logo_src        = $logo[0];
    $show_customlogo = true;
    if (!empty($logo_src)) {
        if (preg_match('/\/themes\/FAU\-[a-z]+\/img\/logos\//i', $logo_src,
            $match)) {
            $show_customlogo = false;
            // Version 2: Check for old Images in theme, that was chosen in customizer, but removed
            // from code later. In this case, ignore this entry.
        }
    }
}

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php print_indented("wp_head",2); ?>
	</head>
	<body id="top" <?php body_class(); ?>><?php wp_body_open(); ?>
		<div id="pagewrapper">
			<div id="headerwrapper">
				<?php print_indented('get_template_part', 4, 'template-parts/header-skiplinks'); ?>
				<div id="meta">
                    <div class="header-container">
                        <div class="header-row" id="meta-menu">
                            <div class="meta-links-container">
                                <a href="#meta-menu" class="meta-links-trigger meta-links-trigger-open">
                                    <span class="meta-links-trigger-text"><?php _e("Organisationsmenü", "fau"); ?> <?php _e("öffnen", "fau"); ?></span>
                                    <span
                                        class="meta-links-trigger-icon<?php echo ($website_type != '3' && $website_type !== '-1') ? ' meta-links-trigger-icon-fau' : '' ?>">
                                        <?php
                                        if ($website_type === '3' || $website_type === '-1') {
                                            echo file_get_contents(get_template_directory().'/svg/icon-organization.svg');
                                        } else {
                                            echo fau_use_svg("fau-logo-2021", 153, 58, 'faubaselogo', false, [
                                                'hidden'     => true,
                                                'labelledby' => 'website-title',
                                                'role'       => 'img'
                                            ]);
                                        }
                                        ?>
                                    </span>
                                </a>
                                <a href="#top" class="meta-links-trigger meta-links-trigger-close">
                                    <span class="meta-links-trigger-text"><?php _e("Organisationsmenü", "fau"); ?> <?php _e("schließen", "fau"); ?></span>
                                    <span class="meta-links-trigger-icon">
                                        <?php echo file_get_contents(get_template_directory().'/svg/icon-close.svg') ?>
                                    </span>
                                </a>
                            </div>
                            <div class="meta-logo">
                                <div class="branding" itemscope itemtype="http://schema.org/Organization">
                                    <?php
                                    if ($show_customlogo) {
                                        $custom_logo_title = get_theme_mod('website_logotitle');
                                        echo '<meta itemprop="url" content="'.$logo_src.'">';
                                        echo '<meta itemprop="name" content="'.get_bloginfo('name', 'display').'">';
                                        echo get_custom_logo();
                                    } else {
                                        get_template_part('template-parts/header', 'meta-textlogo');
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <nav class="meta-links"
                             aria-label="<?php _e('Navigation: Weitere Angebote', 'fau'); ?>">
                            <?php
                                // FAU link
                                if ($website_type !== '3' && $website_type !== '-1') {
                                    echo fau_get_toplinks(null, 1);
                                }
                                
                                // ORGA Breadcrumb
                                if ($defaultoptions['debugmode'] && get_theme_mod('debug_orgabreadcrumb')) {
                                    get_template_part('template-parts/debugoutput', 'orga-breadcrumb');
                                } elseif (is_plugin_active('fau-orga-breadcrumb/fau-orga-breadcrumb.php')) {
                                    get_template_part('template-parts/header', 'orga-breadcrumb');
                                }
                                
                                // Search bar
                                get_template_part('template-parts/header', 'search');
                                
                                // Language switcher
                                if ($defaultoptions['debugmode'] && get_theme_mod('debug_sprachschalter')) {
                                    get_template_part('template-parts/debugoutput', 'sprachschalter');
                                } elseif (is_active_sidebar('language-switcher')) {
                                    dynamic_sidebar('language-switcher');
                                } 
                                
                                // Top links
                                echo fau_get_toplinks(null, 2);
                            ?>
                        </nav>
                    </div>
                </div>
                <?php
                if ($defaultoptions['debugmode'] && get_theme_mod('debug_orgabreadcrumb')) {
                    get_template_part('template-parts/debugoutput', 'orga-breadcrumb');
                } elseif (is_plugin_active('fau-orga-breadcrumb/fau-orga-breadcrumb.php')) {
                    get_template_part('template-parts/header', 'orga-breadcrumb');
                } ?>
                <header id="header">
                    <div class="header-container">
                        <div class="header-row">
                            <div class="branding" id="logo" itemscope itemtype="http://schema.org/Organization">

                                <?php
                                if ($show_customlogo) {
                                    echo '<p class="sitetitle">';
                                    echo '<meta itemprop="url" content="'.$logo_src.'">';
                                    echo '<meta itemprop="name" content="'.get_bloginfo('name', 'display').'">';
                                    echo get_custom_logo();
                                    echo '</p>';
                                } else {
                                    get_template_part('template-parts/header', 'textlogo');
                                }
                                ?>

                            </div>
                            <?php 
                            $menu_style = get_theme_mod('menu_style', 'mega_menu');
                            if (has_nav_menu('main-menu')) {  ?>
                            <nav class="header-menu" id="nav" aria-label="<?php _e("Hauptnavigation", "fau"); ?>">
                                <a href="#nav" id="mainnav-toggle"><span><?php _e("Menu", "fau"); ?></span></a>
                                <a href="#top" id="mainnav-toggle-close"><span><?php _e("Menu", "fau"); ?> <?php _e("schließen", "fau"); ?></span></a>
                                <div id="nav-wrapper">
                                <?php
                               $menustyle= get_theme_mod('main_menu_style', $defaultoptions['main_menu_style']);
                                    if ($menustyle == 'mega') {
                                        wp_nav_menu(array(
                                            'theme_location' => 'main-menu',
                                            'container'      => false,
                                            'items_wrap'     => '<ul class="nav">%3$s</ul>',
                                            'depth'          => 4,
                                            'walker'         => new Walker_Main_Menu_Plainview
                                        ));
                                    } elseif ($menustyle == 'small') {
                                        // This renders a basic dropdown without the mega menu walker
                                        wp_nav_menu(array(
                                            'theme_location' => 'main-menu',
                                            'container'      => false,
                                            'items_wrap'     => '<ul class="navsmall">%3$s</ul>',
                                            'depth'          => 4,
                                            'walker'         => new Walker_Main_Menu_Plainview_Small
                                        ));
                                    }
                                ?>
                                </div>
                            </nav>
                        <?php } ?>
                        </div>
                    </div>
                </header>
            </div>
<?php
     get_template_part('template-parts/header', 'hero');

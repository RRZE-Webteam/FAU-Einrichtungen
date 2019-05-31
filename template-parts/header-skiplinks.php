<?php

/**
 * Template Header: Skiplinks
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.10
 */
?>
	    <nav id="skiplinks" aria-labelledby="skiplinks-title">
		<h2 class="screen-reader-text" id="skiplinks-title"><?php _e('Skiplinks','fau'); ?></h2>
		    <ul class="jumplinks">
			<li><a href="#droppoint"><?php _e('Zum Inhalt springen','fau'); ?></a></li>
			<?php if(!is_tax() && !is_category()  && basename( get_page_template() )=='page-subnav.php') { ?>
			<li><a href="#subnavtitle"><?php _e('Zur Bereichsnavigation springen','fau'); ?></a></li><?php } ?>
		    </ul>
	    </nav>
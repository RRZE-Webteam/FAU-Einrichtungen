<?php

/* 
 * Hilfreiche Links, sofern gesetzt
 */


/* First check, if there are entries */

$spalte1 = '';
$count = 0;
if(has_nav_menu('error-1')) {
    $thislinks = wp_nav_menu( array( 'theme_location' => 'error-1', 'container' => false, 'items_wrap' => '<ul class="sub-menu %2$s">%3$s</ul>', 'echo' => 0 ) );
    if ($thislinks) {
	$spalte1 .= '<h3>'.fau_get_menu_name('error-1').'</h3>'."\n";
	$spalte1 .= $thislinks;
	$count++;
    }
}
$spalte2 = '';
if(has_nav_menu('error-2')) {
    $thislinks = wp_nav_menu( array( 'theme_location' => 'error-2', 'container' => false, 'items_wrap' => '<ul class="sub-menu %2$s">%3$s</ul>', 'echo' => 0 ) );
    if ($thislinks) {
	$spalte2 .= '<h3>'.fau_get_menu_name('error-2').'</h3>'."\n";
	$spalte2 .= $thislinks;
	$count++;
    }
}
$spalte3 = '';
if(has_nav_menu('error-3')) {
    $thislinks = wp_nav_menu( array( 'theme_location' => 'error-3', 'container' => false, 'items_wrap' => '<ul class="sub-menu %2$s">%3$s</ul>', 'echo' => 0 ) );
    if ($thislinks) {
	$spalte3 .= '<h3>'.fau_get_menu_name('error-3').'</h3>'."\n";
	$spalte3 .= $thislinks;
	$count++;
    }
}
$spalte4 = '';
if(has_nav_menu('error-4')) {
    $thislinks = wp_nav_menu( array( 'theme_location' => 'error-4', 'container' => false, 'items_wrap' => '<ul class="sub-menu %2$s">%3$s</ul>', 'echo' => 0 ) );
    if ($thislinks) {
	$spalte4 .= '<h3>'.fau_get_menu_name('error-4').'</h3>'."\n";
	$spalte4 .= $thislinks;
	$count++;
    }
}    
    
if ($count>0) { 
    
    echo "<hr>\n";
    echo "<h2>".__('Folgende Inhalte könnten Ihnen auch helfen:','fau')."</h2>\n";

    echo '<div class="row subpages-menu">'."\n";
    
    if ($count ==1) {
	$usespan = 'span6';
    } elseif ($count ==2) {
	$usespan = 'span4';
    } elseif ($count ==3) {
	$usespan = 'span4';
    } else {
	$usespan = 'span3';
    }
    if ($spalte1) {
	    echo '<div class="'.$usespan.'">'."\n";
	    echo $spalte1;
	    echo "</div>\n";
    }
    if ($spalte2) {
	    echo '<div class="'.$usespan.'">'."\n";
	    echo $spalte2;
	    echo "</div>\n";
    }
    if ($spalte3) {
	    echo '<div class="'.$usespan.'">'."\n";
	    echo $spalte3;
	    echo "</div>\n";
    }
    if ($spalte4) {
	    echo '<div class="'.$usespan.'">'."\n";
	    echo $spalte4;
	    echo "</div>\n";
    }
    echo "</div>\n";
    
}

?>



				
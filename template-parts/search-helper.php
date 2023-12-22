<?php

/* 
 * Hilfreiche Links, sofern gesetzt
 */

$menu = 'error-helper';

/* First check, if there are entries */
if(has_nav_menu($menu)) {
    $menuname = fau_get_menu_name($menu);
    $shortcodeopt = 'menu="'.$menuname.'"';
    $shortcodeopt .= ' showsubs="true"';
    echo "<hr>";
    echo "<p>".__('Folgende Inhalte könnten Ihnen auch helfen:','fau')."</p>\n";
    echo do_shortcode('[portalmenu '.$shortcodeopt.']');

}

?>



				
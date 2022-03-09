<?php

if (is_plugin_active('fau-person/fau-person.php')) {
    // if (class_exists( 'FAUPersonWidget' ) )  {
    $sidebar_personen = get_post_meta($post->ID, 'sidebar_personen', true);
    $sidebar_title_personen = get_post_meta($post->ID, 'sidebar_title_personen', true);

    if (isset($sidebar_personen) && !empty($sidebar_personen)) {
        $persons = $sidebar_personen;
        fau_use_sidebar(true);
        $hstart = 2;
        echo '<div class="widget">';
        if (!fau_empty($sidebar_title_personen)) {
            echo '<h2 class="widget-title">' . $sidebar_title_personen . '</h2>';
            $hstart = 3;
        }
        $params = '';
        foreach ($persons as $person) {
            $params = 'id="' . $person . '" format="sidebar" hstart="' . $hstart . '"';
            echo do_shortcode('[kontakt ' . $params . ']');

        }
        echo '</div>';
    }
}
    
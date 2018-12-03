<?php 
	$output = '';
	$title = get_post_meta( $post->ID, 'sidebar_title_above', true );
	if (strlen(trim($title))>1) {
	    $output .= '<h2'.$title.'</h2>'."\n";
	}
	$text = get_post_meta( $post->ID, 'sidebar_text_above', true );
	if (!fau_empty($text)) {
	    
	    $useeditor = get_theme_mod('advanced_page_sidebar_useeditor_textabove');
	    if ($useeditor==false) {
		$text = wpautop($text);
	    }
	    $text = do_shortcode($text);
	    if(function_exists('mimetypes_to_icons')) {
		$output .= mimetypes_to_icons($text); 
	    } else 	{
		$output .= $text;
	    }

	}

	if (!empty($output)) {
	    echo '<div class="widget">'."\n";
	    echo $output;
	    echo "</div>\n";
	}


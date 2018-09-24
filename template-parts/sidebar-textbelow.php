<?php 
	$output = '';
	$title = get_post_meta( $post->ID, 'sidebar_title_below', true );
	if (strlen(trim($title))>1) {
	    $output .= '<h2 class="widget-title">'.$title.'</h2>'."\n";
	}
	$text = get_post_meta( $post->ID, 'sidebar_text_below', true );
	if (!empty($text)) {
	    $useeditor = get_theme_mod('advanced_page_sidebar_useeditor_textbelow');
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


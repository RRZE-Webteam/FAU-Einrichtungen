<?php 
    global $options;
	$output = '';
	$title = get_post_meta( $post->ID, 'sidebar_title_above', true );
	if (strlen(trim($title))>1) {
	    $output .= '<h2>'.$title.'</h2>'."\n";
	}
	$text = get_post_meta( $post->ID, 'sidebar_text_above', true );
	if (!empty($text)) {
	    if ($options['advanced_page_sidebar_useeditor_textabove']==false) {
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
	    echo '<aside class="widget">'."\n";
	    echo $output;
	    echo "</aside>\n";
	}


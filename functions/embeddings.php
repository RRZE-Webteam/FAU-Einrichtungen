<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.10
*/

/*-----------------------------------------------------------------------------------*/
/* Surround embeddings with div class
/*-----------------------------------------------------------------------------------*/
function fau_wrap_oembed_div($html, $url, $attr) {
	return '<div class="oembed">'.$html.'</div>';
}
add_filter('embed_oembed_html', 'fau_wrap_oembed_div', 10, 3);


/*-----------------------------------------------------------------------------------*/
/* Add filter to insert title in iframe, as long its not patched in WP
 * See:
 * https://core.trac.wordpress.org/ticket/40245
 * https://core.trac.wordpress.org/attachment/ticket/40245/40245.diff
 *
/*-----------------------------------------------------------------------------------*/
if ( ! has_filter( 'oembed_dataparse', 'wp_filter_oembed_title_attribute' ) ) {
    /**
     * Filters the given oEmbed HTML to make sure iframes have a title attribute.
     *
     * @since 5.2.0
     *
     * @param string $result The oEmbed HTML result.
     * @param object $data   A data object result from an oEmbed provider.
     * @param string $url    The URL of the content to be embedded.
     * @return string The filtered oEmbed result.
     */
    function wp_filter_oembed_title_attribute( $result, $data, $url ) {
	    if ( false === $result || ! in_array( $data->type, array( 'rich', 'video' ) ) ) {
		    return $result;
	    }

	    $title = ! empty( $data->title ) ? $data->title : '';

	    $pattern        = '/title\=[\"|\\\']{1}([^\"\\\']*)[\"|\\\']{1}/i';
	    $has_title_attr = preg_match( $pattern, $result, $matches );

	    if ( $has_title_attr && ! empty( $matches[1] ) ) {
		    $title = $matches[1];
	    }

	    /**
	     * Filters the title attribute of the given oEmbed HTML.
	     *
	     * @since 5.2.0
	     *
	     * @param string $title The title attribute.
	     * @param string $result The oEmbed HTML result.
	     * @param object $data   A data object result from an oEmbed provider.
	     * @param string $url    The URL of the content to be embedded.
	     */
	    $title = apply_filters( 'oembed_title_attribute', $title, $result, $data, $url );

	    if ( '' === $title ) {
		    return $result;
	    }

	    if ( $has_title_attr ) {
		    $result = preg_replace( $pattern, 'title="' . esc_attr( $title ) . '"', $result );
	    } else {
		    return str_ireplace( '<iframe ', sprintf( '<iframe title="%s" ', esc_attr( $title ) ), $result );
	    }

	    return $result;
    }
    add_filter( 'oembed_dataparse', 'wp_filter_oembed_title_attribute', 20, 3 );
}
   
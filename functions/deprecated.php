<?php

/* 
 * Deprecated functions
 */

// Old Function for Advertisement
function fau_get_ad() {
 global $wp;
    $slug = add_query_arg([], $wp->request);
    $out = '';
    if (defined('WP_DEBUG') && WP_DEBUG) {
	   $debug = print_r(debug_backtrace(), true);
	   $out = ': ['. $debug. ']';
    }
 
    trigger_error('[' . $slug . '] Function ' . __FUNCTION__. ' is deprecated'. $out, E_USER_WARNING);
    return null;
}


<?php
/*-----------------------------------------------------------------------------------*/
/* Code to support or modify optional plugins
/*-----------------------------------------------------------------------------------*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );



/*-----------------------------------------------------------------------------------*/
/* Plugin CMS-Workflow 
/*-----------------------------------------------------------------------------------*/
function is_workflow_translation_active() {
  // CMS-Workflow < 2.0.0
  global $cms_workflow;
  if ((class_exists('Workflow_Translation')) && (isset($cms_workflow->translation) && $cms_workflow->translation->module->options->activated)) {
      return true;
  // CMS-Workflow >= 2.0.0
  } elseif (class_exists('RRZE\Workflow\Helper') &&
      method_exists('RRZE\Workflow\Helper', 'isModuleActivated')
  ) {
      return \RRZE\Workflow\Helper::isModuleActivated('translation');
  }
  return false;
}

function fau_get_rel_alternate() {
  // CMS-Workflow < 2.0.0
  if ((class_exists('Workflow_Translation')) && (function_exists('get_rel_alternate')) && (is_workflow_translation_active())) {
    return Workflow_Translation::get_rel_alternate();
  // CMS-Workflow >= 2.0.0
  } elseif (
      is_workflow_translation_active() &&
      method_exists('\RRZE\Workflow\Modules\Translation\Translation', 'get_rel_alternate')
  ) {
      return \RRZE\Workflow\Modules\Translation\Translation::get_rel_alternate();
  } else {
      return '';
  }
}

/*-----------------------------------------------------------------------------------*/
/* Plugin TinyMCE: Button für Seitenumbruch ergänzen
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons', 'kb_add_next_page_button', 1, 2 );
function kb_add_next_page_button( $buttons, $id ) {
  if ( 'content' === $id ) {
    array_splice( $buttons, 13, 0, 'wp_page' );
  }
  return $buttons;
}
/*-----------------------------------------------------------------------------------*/
/* Plugin TinyMCE: Disable Emojis 
/*-----------------------------------------------------------------------------------*/
add_filter( 'tiny_mce_plugins', 'fau_disable_emojicons_tinymce' );
function fau_disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
add_filter( 'emoji_svg_url', '__return_false' );


/*-----------------------------------------------------------------------------------*/
/* Plugin TinyMCE: Set Paragraphs
/*-----------------------------------------------------------------------------------*/
add_filter('tiny_mce_before_init', 'fau_tiny_mce_defined_formats' );
/*
 * Modify TinyMCE editor to remove H1.
 */
function fau_tiny_mce_defined_formats($init) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformated=pre';
	return $init;
}

/*-----------------------------------------------------------------------------------*/
/* Plugin TinyMCE: Set Set Buttons on Bar 1
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons', 'fau_remove_tiny_mce_buttons_from_editor');
function fau_remove_tiny_mce_buttons_from_editor( $buttons ) {
    $remove_buttons = array(
       // 'bold',
       // 'italic',
        'strikethrough',
      //  'bullist',
      //  'numlist',
      //  'blockquote',
      //  'hr', // horizontal line
        'alignleft',
         'aligncenter',
        'alignright',
     //   'link',
     //   'unlink',
     //   'wp_more', // read more link
     //   'spellchecker',
     //   'dfw', // distraction free writing mode
     //   'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
    );
    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}


/*-----------------------------------------------------------------------------------*/
/* Plugin TinyMCE: Set Set Buttons on Bar 2
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons_2', 'fau_remove_tiny_mce_buttons_from_kitchen_sink');
function fau_remove_tiny_mce_buttons_from_kitchen_sink( $buttons ) {
    $remove_buttons = array(
      //  'formatselect', // format dropdown menu for <p>, headings, etc
        'underline',
    //    'alignjustify',
        'forecolor', // text color
    //    'pastetext', // paste as text
     //   'removeformat', // clear formatting
     //   'charmap', // special characters
        'outdent',
	'indent',
     //   'undo',
     //   'redo',
     //   'wp_help', // keyboard shortcuts
    );
    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}


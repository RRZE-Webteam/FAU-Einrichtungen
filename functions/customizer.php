<?php
/*-----------------------------------------------------------------------------------*/
/* WP Customizer for theme settings
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since FAU-Einrichtungen 1.9.2
/*-----------------------------------------------------------------------------------*/


add_action( 'customize_register', 'fau_customizer_settings' );
function fau_customizer_settings( $wp_customize ) {
    global $defaultoptions;
	// List of all options, including defaults and fixed theme definitions
    global $setoptions;
	// list of options, that may be changed
    global $options;
    global $OPTIONS_NAME;

   $thememods = get_theme_mods();
   
   if (!current_user_can('manage_sites')) {
       $wp_customize->remove_section( 'custom_css' );
	// Nichts da mit eigenen CSS - Corporate Design rulez.. *huestel*
    }
    
    
    $wp_customize->get_setting( 'blogname' )->transport		= 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport	= 'postMessage';	
    $num = 0;
       
    $definedtypes = array(
	"text", "checkbox", "radio", "select", "textarea", "dropdown-pages", "email", "url", "number", "hidden", "date",
	    // defaults
	"bool", "html", "image", "multiselectlist"
	    // self defined boolean

    );
  
    
    foreach($setoptions[$OPTIONS_NAME] as $tab => $value) {        
	$tabtitel = $value['tabtitle'];    
	
	$desc = '';
	$capability = 'edit_theme_options';
	if (isset($value['capability'])) 
	    $capability = $value['capability'];
	if (isset($value['desc']))
	    $desc = esc_html($value['desc']);
	
	$num = $num +1;
	$wp_customize->add_panel( $tab, array(
		'priority'	=> $num,
	        'capability'	=> $capability,
		'title'		=> esc_html($tabtitel),
		'description'	=> $desc,
	) );
	if (isset($setoptions[$OPTIONS_NAME][$tab]['fields'])) {
	    
	    $nosectionentries = array();	  
	    $sectionprio = 0;
	    foreach($setoptions[$OPTIONS_NAME][$tab]['fields'] as $field => $value) {  
		$sectionprio = $sectionprio +1; 
		if ($value['type'] == 'section') {
		    // Definition einer Section
		    $desc = '';
		    $title = '';
		    $capability = '';
		    if (isset($value['capability'])) 
			$capability = $value['capability'];
	
		    $thisprio = $sectionprio;
		    if (isset($value['priority'])) 
			$thisprio = $value['priority'];
		    if (isset($value['title']))
			$title = esc_html($value['title']);
		    if (isset($value['desc']))
			$desc = esc_html($value['desc']);

		    
		    $sectionid = esc_html($field);
		    
		    $wp_customize->add_section( $sectionid , array(
			'title'		=> $title,
			'description'	=> $desc,
			'panel' 	=> $tab,
			'capability'	=> $capability,
			'priority'	=> $thisprio,
		    ) ); 
		    
		}
	    }
	    $sectionprio = $sectionprio +1; 
	    $sectionid = $tab."-elsesection";
	    $wp_customize->add_section( $sectionid , array(
			'title'		=> __('Sonstiges','fau'),
			'panel' 	=> $tab,
			'priority'	=> $sectionprio,
		    ) ); 
	    
	    foreach($setoptions[$OPTIONS_NAME][$tab]['fields'] as $field => $value) {   
		if ($value['type'] == 'section') {
		    // nothing to do
		} else {
		    if (isset($value['parent'])) {
			 $section = $value['parent'];
		    } else {
			 $section =  $tab."-elsesection";
		    }
		    // Gehoert zu einer Section
		    $title = $desc = $label = $type = '';
		    $optionid = esc_html($field); // $OPTIONS_NAME."[".esc_html($field)."]";
		    
		    
		    
		    
		    if (isset($value['title']))
			$title = esc_html($value['title']);
		    if (isset($value['desc']))
			$desc = $value['desc'];
		    if (isset($value['label']))
			$label = $value['label'];
		    
		    if (isset($value['default'])) {
			$default = $value['default'];  
		    } elseif (isset($defaultoptions[$field])) {
			$default = $defaultoptions[$field];  
		    }	
		   
		    
		    $type = $value['type'];
		    if (!in_array($type, $definedtypes)) {
			$type = 'text';
		    }
		    
		    
			
			
			
			
			if ($type == 'bool') {    
			    $wp_customize->add_setting( $optionid , array(
				'default'     => $default,
				'transport'   => 'refresh',
				'sanitize_callback' => 'fau_sanitize_customizer_bool'
			    ) );
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'checkbox',
				    
			    ) );
			     
			} elseif (($type == 'select') || ($type == 'multiselectlist')) {    
			    $wp_customize->add_setting( $optionid , array(
				'default'     => $default,
				'transport'   => 'refresh',
			    ) );
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'select',
				    'choices'		=>  $value['liste']
				    
			    ) );
			} elseif ($type == 'html') {    
			    $wp_customize->add_setting( $optionid , array(
				'default'     => $default,
				'transport'   => 'refresh',
			    ) );
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'textarea',
				    
			    ) );     
			} elseif ($type == 'image') {    
			  
			    $width = 0;
			    $flexwidth = false;
			    $height = 0;
			    $flexheight = false;
			    
			    if (isset($value['width'])) {
				$width = $value['width'];
			    }
			     if (isset($value['height'])) {
				$height = $value['height'];
			    }
			    if (isset($value['maxwidth'])) {
				$width = $value['maxwidth'];
				$flexwidth = true;
			    }
			    if (isset($value['maxheight'])) {
				$height = $value['maxheight'];
				$flexheight = true;
			    }

			   
			//    if ((!isset($default))|| (empty($default))) {
			/*	$oldimageid = esc_html($field)."_id";
				if (isset($options[$oldimageid]) && ($options[$oldimageid]>0)) {
				    $default = $options[$oldimageid];
				} 
			*/	 
				 
			  // }
			    
			    $wp_customize->add_setting( $optionid , array(
				'default'     => $default,
				'transport'   => 'refresh',
			    ) );
			   
			    
			    $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, $optionid, array(
				'section'     => $section,
				'label'       => $title,
				'description' => $label,
				'flex_width'  => $flexwidth,
				'flex_height' => $flexheight,
				'width'       => $width,
				'height'      => $height,
			    ) ) );	
			     
			     
			} else {
			     $wp_customize->add_setting( $optionid , array(
				'default'     => $default,
				'transport'   => 'refresh',
			    ) );
			    $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> $type,		    
			    ) );
			}

		    
		}
	    }
	    
	    
	}
	
	
	
	
    }
    
}

/*--------------------------------------------------------------------*/
/* Sanitize bool
/*--------------------------------------------------------------------*/
function fau_sanitize_customizer_bool( $value ) {
    if ( ! in_array( $value, array( true, false ) ) )
        $value = false;
 
    return $value;
}

/*--------------------------------------------------------------------*/
/* EOCustomizer
/*--------------------------------------------------------------------*/
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
    $theme_mod_array = 'fau_theme_options';
	// Name of array in theme options
    
    $wp_customize->get_setting( 'blogname' )->transport		= 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport	= 'postMessage';	
    $num = 0;
       
    $definedtypes = array(
	"text", "checkbox", "radio", "select", "textarea", "dropdown-pages", "email", "url", "number", "hidden", "date",
	    // defaults
	"bool", "html"
	    // self defined boolean

    );
  
    
    foreach($setoptions['fau_theme_options'] as $tab => $value) {        
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
	if (isset($setoptions['fau_theme_options'][$tab]['fields'])) {
	    
	    $nosectionentries = array();	  
	    $sectionprio = 0;
	    foreach($setoptions['fau_theme_options'][$tab]['fields'] as $field => $value) {  
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
	    
	    foreach($setoptions['fau_theme_options'][$tab]['fields'] as $field => $value) {   
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
		    
		   
		    $optionid = esc_html($field);
		    if (isset($value['title']))
			$title = esc_html($value['title']);
		    if (isset($value['desc']))
			$desc = $value['desc'];
		    if (isset($value['label']))
			$label = esc_html($value['label']);
		    if (isset($value['default']))
			$default = $value['default'];  
		      
		   
		    
		    $type = $value['type'];
		    if (!in_array($type, $definedtypes)) {
			$type = 'text';
		    }
		    
		    
			
			
			$wp_customize->add_setting( $optionid , array(
			    'default'     => $default,
			    'transport'   => 'refresh',
			) );
			
			if ($type == 'bool') {    
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'checkbox',
				    
			    ) );
			     
			} elseif ($type == 'select') {    
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'select',
				    'choices'		=>  $value['liste']
				    
			    ) );
			} elseif ($type == 'html') {    
			     $wp_customize->add_control( $optionid, array(
				    'label'             => $title,
				    'description'	=> $label,
				    'section'		=> $section,
				    'settings'		=> $optionid,
				    'type' 		=> 'textarea',
				    
			    ) );     
			} else {
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

/*
 * 
    foreach($setoptions['fau_theme_options'] as $tab => $value) {        
	    
	$tabtitel = $value['tabtitle'];             
	$num = $num +1;
echo $tabtitel;
	
	// Custom Uku panels:
	$wp_customize->add_panel( $tab, array(
		'priority'			=> $num,
		'theme_supports' 	         => '',
	        'capability'		=> 'edit_theme_options',

		'title' 	                 => esc_html($tabtitel)
	) );

	
	 if (isset($setoptions['fau_theme_options'][$tab]['fields'])) {
	    $setsection = '';
            foreach($setoptions['fau_theme_options'][$tab]['fields'] as $i => $value) {   
                            $name = $i;
			    $mark_option =0;
			    $userlvl = 0;
                            if (isset($value['title'])) $title = $value['title'];
                            if (isset($value['type'])) $type = $value['type'];
                            if (isset($value['label'])) $label = $value['label'];
                            if (isset($value['parent'])) $parent = $value['parent'];
                            if (isset($value['liste'])) $liste = $value['liste']; 
			    if (isset($value['user_level'])) $userlvl = $value['user_level'];
			    if (isset($value['mark_option']) && $value['mark_option']==1) $mark_option =1; 
			     

                            if ($type == 'section') {
				$wp_customize->add_section( 'cd_colors' , array(
				    'title'      => $title,
				    'panel'	 => $tab,
				) );
				
			    }
	    }
	 }
			

		    
    }
 */
<?php
/*-----------------------------------------------------------------------------------*/
/* WP Customizer for theme settings
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since FAU-Einrichtungen 1.9.2
 * 
 * TODO: Update and check code... outdated
 */
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
        // Eigenes CSS ist aus Corporate Design Gründen nur Supoeradmins erlaubt
    }
    
    add_theme_support('static_front_page' );
    // Changing Defaults
    $wp_customize->get_setting( 'blogname' )->transport		= 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport	= 'postMessage';	
    $wp_customize->remove_section( 'colors' );


    $wp_customize->add_section( 'static_front_page' , array(
        'title'	=> __('Startseite','fau'),
        'priority'	=> 20,
    ) ); 
    // Workaround for https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues/1131
    
    $num = 0;
    $definedtypes = array(
        "text", "checkbox", "radio", "select", "textarea", "dropdown-pages", "email", 
        "url", "number", "hidden", "date",
            // defaults
        "bool", "html", "image", "multiselect", "multiselectlist", "urlchecklist", 
        "range", "range-value", "category", "toggle", "toogle-switch"
            // self defined boolean
    );
    $var = apply_filters('fau_setoptions',$setoptions[$OPTIONS_NAME]);
    
    foreach($var as $tab => $value) {        
	
        if (!in_array($tab, ['title_tagline', 'colors', 'header_image', 'background_image', 'nav', 'static_front_page'])) {
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
        }
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
                    $notifplugin = $ifplugin = $ifclassexists = $iffunctionexists = $ifmodvalue = '';
                    $superadminonly = false;
                    $input_attrs = array();
                    $optionid = esc_html($field); 

                    if (isset($value['title']))
                        $title = esc_html($value['title']);
                    if (isset($value['desc']))
                        $desc = $value['desc'];
                    if (isset($value['label']))
                        $label = $value['label'];
                    if (isset($value['notifplugin']))
                        $notifplugin = $value['notifplugin'];
                    if (isset($value['ifplugin']))
                        $ifplugin = $value['ifplugin'];
                    if (isset($value['ifclass']))
                        $ifclassexists = $value['ifclass'];
                    if (isset($value['iffunction']))
                        $iffunctionexists = $value['iffunction'];
                    if (isset($value['ifsuperadmin']))
                        $superadminonly = $value['ifsuperadmin'];
                    if (isset($value['input_attrs'])) {
                        $input_attrs = $value['input_attrs'];
                    }




                    if (isset($value['default'])) {
                        $default = $value['default'];  
                    } elseif (isset($defaultoptions[$field])) {
                        $default = $defaultoptions[$field];  
                    }	


                    $type = $value['type'];
                    if (!in_array($type, $definedtypes)) {
                        $type = 'text';
                    }
                    $breakthiscontrol = false;


                    if ($notifplugin) {
                        if ( is_plugin_active( $notifplugin ) ) {
                            $breakthiscontrol = true;
                        }
                    }
                    if ($ifplugin) {
                        if ( !is_plugin_active( $ifplugin ) ) {
                            $breakthiscontrol = true;
                        }                        
                    }
                    if ($ifclassexists) {
                        if (!class_exists($ifclassexists)) {
                            $breakthiscontrol = true;
                        }
                    }
                    if ($iffunctionexists) {
                        if (!function_exists($iffunctionexists)) {
                            $breakthiscontrol = true;
                        }
                    }
                    if ($superadminonly) {
                        if (! is_super_admin() ) {
                             $breakthiscontrol = true;
                        }
                    }  
                    if (isset($value['ifmodvalue']) && isset($value['ifmodname'])) {
                        $modvalue = $value['ifmodvalue'];
                        $modname = $value['ifmodname'];

                        $curval = get_theme_mod($modname);
                        if ($curval != $value['ifmodvalue']) {
                            $breakthiscontrol = true;
                        }		
                    }



                    if ($breakthiscontrol==false) {

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
                        } elseif (($type == 'toggle') || ($type == 'toggle-switch')) {
                            $wp_customize->add_setting( $optionid, array(
                                    'default' => 0,
                                    'transport' => 'refresh',
                                    'sanitize_callback' => 'fau_sanitize_customizer_toggle_switch'
                                )
                            );
                            $wp_customize->add_control( new WP_Customize_Control_Toggle_Switch( $wp_customize, $optionid, array(
                                    'label' => $title,
                                    'section' => $section,
                                    'description'	=> $label,
                                )
                            ) );			    
                        } elseif (($type == 'range') || ($type == 'range-value')) {
                            $wp_customize->add_setting( $optionid , array(
                                'default'     => $default,
                                'transport'   => 'refresh',
                                'sanitize_callback' => 'fau_sanitize_customizer_range'
                            ) );

                            $min = 0;
                            $max = $step = 1;
                            $suffix = '';

                            if (isset($value['min'])) {
                                $min = $value['min'];
                            }
                             if (isset($value['max'])) {
                                $max = $value['max'];
                            }
                            if (isset($value['step'])) {
                                $step = $value['step'];
                            }
                            if (isset($value['suffix'])) {
                                $suffix = $value['suffix'];
                            }

                            $wp_customize->add_control( new WP_Customize_Range_Value_Control( $wp_customize, $optionid, array(
                                'type'     => 'range-value',
                                'label'             => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'input_attrs' => array(
                                    'min'    => $min,
                                    'max'    => $max,
                                    'step'   => $step,
                                    'suffix' => $suffix, //optional suffix
                                ),
                            ) ) );
                        } elseif ($type == 'category')  {    
                            $wp_customize->add_setting( $optionid , array(
                                'default'     => $default,
                                'transport'   => 'refresh',
                            ) );
                            $wp_customize->add_control( new WP_Customize_Category_Control( $wp_customize, $optionid, array(
                                'label'             => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'type' 		=> 'category',
                                'input_attrs'	=> $input_attrs

                            ) ) );
                        } elseif ($type == 'select')  {    
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
                                'choices'		=>  $value['liste'],
                                'input_attrs'	=> $input_attrs

                            ) );
                        } elseif (($type == 'multiselect') || ($type == 'multiselectlist')) {
                            $wp_customize->add_setting( $optionid , array(
                                'default'     => $default,
                                'transport'   => 'refresh',
                            ) );
                            $wp_customize->add_control(  new WP_Customize_Control_Multiple_Select( $wp_customize, $optionid, array(
                                'label'         => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'type'          => 'multiple-select',
                                'choices'		=>  $value['liste'],
                                'input_attrs'	=> $input_attrs

                            ) ) );

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
                                'input_attrs'	=> $input_attrs

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
                                'input_attrs'	=> $input_attrs
                            ) ) );	

                        } elseif ($type == 'number') {    
                            $wp_customize->add_setting( $optionid , array(
                                'default'           => $default,
                                'transport'         => 'refresh',
                                'sanitize_callback' => 'fau_sanitize_customizer_number'	
                            ) );
                            $wp_customize->add_control( $optionid, array(
                                'label'         => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'type'          => 'number',
                                'input_attrs'	=> $input_attrs
                            ) );          
                        } elseif ($type == 'text') {    
                            $wp_customize->add_setting( $optionid , array(
                                'default'           => $default,
                                'transport'         => 'refresh',
                                'sanitize_callback' => 'sanitize_text_field'	
                            ) );
                             $wp_customize->add_control( $optionid, array(
                                'label'             => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'type' 		=> 'text',
                                'input_attrs'	=> $input_attrs

                            ) );     


                        } else {
                             $wp_customize->add_setting( $optionid , array(
                                'default'     => $default,
                                'transport'   => 'refresh',
                            ) );
                            $wp_customize->add_control( $optionid, array(
                                'label'         => $title,
                                'description'	=> $label,
                                'section'		=> $section,
                                'settings'		=> $optionid,
                                'type'          => $type,	
                                'input_attrs'	=> $input_attrs				
                            ) );
                        }

                    }
                }
            }
        }
	
    }
    
   
    
    /*-----------------------------------------------------------------------------------*/
    /* Plugin FAU ORGA Breadcrumb: Add to customizer
    /*-----------------------------------------------------------------------------------*/

     if ( is_plugin_active( 'fau-orga-breadcrumb/fau-orga-breadcrumb.php' ) ) {
        // Wenn das FAU.ORG Plugin vorhanden und aktiv ist, erlaube es hier, die Option
        // dazu zu verwalten

           $wp_customize->add_setting( 'fau_orga_breadcrumb_options[site-orga]', array(
            'default'		    => '',
            'sanitize_callback'	    => 'wp_kses_post',
            'type'		    => 'option'
        ) );
        $wp_customize->add_control( 'fau_orga_breadcrumb_options[site-orga]', array(
            'label'		    => esc_html__( 'Organisatorische Zuordnung', 'fau'),
            'description'	    => esc_html__( 'Wählen Sie hier die organisatorische Einheit aus, zu der Ihre Einrichtung oder Ihr Webauftritt gehört.', 'fau'),
            'section'	    => 'webgroup',
            'type'		    => 'select',
            'choices'	    => get_fau_orga_breadcrumb_customizer_choices(),
            'priority'	    => 5,
        ) );
    }
}

/*--------------------------------------------------------------------*/
/* Multiple select customize control class.
/*--------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Control_Multiple_Select extends WP_Customize_Control {
	// The type of customize control being rendered.
	public $type = 'multiple-select';

	//Displays the multiple select on the customize screen.
	public function render_content() {
	    if ( empty( $this->choices ) )
		return;
	    ?>
		<label>
		    <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		    <?php endif;
		    if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		    <?php endif; ?>
		    <select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
			<?php
			    foreach ( $this->choices as $value => $label ) {
				$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
				echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
			    }
			?>
		    </select>
		</label>
	<?php }
    }
}
/*--------------------------------------------------------------------*/
/* Toogle switch
 * adapted from https://github.com/maddisondesigns/customizer-custom-controls
/*--------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Control_Toggle_Switch extends WP_Customize_Control {
	// The type of control being rendered
	public $type = 'toogle-switch';


	public function render_content(){
	?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
	<?php
	}
    }
}
/*-----------------------------------------------------------------------------------*/
/* Add Custom Customizer Controls - Category Dropdown
/*-----------------------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Colorlist_Radio extends WP_Customize_Control {
        // The type of customize control being rendered.
        public $type = 'colorlist-radio';

        // Displays the multiple select on the customize screen.
        public function render_content() {
            if ( empty( $this->choices ) )
                return;
           ?>
                <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif;
                if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
                    <div class="colorlist-radio-group">
                        
                        
                        
                    <?php foreach ( $this->choices as $name => $value ) : ?>                        
                        <label for="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>_<?php echo esc_attr( $name ); ?>" <?php if ($value=="#000") { echo 'style="color: white;"'; } ?>>
                            <input name="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>" id="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>_<?php echo esc_attr( $name ); ?>" type="radio" value="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $name ); ?> >
                                <span class="colorbox" style="background-color: <?php echo esc_attr( $value ); ?>">&nbsp;</span>
                            </input>
                            <span class="screen-reader-text"><?php echo ucfirst(esc_attr( $name) ); ?></span>
                        </label>
                    <?php endforeach; ?>
                        
                        <label for="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>_reset">
                            <input name="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>" id="_customize-colorlist-radio_<?php echo esc_attr( $this->id ); ?>_reset" type="radio" value="" <?php $this->link(); checked( $this->value(), "" ); ?> >
                                <span class="reset"><?php echo __("Reset",'fau'); ?></span> 
                            </input>
                        </label>
                    </div>
	<?php }
        
    }
}

/*-----------------------------------------------------------------------------------*/
/* Add Custom Customizer Controls - Category Dropdown
/*-----------------------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Category_Control extends WP_Customize_Control {

        public function render_content() {
	    ?>

		    <label>
		    <?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		    <?php endif;
		    if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		    <?php endif;


			    $dropdown = wp_dropdown_categories(
				array(
				    'name'              => '_customize-dropdown-categories-' . $this->id,
				    'echo'              => 0,
				    'show_option_none'  => __( '&mdash; Auswählen &mdash;', 'fau' ),
				    'option_none_value' => '0',
				    'selected'          => $this->value(),
				)
			    );

			    // Hackily add in the data link parameter.
			    $dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
			    echo $dropdown;

		    ?>	
		    </label>

	    <?php

        }
    }
}
/*-----------------------------------------------------------------------------------*/
/* Add Custom Customizer Controls - Range Value Control
 * adapted from https://github.com/soderlind/class-customizer-range-value-control 
/*-----------------------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Range_Value_Control extends WP_Customize_Control {
	
	public $type = 'range-value';

	public function enqueue() {
	    global $defaultoptions;
	    wp_enqueue_script( 'customizer-range-value-control', $defaultoptions['src-admin-customizer-js'], array( 'jquery' ), rand(), true );
	}
	// wird in dem admin js gebundelt
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="range-slider"  style="width:100%; display:flex;flex-direction: row;justify-content: flex-start;">
				<span  style="width:100%; flex: 1 0 0; vertical-align: middle;"><input class="range-slider__range" type="range" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); $this->link(); ?>>
				<span class="range-slider__value">0</span></span>
			</div>
			<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
    }
}
/*--------------------------------------------------------------------*/
/* EOCustomizer
/*--------------------------------------------------------------------*/
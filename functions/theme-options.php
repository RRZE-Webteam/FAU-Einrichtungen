<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'fau_options',
                'fau_theme_options', 
                'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'fau' ),
                        __( 'Theme Options', 'fau' ), 
                       'edit_theme_options', 
                        'theme_options', 
                        'theme_options_do_page' );
                          
}


/**
 * Create the options page
 */
function theme_options_do_page($tab = '') {
        global $setoptions;  
        global $options;
        
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

    /*
     *  Reset Options for Theme Option Selector by website type
     */
    global $default_fau_orga_faculty;
    global $defaultoptions;
    
    $options['website_usefaculty'] = $defaultoptions['website_usefaculty'];
    if ( (isset($options['website_usefaculty'])) && (in_array($options['website_usefaculty'],$default_fau_orga_faculty))) {
	
	    $setoptions['fau_theme_options']['website']['fields']['website_type']['liste'] = array(
		0 => __('Fakultätsportal','fau'), 
		1 => __('Department, Lehrstuhl, Einrichtung','fau'));
    } else {
	    $setoptions['fau_theme_options']['website']['fields']['website_type']['liste'] = array(
		2 => __('Zentrale Einrichtung','fau') ,
		3 => __('Website für uniübergreifende Kooperationen mit Externen','fau') );
    }
    
    if (isset($defaultoptions['website_allow_fauportal'])) {
	$siteurl = get_site_url();
	$siteurl = preg_replace("(^https?://)", "", $siteurl );
	if (in_array($siteurl,$defaultoptions['website_allow_fauportal'])) {
	    $setoptions['fau_theme_options']['website']['fields']['website_type']['liste']['-1'] =  __('Zentrales FAU-Portal','fau');
	}
    }
 ?>

	<div class="wrap">            
            <div class="fau-optionen">  <!-- begin: .fau-optionen -->    
            <?php echo "<h2>" . wp_get_theme().': ' . __( 'Konfiguration ändern', 'fau' ) . "</h2>"; ?>

            <?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
            <div class="updated fade"><p><strong><?php _e( 'Neue Konfiguration gespeichert.', 'fau' ); ?></strong></p></div>
            <?php endif; 

        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
        }
        if ((!isset($tab)) || (empty($tab))) {
            $tab = $options['optionpage-tab-default'];
        }
        if (!isset($setoptions['fau_theme_options'][$tab])) {
           // echo "Invalid Tab-Option or undefined Option-Field $tab";            
	    $tab = array_keys($setoptions['fau_theme_options'])[0];
        }        
	
        $myuserlvl = 0;
	if (isset($options['admin_user_level'])) {
	    $myuserlvl = $options['admin_user_level'];
	}
	$askfieldlist = '';
	
        echo "<p class=\"nav-tab-wrapper\">\n";
        foreach($setoptions['fau_theme_options'] as $i => $value) {        
	    
	    if ((isset($value['user_level']) && ($myuserlvl >= $value['user_level'])) || (!isset($value['user_level']))) { 	    
		$tabtitel = $value['tabtitle'];             
		 echo "<a href=\"?page=theme_options&amp;tab=$i\" class=\"nav-tab ";
		 if ($tab==$i) {
		     echo "nav-tab-active";
		 }
		 echo "\">$tabtitel</a>\n";	      
	    }
        }
        echo "</p>\n";  ?>
         
                      
        <form method="post" action="options.php">
            <?php settings_fields( 'fau_options' ); ?>
            <input type="hidden" id="fau_theme_options[tab]" name="fau_theme_options[tab]"  value="<?php echo $tab; ?>">                       
          
          
        <div id="einstellungen">                                       
	<table>	
                <?php
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
				
				if ((($userlvl>0) && ($myuserlvl >= $userlvl)) || ($userlvl<1)) { 	 
				
				    if ((isset($setsection)) && ($setsection != "")) {
					echo "\t\t\t</table>\n";   
					echo "\t\t</td>\n";
					echo "\t</tr>\n";
				    }
				    echo "\t<tr valign=\"top\">\n\t\t<th scope=\"row\">";
				    echo $title;
				    echo "</th>\n\t\t<td>";                                 
				    echo "\t\t\t<table class=\"suboptions\">\n";      
				    $setsection = $name;
				    $setsectionusrlvl = $userlvl;
				    
				} elseif ($userlvl>0) {
				    $setsectionusrlvl = $userlvl;
				}
				
	
			    
                            } elseif (   (isset($setsection) && ($setsection != "") && ($myuserlvl >= $setsectionusrlvl))
				    ||   ((!$setsection) && ($myuserlvl >= $userlvl)) ) {
				  
				$askfieldlist .= $name.",";
				
                               echo "\t<tr valign=\"top\" class=\"option-".$name;
			       if ($mark_option==1) {
				   echo " mark-option";
			       }
			       echo "\">\n\t\t<th scope=\"row\">";
                               echo $title;
                               echo "</th>\n\t\t<td>";

                                if ((!isset($options[$name])) && (isset($value['default'])) && (!empty($value['default']))) {                                       
                                        $options[$name] = $value['default'];                                                                               
                                }
				
                                if ($type =='bool') {
                                    echo "\t\t\t";
                                    echo "<input id=\"$name\" name=\"fau_theme_options[$name]\" 
                                            type=\"checkbox\" value=\"1\" ";
				    
				    if (isset($options[$name])) echo checked( $options[$name],1,false );
				    echo ">\n";
                                    echo "\t\t\t";
                                    echo "<label for=\"$name\">$label</label>\n";                                     
                                } elseif (($type=='text') || ($type=='email')) {
                                    echo "\t\t\t";
                                    echo "<input class=\"regular-text\" id=\"fau_theme_options[$name]\" 
                                            type=\"text\" name=\"fau_theme_options[$name]\" 
					    value=\"";
				    if (isset($options[$name])) echo esc_attr( $options[$name] );				
				    
				    echo "\"><br>\n";
                                    echo "\t\t\t";
                                    echo "<label for=\"fau_theme_options[$name]\">$label</label>\n";
				} elseif (($type=='html') ||($type=='url')) {
                                    echo "\t\t\t";
                                    echo "<input class=\"large-text\" id=\"fau_theme_options[$name]\" 
                                            type=\"text\" name=\"fau_theme_options[$name]\" 
					    size=\"120\" value=\"";
				    if (isset($options[$name])) echo esc_attr( $options[$name] );				
				    
				    echo "\"><br>\n";
                                    echo "\t\t\t";
                                    echo "<label for=\"fau_theme_options[$name]\">$label</label>\n";
				} elseif ($type=='imgurl') {
                                    echo "\t\t\t";
                                    echo "<input class=\"large-text\" id=\"fau_theme_options[$name]\" 
                                            type=\"text\" name=\"fau_theme_options[$name]\" 
					    size=\"120\" value=\"";
				    if (isset($options[$name])) echo esc_attr( $options[$name] );				
				    echo "\"><br>\n";
				    if (isset($options[$name])) {
					    echo "<img class=\"imgurl\" src=\"".esc_attr( $options[$name] )."\" alt=\"\">\n";
				    }    
                                    echo "\t\t\t";
                                    echo "<label for=\"fau_theme_options[$name]\">$label</label>\n";    

				    
                                } elseif ($type=='textarea')  {
                                    echo "\t\t\t";                                                                                                            
                                    echo "<textarea class=\"large-text\" id=\"fau_theme_options[$name]\" 
                                            cols=\"30\" rows=\"10\"  name=\"fau_theme_options[$name]\">";
				    if (isset($options[$name])) echo esc_attr( $options[$name] );
				    echo "</textarea><br>\n";
                                    echo "\t\t\t";
                                    echo "<label for=\"fau_theme_options[$name]\">$label</label>\n";    
                                } elseif ($type=='file') {
                                    echo "\t\t\t";        
				    echo '<div class="uploader">';				  		   
				    ?>
				    
				    <input type="hidden" name="fau_theme_options[<?php echo $name; ?>]" id="file_<?php echo $name; ?>" 
					     value="<?php if (isset($options[$name])) echo sanitize_key( $options[$name]); ?>" />
				    
				    <input type="text" name="fau_theme_options[<?php echo $name; ?>_url]" id="file_<?php echo $name; ?>_url" 
					   value="<?php if (isset($options[$name])) echo wp_get_attachment_url( esc_attr( $options[$name]) ); ?>" />
				    <input class="button" name="file_button_<?php echo $name; ?>" id="file_button_<?php echo $name; ?>" value="<?php _e('Add file', 'fau'); ?>" />
				    <small><a href="#" class="file_remove_<?php echo $name; ?>"><?php _e( "Remove file", 'fau' );?></a></small>
				    <br><label for="fau_theme_options[<?php echo $name; ?>]"><?php echo $label; ?></label>
				    </div><script>
				    jQuery(document).ready(function() {
					jQuery('#file_button_<?php echo $name; ?>').click(function()  {
					    wp.media.editor.send.attachment = function(props, attachment) {
						jQuery('#file_<?php echo $name; ?>_url').val(attachment.url);
						jQuery('#file_<?php echo $name; ?>').val(attachment.id);						
					    }
					    wp.media.editor.open(this);
					    return false;
					});
				    });
				    jQuery(document).ready(function() {
					jQuery('.file_remove_<?php echo $name; ?>').click(function()   {
						jQuery('#file_<?php echo $name; ?>').val('');
						jQuery('#file_<?php echo $name; ?>_url').val('');						
						return false;
					});
				    });		
				    </script>  <?php 	
    
				} elseif (($type=='imageurl') || ($type=='image')) {
                                    echo "\t\t\t"; 
				    echo '<div class="uploader">';
				    echo '<div class="previewimage showimg_'.$name.'">';
				    $addstyle = '';
				    if (isset($value['maxwidth'])) {
					$addstyle .= 'max-width: '.$value['maxwidth'].'px;';
				    }
				    if (isset($value['maxheight'])) {
					$addstyle .= 'max-height: '.$value['maxheight'].'px;';
				    }
				   		   
				     if ((isset($options[$name])) && esc_url( $options[$name])) { 
					  echo '<img src="'.esc_url( $options[$name]).'" class="image_show_'.$name.'"';
					  if (isset($addstyle) && strlen($addstyle)>1) {
					      echo ' style="'.$addstyle.'"';
				           }				   	
					  echo '/>';
				    } else {
					   _e('No Image selected', 'fau');
				     }				   
				    ?>		
				    </div>
				    <input type="hidden" name="fau_theme_options[<?php echo $name; ?>_id]" id="image_<?php echo $name; ?>_id" 
					     value="<?php if ( isset( $options[$name."_id"] ) ) echo sanitize_key( $options[$name."_id"] ) ; ?>" />
				    
				    <input type="text" name="fau_theme_options[<?php echo $name; ?>]" id="image_<?php echo $name; ?>" value="<?php if ( isset( $options[$name] ) ) echo esc_attr( $options[$name] ) ; ?>" />
				    <input class="button" name="image_button_<?php echo $name; ?>" id="image_button_<?php echo $name; ?>" value="<?php _e('Add Image', 'fau'); ?>" />
				    <small><a href="#" class="image_remove_<?php echo $name; ?>"><?php _e( "Remove image", 'fau' );?></a></small>
				    <?php if (isset($value['default']) && (filter_var($value['default'], FILTER_VALIDATE_URL))) { ?>
					<small><a href="#" class="image_reset_<?php echo $name; ?>"><?php _e( "Reset to default", 'fau' );?></a></small>
				    <?php } ?>
    				    
				    <br><label for="fau_theme_options[<?php echo $name; ?>]"><?php echo $label; ?></label>
				    </div><script>
				    jQuery(document).ready(function() {
					jQuery('#image_button_<?php echo $name; ?>').click(function()  {
					    wp.media.editor.send.attachment = function(props, attachment) {
						jQuery('#image_<?php echo $name; ?>').val(attachment.url);
						jQuery('#image_<?php echo $name; ?>_id').val(attachment.id);
						htmlshow = "<img src=\""+attachment.url + "\">";  					   
						jQuery('.showimg_<?php echo $name; ?>').html(htmlshow);

					    }
					    wp.media.editor.open(this);
					    return false;
					});
				    });
				    jQuery(document).ready(function() {
					jQuery('.image_remove_<?php echo $name; ?>').click(function()   {
						jQuery('#image_<?php echo $name; ?>').val('');
						jQuery('#image_<?php echo $name; ?>_id').val('');
						jQuery('.showimg_<?php echo $name; ?>').html('<?php _e('No Image selected', 'fau'); ?>');
						return false;
					});
				    });
				    <?php if (isset($value['default']) && (filter_var($value['default'], FILTER_VALIDATE_URL))) { ?>
				    jQuery(document).ready(function() {
					jQuery('.image_reset_<?php echo $name; ?>').click(function()   {
						jQuery('#image_<?php echo $name; ?>').val("<?php echo $value['default']; ?>");
						jQuery('#image_<?php echo $name; ?>_id').val(0);
						htmlshow = "<img src=\"<?php echo $value['default']; ?>\">";  					   
						jQuery('.showimg_<?php echo $name; ?>').html(htmlshow);
						return false;
					});
				    });
				    <?php } ?>
				    </script>  <?php 		    
                                           
                                } elseif ($type=='number') {
                                    echo "\t\t\t";
                                    echo "<input class=\"number\" size=\"5\" id=\"fau_theme_options[$name]\" 
                                            type=\"text\" name=\"fau_theme_options[$name]\" 
                                            value=\"".esc_attr( $options[$name] )."\"><br>\n";
                                    echo "\t\t\t";
                                    echo "<label for=\"fau_theme_options[$name]\">$label</label>\n";  
				} elseif ($type=='bildlist') {
				   echo "\t\t\t";                                    
                                    foreach($liste as $i => $value) {   
                                        echo "\t\t\t\t";
					$src = $value['src'];
					$label = $value['label'];
					echo "<label class=\"tile";
					if ( $src == $options[$name] ) {
                                            echo ' checked';
                                        }  
					echo "\">\n";
                                        echo '<input type="radio" value="'.$src.'" 
					    name="fau_theme_options['.$name.']"';
                                        if ( $src == $options[$name] ) {
                                            echo ' checked="checked"';
                                        }                                                                                                                                                                
                                        echo '> ';
                                        echo $label.'<br><img src="'.$src.'" alt="" style="width: 320px; height: auto;">';					                                                                                                                                                                                                      
                                        echo "</label>\n";                                          
                                    }                                                                          
                                    echo "<br style=\"clear: left;\">\n";
                                } elseif ($type=='bildchecklist') {
				   echo "\t\t\t";                      
                                    foreach ( $liste as $option ) {    
                                        $checked = '';
                                        if ((isset($options[$name])) && (is_array($options[$name]))) {
                                            foreach ($options[$name] as $current) {    
                                                if ($current == $option['src']) {
                                                     $checked = "checked=\"checked\"";                                                                                            
                                                     break;
                                                }                                        
                                            }
                                        }                                    
                                         ?>       
                                        <label class="plakattile">
                                            <div style="height: 40px; width: 100%; margin:0 auto; background-color: #F28900; color: white; display: block;">  
                                            <input type="checkbox" name="fau_theme_options[<?php echo $name?>][]" 
                                                   value="<?php echo esc_attr( $option['src'] ); ?>" <?php echo $checked; ?> />                                                     
                                            <?php echo $option['label']?>
                                            </div>
                                            <div style="height: 395px; overflow: hidden; margin: 5px auto; width: 280px; padding: 0;">
                                            <img src="<?php echo $option['src'] ?>" style="width: 280px; height: auto;  ">
                                            </div>
                                        </label>
                                     <?php }                                                                                                                             
                                    echo "<br style=\"clear: left;\">\n";
                                } elseif ($type=='bilddirchecklist') {
				   echo "\t\t\t";      
				   $dir = get_template_directory().$value['default'];
				   
				  
				    if (is_dir($dir)) {	   
				       $contents = dirToArray($dir);
				       foreach ($contents as $key => $wert) {      
					   if (is_array($wert)) {	

					       echo "<h4>Ordner $key<h4>";
					       foreach ($wert as $sub) {	       
						   $bildurl = get_template_directory_uri().$value['default'].'/'.$key.'/'.$sub;
						   $checked = '';
						   if ((isset($options[$name])) && (is_array($options[$name]))) {
							foreach ($options[$name] as $current) {    
							    if ($current == $bildurl) {

								 $checked = "checked=\"checked\"";                                                                                            
								 break;
							    }                                        
							}
						    } ?>

						    <label class="plakattile" style="width: 150px; height: 251px">
							<div style="height: 40px; width: 100%; margin:0 auto; background-color: #F28900; color: white; display: block;">  
							<input type="checkbox" name="fau_theme_options[<?php echo $name?>][]" 
							       value="<?php echo esc_attr( $bildurl ); ?>" <?php echo $checked; ?> />                                                     
							<?php echo $sub ?>
							</div>
							<div style="height: 211px; overflow: hidden; margin: 5px auto; width: 150px; padding: 0;">
							<img src="<?php echo $bildurl ?>" style="width: 150px; height: auto;  ">
							</div>
						    </label>		
						    <?php    
					       }
					       echo "<br style=\"clear: left;\">\n";
					   }      
				       }
				       // First Dir only

				       $found=0;
				       foreach ($contents as $key => $wert) {    
					   if (!is_array($wert)) {              
						    if ($found==0) {
							print "<h4>Ordner ".$value['default']."</h4>";
							$found=1;
						    }
						   $bildurl = get_template_directory_uri().$value['default'].'/'.$wert;
						   $checked = '';
						   if ((isset($options[$name])) && (is_array($options[$name]))) {
							foreach ($options[$name] as $current) {    
							    if ($current == $bildurl) {

								 $checked = "checked=\"checked\"";                                                                                            
								 break;
							    }                                        
							}
						    } ?>

						    <label class="plakattile" style="width: 150px; height: 251px">
							<div style="height: 40px; width: 100%; margin:0 auto; background-color: #F28900; color: white; display: block;">  
							<input type="checkbox" name="fau_theme_options[<?php echo $name?>][]" 
							       value="<?php echo esc_attr( $bildurl ); ?>" <?php echo $checked; ?> />                                                     
							<?php echo $wert ?>
							</div>
							<div style="height: 211px; overflow: hidden; margin: 5px auto; width: 150px; padding: 0;">
							<img src="<?php echo $bildurl ?>" style="width: 150px; height: auto;  ">
							</div>
						    </label>		
						    <?php    	  	    	   	   	   	   
					   }

				       }
					echo "<br style=\"clear: left;\">\n"; 
				    }
                                   				    
				} elseif ($type=='urlchecklist') {				    				    
				   echo "\t\t\t";                      
                                    foreach ( $liste as $entry => $listdata ) {    
                                        $checked = '';
					$value = '';
                                        $active = 0;
				 	if (isset($options[$name][$entry]['content'])) {
						$value = $options[$name][$entry]['content'];
					} else {
						$value = $liste[$entry]['content'];
					 }
					 if (isset($options[$name][$entry]['active'])) {
						$active = $options[$name][$entry]['active'];		 
					 }
					 if (($active==1) && (filter_var($value, FILTER_VALIDATE_URL))) {
					    $checked = "checked=\"checked\"";   
					}
                                         ?>       
                                        <div style="display: inline-block; width: 90%;" class="<?php echo $name?>">
					    <label for="fau_theme_options[<?php echo $name?>][<?php echo $entry?>][active]" class="<?php echo $entry?>" style="width: 120px; display: inline-block;">
                                            <input type="checkbox" 
						   id="fau_theme_options[<?php echo $name?>][<?php echo $entry?>][active]"
						   name="fau_theme_options[<?php echo $name?>][<?php echo $entry?>][active]" 
                                                   value="1" <?php echo $checked; ?>>                                                                                               
					    <?php echo $liste[$entry]['name'] ?>
					    </label>
					    
                                            <input id="fau_theme_options[<?php echo $name?>][<?php echo $entry?>][content]" 
                                            type="text" name="fau_theme_options[<?php echo $name?>][<?php echo $entry?>][content]" 
					    size="80" value="<?php echo $value?>">
					  </div>
					 <?php    					    
				    }
				    if (isset($label)) {
					echo "<p>".$label."</p>\n";
				    }
                                    echo "<br style=\"clear: left;\">\n";				    
                                } elseif ($type=='select') {
                                    echo "\t\t\t";
                                    echo "<select id=\"$name\" name=\"fau_theme_options[$name]\">\n";

                                    foreach($liste as $i => $value) {   
                                        echo "\t\t\t\t";
                                        echo '<option value="'.$i.'"';
                                        if ( $i == $options[$name] ) {
                                            echo ' selected="selected"';
                                        }                                                                                                                                                                
                                        echo '>';
                                        if (!is_array($value)) {
                                            echo $value;
                                        } else {
                                            echo $i;
                                        }     
                                        echo '</option>';                                                                                                                                                              
                                        echo "\n";                                            
                                    }  
                                    echo "\t\t\t</select><br>\n";                                   
                                    echo "\t\t\t<label for=\"fau_theme_options[$name]\">$label</label>\n"; 
                                } elseif ($type=='multiselectlist') {
                                     echo "\t\t\t";         
                                    foreach ( $liste as $entry => $listdata ) {    
                                        $checked = '';
					$value = '';                                      
                                         foreach ($options[$name] as $cur) {
                                             if ($cur==$entry) {
                                                $checked = "checked=\"checked\""; 
                                               break;
                                             }
                                         }		
                                         ?>       
					    <label for="fau_theme_options[<?php echo $name?>][<?php echo $entry?>]" >                                                
                                            <input type="checkbox" 
						   id="fau_theme_options[<?php echo $name?>][<?php echo $entry?>]"
						   name="fau_theme_options[<?php echo $name?>][<?php echo $entry?>]" 
                                                   value="<?php echo $entry?>" <?php echo $checked; ?>>                                                                                               
					    <?php echo $liste[$entry] ?>
					    </label><br>
					 <?php    					    
				    }
				    if (isset($label)) {
					echo "<p>".$label."</p>\n";
				    }
                                 } elseif ($type=='fontselect') {
                                    echo "\t\t\t";
                                    echo "<select name=\"fau_theme_options[$name]\">\n";
                                    foreach($liste as $i => $value) {   
                                        echo "\t\t\t\t";
                                        if ((isset($value['webfont']) && $value['webfont']==1)) {
                                            echo '<option style="font-size: 1.5em; font-family: '.$i.';" value="'.$i.'"';
                                        } elseif ($i == 'none') {    
                                            echo '<option style="font-size: 1.5em;" value="'.$i.'"';
                                        } else {
                                            echo '<option style="font-size: 1.5em; font-family: '.$value['family'].';" value="'.$i.'"';                                            
                                        }
                                        if ( $i == $options[$name] ) {
                                            echo ' selected="selected"';
                                        }                                                                                                                                                                
                                        echo '>';
                                        echo $value['title'];
                                        if ($i != 'none')
                                            echo ' (ABCIJL abcijl 1234567890 &Auml;&Ouml;&Uuml;&auml;&ouml;&uuml;&szlig; @<>?)';                 
                                        echo '</option>';                                                                                                                                                              
                                        echo "\n";                                            
                                    }  
                                    echo "\t\t\t</select><br>\n";                                   
                                    echo "\t\t\t<label for=\"fau_theme_options[$name]\">$label</label>\n"; 

                                }
                                echo "\t\t</td>\n";
                                echo "\t</tr>\n";
                            }     

                            if ((isset($setsection)) && ($setsection!="") && ($type != 'section') && (!isset($parent))) {
                                /*
                                    * Kein Parent mehr 
                                    */
                                    echo "\t\t\t</table>\n";   
                                    echo "\t\t</td>\n";
                                    echo "\t</tr>\n";
                                    $setsection = "";
                            }                                                                 
                        }
                            if ((isset($setsection)) && ($setsection!="")) {
                                /*
                                    * Kein Parent mehr 
                                    */
                                    echo "\t\t\t</table>\n";   
                                    echo "\t\t</td>\n";
                                    echo "\t</tr>\n";
                                    $setsection = "";
                            }    
                    } else {
                        _e( 'Option nicht definiert.', 'fau' );
                    }
                ?>
                     
                
	</table>
        </div>                                        
                    
        <p class="submit">
	    <?php if (!empty($askfieldlist)) {
		$askfieldlist = rtrim($askfieldlist,',');
		echo '<input type="hidden" id="fau_theme_options[askfieldlist]" name="fau_theme_options[askfieldlist]" value="'.$askfieldlist.'">'; 
	    } ?>
                <input type="submit" class="button-primary" value="<?php _e( 'Update', 'fau' ); ?>" />
        </p>
</form>               
</div>

</div> <!-- end: .fau-optionen -->      
<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
    global $setoptions;
    global $defaultoptions;
    $options = get_option( 'fau_theme_options' );
    
    $saved = (array) get_option( 'fau_theme_options' );	
        //    $options= $saved;
    $output = wp_parse_args( $saved, $defaultoptions );
       $tab = '';
       if ((isset($_GET['tab'])) && (!empty($_GET['tab']))) {
            $tab = $_GET['tab'];
       }
       if ((empty($tab) && (isset($input['tab'])))) {
            $tab = $input['tab'];
       }

        if (!isset($setoptions['fau_theme_options'][$tab])) {
            return $output;          
        }

	
	
       if (isset($setoptions['fau_theme_options'][$tab]['fields'])) {
	    $paralist = array();
	    $paralist = explode(",",wp_filter_nohtml_kses($input['askfieldlist']));	    
	    
	   
            foreach($setoptions['fau_theme_options'][$tab]['fields'] as $i => $value) {   
                $name = $i;
		if (in_array($name,$paralist)) {
		    $type = $value['type'];  
		    $default = '';
		    if (isset($value['default'])) {
			$default = $value['default'];
		    }
		    if ($type != "section") {
			if (isset($input[$name])) {
			    if ($type=='bool') {
				$output[$name]  = ( $input[$name] == 1 ? 1 : 0 );    
			    } elseif ($type=='text') {
				 $output[$name]  =  wp_filter_nohtml_kses( $input[$name] );
			    } elseif ($type=='email') {
				 $output[$name]  =  sanitize_email( $input[$name] );	     
			    } elseif ($type=='textarea') {
				 $output[$name]  =  $input[$name] ;     
			    } elseif ($type=='html') {;    
				$output[$name] = $input[$name];
			    } elseif (($type=='imageurl') || ($type=='image')) {
				 $output[$name]  =  esc_url( $input[$name] );
				 if (isset($input[$name."_id"])) {
				    $output[$name."_id"]  =  sanitize_key( $input[$name."_id"] );
				 }
			    } elseif (($type=='url') || ($type=='imgurl')) {
				 $output[$name]  =  esc_url( $input[$name] ); 
			    } elseif ($type=='file') {
				$output[$name."_url"]  = wp_filter_nohtml_kses( $input[$name] ); 
				if (isset($input[$name."_id"])) {
				    $output[$name]  =   sanitize_key( $input[$name."_id"] );
				}
			    } elseif ($type=='number') {
				$output[$name]  =  wp_filter_nohtml_kses( $input[$name] ); 
			    } elseif (($type=='select') || ($type=='fontselect')) {                        
				$output[$name]  =  wp_filter_nohtml_kses( $input[$name] ); 
			    } elseif (($type=='bildchecklist') || ($type=='bilddirchecklist')) {                            
				$output[$name]  = $input[$name];
			    } elseif ($type=='multiselectlist') {   	    			   
				$output[$name]  = $input[$name];    
			    } elseif ($type=='urlchecklist') {   	    			   
				$output[$name]  = $input[$name];
			    } else {
				$output[$name]  =  wp_filter_nohtml_kses( $input[$name] );
			    }
			} else {                        
			    if ($type=='bool') {
				$output[$name] =0;
			    } elseif ($type=='text') {
				$output[$name] = "";
			    } elseif ($type=='textarea') {
				$output[$name] = "";     
			    } elseif ($type=='html') {
				$output[$name] = "";    
			    } elseif (($type=='imageurl') || ($type=='image')) {
				$output[$name] = "";    
				$output[$name."_id"] = 0;    
			    } elseif (($type=='url') || ($type=='imgurl')) {
				$output[$name] = "";
			    } elseif ($type=='number') {
				$output[$name] = 0;
			    } elseif ($type=='file') {
				$output[$name] = '';    
				$output[$name."_url"] = '';    
			    } elseif (($type=='select') || ($type=='fontselect')) {                        
				$output[$name] = "";
			    } elseif (($type=='bildchecklist') || ($type=='bilddirchecklist')) {   
				 $output[$name] = '';   
			    } elseif ($type=='multiselectlist') {
				 $output[$name] = array();
			    }
			}
		    }

		}
	    }
       }               

      
    


    if  (isset($input['reset_options']) && ($input['reset_options'] == 1)) {
	delete_option('fau_theme_options');
	
    } 
   return $output;

}


/*
 * Reads Directory and contents, ignoring unused files
 */

function dirToArray($dir) {  
   $result = array();
   $contents = scandir($dir);
   $bad = array(".", "..", ".DS_Store", "_notes", "Thumbs.db", "Browse.plb");
   $cdir = array_diff($contents, $bad);   
   
   foreach ($cdir as $key => $value) {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         }
         else
         {
            $result[] = $value;
         }
      }
   }
  
   return $result;
} 


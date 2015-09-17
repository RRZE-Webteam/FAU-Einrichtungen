<?php 


     
	
    
    if (class_exists( 'FAUPersonWidget' ) ) :
	
	$sidebar_personen = get_post_meta( $post->ID, 'sidebar_personen', true );
	$sidebar_title_personen = get_post_meta( $post->ID, 'sidebar_title_personen', true );	
	if(isset($sidebar_personen) && !empty($sidebar_personen))  { 
	   $persons = $sidebar_personen;
	   $i = 0; 
	   foreach($persons as $person) {
	       if($i == 0) {
		    the_widget('FAUPersonWidget', array('id' => $person, 'title' => $sidebar_title_personen));
		     $i++; 
	       }else {
		    the_widget('FAUPersonWidget', array('id' => $person)); 
		   
	       }
	   } 
       } 
 endif;
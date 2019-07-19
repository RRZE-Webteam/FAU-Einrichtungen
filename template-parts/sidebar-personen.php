<?php 


    if (class_exists( 'FAUPersonWidget' ) )  {
	$sidebar_personen = get_post_meta( $post->ID, 'sidebar_personen', true );
	$sidebar_title_personen = get_post_meta( $post->ID, 'sidebar_title_personen', true );	
	if(isset($sidebar_personen) && !empty($sidebar_personen))  { 
	   $persons = $sidebar_personen;
           fau_use_sidebar(true);

	   if (!fau_empty($sidebar_title_personen)) {
		echo '<h2 class="widget-title">'.$sidebar_title_personen.'</h2>';
	   }
	   foreach($persons as $person) {
		the_widget('FAUPersonWidget', array('id' => $person)); 
	   } 
       } 
    }
    
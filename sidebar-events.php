<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $options;
$topevent_posts = get_posts(array('tag' => $options['start_topevents_tag'], 'numberposts' => $options['start_topevents_max']));
foreach($topevent_posts as $topevent): ?>
    <div class="widget h-event vevent">
	    <?php 
	    $titel = get_post_meta( $topevent->ID, 'topevent_title', true );
	    if (strlen(trim($titel))<3) {
		$titel =  get_the_title($topevent->ID);
	    } 
	    $link = fau_esc_url(get_permalink($topevent->ID));

	    ?>
	    <h2 class="small p-name"><a class="url u-url" href="<?php echo $link; ?>"><?php echo $titel; ?></a></h2>
	    <div class="row">
		<?php 
		    
		    $imageid = get_post_meta( $topevent->ID, 'topevent_image', true );
		    $imagehtml = '';
		    if (isset($imageid) && ($imageid>0)) {
			$image = wp_get_attachment_image_src($imageid, 'topevent-thumb'); 					
			if (($image) && ($image[0])) {  
			    $imagehtml = '<img src="'.fau_esc_url($image[0]).'" width="'.$options['default_topevent_thumb_width'].'" height="'.$options['default_topevent_thumb_height'].'" alt="">';	
			}								    
		    } 
		    if (empty($imagehtml)) {
		       $imagehtml = '<img src="'.fau_esc_url($options['default_topevent_thumb_src']).'" width="'.$options['default_topevent_thumb_width'].'" height="'.$options['default_topevent_thumb_height'].'" alt="">';			    
		    }




		if (isset($imagehtml)) { ?>
		    <div class="span2">
			    <?php echo '<a href="'.$link.'">'.$imagehtml.'</a>'; ?>
		    </div>
		    <div class="span2">
		<?php } else { ?>
		    <div class="span4">
		<?php } 
			$topevent_date  = get_post_meta( $topevent->ID, 'topevent_date', true );
			if (!empty($topevent_date)) {
			    echo '<div class="topevent-date dtstart dt-start" title="'.$topevent_date.'">';
			    echo date_i18n( get_option( 'date_format' ), strtotime( $topevent_date ) );
			    echo "</div>\n";
			}
			
			$desc = get_post_meta( $topevent->ID, 'topevent_description', true );
			if (strlen(trim($desc))<3) {
			    $desc =  fau_custom_excerpt($topevent->ID,$options['default_topevent_excerpt_length']);
			}  ?>   
			<div class="topevent-description summary p-summary"><?php echo $desc; ?></div>

		    </div>			
	    </div>
    </div>
<?php 
endforeach; 


<?php


global $options;

$show =false;
/*
	1 => __('Startseite','fau'),
	2 => __('Portalseiten','fau'),
	3 => __('Suche und Fehlerseiten','fau'),
	4 => __('Inhaltsseite mit Navi','fau'),
	5 => __('Standard Seiten','fau'),
	6 => __('Beiträge','fau'),       
*/
 if ((isset($options['start_topevents_active'])) && ($options['start_topevents_active']==true)) {
    $displayon = $options['topevents_templates'];
    $template = get_page_template();

     foreach ($displayon as $key) {
	if (($key==1) && (is_page_template( 'page-templates/page-start.php' ))) {
	    $show = true;
	    break;
	} elseif (($key==1) && (is_page_template( 'page-templates/page-start-sub.php' ))) {
	    $show = true;
	    break;
	} elseif (($key==2) && (is_page_template( 'page-templates/page-portal.php')))  {
	    $show = true;
	    break;
	} elseif (($key==3) && (is_search() || is_404() ))  {
	    $show = true;
	    break;
	} elseif (($key==4) && (is_page_template( 'page-templates/page-subnav.php')))  {
	    $show = true;
	    break;
	} elseif (($key==5) && (is_page()))  {
	    $show = true;
	    break;
	} elseif (($key==6) && (is_single()))  {	 
	    $show = true;
	    break;
	} else {
    //	echo "<!-- PAGE TEMPLATE: $template -->";
	}
     }
 }
 if ($show) {
    $args =  array(
	'post_type'	    => 'post',
	'post_status'       => 'publish',
    	'meta_query'	    => array(
	    'relation'	=> 'AND',
	    array(
		'key'	    => 'topevent_active',
		'value'	    => 1,
		'compare'   => '=',
		'type'	    => 'NUMERIC'
	    ),
	    array(
		'relation'	=> 'OR',
		array(
		    'key'	    => 'topevent_date',
		    'value'	    => date("Y-m-d"),
		    'compare'   => '>=', 
		),
		array(
		    'key'	    => 'topevent_date',
		    'value'	    =>  date("Y-m-d"),
		    'compare'   => 'NOT EXISTS',
		),
		array(
		    'key'	    => 'topevent_date',
		    'value'	    =>  date('1970-01-01'),
		    'compare'   => '=',
		)
	    ),
	),
	'numberposts' => $options['start_topevents_max'],
    );
    $topevent_posts = get_posts($args);


    foreach($topevent_posts as $topevent) {  
	    $topevent_date  = get_post_meta( $topevent->ID, 'topevent_date', true );
	    $istopevent  = get_post_meta( $topevent->ID, 'topevent_active', true ); 
	    $titel = get_post_meta( $topevent->ID, 'topevent_title', true );
	    if (strlen(trim($titel))<3) {
		$titel =  get_the_title($topevent->ID);
	    } 
	    $link = fau_esc_url(get_permalink($topevent->ID));
	    if (!empty($topevent_date)) {
		 // Workaround fuer alte Eintraege, deren Syntax falsch sein kann
		if (preg_match("/^\d+\-\d+\-\d+$/i", $topevent_date)) {
		    // Ok
		} elseif (preg_match("/^\d+\.\d+\.\d+$/i", $topevent_date)) {    
		    $topevent_date = preg_replace('/\./', '-', $topevent_date);		 
		} else {
		    $topevent_date = '';
		}
		
		$todaysDate = time();
		$postDate = strtotime($topevent_date) + 43200;
		// 12 Stunden offset, damit die Veranstaltung auch noch angezeigt wird, wenn
		// sie am selben Tag gestartet ist.
		if ($postDate < $todaysDate) {
		    $topevent_date = '';
		}
	    }	
	    if ((!empty($topevent_date)) || ($istopevent==1)) {

	    
	?>
	<div class="widget h-event vevent">
		<h2 class="small p-name"><a class="url u-url" href="<?php echo $link; ?>"><?php echo $titel; ?></a></h2>
		<div class="row">
		 <?php 
		    
			$hideimage  = get_post_meta( $topevent->ID, 'topevent_hideimage', true ); 
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
		   if (($hideimage < 1) && (isset($imagehtml))) { ?>
			<div class="span2">
				<?php echo '<a href="'.$link.'">'.$imagehtml.'</a>'; ?>
			</div>
			<div class="span2">
		    <?php } else { ?>
			<div class="span4">
		    <?php } 
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
	    <?php }
	}
   
 }


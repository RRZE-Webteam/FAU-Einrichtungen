<?php



$show =false;
/*
	1 => __('Startseite','fau'),
	2 => __('Portalseiten','fau'),
	3 => __('Suche und Fehlerseiten','fau'),
	4 => __('Inhaltsseite mit Navi','fau'),
	5 => __('Standard Seiten','fau'),
	6 => __('Beiträge','fau'),       
*/


$start_topevents_active = get_theme_mod("start_topevents_active");


 if ((isset($start_topevents_active)) && ($start_topevents_active==true)) {
    $start_topevents_templates = get_theme_mod("topevents_templates");

    $template = get_page_template();

     foreach ($start_topevents_templates as $key) {
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
 if ($show==true) {
    $maxnum = get_theme_mod('start_topevents_max');
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
	'numberposts' => $maxnum,
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
	<div class="widget topevent-widget" itemscope itemtype="http://schema.org/Event">
		<h2 itemprop="name"><a itemprop="url" href="<?php echo $link; ?>"><?php echo $titel; ?></a></h2>
		<?php 
		     global $defaultoptions;
		    $hideimage  = get_post_meta( $topevent->ID, 'topevent_hideimage', true ); 
		    $imageid = get_post_meta( $topevent->ID, 'topevent_image', true );
		    $imagehtml = '';
		    
		    $pretitle = get_theme_mod('advanced_blogroll_thumblink_alt_pretitle');
		    $posttitle = get_theme_mod('advanced_blogroll_thumblink_alt_posttitle');
		    $alttext = $pretitle.$titel.$posttitle;
		    $alttext = esc_html($alttext);
		    
		    $imgwidth = $defaultoptions['default_topevent_thumb_width'];
		    $imgheight = $defaultoptions['default_topevent_thumb_height'];
		    $imgsrcset = '';
			
		    if (isset($imageid) && ($imageid>0)) {
			$image = wp_get_attachment_image_src($imageid, 'topevent-thumb'); 	
			$imgsrcset =  wp_get_attachment_image_srcset($imageid, 'topevent-thumb'); 
			if (($image) && ($image[0])) {  
			    $imagehtml = '<img itemprop="thumbnailUrl" src="'.fau_esc_url($image[0]).'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$alttext.'"';	  
			    if ($imgsrcset) {
				$imagehtml .= ' srcset="'.$imgsrcset.'"';
			    }
			    $imagehtml .= ">";
			}								    
		    } 
		    if (empty($imagehtml)) {
			
			$fallback = get_theme_mod('fallback_topevent_image');
			if ($fallback) {
			    $thisimage = wp_get_attachment_image_src( $fallback,  'topevent-thumb');
			    $imageurl = $thisimage[0]; 	
			    $imgwidth = $thisimage[1];
			    $imgheight = $thisimage[2];
			    $imgsrcset =  wp_get_attachment_image_srcset($fallback, 'topevent-thumb'); 
			} else {	   
			    $imageurl = fau_esc_url($defaultoptions['default_topevent_thumb_src']);
			    
			}
			$imagehtml = '<img itemprop="thumbnailUrl" src="'.$imageurl.'" width="'.$imgwidth.'" height="'.$imgheight.'" alt="'.$alttext.'"';
			if ($imgsrcset) {
			    $imagehtml .= ' srcset="'.$imgsrcset.'"';
			}
			$imagehtml .= ">";
 
		    }
		    if (($hideimage < 1) && (isset($imagehtml))) { ?>
			<div class="event-thumb" aria-hidden="true" role="presentation" tabindex="-1" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php echo '<a href="'.$link.'">'.$imagehtml.'</a>'; 
				if (isset($imageid) && ($imageid>0)) {
				    $schema = "";
				    $bigimage = wp_get_attachment_image_src($imageid, 'full'); 
				    $schema .= '<meta itemprop="url" content="'.fau_make_absolute_url($bigimage[0]).'">';
				    $schema .= '<meta itemprop="width" content="'.$bigimage[1].'">';
				    $schema .= '<meta itemprop="height" content="'.$bigimage[2].'">';	   
				    echo $schema;
				}
				?>
			</div>
		    <?php } ?>
		    <div class="event-data">
		    <?php 
			    if (!empty($topevent_date)) {
				echo '<div class="topevent-date" itemprop="startDate" content="'.$topevent_date.'">';
				echo date_i18n( get_option( 'date_format' ), strtotime( $topevent_date ) );
				echo "</div>\n";
			    }
			    $desc = get_post_meta( $topevent->ID, 'topevent_description', true );
			    if (strlen(trim($desc))<3) {
				$desc =  fau_custom_excerpt($topevent->ID,get_theme_mod('default_topevent_excerpt_length'));
			    }  ?>   
			<div class="topevent-description" itemprop="description"><?php echo $desc; ?></div>
		    </div>			
		
	</div>
	    <?php }
	}
   
 }


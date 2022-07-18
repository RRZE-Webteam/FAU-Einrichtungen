<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

if (isset($_GET['format']) && $_GET['format'] == 'embedded') {
    get_template_part('template-parts/index', 'embedded');
    return;
}
if ( is_active_sidebar( 'news-sidebar' ) ) { 
    fau_use_sidebar(true);    
}
get_header();

$posttype = get_post_type();
$screenreadertitle = '';
$herotype = get_theme_mod('advanced_header_template');
if (($posttype == 'post') && (is_archive())) {
    
    if ($herotype=='banner') {
	get_template_part('template-parts/hero', 'banner');
    } elseif ($herotype=='slider') {	
	get_template_part('template-parts/hero', 'sliderpage-slider');
    } else {
	get_template_part('template-parts/hero', 'category');
    }
    if (is_category()) {
	$screenreadertitle = single_cat_title("", false);
    } else {
	$screenreadertitle = get_the_archive_title();
    }

} else {
     if ($herotype=='banner') {
	get_template_part('template-parts/hero', 'banner');
    } elseif ($herotype=='slider') {	
	get_template_part('template-parts/hero', 'sliderpage-slider');
    } else {
      get_template_part('template-parts/hero', 'banner');
//	get_template_part('template-parts/hero', 'index');
    }
	
   
    $screenreadertitle = __('Index','fau');
}
?>

    <div id="content">
	    <div class="content-container">
		    <div class="post-row">
			<?php if(get_post_type() == 'post') { ?>
			<main class="entry-content">
			<?php } else { ?>
			<main class="col-xs-12">
			<?php } 
			    
			    if (empty($herotype)) {   ?>
				<h1 id="maintop"  class="screen-reader-text"><?php echo $screenreadertitle; ?></h1>
			     <?php } else { ?>
				<h1 id="maintop" ><?php echo $screenreadertitle; ?></h1>
			     <?php }
			   
			    
			    $line=0;
			    while ( have_posts() )  {
				the_post();  

				$line++;
				if ($posttype == 'person')  { 	
				    echo FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 0, 'showlist' => 1 )); 				    
				} elseif($posttype == 'post') { 
				      echo fau_display_news_teaser($post->ID,true);
				 } else { ?>

				    <h2 class="small"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

				    <?php if(has_post_thumbnail( $post->ID )) { ?>
				     <div class="row">
					<div class="span3">
					    <?php 
					    $post_thumbnail_id = get_post_thumbnail_id( $post->ID); 
					    echo fau_get_image_htmlcode($post_thumbnail_id);  
					    ?>
					</div>
					 <div class="span5">
				    <?php }  
				    the_content(); 
				    if(has_post_thumbnail( $post->ID )) { ?>
					</div>
				    </div>
				    <?php } 

				 }
				
				 }
				if  (($posttype=='person') || ($posttype=='post')) {
                                    $next = get_next_posts_link(__('Ältere Beiträge', 'fau'));
                                    $prev = get_previous_posts_link(__('Neuere Beiträge', 'fau'));
                                    
                                    if ($next || $prev) {
                                        echo '<nav class="index-navigation">';
                                        if ($prev) {
                                            echo '<div class="prev">'.$prev.'</div>';
                                        }
                                        if ($next) {
                                            echo '<div class="next">'.$next.'</div>';
                                        }
                                        echo '</nav>';
                                    } 
                                }
			     ?>
			</main>
			<?php 
			if($posttype=='post') {
			     get_template_part('template-parts/sidebar', 'posts');
			}
			?>
		    </div>    
	    </div>
	
    </div>
<?php 
get_template_part('template-parts/footer', 'social'); 
get_footer(); 


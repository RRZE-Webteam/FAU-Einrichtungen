<?php
/**
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

?>

<h2 class="small">
	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h2>
 	
<div class="row">
	<?php if(has_post_thumbnail( $post->ID )): ?>
	<div class="span3">
		<?php the_post_thumbnail('post-thumb'); ?>
	</div>
	<div class="span5">
	<?php else: ?>
	<div class="span8">
	<?php endif; ?>
		<?php the_content(); ?>
	</div>
</div>


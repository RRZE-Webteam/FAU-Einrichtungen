<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

?>

<section id="hero" class="hero-small">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			    <?php fau_breadcrumb(); ?>
				<p class="presentationtitle" aria-hidden="true" role="presentation"><?php single_cat_title(); ?></p>
				<?php
                                    $title_presentation = '';
                                    if(get_post_type() == 'post') {
                                        $title_presentation = get_theme_mod('title_hero_post_categories');
				    }
                                    if (!fau_empty($title_presentation)) {
                                ?>
				<div aria-hidden="true" role="presentation" class="hero-meta-portal">
                                    <?php echo $title_presentation; ?>
				</div>
                                <?php } ?>
			</div>
		</div>
	</div>
</section>

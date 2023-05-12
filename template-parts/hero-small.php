<?php
/**
 * Template part for small hero
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
 
?>

    <section id="hero" class="hero-small">
        <div class="hero-container hero-content">
            <div class="hero-row">
                <?php fau_breadcrumb(); ?>			
            </div>
            <div class="hero-row" aria-hidden="true" role="presentation">
                <p class="presentationtitle" <?php echo fau_get_page_langcode($post->ID);?>><?php echo fau_get_hero_title(); ?></p>
            </div>
        </div>
    </section>

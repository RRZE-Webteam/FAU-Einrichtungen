<?php
/*
*Template Name: Scroll Stories
* Template Post Type: post, page,
*/

// Enqueue necessary JavaScript files
function scroll_page_scripts() {
    wp_enqueue_script( 'd3', 'https://d3js.org/d3.v5.min.js' );
    wp_enqueue_script( 'scrollmagic', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js' );
    wp_enqueue_script( 'animation-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/animation.gsap.min.js' );
    wp_enqueue_script( 'debug', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js' );
    wp_enqueue_script( 'scroll-page-script', get_template_directory_uri() . '/js/fau-scroll-stories.min.js', array( 'jquery', 'd3', 'scrollmagic', 'animation-gsap', 'debug' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'scroll_page_scripts' );

// Load the header template
get_header();

?>

<!-- Your page content goes here -->
<?php the_content(); ?>

<?php
// Load the footer template
get_footer();
?>

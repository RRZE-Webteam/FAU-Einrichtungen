<?php
/*
*Template Name: Scroll Stories
* Template Post Type: post, page,
*/

// Enqueue necessary JavaScript files
function scroll_page_scripts() {
    wp_enqueue_script( 'd3', get_template_directory_uri() . '/js/fau-d3.v5.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'scrollmagic',  get_template_directory_uri() . '/js/fau-ScrollMagic.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'animation-gsap',  get_template_directory_uri() . '/js/fau-animation.gsap.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'debug',  get_template_directory_uri() . '/js/fau-debug.addIndicators.min.js', array( 'jquery' ), '1.0.0', true );
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

<?php
/**
* Template for info screen blogroll
*
* @package WordPress
* @subpackage FAU
* @since FAU 1.11.19
*/
?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class('embedded'); ?>>
         <div id="content">
            <main class="entry-content">
                <?php
                while ( have_posts() ) {
                    the_post();
                    echo fau_display_news_teaser($post->ID, true);
                }
                    ?>
            </main>
         </div>
    </body>
</html>
<?php
global $univis_data;

get_header();
?>
<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/hero', 'small'); ?>
    <div id="content">
        <div class="container">
            <?php
            echo fau_get_ad('werbebanner_seitlich', false);
            ?>

            <div class="row">
               <div class="col-xs-12">
                    <main id="droppoint">
                        <?php
                        echo $univis_data;
                        ?>
                    
                        <nav class="navigation">
                            <div class="nav-previous"><a href="<?php echo get_permalink();?>"><?php _e('<span class="meta-nav">&laquo;</span> Zurück zur Übersicht', 'fau'); ?></a></div>
                        </nav>
                    </main>
             </div>
            </div>
        </div>
    </div>
<?php
endwhile;
get_footer();

<?php
/**
 * Template Name: Startseite
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
?>
<div id="content" class="start-sub">
    <div class="content-container">
        <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
        <div class="content-row">
            <div class="portalpage-content">
                <main<?php echo fau_get_page_langcode($post->ID); ?>>
                    <h1 id="maintop" class="screen-reader-text">
                        <?php the_title(); ?>
                    </h1>
                    <?php
                    the_content();

                    wp_reset_postdata();
                    wp_reset_query();

                    $number = 0;
                    $displayedposts = array();
                    $max = get_theme_mod('start_max_newspertag');
                    $maxall = get_theme_mod('start_max_newscontent');

                    $maxpositioncount = get_theme_mod('start_max_tagposition');
                    $showsticky = get_theme_mod('advance_show_sticky_posts');
                    $maxsticky = get_theme_mod('advance_show_sticky_posts_max');

                    if ($showsticky) {
                        $sticky = get_option('sticky_posts');
                        if (!empty($sticky)) {
                            // Sticky posts exist
                            $sticky_query = new WP_Query(
                                array(
                                    'post__in' => get_option('sticky_posts'),
                                    'ignore_sticky_posts' => false,
                                    'posts_per_page' => $maxsticky,
                                    'post__not_in' => $displayedposts,
                                )
                            );

                            while ($sticky_query->have_posts() && $number < $maxall) {
                                $sticky_query->the_post();
                                echo fau_display_news_teaser($post->ID);
                                $number++;
                                $displayedposts[] = $post->ID;
                            }

                            wp_reset_postdata();
                        }
                    }

                    // Retrieve regular posts with specific tags
                    for ($j = 1; $j <= $maxpositioncount && $number < $maxall; $j++) {
                        $i = 0;
                        $thistag = get_theme_mod('start_prefix_tag_newscontent') . $j;

                        $query = new WP_Query(
                            array(
                                'tag' => $thistag,
                                'ignore_sticky_posts' => true,
                                'post__not_in' => $displayedposts,
                            )
                        );

                        while ($query->have_posts() && $i < $max && $number < $maxall) {
                            $query->the_post();
                            echo fau_display_news_teaser($post->ID);
                            $i++;
                            $number++;
                            $displayedposts[] = $post->ID;
                        }

                        wp_reset_postdata();
                        wp_reset_query();
                    }

                    $newscat = get_theme_mod('start_link_news_cat');
                    if ($number < $maxall) {
                        $num = $maxall - $number;
                        $num = ($num <= 0) ? 1 : $num;

                        if ($newscat) {
                            $query = new WP_Query(
                                array(
                                    'post__not_in' => $displayedposts,
                                    'posts_per_page' => $num,
                                    'has_password' => false,
                                    'post_type' => 'post',
                                    'cat' => $newscat
                                )
                            );
                        } else {
                            $query = new WP_Query(
                                array(
                                    'post__not_in' => $displayedposts,
                                    'posts_per_page' => $num,
                                    'has_password' => false,
                                    'post_type' => 'post',
                                    'ignore_sticky_posts' => true,
                                )
                            );
                        }

                        while ($query->have_posts()) {
                            $query->the_post();
                            echo fau_display_news_teaser($post->ID);
                            wp_reset_postdata();
                            $number++;
                        }
                    }

                    if ($number > 0 && get_theme_mod('start_link_news_show')) {
                        echo fau_get_category_links();
                    }
                    ?>
                    </main>

            </div>
            <?php get_template_part('template-parts/sidebar', 'portal'); ?>
        </div>
        <?php


        get_template_part('template-parts/content', 'portalmenu-unten');

        $logoliste = get_post_meta($post->ID, 'fauval_imagelink_catid', true);
        if ($logoliste) {
            /* New since 1.10.57 */
            $logosize = get_post_meta($post->ID, 'fauval_imagelink_size', true);
            $size = $logosize != '' ? esc_attr($logosize) : "logo-thumb";
            $logos = fau_imagelink_get(array('size' => $size, 'catid' => $logoliste));
            if ((isset($logos) && (!empty($logos)))) {
                echo "<hr>\n";
                echo $logos;
            }

        } ?>
    </div>
</div>
<?php
get_footer();
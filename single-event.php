<?php


function event_get_view(&$event, &$content) {
    ob_start();

    event_single_view($event);
    
    echo wpautop($content, true);

    $single_content = ob_get_contents();
    ob_end_clean();

    return $single_content;
}

function event_single_view(&$event) {
    global $event_view_helper,
    $event_calendar_helper,
    $event_settings;
            
    $categories = array();
    $terms = get_the_terms($event->post_id, 'event_category');
    foreach ($terms as $category) {
        $categories[] = 'event-category-' . $category->slug;
    }
    $categories = implode(' ', $categories);
    
    $location = nl2br($event->location);    
    ?>
    <div class="event-detail-item <?php echo $categories; ?>">
        <div class="event-date">
            <div class="event-date-month">
                <?php echo $event->start_month_html ?>
            </div>
            <div class="event-date-day">
                <?php echo $event->start_day_html ?>
            </div>
        </div>
        <div class="event-info event-id-<?php echo $event->post_id ?> <?php if( $event->allday ) echo 'event-allday'; ?>">
            <?php if( $event->allday && !$event->multiday ) : ?>
            <div class="event-allday" style="text-transform: uppercase;">
                <?php _e( 'Ganztägig', 'fau' ); ?>
            </div>
            <?php elseif( $event->allday && $event->multiday ) : ?>
            <div class="event-time">
                <?php echo esc_html( sprintf( __( '%1$s bis %2$s', 'fau' ), $event->long_start_date, $event->long_end_date ) ) ?>
            </div>            
            <?php elseif( !$event->allday && $event->multiday ) : ?>
            <div class="event-time">
                <?php echo esc_html( sprintf( __( '%1$s bis %2$s', 'fau' ), $event->long_start_time, $event->long_end_time ) ) ?>
            </div>
            <?php else: ?>
            <div class="event-time">
                <?php echo esc_html( sprintf( __( '%1$s Uhr bis %2$s Uhr', 'fau' ), $event->short_start_time, $event->short_end_time ) ) ?>
            </div>            
            <?php endif; ?>
            <?php $location = $location ? $location : '&nbsp;'; ?>
            <div class="event-location"><?php echo $location ?></div>
        </div>
    </div>
    <?php
}

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php get_template_part('hero', 'small'); ?>

    <div id="content">
        <div class="container">

            <div class="row">
                <div class="span12">
		    <main>
                    <?php 
		    $content = '';
		    if (defined('EVENT_POST_TYPE') && get_post_type() == EVENT_POST_TYPE) {
			global $event_events_helper;

			$event = $event_events_helper->get_event(get_the_ID());
			$content = event_get_view($event, $content);
		    }
 
		     
		    echo $content; ?>
                    <div>
                    <?php the_content(); ?>
                    </div>
                    </main>
                </div>
                
            </div>

        </div>
    </div>
    
<?php endwhile;

get_footer();

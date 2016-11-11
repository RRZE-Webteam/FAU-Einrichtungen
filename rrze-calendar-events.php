<?php

global $options, $rrze_calendar_data, $rrze_calendar_endpoint_name;

if (!class_exists('RRZE_Calendar')) {
    wp_die();
}

$breadcrumb = '';
if (isset($options['breadcrumb_root'])) {
    if ($options['breadcrumb_withtitle']) {
        $breadcrumb .= '<h3 class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title').'</h3>';
        $breadcrumb .= "\n";
    }
    $breadcrumb .= '<nav aria-labelledby="bc-title" class="breadcrumbs">'; 
    $breadcrumb .= '<h4 class="screen-reader-text" id="bc-title">'.__('Sie befinden sich hier:','fau').'</h4>';
    $breadcrumb .= '<a data-wpel-link="internal" href="' . site_url('/') . '">' . $options['breadcrumb_root'] . '</a>' . $options['breadcrumb_delimiter'];
    $breadcrumb .= $options['breadcrumb_beforehtml'] . $rrze_calendar_endpoint_name . $options['breadcrumb_afterhtml'];
}
get_header(); ?>

    <section id="hero" class="hero-small">
        <div class="container">
            <div class="row">
                <div class="span12">

                    <?php echo $breadcrumb; ?>

                    <div class="hero-meta-portal"></div>
                </div>
            </div>
            <div class="row">
                <div class="span12">

                    <h1><?php echo $rrze_calendar_endpoint_name; ?></h1>

                </div>
            </div>
        </div>
    </section>

    <div id="content">
        <div class="container">

            <div class="row">
                <div class="span12">
                    <main>                        

                        <div class="events-list">
                            <?php if (empty($rrze_calendar_data)): ?>
                            <p><?php _e('Keine bevorstehenden Termine', 'fau'); ?></p>
                            <?php else: ?>
                            <ul>
                                <?php foreach ($rrze_calendar_data as $date): ?>
                                    <?php foreach ($date as $event): 
					$bgcolorclass = '';
					$inline = '';
					if (isset($event->category)) {
					    if (isset($event->category->bgcol)) {
						$bgcolorclass = $event->category->bgcol;
					    } elseif (isset($event->category->color)) {
						$inline = 'style="background-color:' . $event->category->color.'"';
					    }
					}
					
					?>
                                        <li>                                           
                                            <div class="event-item">
                                                <div class="event-date <?php echo $bgcolorclass; ?>" <?php echo $inline; ?>>
                                                    <span class="event-date-month"><?php echo $event->start_month_html ?></span>
                                                    <span class="event-date-day"><?php echo $event->start_day_html ?></span>
                                                </div>
                                                <div class="event-info event-id-<?php echo $event->id ?> <?php if ($event->allday) echo 'event-allday'; ?>">
                                                    <?php if ($event->allday && !$event->multiday) : ?>
                                                        <div class="event-allday" style="text-transform: uppercase;">
                                                            <?php _e('Ganztägig', 'fau'); ?>
                                                        </div>
                                                    <?php elseif ($event->allday && $event->multiday) : ?>
                                                        <div class="event-time">
                                                            <?php echo esc_html(sprintf(__('%1$s bis %2$s', 'fau'), $event->long_start_date, $event->long_end_date)) ?>
                                                        </div>            
                                                    <?php elseif (!$event->allday && $event->multiday) : ?>
                                                        <div class="event-time">
                                                            <?php echo esc_html(sprintf(__('%1$s bis %2$s', 'fau'), $event->long_start_time, $event->long_end_time)) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="event-time">
                                                            <?php echo esc_html(sprintf(__('%1$s Uhr bis %2$s Uhr', 'fau'), $event->short_start_time, $event->short_end_time)) ?>
                                                        </div>            
                                                    <?php endif; ?>
                                                    <div class="event-title">
                                                        <a href="<?php echo $event->endpoint_url; ?>"><?php echo esc_html($event->summary); ?></a>
                                                    </div>                                                    
                                                    <div class="event-location">
                                                        <?php echo $event->location ? nl2br($event->location) : '&nbsp;'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>      
                        </div>
                      
                    </main>
                </div>

            </div>

        </div>
    </div>

<?php get_footer(); ?>

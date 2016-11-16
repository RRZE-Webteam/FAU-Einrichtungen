<?php

global $options, $rrze_calendar_data, $rrze_calendar_endpoint_url, $rrze_calendar_endpoint_name;

if (!class_exists('RRZE_Calendar') || empty($rrze_calendar_data)) {
    wp_die();
}
$event = &$rrze_calendar_data;
$bgcolorclass = '';
$inline = '';
if (isset($event->category)) {
    if (!empty($event->category->bgcol)) {
	$bgcolorclass = $event->category->bgcol;
    } elseif (!empty($event->category->color)) {
	$inline = 'style="background-color:' . $event->category->color.'"';
    }
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
    $breadcrumb .= '<a data-wpel-link="internal" href="' . $rrze_calendar_endpoint_url . '">' . $rrze_calendar_endpoint_name . '</a>' . $options['breadcrumb_delimiter'];
    $breadcrumb .= $options['breadcrumb_beforehtml'] . $event->summary . $options['breadcrumb_afterhtml'];
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
                    <h1><?php echo $event->summary; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <div id="content">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <main>
                        <div class="event-detail-item">
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
                                <div class="event-location"><?php echo $event->location ? $event->location : '&nbsp;'; ?></div>
                            </div>
                        </div>
                        <div>
                            <?php
			    
			    $content =  make_clickable(nl2br($event->description));; 
			    $content = apply_filters( 'the_content', $content );
			    echo $content;
			    
			    ?>
                        </div>                        
                        <div class="events-more-links">
                            <a class="events-more" href="<?php echo $event->subscribe_url; ?>"><?php _e('Abonnement', 'fau'); ?></a>
                        </div>                          
                    </main>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>

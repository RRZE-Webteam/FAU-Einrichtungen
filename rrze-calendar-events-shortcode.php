<?php

global $rrze_calendar_data, $rrze_calendar_page_url, $rrze_calendar_subscribe_url;

if (!class_exists('RRZE_Calendar')) {
    wp_die();
}
?>

<div class="rrze-calendar events-list">
    <?php if (empty($rrze_calendar_data)): ?>
    <p><?php _e('Keine bevorstehenden Termine', 'fau'); ?></p>
    <?php else: ?>
    <ul>
    <?php foreach ($rrze_calendar_data as $date): ?>
        <?php foreach ($date as $event): 
	    $bgcolorclass = '';
	    $inline = '';
	    if (isset($event->category)) {
		if (!empty($event->category->bgcol)) {
		    $bgcolorclass = $event->category->bgcol;
		} elseif (!empty($event->category->color)) {
		    $inline = 'style="background-color:' . $event->category->color.'"';
		}
	    }
	    ?>
        <li>                                           
            <div class="event-item" itemscope itemtype="http://schema.org/Event">
		<meta itemprop="startDate" content="<?php echo date_i18n( "c", $event->start ); ?>">
		<meta itemprop="endDate" content="<?php echo date_i18n( "c", $event->end ); ?>">
                <div class="event-date <?php echo $bgcolorclass; ?>" <?php echo $inline; ?>>
                    <span class="event-date-month"><?php echo $event->start_month_html ?></span>
                    <span class="event-date-day"><?php echo $event->start_day_html ?></span>
                </div>
                <div class="event-info event-id-<?php echo $event->id ?> <?php if ($event->allday) echo 'event-allday'; ?>">
		    <?php if ($event->allday && !$event->multiday) { ?>
			<div class="event-allday">
			    <?php _e('Ganztägig', 'fau'); ?>
			</div>
		    <?php } else { ?>
			<div class="event-time">
			<?php 
			if ($event->allday && $event->multiday) {
			    echo esc_html(sprintf(__('%1$s bis %2$s', 'fau'), $event->long_start_date, $event->long_end_date));
			} elseif (!$event->allday && $event->multiday) {
			    echo esc_html(sprintf(__('%1$s bis %2$s', 'fau'), $event->long_start_time, $event->long_end_time)); 
			} else {
			    echo esc_html(sprintf(__('%1$s Uhr bis %2$s Uhr', 'fau'), $event->short_start_time, $event->short_end_time));
			} ?>
			</div>       
		    <?php } ?>		
		    

                    <div class="event-title" itemprop="name">
                        <a itemprop="url" href="<?php echo $event->endpoint_url; ?>"><?php echo esc_html($event->summary); ?></a>
                    </div>                                                    
                    <div class="event-location" itemprop="location">
                        <?php echo $event->location ? nl2br($event->location) : '&nbsp;'; ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
    <?php if ($rrze_calendar_page_url): ?>
    <p class="events-more-links">
        <a class="events-more" href="<?php echo $rrze_calendar_page_url; ?>"><?php _e('Mehr Veranstaltungen', 'fau'); ?></a>
    </p>
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($rrze_calendar_subscribe_url): ?>
    <p class="events-more-links">
        <a class="events-more" href="<?php echo $rrze_calendar_subscribe_url; ?>"><?php _e('Abonnement', 'fau'); ?></a>
    </p>
    <?php endif; ?>    
</div>

<?php

global $rrze_calendar_data, $rrze_calendar_page_url, $rrze_calendar_subscribe_url;

if (!class_exists('RRZE_Calendar')) {
    wp_die();
}
?>

<div class="events-list">
    <?php if (empty($rrze_calendar_data)): ?>
    <p><?php _e('Keine bevorstehenden Termine', 'fau'); ?></p>
    <?php else: ?>
    <ul>
    <?php foreach ($rrze_calendar_data as $date): ?>
        <?php foreach ($date as $event): ?>
        <li>                                           
            <div class="event-item">
                <div class="event-date <?php echo $event->category->bgcol; ?>" <?php echo (!$event->category->bgcol && $event->category->color) ? 'style="background-color:' . $event->category->color . '"' : ''; ?>>
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

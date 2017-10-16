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
    $breadcrumb .= '<a data-wpel-link="internal" href="' . site_url('/') . '">' . $options['breadcrumb_root'] . '</a>';
}

$maxentries = 10;
get_header(); ?>

    <section id="hero" class="hero-small">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo $breadcrumb; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h1><?php echo $rrze_calendar_endpoint_name; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <div id="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <main>	                          
			<div class="rrze-calendar events-list">
				<?php if (empty($rrze_calendar_data)): ?>
				<p><?php _e('Keine bevorstehenden Termine', 'fau'); ?></p>
				<?php else: ?>
				<ul>
				   <?php 
				    $num = 0;
				    foreach ($rrze_calendar_data as $date) {
					    
					if ($num >= $maxentries) break;					
					foreach ($date as $event) {
					    $bgcolorclass = '';
					    $inline = '';
					    $num++;
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
					<?php } 
				    } ?>
				</ul>
				<?php endif; ?>      
			    
			    <div class="events-more-links">
				<a class="events-more" href="<?php echo $event->subscribe_url; ?>"><?php _e('Abonnement', 'fau'); ?></a>
			    </div>  
                      </div>
                    </main>
                </div>

            </div>

        </div>
    </div>

<?php get_footer(); ?>

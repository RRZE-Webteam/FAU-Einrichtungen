<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.2
*/


/* Tagcloud Menu Widget */
global $options;

add_action('widgets_init', function() {
if (function_exists('get_field'))
        return register_widget( 'FAUMenuTagcloudWidget' );
});

class Walker_Tagcloud_Menu extends Walker_Nav_Menu
{
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {

	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {

	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'span2';

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts['class'] = 'logo-item';

		$post = get_post($item->object_id);

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		
		$item_output .= '<li class="tag">';
		if($post->post_type == 'imagelink')
		{
			$item_output .= '<a'. $attributes .' href="'.get_field('protocol', $item->object_id).get_field('link', $item->object_id).'">';
		}
		else
		{
			$item_output .= '<a'. $attributes .'>';
		}

		$item_output .= $item->title;
		
		$item_output .= '</a>';
		$item_output .= '</li>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function end_el(&$output, $item, $depth=0, $args=array()) {      

    }  
    
}


class FAUMenuTagcloudWidget extends WP_Widget
{
	public function __construct() {
	    parent::__construct(
		'FAUMenuTagcloudWidget', __('Tagcloud-Menü', 'fau'), array(
		'description' => __('Tagcloud-Menü', 'fau'),
		'class' => 'FAUMenuTagcloudWidget',
		)
	    ); 
	}
 

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'menu-slug' => '' ) );
		$slug = $instance['menu-slug'];
		if (isset($instance['title'])) {
		    $title = $instance['title'];
		} else {
		    $title = '';
		}
		
		$menus = get_terms('nav_menu');
		
		echo '<p>';
			echo '<label for="'.$this->get_field_id('title').'">'. __('Titel', 'fau'). ': </label>';
			echo '<input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$title.'">';
		echo '</p>';
		
		echo '<p>';
			echo '<label for="'.$this->get_field_id('menu-slug').'">' . __('Menü', 'fau') . ': ';
				echo '<select id="'.$this->get_field_id('menu-slug').'" name="'.$this->get_field_name('menu-slug').'">';
					foreach($menus as $item)
					{
						echo '<option value="'.$item->slug.'"';
							if($item->slug == esc_attr($slug)) echo ' selected';
						echo '>'.$item->name.'</option>';
					}
				echo '</select>';
			echo '</label>';
		echo '</p>';

	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['menu-slug'] = $new_instance['menu-slug'];
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);

		echo $before_widget;
		
		if(!empty($instance['title']))	echo '<h2 class="small">'.$instance['title'].'</h2>';
		
		echo '<ul class="tagcloud">';
		
		$slug = empty($instance['menu-slug']) ? ' ' : $instance['menu-slug'];

		if (!empty($slug))
		{
			wp_nav_menu( array( 'menu' => $slug, 'container' => false, 'items_wrap' => '%3$s', 'link_before' => '', 'link_after' => '', 'walker' => new Walker_Tagcloud_Menu));
		}
		
		echo '</ul>';
		echo $after_widget;
	}
}



add_action('widgets_init', function() {
    if (defined('EVENT_POST_TYPE'))
        return register_widget( 'Event_Widget' );
});
if ( !class_exists( 'Event_Widget' ) ) :  
class Event_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'event_widget', __('Nächste Termine', 'fau'), array(
            'description' => __('Nächste Termine anzeigen', 'fau'),
            'class' => 'event-widget',
            )
        );
    }

    public function form($instance) {
        $default = array(
            'title' => __('Nächste Termine', 'fau'),
            'events_per_page' => 5,
            'subscribe_link' => -1,
            'show_subscribe_buttons' => true,
            'limit_by_cat' => false,
            'limit_by_tag' => false,
            'limit_by_post' => false,
            'event_cat_ids' => array(),
            'event_tag_ids' => array(),
            'event_post_ids' => array(),
        );
        
        $instance = wp_parse_args((array) $instance, $default);

        $events_categories = get_terms('event_category', array('orderby' => 'name', "hide_empty" => false));
        $events_tags = get_terms('event_tag', array('orderby' => 'name', "hide_empty" => false));
        $get_events = new WP_Query(array('post_type' => EVENT_POST_TYPE, 'posts_per_page' => -1));
        $events_options = $get_events->posts;

        $fields = array(
            'title' => array('value' => $instance['title']),
            'events_per_page' => array('value' => $instance['events_per_page']),
            'subscribe_link' => array('value' => $instance['subscribe_link']),
            'show_subscribe_buttons' => array('value' => $instance['show_subscribe_buttons']),
            'limit_by_cat' => array('value' => $instance['limit_by_cat']),
            'limit_by_tag' => array('value' => $instance['limit_by_tag']),
            'limit_by_post' => array('value' => $instance['limit_by_post']),
            'event_cat_ids' => array(
                'value' => (array) $instance['event_cat_ids'],
                'options' => $events_categories
            ),
            'event_tag_ids' => array(
                'value' => (array) $instance['event_tag_ids'],
                'options' => $events_tags
            ),
            'event_post_ids' => array(
                'value' => (array) $instance['event_post_ids'],
                'options' => $events_options
            ),
        );
        foreach ($fields as $field => $data) {
            $fields[$field]['id'] = $this->get_field_id($field);
            $fields[$field]['name'] = $this->get_field_name($field);
            $fields[$field]['value'] = $data['value'];
            
            if (isset($data['options'])) {
                $fields[$field]['options'] = $data['options'];
            }
        }

        $this->display_widget_form($fields);
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['events_per_page'] = intval($new_instance['events_per_page']);
        if ($instance['events_per_page'] < 1) {
            $instance['events_per_page'] = 1;
        }
        
        $instance['subscribe_link'] = intval($new_instance['subscribe_link']);
        $instance['show_subscribe_buttons'] = $new_instance['show_subscribe_buttons'] ? true : false;

        $instance['limit_by_cat'] = false;
        $instance['event_cat_ids'] = array();
        if (!empty($new_instance['limit_by_cat']) && !empty($new_instance['event_cat_ids'])) {
            $instance['limit_by_cat'] = true;
            $instance['event_cat_ids'] = $new_instance['event_cat_ids'];
        }

        $instance['limit_by_tag'] = false;
        $instance['event_tag_ids'] = array();
        if (!empty($new_instance['limit_by_tag']) && !empty($new_instance['event_tag_ids'])) {
            $instance['limit_by_tag'] = true;
            $instance['event_tag_ids'] = $new_instance['event_tag_ids'];
        }

        $instance['limit_by_post'] = false;
        $instance['event_post_ids'] = array();
        if (!empty($new_instance['limit_by_post']) && !empty($new_instance['event_post_ids'])) {
            $instance['limit_by_post'] = true;
            $instance['event_post_ids'] = $new_instance['event_post_ids'];
        }
        
        return $instance;
    }

    public function widget($args, $instance) {
        global $event_events_helper, $event_calendar_helper;

        if(empty($event_events_helper) || empty($event_calendar_helper)) {
            _e('Das Termin-Plugin ist nicht vorhanden.', 'fau');
            return;
        }
        
        $subscribe_filter = '';
        $subscribe_filter .= $instance['event_cat_ids'] ? '&event_cat_ids=' . join(',', $instance['event_cat_ids']) : '';
        $subscribe_filter .= $instance['event_tag_ids'] ? '&event_tag_ids=' . join(',', $instance['event_tag_ids']) : '';
        $subscribe_filter .= $instance['event_post_ids'] ? '&event_post_ids=' . join(',', $instance['event_post_ids']) : '';

        $timestamp = $event_events_helper->gmt_to_local(time());

        $limit = array(
            'cat_ids' => $instance['event_cat_ids'],
            'tag_ids' => $instance['event_tag_ids'],
            'post_ids' => $instance['event_post_ids'],
        );

        $event_results = $event_calendar_helper->get_events_relative_to($timestamp, $instance['events_per_page'], 0, $limit);
        $dates = $event_calendar_helper->get_agenda_date_array($event_results['events']);

        $args['title'] = $instance['title'];
        $args['subscribe_link'] = $instance['subscribe_link'];
        $args['show_subscribe_buttons'] = $instance['show_subscribe_buttons'];
        $args['dates'] = $dates;
        $args['subscribe_url'] = EVENT_EXPORT_URL . $subscribe_filter;

        $this->display_widget($args);
    }

    private function display_widget_form($args) {
        extract($args);
        ?>        
        <p>
            <label for="<?php echo $title['id'] ?>"><?php echo __('Titel', 'fau') . ': ' ?></label>
            <input class="widefat" id="<?php echo $title['id'] ?>" name="<?php echo $title['name'] ?>" type="text" value="<?php echo $title['value'] ?>" />
        </p>
        <p>
            <label for="<?php echo $events_per_page['id'] ?>"><?php echo __('Anzahl der Termine', 'fau') . ': ' ?></label>
            <input id="<?php echo $events_per_page['id'] ?>" name="<?php echo $events_per_page['name'] ?>" type="text" size="3" value="<?php echo $events_per_page['value'] ?>" />
        </p>
        <p class="event-limit-by-container">
            <?php _e('Filter:', 'fau') ?>
            <br>

            <input id="<?php echo $limit_by_cat['id'] ?>" name="<?php echo $limit_by_cat['name'] ?>" type="checkbox" value="1" <?php if ($limit_by_cat['value']) echo 'checked="checked"' ?> />
            <label for="<?php echo $limit_by_cat['id'] ?>"><?php _e('Termine mit diesen Kategorien', 'fau') ?></label>
        </p>
        <div class="event-limit-by-options-container" <?php if (!$limit_by_cat['value']) { ?> style="display: none;" <?php } ?>>
            <select id="<?php echo $event_cat_ids['id'] ?>" name="<?php echo $event_cat_ids['name'] ?>[]" size="5" multiple="multiple">
                <?php foreach ($event_cat_ids['options'] as $event_cat): ?>
                    <option value="<?php echo $event_cat->term_id; ?>"<?php if (in_array($event_cat->term_id, $event_cat_ids['value'])) { ?> selected="selected"<?php } ?>><?php echo $event_cat->name; ?></option>
                <?php endforeach ?>
                <?php if (count($event_cat_ids['options']) == 0) : ?>
                    <option disabled="disabled"><?php _e('Keine Kategorien vorhanden.', 'fau') ?></option>
                <?php endif ?>
            </select>
        </div>
        <p class="event-limit-by-container">

            <input id="<?php echo $limit_by_tag['id'] ?>" name="<?php echo $limit_by_tag['name'] ?>" type="checkbox" value="1" <?php if ($limit_by_tag['value']) echo 'checked="checked"' ?> />
            <label for="<?php echo $limit_by_tag['id'] ?>"><?php _e('oder Termine mit diesen Schlagworte', 'fau') ?></label>
        </p>
        <div class="event-limit-by-options-container" <?php if (!$limit_by_tag['value']) { ?> style="display: none;" <?php } ?>>
            <select id="<?php echo $event_tag_ids['id'] ?>" name="<?php echo $event_tag_ids['name'] ?>[]" size="5" multiple="multiple">
                <?php foreach ($event_tag_ids['options'] as $event_tag): ?>
                    <option value="<?php echo $event_tag->term_id; ?>"<?php if (in_array($event_tag->term_id, $event_tag_ids['value'])) { ?> selected="selected"<?php } ?>><?php echo $event_tag->name; ?></option>
                <?php endforeach ?>
                <?php if (count($event_tag_ids['options']) == 0) : ?>
                    <option disabled="disabled"><?php _e('Keine Schlagworte vorhanden.', 'fau') ?></option>
                <?php endif ?>
            </select>
        </div>
        <p class="event-limit-by-container">

            <input id="<?php echo $limit_by_post['id'] ?>" name="<?php echo $limit_by_post['name'] ?>" type="checkbox" value="1" <?php if ($limit_by_post['value']) echo 'checked="checked"' ?> />
            <label for="<?php echo $limit_by_post['id'] ?>"><?php _e('oder diese Termine', 'fau') ?></label>
        </p>
        <div class="event-limit-by-options-container" <?php if (!$limit_by_post['value']) { ?> style="display: none;" <?php } ?>>
            <select id="<?php echo $event_post_ids['id'] ?>" name="<?php echo $event_post_ids['name'] ?>[]" size="5" multiple="multiple">
                <?php foreach ($event_post_ids['options'] as $event_post): ?>
                    <option value="<?php echo $event_post->ID; ?>"<?php if (in_array($event_post->ID, $event_post_ids['value'])) { ?> selected="selected"<?php } ?>><?php echo $event_post->post_title; ?></option>
                <?php endforeach ?>
                <?php if (count($event_post_ids['options']) == 0) : ?>
                    <option disabled="disabled"><?php _e('Keine Termine vorhanden.', 'fau') ?></option>
                <?php endif ?>
            </select>
        </div>
        <br>
        <p>
            <label for="<?php echo $subscribe_link['id'] ?>"><?php echo __('Abonnement-Link (statische Seite)', 'fau') . ': ' ?></label>
            <?php wp_dropdown_pages(array(
                'id' => $subscribe_link['id'],
                'name' => $subscribe_link['name'],
                'selected' => $subscribe_link['value'],
                'show_option_none' => __('— Auswählen —', 'fau'),
                'option_none_value' => -1
            )); ?>
        </p>        
        <p>
            <input id="<?php echo $show_subscribe_buttons['id'] ?>" name="<?php echo $show_subscribe_buttons['name'] ?>" type="checkbox" value="1" <?php if ($show_subscribe_buttons['value']) echo 'checked="checked"' ?> />
            <label for="<?php echo $show_subscribe_buttons['id'] ?>"><?php _e('Abonnement-Link anzeigen', 'fau') ?></label>
        </p>
        <?php
    }

    private function display_widget($args) {
        extract($args);
        echo $before_widget;

        if( $title ) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div>
            <?php if( ! $dates ): ?>
                <p><?php _e( 'Keine bevorstehenden Termine', 'fau' ); ?></p>
            <?php else: ?>
                <ul>
                    <?php foreach( $dates as $timestamp => $date_info ): ?>                       
                        <?php foreach( $date_info['events'] as $category ): ?>
                            <?php foreach( $category as $event ): ?>
                            <?php $cat = get_the_terms($event->post_id, 'event_category'); ?>
                            <li class="<?php foreach ($cat as $c) : echo ' event-category-' . $c->slug; endforeach; ?>
                                <?php if (isset($date_info['today']) && $date_info['today']) echo ' event-today'; ?>">
                                <div class="event-date">
                                    <?php /* echo date_i18n( get_option( 'date_format' ), $timestamp, true ) */?>
                                    <div class="event-date-month">
                                        <?php echo date_i18n('M', $timestamp, TRUE); ?>
                                    </div>
                                    <div class="event-date-day">
                                        <?php echo date_i18n('d', $timestamp, TRUE); ?>
                                    </div>
                                </div>
                                <div class="event-info event-id-<?php echo $event->post_id; ?>
                                    <?php if( $event->allday ) echo 'event-allday'; ?>">
                                    <?php if( ! $event->allday ): ?>
                                        <div class="event-time"><?php echo esc_html( sprintf( __( '%s Uhr bis %s Uhr', 'fau' ), $event->start_time, $event->end_time ) ) ?></div>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_attr( get_permalink( $event->post_id ) ); ?>">
                                        <div class="event-title">
                                            <?php echo esc_html( apply_filters( 'the_title', $event->post->post_title ) ); ?>
                                        </div>
                                    </a>
                                    <div class="event-location">
                                        <?php if ( !empty( $event->venue ) ): ?>
                                            <?php echo sprintf( '%s', $event->venue ); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    
                    <?php endforeach; ?>
                    <li>
                        <div class="events-more-links">               
                        <?php if( $show_subscribe_buttons && $subscribe_link > 0): ?>
                            <a class="events-more" href="<?php echo get_permalink($subscribe_link); ?>"><?php _e( 'Mehr Veranstaltungen', 'fau' ); ?></a>
                        <?php endif; ?>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <?php 
        echo $after_widget;       
    }
    
}
endif;


<?php
/**
 * Registers Events Custom Content type and adds fields.
 *
 * @since             1.0.0
 * @package           CD Events Pull
 */

/**
 * Post Type: Events.
 */
function cptui_register_my_cpts_events() {

	$labels = [
		'name' => __( 'Events', 'custom-post-type-ui' ),
		'singular_name' => __( 'Events', 'custom-post-type-ui' ),
	];

	$args = [
		'label' => __( 'Events', 'custom-post-type-ui' ),
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'delete_with_user' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => true,
		'rewrite' => [
			'slug' => 'events',
			'with_front' => true,
		],
		'query_var' => true,
		'menu_icon' => 'dashicons-calendar-alt',
		'supports' => [ 'title', 'thumbnail', 'revisions', 'page-attributes' ],
		'taxonomies' => [ 'event_categories' ],
		'menu_position' => 5,
	];

	register_post_type( 'events', $args );
}

add_action( 'init', 'cptui_register_my_cpts_events' );


/* Events custom fields */
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key' => 'group_5e87a6189e8c8',
		'title' => 'Events Fields',
		'fields' => array(
			array(
				'key' => 'field_5e87a6d08e662',
				'label' => 'Title',
				'name' => 'title',
				'type' => 'text',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5e87a7238e664',
				'label' => 'Date',
				'name' => 'date',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'F j, Y',
				'return_format' => 'F j, Y',
				'first_day' => 1,
			),
			array(
				'key' => 'field_5e8cbe2e0afc4',
				'label' => 'Info',
				'name' => 'info',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_5e87a822aefbe',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5e93bd815aea0',
				'label' => 'Event Start Time',
				'name' => 'start_time',
				'type' => 'time_picker',
				'instructions' => '',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'g:i a',
				'return_format' => 'g:i a',
				'first_day' => 1,
			),
			array(
				'key' => 'field_5e93bd9e47d34',
				'label' => 'Is All Day Event?',
				'name' => 'is_all_day',
				'type' => 'true_false',
				'instructions' => 'Check this box to let attendees know this is event will run all day.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_5e8fbc15010fd',
				'label' => 'event_id',
				'name' => 'event_id',
				'type' => 'text',
				'instructions' => 'Set by CD Events plugin do not edit.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5e979f1e9f171',
				'label' => 'Localist Url',
				'name' => 'localist_url',
				'type' => 'text',
				'instructions' => 'Set by CD Events plugin do not edit.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5f63ce9c9ad2a',
				'label' => 'Featured image URL',
				'name' => 'featured_image_url',
				'type' => 'text',
				'instructions' => 'Typically this fields is set by CD Events Feed plugin, but this can be used to set the URL for the featured image if it hosted somewhere else.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'events',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array(
			0 => 'excerpt',
			1 => 'discussion',
			2 => 'comments',
			3 => 'slug',
			4 => 'author',
			5 => 'format',
			6 => 'categories',
			7 => 'tags',
			8 => 'send-trackbacks',
		),
		'active' => true,
		'description' => '',
	));

endif;

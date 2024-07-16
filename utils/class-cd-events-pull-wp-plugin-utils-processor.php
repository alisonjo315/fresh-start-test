<?php
/**
 * The migration processing of event data.
 *
 * @link       https://github.com/CU-CommunityApps
 * @since      1.0.0
 *
 * @package    Cd_Events_Pull_Wp_Plugin
 * @subpackage Cd_Events_Pull_Wp_Plugin/admin
 */

/**
 * The migration processing of event data.
 *
 * @package    Cd_Events_Pull_Wp_Plugin
 * @subpackage Cd_Events_Pull_Wp_Plugin/utils
 * @author     psw58 <psw58@cornell.edu>
 */
class Cd_Events_Pull_Wp_Plugin_Utils_Processor {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $status_log Records some data about the events pull.
	 */
	private $status_log;

	/**
	 * The name option name of the log.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $log_option the name of the variable to hold.
	 */
	static $log_option = 'cd_events_status_log';

	/**
	 * The key(name) for the timer.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $transient_timer_key
	 */
	static $transient_timer_key = 'cd-events-wp-pull';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->status_log = [];
		// Creates a new option; does nothing if option already exists.
		add_option( self::$log_option, $this->status_log );

	}

	/**
	 * Sets a timer when the timer expires reloads the event data.
	 *
	 * Consider replacing with CRON clock.
	 */
	public function cd_events_pull_get_cron_timer() {
		$data = get_transient( self::$transient_timer_key );
		$experation = get_option( 'cd_events_pull_timer' );
		if ( empty( $data ) ) {
			// Get the wp datetime.
			$date = current_time( 'timestamp' );
			$date  = date( 'D M d, Y G:i', $date );
			$this->write_log( 'Info: Last Ran: ' . $date );
			set_transient( self::$transient_timer_key , $date , $experation );
			$this->cd_events_pull();
		}
	}

	/**
	 * ETL of events from API.
	 */
	private function cd_events_pull() {
		$this->write_log( 'Notice: Starting extract events from API.' );
		$events_response = $this->cd_extract_events();
		if ( ! $events_response || array_key_exists( 'error', $events_response ) ) {
			$this->write_log( 'Warning: no events recieved from API.' );
			return $events_response;
		};
		$this->write_log( 'Notice: Starting transform event data.' );
		$t_events = $this->cd_transform_events( $events_response );
		$this->write_log( 'Notice: Starting load of event data to WordPress.' );
		$this->cd_load_events( $t_events );
		$this->write_log( 'Notice: Successfully Completed.' );
	}

	/**
	 * Extracts the event data.
	 */
	private function cd_extract_events() {
		// A comma seperated list of department ids.
		$url = get_option( 'cd_events_pull_url' );
		if ( empty( $url ) ) {
			$this->write_log( 'Warning: - CD Events recieved empty url ' . $url );
			return;
		}
		$count = get_option( 'cd_events_pull_count' ) ?: 5;
		$department_string = get_option( 'cd_events_pull_department_ids' );
		if ( ! empty( $department_string ) ) {
			$stripped_dept_ids = str_replace( ' ', '', $department_string );
			$departement_array = explode( ',', $stripped_dept_ids );
			$type_end = implode( '&type[]=', $departement_array );
			$type_string = '&type[]=' . $type_end;
		} else {
			$type_string = '';
		}
		$keyword = get_option( 'cd_events_pull_keyword' );
		$distinct = 'true';
		$page = '1';
		$direction = 'asc';
		$days = 365;
		$data = [
			'apikey' => '',
			'distinct' => $distinct,
			'pp' => $count,
			'page' => $page,
			'direction' => $direction,
			'days' => $days,
		];

		if ( ! empty( $keyword ) ) {
			$data['keyword'] = $keyword;
		}
		$query = http_build_query( $data, null, '&', PHP_QUERY_RFC3986 );
		$uri = "$url?$query$type_string";
		$this->write_log( "Info: url endpoint: $uri" );
		$response = wp_safe_remote_get( $uri );
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$json_body = $response['body'];
			$body = json_decode( $json_body );
			return $body;
		}
		$this->write_log( 'Warning: ' . wp_json_encode( $response ) );
		return (object) [
			'error' => $response,
		];
	}

	/**
	 * Restructure events data.
	 *
	 * @param array $events_response The events fetched from cornell calendar.
	 */
	private function cd_transform_events( $events_response ) {
		$post_type = get_option( 'cd_events_pull_post_type' );
		if ( empty( $post_type ) ) {
			$this->write_log( 'Warning: custom post type is required' );
		}
		$event_id    = get_option( 'cd_events_pull_event_id' ) ?: 'event_id';
		$title       = get_option( 'cd_events_pull_title' );
		$date        = get_option( 'cd_events_pull_date' );
		$location    = get_option( 'cd_events_pull_location' );
		$description = get_option( 'cd_events_pull_description' );
		$image_url   = get_option( 'cd_events_pull_image' );
		$localist_url   = get_option( 'cd_events_pull_localist_url' );
		$is_all_day  = get_option( 'cd_events_pull_is_all_day' );
		$start_time  = get_option( 'cd_events_pull_start_time' );
		$events      = $events_response->events;
		$first       = $events_response->date->first;
		$last        = $events_response->date->last;
		$this->write_log( "Info: API query event dates $first to $last." );
		$this->write_log( 'Info: Paging info: ' . wp_json_encode( $events_response->page ) );
		$t_events = [];
		foreach ( $events as $event ) {
			$e = $event->event;
			$event_stsrt_time = '';
			if ( $e->event_instances[0]->event_instance->start ) {
				$event_stsrt_time = get_date_from_gmt( $e->event_instances[0]->event_instance->start );
			}
			$t = (object) [
				// The post_title and post_content are required.
				'post_content' => $e->description,
				'post_title' => $e->title,
				'post_type' => $post_type,
				'post_author' => 1,
				'meta_input' => [
					$event_id => $e->id,
					$title => $e->title,
					$date => $e->first_date,
					$location => $e->location_name,
					$description => $e->description,
					$image_url => $e->photo_url,
					$start_time => $event_stsrt_time,
					$is_all_day => $e->event_instances[0]->event_instance->all_day,
					$localist_url => $e->localist_url,
				],
			];
			array_push( $t_events, $t );
		}
		return $t_events;
	}

	/**
	 * Loads the events into WordPress.
	 *
	 * @param array $t_events The events ready to load.
	 */
	private function cd_load_events( $t_events ) {
		$publish   = get_option( 'cd_events_pull_is_publish' ) ? 'publish' : 'draft';
		$post_type = get_option( 'cd_events_pull_post_type' );
		$event_id  = get_option( 'cd_events_pull_event_id' ) ?: 'event_id';
		$update    = get_option( 'cd_events_pull_is_update' );
		foreach ( $t_events as $t_event ) {
			$args = array(
				'numberposts' => 1,
				'post_type' => $post_type,
				'meta_key'  => $event_id,
				'meta_value' => $t_event->meta_input[ $event_id ],
				'post_status' => 'any',
			);
			$event_query = new WP_Query( $args );
			if ( ! $event_query->have_posts() ) {
				$t_event->post_status = $publish;
				$post_id = wp_insert_post( $t_event );
				$this->write_log( 'Notice: Created New Event: ' . $t_event->post_title . ' ID:' . $post_id );
			} else {
				if ( $update ) {
					$post_id = $event_query->posts[0]->ID;
					$t_event->ID = $post_id;
					wp_update_post( $t_event );
					$this->write_log( 'Notice: Updated Event:  ' . $t_event->post_title . ' ID:' . $post_id );
				}
			}
		}
	}

	/**
	 * Writes to the error logger
	 *
	 * @param mixed $log The content to log.
	 */
	private function write_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			$log = wp_json_encode( $log );
		}
		array_push( $this->status_log, $log );
		update_option( self::$log_option, $this->status_log );
		if ( true === WP_DEBUG ) {
			error_log( $log );
		}
	}

}

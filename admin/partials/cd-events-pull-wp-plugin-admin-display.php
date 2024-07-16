<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/CU-CommunityApps
 * @since      1.0.0
 *
 * @package    Cd_Events_Pull_Wp_Plugin
 * @subpackage Cd_Events_Pull_Wp_Plugin/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div>
	<h2><img src='//www.cornell.edu/favicon.ico' alt=''> CD Events, Logs and General Info</h2>
	<p>
		This plugin was created by  <b >Cornell Custom Development</b>. For support contact <a href='mailto:iws-support@cornell.edu'>iws-support@cornell.edu</a>
	</p>
	<h3>Status</h3>
	<p>

		<?php
		$logs = get_option( 'cd_events_status_log' );
		if ( is_array( $logs ) ) {
			$last_ran = array_shift( $logs );
			echo '<div>' . esc_html( $last_ran ) . '</br></br><button id="cd_events_expand">Toggle Display of Status Logs</button></div>';
			echo( '<div class="cd-events-show" hidden><ul>' );
			foreach ( $logs as $log ) {
				if ( is_array( $log ) || is_object( $log ) ) {
					$log = wp_json_encode( $log );
				}
				echo( '<li>' . esc_html( $log ) . '</li>' );
			}
			echo( '</ul></div> </br><hr>');
		} else {
			echo( 'ERROR No Status Available!' );
		}
		?>
	</p>
	<h3>Instructions</h3>
	<p>
		Pulls events from the CU Calendar (<a href='https://developer.localist.com/doc/api'>Localist API</a>) and saves it to a WordPress custom content type.
		Use the settings tab to controll how data is pulled, filtered, and loaded into WP custom content fields. Using the Settings Tab.
	</p>
	<!-- <p>
		<h4>Settings Configuration:</h4>
		<ul>
			<li>
				<b>URL</b> The API url endpoint only change this if you want to pull events from Weill Cornell.
			</li>
			<li>
				<b>Count</b> The number of events to pull per request.
			</li>
			<li>
				<b>Department ID's</b> Filters all events by single department id or a comma seperated list of ids. No whitespaces.
			</li>
			<li>
				<b>Keywords</b> Filters all events by a keyword tag. *note the Department ids and keyword are combined filters.
			</li>
			<li>
				<b>Custom Post Type</b> The Slug name of the WP custom content type to load the events into.
			</li>
			<li>
				<b>Title Field</b> The Slug name of the WP custom custom field to load the title into.
			</li>
			<li>
				<b>Date Field of Custom Post</b> The slug name of the WP custom field to set the data into.
			</li>
			<li>
				<b>Location Field of Custom Post</b> The slug name of the WP custom field to load the event location.
			</li>
			<li>
				<b>Description Field of Custom Post</b> The slug name of the WP custom field to load the long text description.
			</li>
			<li>
				<b>Image Field of Custom Post</b> The slug name of the WP custom field to load the image url.
			</li>
			<li>
				<b>Update all existing events</b> Check this box to update the event fields on every load.
			</li>
			<li>
				<b>Publish all new events</b> Check this box to automatically publish events on load.
			</li>
		</ul>
	<p> -->

</div>

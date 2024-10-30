<?php
/**
 * Plugin Name: Author Role Widget
 * Plugin URI: http://elgunvo.de
 * Description: A widget similar to the one of Author Advatar but with Jquery Search.
 * Version: 1
 * Author: Ashley Johnson
 * Author URI: http://www.elgunvo.de
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

add_action( 'widgets_init', 'aw_load_widgets' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */

function aw_load_widgets() {
	register_widget( 'BP_Member_Widget' );
}

class BP_Member_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function BP_Member_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'aww', 'description' => __('A widget to display users and roles.', 'aww') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'aw-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'AW-widget', __('BP Member Widget', 'aww'), $widget_ops, $control_ops );	
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$role = apply_filters('widget_title', $instance['role'] );
		$number = apply_filters('widget_title', $instance['nummer'] );
		$num_results = apply_filters('widget_title', $instance['searcher'] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$res_num = $num_results;
		echo "<script type='text/javascript' src='" . get_bloginfo ( 'siteurl' ) . "/wp-content/plugins/authors-widget/scripts.js'></script><script type='text/javascript' src='" . get_bloginfo ( 'siteurl' ) . "/wp-content/plugins/authors-widget/jQuery.js'></script><div style='overflow: hidden; padding-bottom: 10px; padding-left: 5px; padding-right: 5px; border-bottom: 1px solid #fff; margin-bottom: 10px;'><input type='text' style='width: 100%; height:19px; float: left;' value='' onKeyUp='showResult(this.value, " . $res_num . ");' name='search'></div>";
		
			
		if ( $role )
			global $wpdb;
 
			$authors = $wpdb->get_results("SELECT * FROM $wpdb->users");
			
				echo "<style type='text/css'>#users-list {clear: left;float: left;margin: 0 0 5px 0;} #users-list img {width: 40px;height: 40px;float: left; margin-right: 5px;} #users-list div.authname {margin: 20px 0 0 10px;float: left;}</style>";
				
				$ind = 0;
				
				echo "<div id='plugin_normal'>";
				foreach($authors as $author) {
				$last_activity[] = get_usermeta($author->ID,'last_activity');
				$members[] = $author->ID;
				arsort($last_activity);
				}
				
				foreach ($last_activity as $activity => $items){
				
				$user = new WP_User( $members[$activity] );
				$roler = $user->roles[0];
				
				$displayed_name = get_the_author_meta('display_name', $members[$activity]);
				
				$last_activitys = get_usermeta($members[$activity],'last_activity');

				if (function_exists('bp_core_get_user_domain')) {
					$link = bp_core_get_user_domain($members[$activity]);

				}
				elseif (function_exists('bp_core_get_userurl')) { // BP versions < 1.1
					$link = bp_core_get_userurl($members[$activity]);
				}


			
				if ($role == $roler & $ind < $number) {
				$ind = $ind + 1;
				echo "<div id='users-list'>";
				echo "<a href='$link'>";
				echo get_avatar($members[$activity]);
				echo "</a>";
				echo "<a href='$link'>";
				echo $displayed_name;
				echo "</a></div>";
				}
				}
				echo "</div>";
				echo "<div id='plugin_livesearch' style='display: none;' class='ss'>";
				
				echo "</div>";
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['role'] = strip_tags( $new_instance['role'] );
		$instance['nummer'] = strip_tags( $new_instance['nummer'] );
		$instance['searcher']  = strip_tags( $new_instance['searcher'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Aktive Sponsoren', 'aww'), 'role' => __('subscriber', 'aww'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'role' ); ?>"><?php _e('Role:', 'aww'); ?></label>
			<select name="<?php echo $this->get_field_name( 'role' ); ?>" id="<?php echo $this->get_field_id( 'role' ); ?>">
			<option><?php echo $instance['role']; ?></option>
			<?php wp_dropdown_roles(); ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'nummer' ); ?>"><?php _e('Num of Results:', 'aww'); ?></label>
			<select name="<?php echo $this->get_field_name( 'nummer' ); ?>" id="<?php echo $this->get_field_id( 'nummer' ); ?>">
			<option><?php echo $instance['nummer']; ?></option>
			<option>5</option>
			<option>10</option>
			<option>15</option>
			<option>20</option>
			<option>25</option>
			<option>30</option>
			<option>35</option>
			<option>40</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'searcher' ); ?>"><?php _e('Num of Search Results:', 'aww'); ?></label>
			<select name="<?php echo $this->get_field_name( 'searcher' ); ?>" id="<?php echo $this->get_field_id( 'searcher' ); ?>">
			<option><?php echo $instance['searcher']; ?></option>
			<option>5</option>
			<option>10</option>
			<option>15</option>
			<option>20</option>
			<option>25</option>
			<option>30</option>
			<option>35</option>
			<option>40</option>
			</select>
		</p>

	<?php
	}
}

?>
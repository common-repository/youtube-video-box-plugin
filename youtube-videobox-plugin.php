<?php
/**
 * Plugin Name: Youtube Video Box Plugin
 * Plugin URI: http://www.sramekdesign.com/wordpress/plugins/youtube-video-box-plugin/
 * Description: With Youtube Video Box Plugin you can add unlimited number of sidebar widgets to display Youtube videos in <strong>XHTML valid</strong> format. This plugin also allows you to easily customize width and height of the videos directly in your widget panel. Optional function that enhances this plugin gives you the ability to chose colors in YouTube and enable or disable them in your widget panel. This plugin is ideal for those of you who wish to display several videos in easy-to-manage widgets, yet maintain a xhtml valid, SEO friendly website code. Compatible with any java / ajax powered plugin, doesn't affect the loading time and thanks to its' lite structure, will not crash your site.
 * Tags: youtube, video, videobox, xhtml valid, embed video
 * Version: 0.9.1
 * Author: Tom Sramek
 * Author URI: http://psdtowordpress.us
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.9
 */
add_action( 'widgets_init', 'yvb_load_widgets' );

/**
 * Register our widget.
 * 'Youtube_Video_Box_Widget' is the widget class used below.
 *
 * @since 0.9
 */
function yvb_load_widgets() {
	register_widget( 'Youtube_Video_Box_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 *
 * @since 0.9
 */
class Youtube_Video_Box_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Youtube_Video_Box_Widget() {
		/* Widget settings. */
		$widget_ops = array(
         'classname' => 'yvb_class',
         'description' => __('Display your video easily', 'yvb_class') );

		/* Widget control settings. */
		$control_ops = array(
         'width' => 200,
         'height' => 350,
         'id_base' => 'yvb_class-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'yvb_class-widget', __('Youtube Video Box', 'yvb_class'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
        $width = apply_filters('widget_title', $instance['width'] );
        $height = apply_filters('widget_title', $instance['height'] );
		$video = $instance['video'];
        $enable_params = isset( $instance['enable_params'] ) ? $instance['enable_params'] : false;
        
        // change width and height
        $video = preg_replace('/(width)=("[^"]*")/i', 'width="'.$width.'"', $video);
        $video = preg_replace('/(height)=("[^"]*")/i', 'height="'.$height.'"', $video);
        
        // strbet($inputStr, $delimeterLeft, $delimeterRight, $debug=false)
        // gets src="$value& string
        $delimeterLeft = ("src=\"");
        
        // check if parameters are enabled
        if ($enable_params) {
              $delimeterRight = ("\"");
            } else {
              $delimeterRight = ("&");    
          }
        
        // extracting url of the video, something like http://www.youtube.com/v/S7r3xXGWVNM
        $video_url = strbet($video, $delimeterLeft, $delimeterRight, $debug = false);   

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display video from widget settings if one was input. */        
		echo '<object type="application/x-shockwave-flash" style="width:'.$width.'px; height:'.$height.'px;" data="'.$video_url.'"><param name="movie" value="'.$video_url.'" /></object> ';		

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title, width and height to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );	
        $instance['width'] = strip_tags( $new_instance['width'] );
        $instance['height'] = strip_tags( $new_instance['height'] );	

		/* No need to strip tags video and enable_params */		
        $instance['video'] =  $new_instance['video'];
        $instance['enable_params'] = $new_instance['enable_params'];
		
		$instance['filter'] = isset($new_instance['filter']);

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
         'title' => __('Video title', 'yvb_class'),
         'width' => __('200', 'yvb_class'),
         'height' => __('100', 'yvb_class'),
         'video' => __('Here comes video embed code', 'yvb_class') );
		$instance = wp_parse_args( (array) $instance, $defaults );       
         ?>
        
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'yvb_class'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>
        
        <!-- Width: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width:', 'yvb_class'); ?></label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:95%;" />
		</p>
        
        <!-- Height: Text Input -->
		<p>
		 <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height:', 'yvb_class'); ?></label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:95%;" />
		</p>

		<!-- Video: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'video' ); ?>"><?php _e('Video:', 'yvb_class'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'video' ); ?>" name="<?php echo $this->get_field_name( 'video' ); ?>" style="width:98%; height: 150px;"><?php echo apply_filters('widget_title', $instance['video'] ); ?></textarea>
		</p>
        
        <!-- Enable parameters? Checkbox -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['enable_params'], true ); ?> id="<?php echo $this->get_field_id( 'enable_params' ); ?>" name="<?php echo $this->get_field_name( 'enable_params' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'enable_params' ); ?>"><?php _e('Enable parameters (colors, related videos etc.)?  This will result in invalid XHTML code.', 'yvb_class'); ?></label>
		</p>		

	<?php
	}
}

?>
<?php
/**
 * extract url of the video 
 *
 * @since 0.9
 */
function strbet($inputStr, $delimeterLeft, $delimeterRight, $debug=false) {
    $posLeft=strpos($inputStr, $delimeterLeft);
    if ( $posLeft===false ) {
        if ( $debug ) {
            echo "Warning: left delimiter '{$delimeterLeft}' not found";
        }
        return false;
    }
    $posLeft+=strlen($delimeterLeft);
    $posRight=strpos($inputStr, $delimeterRight, $posLeft);
    if ( $posRight===false ) {
        if ( $debug ) {
            echo "Warning: right delimiter '{$delimeterRight}' not found";
        }
        return false;
    }
    return substr($inputStr, $posLeft, $posRight-$posLeft);
}
?>
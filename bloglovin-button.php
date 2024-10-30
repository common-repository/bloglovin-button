<?php
/*
Plugin Name: Bloglovin Button
Plugin URI: https://wordpress.org/plugins/bloglovin-button/
Version: 1.3.9
Author: pipdig
Description: Easily add the Bloglovin Button to your WordPress blog.
Text Domain: bloglovin-button
Author URI: https://www.pipdig.co/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class bloglovin_button_widget extends WP_Widget {
 
  public function __construct() {
     $widget_ops = array('classname' => 'bloglovin_button_widget', 'description' => __("Display the official Bloglovin' button.", 'bloglovin-button') );
     parent::__construct('bloglovin_button_widget', __("Bloglovin' Button", 'bloglovin-button'), $widget_ops);
  }
  
  function widget($args, $instance) {
    // PART 1: Extracting the arguments + getting the values
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
    $links = get_option('pipdig_links');
	if (isset($instance['bloglovin_url'])) {
		$bloglovin_url = esc_url($instance['bloglovin_url']);
	} elseif (isset($links['bloglovin'])) { // pull from p3 if available
		$bloglovin_url = esc_url($links['bloglovin']);
	} else {
		$bloglovin_url = '';
	}
	$style_select = empty($instance['style_select']) ? '' : $instance['style_select'];
	
    // Before widget code, if any
    echo (isset($before_widget)?$before_widget:'');
   
    // PART 2: The title and the text output
    if (!empty($title)) {
		echo $before_title . $title . $after_title;
	}
	
	switch ($style_select) {
		case '1':
			$counter = 'true';
			$button = 'button';
		break;
		case '2':
			$counter = 'false';
			$button = 'button';
		break;
		case '3':
			$counter = 'false';
			$button = '';
		break;
	}

    if (!empty($bloglovin_url)) {
		if ($url = parse_url($bloglovin_url)) {
			$bloglovin_url =  'https://'.$url['host'].$url['path'];
			$bloglovin_url = rtrim($bloglovin_url,"/");
		}
		?>
		<a data-blsdk-counter="<?php echo $counter; ?>" data-blsdk-type="<?php echo $button; ?>" target="_blank" href="<?php echo $bloglovin_url; ?>" class="blsdk-follow">Follow</a>
		<script defer>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "https://www.bloglovin.com/widget/js/loader.js?v=1";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "bloglovin-sdk"))</script>
		<?php
	} else {
		_e("Setup not complete. Please add your Bloglovin' URL to the Bloglovin' Button in the dashboard.", 'bloglovin-button');
	}
    // After widget code, if any
    echo (isset($after_widget)?$after_widget:'');
  }
 
  public function form( $instance ) {
   
    // PART 1: Extract the data from the instance variable
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
	if (isset($instance['bloglovin_url'])) {
		$bloglovin_url = esc_url($instance['bloglovin_url']);
	}
	$links = get_option('pipdig_links');
	if (isset($instance['bloglovin_url'])) {
		$bloglovin_url = esc_url($instance['bloglovin_url']);
	} elseif (isset($links['bloglovin'])) { // pull from p3 if available
		$bloglovin_url = esc_url($links['bloglovin']);
	} else {
		$bloglovin_url = '';
	}
	$style_select = ( isset( $instance['style_select'] ) && is_numeric( $instance['style_select'] ) ) ? (int) $instance['style_select'] : 1;
 
   
    // PART 2-3: Display the fields
    ?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'bloglovin-button'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('bloglovin_url'); ?>"><?php _e("Bloglovin' URL:", 'bloglovin-button'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('bloglovin_url'); ?>" name="<?php echo $this->get_field_name('bloglovin_url'); ?>" type="url" value="<?php echo esc_url($bloglovin_url); ?>" required />
	</p>
	
	<p style="font-size: 90%"><?php _e("You should use your full Bloglovin' URL. For example:", 'bloglovin-button'); ?><br /><em>https://www.bloglovin.com/blogs/inthefrow-4177899</em></p>
	
	<p>
		<legend><h4><?php _e('Button style:', 'bloglovin-button'); ?></h4></legend>
		
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-1') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="1" <?php checked( $style_select == 1, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-1' ) ?>"><img src="<?php echo plugins_url( 'img/button_with_count.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>
<br /><br />
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-2') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="2" <?php checked( $style_select == 2, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-2' ) ?>"><img src="<?php echo plugins_url( 'img/button_no_count.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>
<br /><br />
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-3') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="3" <?php checked( $style_select == 3, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-3' ) ?>"><img src="<?php echo plugins_url( 'img/bloglovin-button-full.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>
	</p>
	
	<p><?php _e("You can also add your Bloglovin' button to any post/page by using the shortcode [bloglovin_button]", 'bloglovin-button'); ?></p>
    <?php
   
  }
 
  function update($new_instance, $old_instance) {
	if (!is_customize_preview()) {
		update_option('pipdig_bloglovin_btn_url', $new_instance['bloglovin_url']);
	}
	$instance = $old_instance;
	$instance['title'] = sanitize_text_field($new_instance['title']);
	$instance['bloglovin_url'] = esc_url($new_instance['bloglovin_url']);
	$instance['style_select'] = ( isset( $new_instance['style_select'] ) && $new_instance['style_select'] > 0 && $new_instance['style_select'] < 4 ) ? (int) 	$new_instance['style_select'] : 0; // 4 is total radio +1
	
	return $instance;
  }
  
}

function bloglovin_button_register_widgets() {
	register_widget('bloglovin_button_widget');
}
add_action('widgets_init', 'bloglovin_button_register_widgets');



function bloglovin_button_shortcode( $atts ) {
	extract( shortcode_atts(
		array(
			'email' => '',
		), $atts )
	);
	
	if (get_option('pipdig_bloglovin_btn_url')) {
		$bloglovin_url = get_option('pipdig_bloglovin_btn_url');
	} else {
		$links = get_option('pipdig_links');
		if (isset($links['bloglovin'])) { // pull from p3 if available
			$bloglovin_url = $links['bloglovin'];
		}
	}

	$output = '';
	
	if (empty($bloglovin_url)) {
		return $output;
	}
	
	if ($email == 'true') {
		$output = '<div style="text-align:center;max-width:320px;margin:0 auto"><a title="'.__('Follow on Bloglovin', 'bloglovin-button').'" class="blsdk-follow" href="'.esc_url($bloglovin_url).'" target="_blank" rel="nofollow" data-blsdk-type="" data-blsdk-counter="false">'.__('Follow on Bloglovin', 'bloglovin-button').'</a><scr'.'ipt defer>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "https://www.bloglovin.com/widget/js/loader.js?v=1";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "bloglovin-sdk"))</scr'.'ipt>';
	} else {
		$output = '
		<a title="'.esc_attr(__('Follow on Bloglovin', 'bloglovin-button')).'" target="_blank" class="bl_button_shortcode nopin" rel="nofollow" href="'.esc_url($bloglovin_url).'"><img src="data:image/png;base64,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" style="width: 120px; height: auto" class="nopin" data-pin-nopin="true" alt="'.esc_attr(__('Follow on Bloglovin', 'bloglovin-button')).'" /></a>
		';
	}
	
	return $output;
}
add_shortcode( 'bloglovin_button', 'bloglovin_button_shortcode' );
<?php
/*
Plugin Name: Really simple Facebook Twitter share buttons
Plugin URI: http://www.whiletrue.it
Description: Puts Facebook, LinkedIn and Twitter share buttons above or below your posts.
Author: WhileTrue
Version: 1.3.0
Author URI: http://www.whiletrue.it
*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

add_filter('the_content', 'really_simple_share');

add_filter('plugin_action_links', 'really_simple_share_add_settings_link', 10, 2 );

add_action('admin_menu', 'really_simple_share_menu');

function really_simple_share_menu() {
	add_options_page('Really simple share Options', 'Really simple share', 'manage_options', 'really_simple_share_options', 'really_simple_share_options');
}

function really_simple_share_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
	if ($file == $this_plugin){
		$settings_link = '<a href="admin.php?page=really_simple_share_options">'.__("Settings").'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
} 

function really_simple_share ($content) {

	$option_string = get_option('really_simple_share');

	if ($option_string=='above' or $option_string=='below') {
		// Versions below 1.2.0 compatibility
		$option = really_simple_share_get_default_options($option_string);
	} else if(!is_array($option_string)) {
		// Versions below 1.2.2 compatibility
		$option = json_decode($option_string, true);
	} else {
		$option = $option_string;
	}

	if (is_single()) {
		if (!$option['show_in']['posts']) {
			return $content;
		}
	} else if (is_singular()) {
		if (!$option['show_in']['pages']) {
			return $content;
		}
	} else if (is_home()) {
		if (!$option['show_in']['home_page']) {
			return $content;
		}
	} else if (is_tag()) {
		if (!$option['show_in']['tags']) {
			return $content;
		}
	} else if (is_category()) {
		if (!$option['show_in']['categories']) {
			return $content;
		}
	} else if (is_date()) {
		if (!$option['show_in']['dates']) {
			return $content;
		}
	} else if (is_author()) {
		//IF DISABLED INSIDE PAGES
		if (!$option['show_in']['authors']) {
			return $content;
		}
	} else {
		// IF NONE OF PREVIOUS, IS DISABLED
		return $content;
	}
	
	$first_shown = false; // NO PADDING FOR THE FIRST BUTTON
	
	$out = '<div style="height:21px; padding-top:2px;" class="really_simple_share">';
	if ($option['active_buttons']['facebook']==true) {
		$first_shown = true;
		
		// REMOVE HTTP:// FROM STRING
		$facebook_link = get_permalink();
		if (substr($facebook_link,0,7)=='http://') {
			$facebook_link = substr($facebook_link,7);
		}
		
		$out .= '<div style="float:left; width:100px;" class="really_simple_share_facebook"> 
				<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php"
					share_url="'.$facebook_link.'">Share</a> 
				<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> 
			</div>';
	}
	if ($option['active_buttons']['facebook_like']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; width:90px; '.$padding.'" class="really_simple_share_facebook_like"> 
				<iframe src="http://www.facebook.com/plugins/like.php?href='.get_permalink().'&amp;layout=button_count&amp;show_faces=false&amp;width=90&amp;action=lifdke&amp;colorscheme=light&amp;height=21" 
					scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90; height:21px;" allowTransparency="true"></iframe>
			</div>';
	}
	if ($option['active_buttons']['linkedin']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_linkedin"> 
	  			<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
				<script type="in/share" data-counter="right" data-url="'.get_permalink().'"></script>
			</div>';
	}
	if ($option['active_buttons']['buzz']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_buzz"> 
				<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count" 
					data-url="'.get_permalink().'"></a>
				<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
			</div>';
	}
	if ($option['active_buttons']['digg']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_digg"> 
				<script type="text/javascript">
				(function() {
				var s = document.createElement("SCRIPT"), s1 = document.getElementsByTagName("SCRIPT")[0];
				s.type = "text/javascript";
				s.async = true;
				s.src = "http://widgets.digg.com/buttons.js";
				s1.parentNode.insertBefore(s, s1);
				})();
				</script>
				<a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url='.get_permalink().'&amp;title='.htmlentities(get_the_title()).'"></a>	
			</div>';
	}
	if ($option['active_buttons']['stumbleupon']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_stumbleupon"> 
				<script src="http://www.stumbleupon.com/hostedbadge.php?s=1&r='.get_permalink().'"></script>
			</div>';
	}	
	if ($option['active_buttons']['twitter']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_twitter"> 
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" 
					data-text="'.htmlentities(get_the_title()).'" data-url="'.get_permalink().'">Tweet</a> 
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> 
			</div>';
	}
	
	$out .= '</div>
	<br style="clear:both;" />';
		
	if ($option['position']=='below') {
		return $content.$out;
	} else {
		return $out.$content;
	}
}

function really_simple_share_options () {

	$option_name = 'really_simple_share';

	//must check that the user has the required capability 
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// See if the user has posted us some information
	if( isset($_POST['really_simple_share_position'])) {
		$option = array();

		$option['active_buttons']['facebook'] = ($_POST['really_simple_share_active_facebook']=='on') ? true : false;
		$option['active_buttons']['facebook_like'] = ($_POST['really_simple_share_active_facebook_like']=='on') ? true : false;
		$option['active_buttons']['twitter']  = ($_POST['really_simple_share_active_twitter'] =='on') ? true : false;
		$option['active_buttons']['linkedin'] = ($_POST['really_simple_share_active_linkedin']=='on') ? true : false;
		$option['active_buttons']['buzz']     = ($_POST['really_simple_share_active_buzz']    =='on') ? true : false;
		$option['active_buttons']['digg']     = ($_POST['really_simple_share_active_digg']    =='on') ? true : false;
		$option['active_buttons']['stumbleupon'] = ($_POST['really_simple_share_active_stumbleupon']=='on') ? true : false;

		$option['position'] = esc_html($_POST['really_simple_share_position']);
		
		$option['show_in']['posts']      = ($_POST['really_simple_share_show_posts']     =='on') ? true : false;
		$option['show_in']['pages']      = ($_POST['really_simple_share_show_pages']     =='on') ? true : false;
		$option['show_in']['home_page']  = ($_POST['really_simple_share_show_home']      =='on') ? true : false;
		$option['show_in']['tags']       = ($_POST['really_simple_share_show_tags']      =='on') ? true : false;
		$option['show_in']['categories'] = ($_POST['really_simple_share_show_categories']=='on') ? true : false;
		$option['show_in']['dates']      = ($_POST['really_simple_share_show_dates']     =='on') ? true : false;
		$option['show_in']['authors']    = ($_POST['really_simple_share_show_authors']   =='on') ? true : false;
		
		update_option($option_name, $option);
		// Put an settings updated message on the screen
		$out .= '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
	}
	
	//GET STORED VALUES
	$option = array();
	$option_string = get_option($option_name);
	 
	if ($option_string===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		$option = really_simple_share_get_default_options();
		add_option($option_name, 'above');
		$option_string = get_option($option_name);
	}
	
	if ($option_string=='above' or $option_string=='below') {
		// Versions below 1.2.0 compatibility
		$option = really_simple_share_get_default_options($option_string);
	} else if(!is_array($option_string)) {
		// Versions below 1.2.2 compatibility
		$option = json_decode($option_string, true);
	} else {
		$option = $option_string;
	}
	
	$sel_above = ($option['position']=='above') ? 'selected="selected"' : '';
	$sel_below = ($option['position']=='below') ? 'selected="selected"' : '';

	$active_facebook = ($option['active_buttons']['facebook']==true) ? 'checked="checked"' : '';
	$active_facebook_like = ($option['active_buttons']['facebook_like']==true) ? 'checked="checked"' : '';
	$active_twitter  = ($option['active_buttons']['twitter'] ==true) ? 'checked="checked"' : '';
	$active_linkedin = ($option['active_buttons']['linkedin']==true) ? 'checked="checked"' : '';
	$active_buzz     = ($option['active_buttons']['buzz']    ==true) ? 'checked="checked"' : '';
	$active_digg     = ($option['active_buttons']['digg']    ==true) ? 'checked="checked"' : '';
	$active_stumbleupon = ($option['active_buttons']['stumbleupon']==true) ? 'checked="checked"' : '';

	$show_in_posts = ($option['show_in']['posts']==true) ? 'checked="checked"' : '';
	$show_in_pages = ($option['show_in']['pages'] ==true) ? 'checked="checked"' : '';
	$show_in_home  = ($option['show_in']['home_page']==true) ? 'checked="checked"' : '';
	$show_in_tags  = ($option['show_in']['tags']==true) ? 'checked="checked"' : '';
	$show_in_categories  = ($option['show_in']['categories']==true) ? 'checked="checked"' : '';
	$show_in_dates  = ($option['show_in']['dates']==true) ? 'checked="checked"' : '';
	$show_in_authors  = ($option['show_in']['authors']==true) ? 'checked="checked"' : '';

	// SETTINGS FORM
	
	$out .= '
	<div class="wrap">
		<h2>'.__( 'Really simple Facebook and Twitter share buttons', 'menu-test' ).'</h2>
		<form name="form1" method="post" action="">

		<table>

		<tr><td valign="top">'.__("Active share buttons", 'menu-test' ).':</td>
		<td>'
		.' <input type="checkbox" name="really_simple_share_active_facebook_like" '.$active_facebook_like.'> '
		. __("Facebook like", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_facebook" '.$active_facebook.'> '
		. __("Facebook share (deprecated)", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_digg" '.$active_digg.'> '
		. __("Digg", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_buzz" '.$active_buzz.'> '
		. __("Google Buzz", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_linkedin" '.$active_linkedin.'> '
		. __("Linkedin", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_stumbleupon" '.$active_stumbleupon.'> '
		. __("Stumbleupon", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_twitter" '.$active_twitter.'> '
		. __("Twitter", 'menu-test' )
		.'<br /><br /></td></tr>

		<tr><td valign="top">'.__("Show buttons in these pages", 'menu-test' ).':</td>
		<td>'
		.' <input type="checkbox" name="really_simple_share_show_home" '.$show_in_home.'> '
		. __("Home page", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_show_posts" '.$show_in_posts.'> '
		. __("Single Posts", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_show_pages" '.$show_in_pages.'> '
		. __("Pages", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_show_tags" '.$show_in_tags.'> '
		. __("Tags", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_show_categories" '.$show_in_categories.'> '
		. __("Categories", 'menu-test' ).' &nbsp;&nbsp;<br />'
		.' <input type="checkbox" name="really_simple_share_show_dates" '.$show_in_dates.'> '
		. __("Date based archives", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_show_authors" '.$show_in_authors.'> '
		. __("Author archives", 'menu-test' )
		.'<br /><br /></td></tr>

		<tr><td valign="top">'.__("Position", 'menu-test' ).':</td>
		<td><select name="really_simple_share_position">
			<option value="above" '.$sel_above.' > '.__('above the post', 'menu-test' ).'</option>
			<option value="below" '.$sel_below.' > '.__('below the post', 'menu-test' ).'</option>
			</select><br /> 
		<br /></td></tr>

<!--
		<tr><td valign="top">'.__("Active buttons", 'menu-test' ).':</td>
		<td><input type="text" name="really_simple_share_text" value="'.stripslashes($really_simple_share_options[1]).'" size="100"><br />
		<span class="description">'.__("Facebook share test", 'menu-test' ).'</span><br />
		<br /></td></tr>
-->

		</table>
		<hr />
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="'.esc_attr('Save Changes').'" />
		</p>

		</form>
	</div>
	';
	echo $out; 
}


// PRIVATE FUNCTIONS

function really_simple_share_get_default_options ($position='above') {
	$option = array();
	$option['active_buttons'] = array('facebook'=>false, 'twitter'=>true, 'linkedin'=>false, 'buzz'=>false, 'digg'=>false, 'stumbleupon'=>false, 'facebook_like'=>true);
	$option['position'] = $position;
	$option['show_in'] = array('posts'=>true, 'pages'=>true, 'home_page'=>true, 'tags'=>true, 'categories'=>true, 'dates'=>true, 'authors'=>true);
	return $option;
}

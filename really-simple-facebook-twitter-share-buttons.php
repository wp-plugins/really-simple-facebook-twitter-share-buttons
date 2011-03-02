<?php
/*
Plugin Name: Really simple Facebook Twitter share buttons
Plugin URI: http://www.whiletrue.it
Description: Puts Facebook, LinkedIn and Twitter share buttons above or below your posts.
Author: WhileTrue
Version: 1.2.0
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

add_action('admin_menu', 'really_simple_share_menu');

function really_simple_share_menu() {
	add_options_page('Really simple share Options', 'Really simple share', 'manage_options', 'really_simple_share_options', 'really_simple_share_options');
}



function really_simple_share ($content) {

	$option_string = get_option('really_simple_share');

	if ($option_string=='above' or $option_string=='below') {
		// Versions below 1.2.0 compatibility
		$option = array();
		$option['active_buttons'] = array('facebook'=>true, 'twitter'=>true, 'linkedin'=>true);
		$option['position'] = get_option('really_simple_share');
		$option['show_in'] = array('posts'=>true, 'pages'=>true, 'home_page'=>true, 'tags'=>true, 'categories'=>true, 'dates'=>true, 'authors'=>true);

	} else {
		$option = json_decode($option_string, true);
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
	
	$out = '<div style="height:21px; padding-top:2px;">';
	if ($option['active_buttons']['facebook']==true) {
		$out .= '<div style="float:left;"> 
				<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a> 
				<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> 
			</div>';
	}
	if ($option['active_buttons']['linkedin']==true) {
		$out .= '<div style="float:left; padding-left:20px;"> 
	  			<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
				<script type="in/share" data-counter="right"></script>
			</div>';
	}
	if ($option['active_buttons']['twitter']==true) {
		$out .= '<div style="float:left; padding-left:20px;"> 
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a> 
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

		// TODO: convert to ternary operator
		$option['active_buttons'] = array('facebook'=>false, 'twitter'=>false, 'linkedin'=>false);
		if ($_POST['really_simple_share_active_facebook']=='on') { $option['active_buttons']['facebook'] = true; }
		if ($_POST['really_simple_share_active_twitter']=='on') { $option['active_buttons']['twitter'] = true; }
		if ($_POST['really_simple_share_active_linkedin']=='on') { $option['active_buttons']['linkedin'] = true; }

		$option['position'] = esc_html($_POST['really_simple_share_position']);
		
		$option['show_in'] = array('posts'=>false, 'pages'=>false, 'home_page'=>false, 'tags'=>false, 'categories'=>false, 'dates'=>false, 'authors'=>false);
		if ($_POST['really_simple_share_show_posts']=='on') { $option['show_in']['posts'] = true; }
		if ($_POST['really_simple_share_show_pages']=='on') { $option['show_in']['pages'] = true; }
		if ($_POST['really_simple_share_show_home']=='on') { $option['show_in']['home_page'] = true; }
		if ($_POST['really_simple_share_show_tags']=='on') { $option['show_in']['tags'] = true; }
		if ($_POST['really_simple_share_show_categories']=='on') { $option['show_in']['categories'] = true; }
		if ($_POST['really_simple_share_show_dates']=='on') { $option['show_in']['dates'] = true; }
		if ($_POST['really_simple_share_show_authors']=='on') { $option['show_in']['authors'] = true; }
		
		update_option($option_name, json_encode($option));
		// Put an settings updated message on the screen
		$out .= '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
	}
	
	//GET STORED VALUES
	$option = array();
	$option_string = get_option($option_name);
	 
	if ($option_string===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		$option = array();
		$option['active_buttons'] = array('facebook'=>true, 'twitter'=>true, 'linkedin'=>true);
		$option['position'] = 'above';
		$option['show_in'] = array('posts'=>true, 'pages'=>true, 'home_page'=>true, 'tags'=>true, 'categories'=>true, 'dates'=>true, 'authors'=>true);
		
		add_option($option_name, 'above');
		$option_string = get_option($option_name);
	}
	
	if ($option_string=='above' or $option_string=='below') {
		// Versions below 1.2.0 compatibility

		$really_simple_share_options = explode('|||',$option_string);

		$option = array();
		$option['active_buttons'] = array('facebook'=>true, 'twitter'=>true, 'linkedin'=>true);
		$option['position'] = $really_simple_share_options[0];
		$option['show_in'] = array('posts'=>true, 'pages'=>true, 'home_page'=>true, 'tags'=>true, 'categories'=>true, 'dates'=>true, 'authors'=>true);
	} else {
		$option = json_decode($option_string, true);
	}
	
	$sel_above = ($option['position']=='above') ? 'selected="selected"' : '';
	$sel_below = ($option['position']=='below') ? 'selected="selected"' : '';

	$active_facebook = ($option['active_buttons']['facebook']==true) ? 'checked="checked"' : '';
	$active_twitter  = ($option['active_buttons']['twitter'] ==true) ? 'checked="checked"' : '';
	$active_linkedin = ($option['active_buttons']['linkedin']==true) ? 'checked="checked"' : '';

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
		.' <input type="checkbox" name="really_simple_share_active_facebook" '.$active_facebook.'> '
		. __("Facebook", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_twitter" '.$active_twitter.'> '
		. __("Twitter", 'menu-test' ).' &nbsp;&nbsp;'
		.' <input type="checkbox" name="really_simple_share_active_linkedin" '.$active_linkedin.'> '
		. __("Linkedin", 'menu-test' )
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

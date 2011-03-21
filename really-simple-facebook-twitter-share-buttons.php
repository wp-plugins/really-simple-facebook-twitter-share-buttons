<?php
/*
Plugin Name: Really simple Facebook Twitter share buttons
Plugin URI: http://www.whiletrue.it
Description: Puts Facebook, LinkedIn and Twitter share buttons above or below your posts.
Author: WhileTrue
Version: 1.4.1
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


// ACTION AND FILTERS

add_filter('the_content', 'really_simple_share_content');

add_filter('the_excerpt', 'really_simple_share_excerpt');

add_filter('plugin_action_links', 'really_simple_share_add_settings_link', 10, 2 );

add_action('admin_menu', 'really_simple_share_menu');


// PUBLIC FUNCTIONS

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


function really_simple_share_content ($content) {
	return really_simple_share ($content, 'the_content');
}


function really_simple_share_excerpt ($content) {
	return really_simple_share ($content, 'the_excerpt');
}


function really_simple_share ($content, $filter) {
	static $really_simple_share_js_loaded = false;
	static $last_execution = '';

	// IF the_excerpt IS EXECUTED AFTER the_content MUST OVERRIDE ALL the_content CHANGES
	if ($filter=='the_excerpt' and $last_execution=='the_content') {
		remove_filter('the_content', 'really_simple_share_content');
		$content = the_content();
		$really_simple_share_js_loaded = false;
	}

	//GET ARRAY OF STORED VALUES
	$option = really_simple_share_get_options_stored();

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
	} else if (is_search()) {
		if (!$option['show_in']['search']) {
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
		
		if (!$really_simple_share_js_loaded) {
			$out .= '
			<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> 
			';
		}
		
		$out .= '<div style="float:left; width:100px;" class="really_simple_share_facebook"> 
				<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php" share_url="'.$facebook_link.'">Share</a> 
			</div>';
	}
	if ($option['active_buttons']['facebook_like']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		// OPTION facebook_like_text FILTERING
		$option_facebook_like_text = ($option['facebook_like_text']=='recommend') ? 'recommend' : 'like';
		$out .= '<div style="float:left; width:100px; '.$padding.'" class="really_simple_share_facebook_like"> 
				<iframe src="http://www.facebook.com/plugins/like.php?href='.get_permalink().'&amp;layout=button_count&amp;show_faces=false&amp;width=90&amp;action='.$option_facebook_like_text.'&amp;colorscheme=light&amp;height=21" 
					scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90; height:21px;" allowTransparency="true"></iframe>
			</div>';
	}
	if ($option['active_buttons']['linkedin']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		if (!$really_simple_share_js_loaded) {
			$out .= '
			<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
			';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_linkedin"> 
				<script type="in/share" data-counter="right" data-url="'.get_permalink().'"></script>
			</div>';
	}
	if ($option['active_buttons']['buzz']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		if (!$really_simple_share_js_loaded) {
			$out .= '
			<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
			';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_buzz"> 
				<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count" 
					data-url="'.get_permalink().'"></a>
			</div>';
	}
	if ($option['active_buttons']['digg']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		if (!$really_simple_share_js_loaded) {
			$out .= '
			<script type="text/javascript" src="http://widgets.digg.com/buttons.js"></script>
			';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_digg"> 
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
				<script type="text/javascript" src="http://www.stumbleupon.com/hostedbadge.php?s=1&amp;r='.get_permalink().'"></script>
			</div>';
	}	
	if ($option['active_buttons']['twitter']==true) {
		$padding = 'padding-left:10px;';
		if (!$first_shown) {
			$first_shown = true;
			$padding = '';
		}
		if (!$really_simple_share_js_loaded) {
			$out .= '
			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> 
			';
		}
		$out .= '<div style="float:left; '.$padding.'" class="really_simple_share_twitter"> 
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" 
					data-text="'.get_the_title().stripslashes($option['twitter_text']).'" data-url="'.get_permalink().'">Tweet</a> 
			</div>';
	}
	
	$out .= '</div>
	<br style="clear:both;" />';

	// AFTER THE FIRST EXECUTION, ALL NEEDED JS ARE LOADED
	$really_simple_share_js_loaded = true;

	// REMEMBER LAST FILTER EXECUTION TO HANDLE the_excerpt VS the_content	
	$last_execution = $filter;
	
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
		$option['show_in']['home_page']  = ($_POST['really_simple_share_show_home_page'] =='on') ? true : false;
		$option['show_in']['tags']       = ($_POST['really_simple_share_show_tags']      =='on') ? true : false;
		$option['show_in']['categories'] = ($_POST['really_simple_share_show_categories']=='on') ? true : false;
		$option['show_in']['dates']      = ($_POST['really_simple_share_show_dates']     =='on') ? true : false;
		$option['show_in']['authors']    = ($_POST['really_simple_share_show_authors']   =='on') ? true : false;
		$option['show_in']['search']    = ($_POST['really_simple_share_show_search']   =='on') ? true : false;
		
		$option['facebook_like_text'] = ($_POST['really_simple_share_facebook_like_text']=='recommend') ? 'recommend' : 'like';
		$option['twitter_text'] = esc_html($_POST['really_simple_share_twitter_text']);
		
		update_option($option_name, $option);
		// Put a settings updated message on the screen
		$out .= '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
	}
	
	//GET ARRAY OF STORED VALUES
	$option = really_simple_share_get_options_stored();
	
	$sel_above = ($option['position']=='above') ? 'selected="selected"' : '';
	$sel_below = ($option['position']=='below') ? 'selected="selected"' : '';

	$sel_like      = ($option['facebook_like_text']=='like'     ) ? 'selected="selected"' : '';
	$sel_recommend = ($option['facebook_like_text']=='recommend') ? 'selected="selected"' : '';

	$active_buttons = array(
		'facebook_like'=>'Facebook like',
		'facebook'=>'Facebook share (deprecated)',
		'twitter'=>'Twitter',
		'linkedin'=>'Linkedin',
		'buzz'=>'Google Buzz',
		'digg'=>'Digg',
		'stumbleupon'=>'Stumbleupon',
	);	

	$show_in = array(
		'posts'=>'Single posts',
		'pages'=>'Pages',
		'home_page'=>'Home page',
		'tags'=>'Tags',
		'categories'=>'Categories',
		'dates'=>'Date based archives',
		'authors'=>'Author archives',
		'search'=>'Search results',
	);
	
	// SETTINGS FORM

	$out .= '
	<div class="wrap">
	<h2>'.__( 'Really simple Facebook and Twitter share buttons', 'menu-test' ).'</h2>
	<form name="form1" method="post" action="">

	<table>

	<tr><td valign="top" colspan="2"><h3>'.__("General options", 'menu-test' ).'</h3></td></tr>

	<tr><td valign="top">'.__("Active share buttons", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">';

		
	foreach ($active_buttons as $name => $text) {
		$checked = ($option['active_buttons'][$name]) ? 'checked="checked"' : '';
		$out .= '<div style="width:250px; float:left;">
				<input type="checkbox" name="really_simple_share_active_'.$name.'" '.$checked.'> '
				. __($text, 'menu-test' ).' &nbsp;&nbsp;</div>';

	}

	$out .= '</td></tr>

	<tr><td valign="top">'.__("Show buttons in these pages", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">';

	foreach ($show_in as $name => $text) {
		$checked = ($option['show_in'][$name]) ? 'checked="checked"' : '';
		$out .= '<div style="width:250px; float:left;">
				<input type="checkbox" name="really_simple_share_show_'.$name.'" '.$checked.'> '
				. __($text, 'menu-test' ).' &nbsp;&nbsp;</div>';

	}

	$out .= '</td></tr>

	<tr><td valign="top">'.__("Position", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;"><select name="really_simple_share_position">
		<option value="above" '.$sel_above.' > '.__('above the post', 'menu-test' ).'</option>
		<option value="below" '.$sel_below.' > '.__('below the post', 'menu-test' ).'</option>
		</select>
	</td></tr>

	<tr><td valign="top" colspan="2"><h3>'.__("Single buttons options", 'menu-test' ).'</h3></td></tr>

	<tr><td valign="top">'.__("Facebook Like text", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;"><select name="really_simple_share_facebook_like_text">
		<option value="like" '.$sel_like.' > '.__('like', 'menu-test' ).'</option>
		<option value="recommend" '.$sel_recommend.' > '.__('recommend', 'menu-test' ).'</option>
		</select>
	</td></tr>

	<tr><td valign="top">'.__("Twitter additional text", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">
		<input type="text" name="really_simple_share_twitter_text" value="'.stripslashes($option['twitter_text']).'" size="100"><br />
		<span class="description">'.__("Optional text added at the end of every tweet, e.g. ' (via @authorofblogentry)'.<br />
		If you use it, insert an initial space or puntuation mark.", 'menu-test' ).'</span>
	</td></tr>

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

function really_simple_share_get_options_stored () {
	//GET ARRAY OF STORED VALUES
	$option = get_option('really_simple_share');
	 
	if ($option===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		$option = really_simple_share_get_options_default();
		add_option('really_simple_share', $option);
	} else if ($option=='above' or $option=='below') {
		// Versions below 1.2.0 compatibility
		$option = really_simple_share_get_options_default($option);
	} else if(!is_array($option)) {
		// Versions below 1.2.2 compatibility
		$option = json_decode($option, true);
	}
	
	// Versions below 1.4.1 compatibility
	if (!isset($option['facebook_like_text'])) {
		$option['facebook_like_text'] = 'like';
	}
	
	return $option;
}

function really_simple_share_get_options_default ($position='above') {
	$option = array();
	$option['active_buttons'] = array('facebook'=>false, 'twitter'=>true, 'linkedin'=>false, 'buzz'=>false, 'digg'=>false, 'stumbleupon'=>false, 'facebook_like'=>true);
	$option['position'] = $position;
	$option['show_in'] = array('posts'=>true, 'pages'=>true, 'home_page'=>true, 'tags'=>true, 'categories'=>true, 'dates'=>true, 'authors'=>true, 'search'=>true);
	$option['twitter_text'] = '';
	$option['facebook_like_text'] = 'like';
	return $option;
}

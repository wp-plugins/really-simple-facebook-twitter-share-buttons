<?php
/*
Plugin Name: Really simple Facebook Twitter share buttons
Plugin URI: http://www.whiletrue.it
Description: Puts Facebook and Twitter share buttons above or below your posts.
Author: WhileTrue
Version: 1.0.0
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
	add_options_page('Really simple share Options', 'Really simple share', 'manage_options', 'my-unique-identifier', 'really_simple_share_options');
}



function really_simple_share ($content) {
		// WORKS ONLY ON SINGLE POST
		if (!is_single()) {
			return $content;
		}
		$really_simple_share_options = explode('|||',get_option('really_simple_share'));

		$out = '<div style="height:20px; padding-top:2px;">
			<div style="float:left;"> 
				<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a> 
				<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> 
			</div> 
			<div style="float:left; padding-left:20px;"> 
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a> 
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> 
			</div>
		</div>
		<br style="clear:both;" />';
	
		
	if ($really_simple_share_options[0]=='below') {
		return $content.$out;
	} else {
		return $out.$content;
	}
}


function really_simple_share_options () {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // See if the user has posted us some information
    if( isset($_POST['really_simple_share_position'])) {

        update_option( 'really_simple_share', esc_html($_POST['really_simple_share_position']));

		// Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}
	
	//GET STORED VALUES
	$option_string = get_option('really_simple_share');

	if ($option_string===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		add_option('really_simple_share', 'above');
		$option_string = get_option('really_simple_share');
	}

	$really_simple_share_options = explode('|||',$option_string);

	$sel_above = ($really_simple_share_options[0]=='above') ? 'selected="selected"' : '';
	$sel_below = ($really_simple_share_options[0]=='below') ? 'selected="selected"' : '';

    // SETTINGS FORM
    ?>
	
<div class="wrap">
	<h2><?php echo __( 'Really simple Facebook and Twitter share buttons', 'menu-test' ); ?></h2>
	<form name="form1" method="post" action="">

	<table>
<!--
	<tr><td valign="top"><?php _e("Text", 'menu-test' ); ?>:</td>
	<td><input type="text" name="reading_time_text" value="<?php echo stripslashes($reading_time_options[0]); ?>" size="100"><br />
	<span class="description"><?php _e("Facebook share test", 'menu-test' ); ?></span><br />
	<br /></td></tr>
-->	

	<tr><td valign="top"><?php _e("Position", 'menu-test' ); ?>:</td>
	<td><select name="really_simple_share_position">
		<option value="above" <?php echo $sel_above; ?> > <?php _e('above the post', 'menu-test' ); ?></option>
		<option value="below" <?php echo $sel_below; ?> > <?php _e('below the post', 'menu-test' ); ?></option>
		</select><br /> 
	<br /></td></tr>

	</table>
	<hr />
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>

	</form>
</div>

<?php
 
}

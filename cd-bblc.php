<?php
/*
Plugin Name: CD BuddyBar Logo Changer
Plugin URI: http://cosydale.com/my-plugin-cd-buddybar-logo-changer.html
Description: Изменение в BuddyBar текста/логотипа на свой собственный из админки.
Author: slaFFik
Author URI: http://cosydale.com
Version: 2.2
*/
register_activation_hook( __FILE__, 'cd_bblc_activation' );
function cd_bblc_activation() {
	global $bp;
	$cd_bblc[ 'enabled' ] = 0;
	$cd_bblc[ 'type' ] = 'text';
	$cd_bblc[ 'image' ] = 'http://demo.cosydale.com/images/buddy.png';
	$cd_bblc[ 'text' ] = __( 'NetWork', 'cd_bblc' );
	$cd_bblc[ 'link' ] = $bp->root_domain;
	$cd_bblc[ 'js_usage' ] = 'off';
	add_option( 'cd_bblc', $cd_bblc, '', 'yes' );
	$blogs_ids = get_blog_list( 0, 'all' );
	foreach ($blogs_ids as $blog) {
		add_blog_option( $blog['blog_id'], 'cd_bblc', $cd_bblc );
	}
}

function cd_bblc_load_textdomain() {
	$locale = apply_filters( 'buddypress_locale', get_locale() );
	$mofile = dirname( __File__ )   . "/langs/cd_bblc-$locale.mo";

	if ( file_exists( $mofile ) )
		load_textdomain( 'cd_bblc', $mofile );
}
add_action ( 'plugins_loaded', 'cd_bblc_load_textdomain', 7 );

function cd_bblc_check_menu() {
	if ( !is_site_admin() )
		return false;
	add_submenu_page( 'bp-general-settings', __( 'Logo Changer', 'cd_bblc' ), __( 'Logo Changer', 'cd_bblc' ), 'manage-options', 'cd_bblc', 'cd_bblc_admin' );	
}
add_action( 'admin_menu', 'cd_bblc_check_menu' );

function cd_bblc_admin() {
	$cd_bblc = get_option( 'cd_bblc' );
	$hidden_field_name = 'hidden_field_name';
	
	if ( $_POST[ $hidden_field_name ] == 'Y' ) {
		// save all inputed data
		$cd_bblc[ 'enabled' ] = 0;
		if ( $_POST[ 'cd_bblc_enabled' ] == 1 ) 
			$cd_bblc[ 'enabled' ] = 1;
		
		$cd_bblc[ 'type' ] = 'text';
		if ( $_POST[ 'cd_bblc_type' ] == 'image' )
			$cd_bblc[ 'type' ] = 'image';

		if ( $_POST[ 'cd_bblc_text' ] != null ) {
			$cd_bblc[ 'text' ] = $_POST[ 'cd_bblc_text' ];
		}else{
			$cd_bblc[ 'text' ] = '';
		}
		
		if ( $_POST[ 'cd_bblc_link' ] )
			$cd_bblc[ 'link' ] = stripslashes( $_POST[ 'cd_bblc_link' ] );

		if ( $_POST[ 'cd_bblc_image' ] != null ) {
			$cd_bblc[ 'image' ] = stripslashes( $_POST[ 'cd_bblc_image' ] );
		}else{
			$cd_bblc[ 'image' ] = '';
		}
		
		$cd_bblc[ 'js_usage' ] = 0;
		if ( $_POST[ 'cd_bblc_js_usage' ] == 1 )
			$cd_bblc[ 'js_usage' ] = 1;

		if ( $_POST[ 'cd_bblc_onmouseover' ] != null ) {
			$cd_bblc[ 'onmouseover' ] = stripslashes( $_POST[ 'cd_bblc_onmouseover' ] );
		}else{
			$cd_bblc[ 'onmouseover' ] = '';
		}
		if ( $_POST[ 'cd_bblc_onclick' ] != null ) {
			$cd_bblc[ 'onclick' ] = stripslashes( $_POST[ 'cd_bblc_onclick' ] );
		}else{
			$cd_bblc[ 'onclick' ] = '';
		}
		if ( $_POST[ 'cd_bblc_onmouseout' ] != null ) {
			$cd_bblc[ 'onmouseout' ] = stripslashes( $_POST[ 'cd_bblc_onmouseout' ] );
		}else{
			$cd_bblc[ 'onmouseout' ] = '';
		}
		
		$blogs_ids = get_blog_list( 0, 'all' );
		foreach ($blogs_ids as $blog) {
			update_blog_option( $blog['blog_id'], 'cd_bblc', $cd_bblc );
		}
		update_option( 'cd_bblc', $cd_bblc );
		
		echo "<div id='message' class='updated fade'><p>" . __( 'Options updated.', 'cd_bblc' ) . "</p></div>";
	}
?>
<div class="wrap">
	<h2><?php _e('CD BuddyBar Logo Changer', 'cd_bblc' ) ?></h2>

<?php #print_r ($cd_bblc) ?>
	<p><?php _e( 'Choose here, what do you want to show instead of the default sitename in BuddyBar - logo or your own text (i.e.: NetWork).<br>You can control the link (in case you want to redirect users not to the main page) and even add some javascript code!<br><strong>NOTE:</strong> To disable some feature (displaying text or image, using js code) - just leave the appropriate field blank.<br>P.S. For js-coders - link is having id="admin-bar-logo".', 'cd_bblc' ) ?></p>
	<?php #print_r( $cd_bblc ); ?>
	<form action="<?php echo site_url() . '/wp-admin/admin.php?page=cd_bblc' ?>" name="cd_bblc_form" id="cd_bblc_form" method="post">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />

		<h3><?php _e( "Let's make some changes:", 'cd_bblc' ) ?></h3>
		<table class="form-table">
			<tr valign="top"><?php // CD_BBLC_ENABLED ?>
				<th scope="row"><label for="cd_bblc_enabled"><?php _e( 'Logo changer enabled', 'cd_bblc' ) ?></label></th>
				<td>
					<input name="cd_bblc_enabled" type="checkbox" id="cd_bblc_enabled" value="1"<?php echo( '1' == $cd_bblc[ 'enabled' ] ? ' checked="checked"' : '' ); ?> />
					<?php _e( 'Turn on the BuddyBar Logo changer.', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_LINK ?>
				<th scope="row"><label for="cd_bblc_link"><?php _e( 'Logo Link', 'cd_bblc' ) ?></label></th>
				<td>
					<input name="cd_bblc_link" type="text" id="cd_bblc_link" style="width: 75%" value="<?php echo attribute_escape( $cd_bblc[ 'link' ] ); ?>" size="45" />
					<br />
					<?php _e( 'Type here that url you want to use instead of the default main page link. <br>Do not leave this field blank - url MUST be typed.', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"><?php // CD_BBLC_TYPE ?>
				<th scope="row"><label for="cd_bblc_type"><?php _e( 'Logo Type', 'cd_bblc' ) ?></label></th>
				<td>
					<select name="cd_bblc_type" id="cd_bblc_type" style="height: auto;">
						<option value="text"<?php echo( ( 'text' == $cd_bblc[ 'type' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'Text', 'cd_bblc' ); ?></option>
						<option value="image"<?php echo( ( 'image' == $cd_bblc[ 'type' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'Image', 'cd_bblc' ); ?></option>
					</select><br />
					<?php _e( 'What would you like to use - text or image - in BuddyBar as your logo?', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_TEXT ?>
				<th scope="row"><label for="cd_bblc_text"><?php _e( 'Logo Text', 'cd_bblc' ) ?></label></th>
				<td>
					<input name="cd_bblc_text" type="text" id="cd_bblc_text" style="width: 75%" value="<?php echo attribute_escape( $cd_bblc[ 'text' ] ); ?>" size="45" />
					<br />
					<?php _e( 'Type here that text you want to display instead of the default SiteName: ', 'cd_bblc' ); 
					echo get_blog_option( BP_ROOT_BLOG, 'blogname');
					?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_IMAGE ?>
				<th scope="row"><label for="cd_bblc_image"><?php _e( 'Logo Image path', 'cd_bblc' ) ?></label></th>
				<td>
					<input name="cd_bblc_image" type="text" id="cd_bblc_image" style="width: 75%" value="<?php echo stripslashes( $cd_bblc[ 'image' ] ); ?>" size="45" />
					<br />
					<?php _e( 'Full web path to your image (no more than 20px in height), i.e.: http://example.com/images/buddylogo.png<br>Use white text (if any exists) color and transparent background on your image.', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_JS_USAGE ?>
				<th scope="row"><label for="cd_bblc_js_usage"><?php _e( 'JS usage', 'cd_bblc' ) ?></label></th>
				<td>
					<select name="cd_bblc_js_usage" id="cd_bblc_js_usage" style="height: auto;">
						<option value="1"<?php echo( ( 1 == $cd_bblc[ 'js_usage' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'ON - I will use js', 'cd_bblc' ); ?></option>
						<option value="0"<?php echo( ( 0 == $cd_bblc[ 'js_usage' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'OFF - I will NOT use js', 'cd_bblc' ); ?></option>
					</select><br />
					<?php _e( 'It\'s OFF by default. Don\'t use it, if you don\'t know what to do with all fields below.', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_ONMOUSEOVER ?>
				<th scope="row"><label for="cd_bblc_onmouseover"><?php _e( 'onMouseOver JS effect', 'cd_bblc' ) ?></label></th>
				<td>
					<textarea name="cd_bblc_onmouseover" type="text" id="cd_bblc_onmouseover" rows="3" style="width: 75%"><?php echo $cd_bblc[ 'onmouseover' ]; ?></textarea>
					<br />
					<?php _e( 'This code will be added to the link, i.e.: this.style.fontWeight=\'bold\';', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_ONMOUSEOUT ?>
				<th scope="row"><label for="cd_bblc_onmouseout"><?php _e( 'onMouseOut JS effect', 'cd_bblc' ) ?></label></th>
				<td>
					<textarea name="cd_bblc_onmouseout" type="text" id="cd_bblc_onmouseout" rows="3" style="width: 75%"><?php echo $cd_bblc[ 'onmouseout' ]; ?></textarea>
					<br />
					<?php _e( 'This code will be added to the link, i.e.: this.style.fontWeight=\'normal\';', 'cd_bblc' ); ?>
				</td>
			</tr>
			<tr valign="top"> <?php // CD_BBLC_ONCLICK ?>
				<th scope="row"><label for="cd_bblc_onclick"><?php _e( 'onClick JS effect', 'cd_bblc' ) ?></label></th>
				<td>
					<textarea name="cd_bblc_onclick" type="text" id="cd_bblc_onclick" rows="3" style="width: 75%"><?php echo $cd_bblc[ 'onclick' ]; ?></textarea>
					<br />
					<?php _e( 'This code will be added to the link, i.e.: alert(\'Go to the main page!\');return true', 'cd_bblc' ); ?>
				</td>
			</tr>
		</table>

	<p class="submit"><input type="submit" name="submit" value="<?php _e( 'Save Settings', 'cd_bblc' ) ?>"/></p>
	</form>
</div>
<?php
}

add_action( 'wpmu_new_blog', 'cd_bblc_new_blogs_options', 10, 2 );
function cd_bblc_new_blogs_options( $blog_id, $user_id ) {
	$cd_bblc = get_option( 'cd_bblc' );
	add_blog_option( $blog_id, 'cd_bblc', $cd_bblc );
}

function cd_bblc_buddybar_logo () {
	global $bp;
	$data = get_option( 'cd_bblc' );
	if ( $data[ 'enabled' ] == 1 ) {
		
		if ( $data[ 'js_usage' ] == 1 && $data[ 'onmouseover' ] != null ) {
			$js_onmouseover = 'onMouseOver="'. $data[ 'onmouseover' ] . '"';
		}
		if ( $data[ 'js_usage' ] == 1 && $data[ 'onmouseout' ] != null ) {
			$js_onmouseout = 'onMouseOut="'. $data[ 'onmouseout' ] . '"';
		}
		if ( $data[ 'js_usage' ] == 1 && $data[ 'onclick' ] != null ) {
			$js_onclick = 'onClick="'. $data[ 'onclick' ] . '"';
		}
		if ( $data[ 'link' ] ) {
			$link = $data[ 'link' ];
		}else{
			$link = $bp->root_domain;
		}
			
		$link = '<a href="' . $link . '" '. $js_onmouseover .' '. $js_onmouseout .' '. $js_onclick .' id="admin-bar-logo">';
		if ( $data[ 'type' ] == 'text' ) {
			$link .= $data[ 'text' ];
		}elseif ( $data[ 'type' ] == 'image' ) {
			$link .= '<img src="'. stripslashes( $data[ 'image' ] ) .'" alt="'.get_blog_option( BP_ROOT_BLOG, 'blogname') .'" />';
		}
		$link .= '</a>';
		echo $link;
	}else{
		echo '<a href="' . $bp->root_domain . '" id="admin-bar-logo">' . get_blog_option( BP_ROOT_BLOG, 'blogname') . '</a>';
	}
}
remove_action('bp_adminbar_logo','bp_adminbar_logo');
add_action('bp_adminbar_logo','cd_bblc_buddybar_logo');

register_deactivation_hook( __FILE__, 'cd_bblc_deactivation' );
function cd_bblc_deactivation() {
	$blogs_ids = get_blog_list( 0, 'all' );
	foreach ($blogs_ids as $blog) {
		delete_blog_option( $blog['blog_id'], 'cd_bblc', $cd_bblc );
	}
}

?>
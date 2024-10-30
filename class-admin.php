<?php

add_action('admin_init', 'rocker_kjcookie_init' );
function rocker_kjcookie_init(){
	register_setting( 'rocker_kjcookie_options', 'rocker_kjcookie' );
}
 	
add_action('admin_menu', 'show_rocker_kjcookie_options');
function show_rocker_kjcookie_options() {
	add_options_page('kjrocker Cookie Consent', 'kjrocker Cookie Consent', 'manage_options', 'rocker_kjcookie', 'rocker_kjcookie_options');
}

add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {
    $screen = get_current_screen();

    if ( $screen->id == 'settings_page_rocker_kjcookie') {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'elc-color-picker', plugins_url('js/settings.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
} );

// ADMIN PAGE
function rocker_kjcookie_options() {
?>
	<div class="wrap">
        
		<h1>KJRocker Cookie Concent
           
        </h1>

        

		<form method="post" action="options.php">
			<?php settings_fields('rocker_kjcookie_options'); ?>
			<?php
                kj_check_defaults();
                $options = get_option('rocker_kjcookie');

                $length = sanitize_text_field($options['length']);
                $position = sanitize_text_field($options['position']);
                $kjcookieenabled = sanitize_text_field($options['kj-cookieenabled']);
                $kjcookiedisabled = sanitize_text_field($options['kj-cookiedisabled']);

            ?>

			<table class="form-table">
				<tr valign="top">
                    <th scope="row"><label for="enabled"><?php esc_html_e('Activate'); ?></label></th>
					<td>
                        <input id="enabled" name="rocker_kjcookie[enabled]" type="checkbox" value="1" <?php checked('1', $options['enabled']); ?> />
                    </td>
				</tr>

				<tr valign="top"><th scope="row"><label for="lengthnum">
                    <?php esc_html_e('Cookie acceptance length', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="lengthnum" type="text" name="rocker_kjcookie[lengthnum]" value="<?php echo absint( $options['lengthnum'] ); ?>" size="5" />
						<select name="rocker_kjcookie[length]">
							  <option value="days"<?php if ($length == 'days') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('days', 'kjrocker-cookie-consent'); ?></option>
							  <option value="weeks"<?php if ($length == 'weeks') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('weeks', 'kjrocker-cookie-consent'); ?></option>
							  <option value="months"<?php if ($length == 'months') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('months', 'kjrocker-cookie-consent'); ?></option>
						</select><br>
<small><?php esc_html_e('Once the user clicks accept the bar will disappear. You can set how long this will apply for before the bar reappears to the user.', 'kjrocker-cookie-consent'); ?> <?php esc_html_e('Set "0" for SESSION cookie.', 'kjrocker-cookie-consent'); ?></small>
					</td>
				</tr>
               
                <tr valign="top"><th scope="row"><label for="networkshare"><?php esc_html_e('Share Cookie across Network', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="networkshare" name="rocker_kjcookie[networkshare]" type="checkbox" value="1" <?php checked('1', $options['networkshare']); ?> /><br>
<small><?php esc_html_e('Click here if you want to share cookie across your network (subdomains or multisite)', 'kjrocker-cookie-consent'); ?></small></td>
				</tr>
                <tr valign="top"><th scope="row"><label for="networkshareurl">
                    <?php esc_html_e('Network Domain', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="networkshareurl" type="text" name="rocker_kjcookie[networkshareurl]" value="<?php echo esc_attr( $options['networkshareurl'] ); ?>" size="40" /></td>
				</tr>
			</table>
        <hr>
			<h3 class="title"><?php esc_html_e('Appearance'); ?></h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="position"><?php esc_html_e('Position', 'kjrocker-cookie-consent'); ?></label></th>
					<td>
						<select name="rocker_kjcookie[position]">
							  <option value="bottomright"<?php if ($position == 'bottomright') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Bottom Right', 'kjrocker-cookie-consent'); ?></option>
							  <option value="topright"<?php if ($position == 'topright') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Top Right', 'kjrocker-cookie-consent'); ?></option>
                              <option value="topcenter"<?php if ($position == 'topcenter') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Top Center', 'kjrocker-cookie-consent'); ?></option>
							  <option value="bottomleft"<?php if ($position == 'bottomleft') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Bottom Left', 'kjrocker-cookie-consent'); ?></option>
							  <option value="topleft"<?php if ($position == 'topleft') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Top Left', 'kjrocker-cookie-consent'); ?></option>
                              <option value="bottomcenter"<?php if ($position == 'bottomcenter') { echo ' selected="selected"'; } ?>>
                                  <?php esc_html_e('Bottom Center', 'kjrocker-cookie-consent'); ?></option>
						</select>
					</td>
				</tr>
                <tr valign="top"><th scope="row"><label for="backgroundcolor">
                    <?php esc_html_e('Background Color', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="backgroundcolor" type="text" name="rocker_kjcookie[backgroundcolor]" value="<?php echo $options['backgroundcolor']; ?>" class="color-field" data-default-color="#000000"/></td>
				</tr>
                <tr valign="top"><th scope="row"><label for="fontcolor">
                    <?php esc_html_e('Font Text Color', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="fontcolor" type="text" name="rocker_kjcookie[fontcolor]" value="<?php echo $options['fontcolor']; ?>"  class="color-field" data-default-color="#ffffff"/></td>
				</tr>
			</table>
        <hr>
			<h3 class="title"><?php esc_html_e('Content'); ?></h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="barmsg">
                    <?php esc_html_e('Cookie Consent Message', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input class="i18n-multilingual-display" id="barmsg" type="text" name="rocker_kjcookie[barmsg]" value="<?php echo esc_attr( $options['barmsg'] ); ?>" size="100" /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="blink">
                    <?php esc_html_e('More Info Text', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="blink" type="text" name="rocker_kjcookie[blink]" value="<?php echo esc_attr( $options['blink'] ); ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="bbtn">
                    <?php esc_html_e('Button Text', 'kjrocker-cookie-consent'); ?></label></th>
					<td><input id="bbtn" type="text" name="rocker_kjcookie[bbtn]" value="<?php echo esc_attr( $options['bbtn'] ); ?>" /></td>
				</tr>
                <tr valign="top"><th scope="row"><label for="blinkid">
                    <?php esc_html_e('More Info Link', 'kjrocker-cookie-consent'); ?><br/><small>
                    <?php esc_html_e('Use this field to link cookie policy page.', 'kjrocker-cookie-consent'); ?></small></label></th>
                    <td>
                    <?php
                    $args = array(
                        'depth'                 => 0,
                        'child_of'              => 0,
                        'selected'              => $options['blinkid'],
                        'echo'                  => 0,
                        'name'                  => 'rocker_kjcookie[blinkid]',
                        'id'                    => 'blinkid', 
                        // 'show_option_none'      => '* '.__('Custom Message'), 
                        'show_option_no_change' => null, 
                        'option_none_value'     => null, 
                    ); ?>

                    <?php
                    $lol = wp_dropdown_pages($args);
                    $add = null;
                    if ( $options['blinkid'] == 'C' ) { $add = ' selected="selected" '; }
                    $end = '<option class="level-0" value="C"'.$add.'>* '.__('Custom URL').'</option></select>';
                    $lol = preg_replace('#</select>$#', $end, trim($lol)); 
                    echo $lol; ?>
                        <br><br><input id="boxlinkblank" name="rocker_kjcookie[boxlinkblank]" type="checkbox" value="1" <?php checked('1', $options['boxlinkblank']); ?> /><label for="boxlinkblank"><small>Add target="_blank"</small></label>
                    </td>
                    
				</tr>
                <tr valign="top"><th scope="row"><label for="customurl">
                    <?php esc_html_e('Custom URL'); ?></label></th>
					<td><input id="customurl" type="text" name="rocker_kjcookie[customurl]" value="<?php echo esc_attr( $options['customurl'] ); ?>" />
                        <small> <?php esc_html_e('Enter the destination URL'); ?></small></td>
				</tr>
                
                <tr>
            </table>
                <hr>
                <h3 class="title">Shortcode [cookie-control]</h3>
			<table class="form-table" id="cookie_random_info">
                </tr>
                    <tr valign="top"><th scope="row"><label for="kj-cookieenabled">
                    <?php esc_html_e('Cookie enabled message', 'kjrocker-cookie-consent'); ?><br>
                    <small><?php esc_html_e('Message when cookies have been accepted', 'kjrocker-cookie-consent'); ?></small></label></th>
					<td>
<textarea style='font-size: 90%; width:95%;' name='rocker_kjcookie[kj-cookieenabled]' id='kj-cookieenabled' rows='9' ><?php echo esc_textarea( $kjcookieenabled ); ?></textarea><br>
                    
                    <label style="font-size:0.9em;font-weight:bold;" for="kj-disablecookie"><?php esc_html_e('"Disable Cookie" Text', 'kjrocker-cookie-consent'); ?></label>
                    <input id="kj-disablecookie" type="text" name="rocker_kjcookie[kj-disablecookie]" value="<?php echo $options['kj-disablecookie']; ?>" />
					</td>
				</tr>
                <tr valign="top"><th scope="row"><label for="kj-cookiedisabled">
                    <?php esc_html_e('Cookie disabled message', 'kjrocker-cookie-consent'); ?><br>
                    <small><?php esc_html_e('Message when cookies haven\'t been accepted', 'kjrocker-cookie-consent'); ?></small></label></th>
					<td>
<textarea style='font-size: 90%; width:95%;' name='rocker_kjcookie[kj-cookiedisabled]' id='kj-cookiedisabled' rows='9' ><?php echo esc_textarea( $kjcookiedisabled ); ?></textarea>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
		</form>
	</div>
<style>
    #cookie_random_info{
    display: none !important;
}
</style>
<?php
}
?>
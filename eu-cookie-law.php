<?php
/*
Plugin Name:  kjrocker Cookie Consent
Plugin URI:   https://www.kjrocker.com/kj-rocker-cookie-consent/
Description:  kjrocker Cookie Consent informs users that your site uses cookies.
Version:      1.1.4  
Author:       kjrocker.com
Author URI:   https://www.kjrocker.com/
*/

function kjcookie_start() {
    
    load_plugin_textdomain( 'kjrocker-cookie-consent' );
    
    if ( is_admin() ) {
        require 'class-admin.php';
    } else {
        require 'class-frontend.php';
    }
    
} add_action('init', 'kjcookie_start');

function kj_action_admin_init() {
    $arraya_kj_v = get_plugin_data ( __FILE__ );
    $new_version = sanitize_text_field($arraya_kj_v['Version']);
        
    if ( version_compare($new_version,  get_option('kj_version_number') ) == 1 ) {
        kj_check_defaults();
        update_option( 'kj_version_number', $new_version );   
    }

    if ( kjcookie_option('tinymcebutton') ) {
        require 'inc/tinymce.php';
    }
    $eda = __('05Media Cookies Concent informs users that your site uses cookies.', 'kjrocker-cookie-consent');
} add_action('admin_init', 'kj_action_admin_init');

function kj_check_defaults() { require 'defaults.php'; }

function kjcookie_option($name) {
    $options = get_option('rocker_kjcookie');
    $options_name = sanitize_text_field($options[$name]);
    if ( isset( $options_name ) ) {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $options_name);
    }
    return false;
}

?>
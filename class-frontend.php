<?php    

    $kjcookieSet = 0;

add_action('wp_head', function() {
    
    global $kjcookieSet;
    global $deleteCookieUrlCheck;    

    if ( kj_isSearchEngine() ) {
        $kjcookieSet = 1;
    }
    
	wp_register_style	('basecss', plugins_url('css/style.css', __FILE__), false);
	wp_enqueue_style	('basecss');
    
    $kjData = array(
        'kjcookieSet' => ( $kjcookieSet || kj_cookie_accepted() ),
        'autoBlock' =>  kjcookie_option('autoblock'),
        'expireTimer' => kjcookie_get_expire_timer(),
        'scrollConsent' => kjcookie_option('scrollconsent'),
        'networkShareURL' => kj_get_cookie_domain(),
        'isCookiePage' => kjcookie_option('blinkid') == get_the_ID(),
        'isRefererWebsite' => kjcookie_option('navigationconsent') && wp_get_referer() && ( kjcookie_option('blinkid') != get_the_ID() )
    );
    
    wp_enqueue_script(
        'kjcookielaw-scripts',
        plugins_url('js/scripts.js', __FILE__),
        array( 'jquery' ),
        get_option('kj_version_number'),
        true
    );
    wp_localize_script('kjcookielaw-scripts','kjcookielaw_data',$kjData);
    
});

function kj_isSearchEngine(){
    $engines  = array(
        'google',
		'googlebot',
        'yahoo',
        'facebook',
        'twitter',
		'slurp',
		'search.msn.com',
		'nutch',
		'simpy',
		'bot',
		'aspseek',
		'crawler',
		'msnbot',
		'libwww-perl',
		'fast',
		'baidu',
	);
                
	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
        return false;
    }
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach ( $engines as $engine ) {
        if (stripos($ua, $engine) !== false) {
            return true;
		}
		return false;
	}
}

function kj_get_cookie_domain() {
    
    if ( kjcookie_option('networkshare') ) {
        return 'domain='.kjcookie_option('networkshareurl').'; ';
    }
    return '';
}

function kj_cookie_accepted() {
    global $kjcookieSet;
    
    if ( ! kjcookie_option('enabled') ) { return true; }
    
    if ( isset( $_COOKIE['kjcookie'] )  || $kjcookieSet ) {
        return true;
    } else {
        return false;
    }
}

function kjcookie_get_expire_timer() {
    
    switch( kjcookie_option('length') ){
        case "hours":
            $multi = 1;
            break;
        case "days":
            $multi = 1;
            break;
        case "weeks":
            $multi = 7;
            break;
        case "months":
            $multi = 30;
            break;
    }
    return $multi *  kjcookie_option('lengthnum');
}
    
add_action('wp_footer', function() {
    
	if ( kj_cookie_accepted()  ) { return; }
    
    $target = '';
    if ( kjcookie_option('blinkid') == 'C') {
        $link =  kjcookie_option('customurl');
        if ( kjcookie_option('boxlinkblank') ) { $target = 'target="_blank" '; }
    } else if ( kjcookie_option('blinkid') ) {
        $link = get_permalink( apply_filters( 'wpml_object_id', kjcookie_option('blinkid'), 'page' ) );
        if ( kjcookie_option('boxlinkblank') ) { $target = 'target="_blank" '; }
    } else {
        $link = '#';
    }

    $return = '<!-- KJ Cookie Law '.get_option( 'kj_version_number' ).' -->';
    $return .= '<div class="pea_cook_wrapper pea_cook_'.kjcookie_option('position').'" style="color:'.kj_frontstyle('fontcolor').';background:rgb('.kj_frontstyle('backgroundcolor').');background: rgba('.kj_frontstyle('backgroundcolor').',0.85);">';
    $return .= '<p>'.esc_html(kjcookie_option('barmsg')).' <a style="color:'.esc_attr(kjcookie_option('fontcolor')).';" href="'.$link.'" '.$target.'id="fom">'.kjcookie_option('blink').'</a> <button id="pea_cook_btn" class="pea_cook_btn">'.kjcookie_option('bbtn').'</button></p>';
    $return .= '</div>';
    echo apply_filters( 'kj_cookie_law_frontend_banner', $return );

    $return = '<div class="pea_cook_more_info_popover"><div class="pea_cook_more_info_popover_inner" style="color:'.kj_frontstyle('fontcolor').';background-color: rgba('.kj_frontstyle('backgroundcolor').',0.9);">';
    $return .= '<p>'.kjcookie_option('box_content').'</p><p><a style="color:'.esc_html(kjcookie_option('fontcolor')).';" href="#" id="pea_close">'.kjcookie_option('close_link').'</a></p>';
    $return .= '</div></div>';
    echo apply_filters( 'kj_cookie_law_frontend_popup', $return );
}, 1000);

function generate_kj_cookie_notice_text($height, $width, $text) {
    return '<div class="kjcookie" style="color:'.kj_frontstyle('fontcolor').'; background: rgba('.kj_frontstyle('backgroundcolor').',0.85) url(\''.plugins_url('img/block.png', __FILE__).'\') no-repeat; background-position: -30px -20px; width:'.$width.';height:'.$height.';"><span>'.$text.'</span></div><div class="clear"></div>';    
}

function generate_kj_cookie_notice($height, $width) {
    return generate_kj_cookie_notice_text($height, $width, kjcookie_option('box_htmlcontent') );
}

add_shortcode( 'cookie', function ( $atts, $content = null ) {
    extract(shortcode_atts(
        array(
            'height' => '',
            'width' => '',
            'text' => kjcookie_option('box_htmlcontent')
        ),
        $atts)
    );
    if ( kj_cookie_accepted() ) {
        return do_shortcode( $content );
    } else {
        if (!$width) { $width = kj_pulsci($content,'width='); }
        if (!$height) { $height = kj_pulsci($content,'height='); }
        return generate_kj_cookie_notice($height, $width);
    }
} );



function kj_buffer_start() { ob_start(); }
function kj_buffer_end() {
    $contents = kj_erase(ob_get_contents());
    ob_end_clean();
    echo $contents;
}

add_action('wp_head', 'kj_buffer_start'); 
add_action('wp_footer', 'kj_buffer_end'); 

function kj_erase($content) {
    if ( !kj_cookie_accepted() && kjcookie_option('autoblock') &&
        !(get_post_field( 'kjcookielaw_exclude', get_the_id() ) )
       ) {
        
        $content = preg_replace('#<iframe.*?\/iframe>|<object.*?\/object>|<embed.*?>#is', generate_kj_cookie_notice('auto', '100%'), $content);
        if ( !kjcookie_option('exclude_script') ) {
            $content = preg_replace('#<script.(?:(?!kjcookielaw_exclude).)*?\/script>#is', '', $content);
        }
        $content = preg_replace('#<!cookie_start.*?\!cookie_end>#is', generate_kj_cookie_notice('auto', '100%'), $content);
        $content = preg_replace('#<div id=\"disqus_thread\".*?\/div>#is', generate_kj_cookie_notice('auto', '100%'), $content);
    }
    return $content;
}

//Compatibility for Jetpack InfiniteScroll
add_filter( 'infinite_scroll_js_settings', 'kj_infinite_scroll_js_settings' );
function kj_infinite_scroll_js_settings($js_settings) {
    return array_merge ( $js_settings, array( 'kjcookielaw_exclude' => 1) );
}

add_filter( 'widget_text', 'do_shortcode');

function kj_pulsci($content,$ricerca){
	$caratteri = strlen($ricerca)+6;
	$stringa = substr($content, strpos($content, $ricerca), $caratteri);
	$stringa = str_replace($ricerca, '', $stringa);
	$stringa = trim(str_replace('"', '', $stringa));
	return $stringa;
}

function kj_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   return array($r, $g, $b);
}

function kj_frontstyle($name) {
    switch ($name) {
    case 'fontcolor':
        return  kjcookie_option('fontcolor');
        break;
    case 'backgroundcolor':
        $backgroundcolors = kj_hex2rgb( kjcookie_option('backgroundcolor') );
        return $backgroundcolors[0].','.$backgroundcolors[1].','.$backgroundcolors[2];
        break;
    }
}

add_shortcode( 'cookie-control', function ( $atts ) {
    if ( !kjcookie_option('enabled') ) { return; }
    if ( kj_cookie_accepted() ) {
        return '
            <div class="pea_cook_control" style="color:'.kj_frontstyle('fontcolor').'; background-color: rgba('.kj_frontstyle('backgroundcolor').',0.9);">
                <b>'.kjcookie_option('kj-cookieenabled').'</b><br>
                <button id="kj_revoke_cookies" class="kj_control_btn" style="color:rgba('.kj_frontstyle('backgroundcolor').'); background-color: '.kj_frontstyle('fontcolor').';">'.kjcookie_option('kj-disablecookie').'</button>
            </div>';
    } else {
        return '
            <div class="pea_cook_control" style="color:'.kj_frontstyle('fontcolor').'; background-color: rgba('.kj_frontstyle('backgroundcolor').',0.9);">
                '.str_replace( '%s', kjcookie_option('bbtn'), kjcookie_option('kj-cookiedisabled') ).'
            </div>';            
    }
} );

function kj_cookie_list_shortcode( $atts ) {
   
    echo '<h3>Active Cookies</h3>
    <table style="width:100%; word-break:break-all;">
        <tr>
            <th>'.__('Name', 'kjrocker-cookie-consent').'</th>
            <th>'.__('Value', 'kjrocker-cookie-consent').'</th> 
        </tr>';
    foreach ($_COOKIE as $key=>$kj_cookie) {

        echo '<tr>';
        echo '<td>'.esc_html($key).'</td>';
        echo '<td>'.esc_html($kj_cookie).'</td>';
        echo '</tr>';
    }
    echo '</table>';
    
}
add_shortcode( 'cookie-list', 'kj_cookie_list_shortcode' );
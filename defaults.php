<?php

    $defaults = array (
        array('enabled', '0'),
        //array('lengthnum', '1'),
        array('length', 'months'),
        array('position', 'bottomright'),
        array('barmsg', __('By continuing to use the site, you agree to the use of cookies.', 'kjrocker-cookie-consent')),
        array('blink', __('more information', 'kjrocker-cookie-consent')),
        array('bbtn', __('Accept', 'kjrocker-cookie-consent')),
        array('close_link', __('Close', 'kjrocker-cookie-consent')),
        array('box_content', __('The cookie settings on this website are set to "allow cookies" to give you the best browsing experience possible. If you continue to use this website without changing your cookie settings or you click "Accept" then you are consenting to this.', 'kjrocker-cookie-consent')),
        array('box_htmlcontent', __('<b>Content not available.</b><br><small>Please allow cookies by clicking Accept on the banner</small>', 'kjrocker-cookie-consent')),
        array('backgroundcolor', '#000000'),
        array('fontcolor', '#FFFFFF'),
        array('autoblock', '0'),
        array('boxlinkblank', '0'),
        array('tinymcebutton', '0'),
        array('scrollconsent', '0'),
        array('navigationconsent', '0'),
        array('networkshare', '0'),
        array('onlyeuropean', '0'),
        array('customurl', get_site_url() ),
        array('kj-disablecookie', __('Revoke cookie consent', 'kjrocker-cookie-consent')),
        array('kj-cookieenabled', __('Cookies are enabled', 'kjrocker-cookie-consent')),
        array('kj-cookiedisabled', __('Cookies are disabled<br>Accept Cookies by clicking "%s" in the banner.', 'kjrocker-cookie-consent')),
        array('networkshareurl', kj_getshareurl()),
        array('exclude_script', '0')
    );

    $my_options = get_option('rocker_kjcookie');
    $conta = count($defaults);
    for($i=0;$i<$conta;$i++){
        if (!$my_options[$defaults[$i][0]]) {
            $my_options[$defaults[$i][0]] = $defaults[$i][1];
            update_option('rocker_kjcookie', $my_options);            
        }
    }

    function kj_getshareurl() {
        if ( is_multisite() ) {
            $sURL = network_site_url();
        } else {
            $sURL = site_url();
        }
        $sURL    = site_url(); // WordPress function
        $asParts = parse_url( $sURL ); // PHP function

        if ( ! $asParts )
          wp_die( 'ERROR: Corrupt path for parsing.' ); // replace this with a better error result

        $sScheme = $asParts['scheme'];
        $nPort   = $asParts['port'];
        $sHost   = $asParts['host'];
        $nPort   = 80 == $nPort ? '' : $nPort;
        $nPort   = 'https' == $sScheme AND 443 == $nPort ? '' : $nPort;
        $sPort   = ! empty( $sPort ) ? ":$nPort" : '';
        $sReturn = $sHost . $sPort;

        return $sReturn;
    }
?>
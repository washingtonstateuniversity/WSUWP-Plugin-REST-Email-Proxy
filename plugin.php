<?php
/*
Plugin Name: WSUWP REST Email Proxy
Version: 0.0.1
Description: Provide an proxy for sending email from remote WordPress servers.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-REST-Email-Proxy
*/

namespace WSU\RestEmailProxy;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'plugins_loaded', 'WSU\RestEmailProxy\bootstrap' );
/**
 * Loads the rest of the REST Email Proxy.
 *
 * @since 0.0.1
 */
function bootstrap() {}

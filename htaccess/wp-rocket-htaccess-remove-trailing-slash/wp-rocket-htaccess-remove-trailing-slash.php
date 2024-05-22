<?php
/**
 * Plugin Name: WP Rocket | Remove Trailing Slash from URLs
 * Description: Removes Trailing Slash from URLs.
 * Author:      WP Rocket Support Team
 * Author URI:  http://wp-rocket.me/
 * License:     GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright SAS WP MEDIA 2019
 */
 
namespace WP_Rocket\Helpers\htaccess\redirect\remove_trailing_slash;

// Standard plugin security, keep this line in place.
defined( 'ABSPATH' ) or die();

/**
 * Forces a trailing slash on GET requests.
 *
 * @author Vasilis Manthos
 * @param  string $marker Block of WP Rocket rules
 * @return string Extended block of WP Rocket rules
 */
function render_rewrite_rules( $marker ) {
	
	$redirection  = '# Remove trailing slash' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_URI} !wp-admin' . PHP_EOL;
	$redirection .= 'RewriteEngine On' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_METHOD} GET' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_URI} (.*)/$' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_URI} !^/wp-json/' . PHP_EOL;
	$redirection .= 'RewriteCond %{REQUEST_FILENAME} !\.(gif|jpg|png|jpeg|css|xml|txt|js|php|scss|webp|mp3|avi|wav|mp4|mov|pdf)$ [NC]' . PHP_EOL;
	$redirection .= 'RewriteRule ^(.*)/$ /$1 [R=301,L]' . PHP_EOL . PHP_EOL;
	
	// Prepend redirection rules to WP Rocket block.
	$marker = $redirection . $marker;
	
	return $marker;
}
add_filter( 'before_rocket_htaccess_rules', __NAMESPACE__ . '\render_rewrite_rules' );

/**
 * Updates .htaccess, regenerates WP Rocket config file.
 *
 * @author Caspar Hübinger
 */
function flush_wp_rocket() {
	
	if ( ! function_exists( 'flush_rocket_htaccess' )
	  || ! function_exists( 'rocket_generate_config_file' ) ) {
		return false;
	}
	
	// Update WP Rocket .htaccess rules.
	flush_rocket_htaccess();
	
	// Regenerate WP Rocket config file.
	rocket_generate_config_file();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\flush_wp_rocket' );

/**
 * Removes customizations, updates .htaccess, regenerates config file.
 *
 * @author Caspar Hübinger
 */
function deactivate() {
	
	// Remove all functionality added above.
	remove_filter( 'before_rocket_htaccess_rules', __NAMESPACE__ . '\render_rewrite_rules' );
	
	// Flush .htaccess rules, and regenerate WP Rocket config file.
	flush_wp_rocket();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate' );

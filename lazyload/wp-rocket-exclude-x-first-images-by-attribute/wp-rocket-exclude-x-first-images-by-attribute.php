<?php
/**
 * Plugin Name: WP Rocket | Exclude X first images from Lazy Load by Attribute
 * Description: Disables lazy load for the first X images by Attribute.
 * Plugin URI:  https://github.com/wp-media/wp-rocket-helpers/tree/master/lazyload/wp-rocket-exclude-x-first-images-by-attribute/
 * Author:      WP Rocket Support Team
 * Author URI:  http://wp-rocket.me/
 * License:     GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright SAS WP MEDIA 2018
 */

namespace WP_Rocket\Helpers\lazyload\exclude_by_attribute;

// Standard plugin security, keep this line in place.
defined( 'ABSPATH' ) or die();

// EDIT ANY OF THE FOLLOWING AS NEEDED

/**
 * Disable LazyLoad on single post views.
 *
 * @author Adame Dahmani
 * @param  string $html HTML contents.
 * 
 */

// Change width="350" to any unique attribute you want to target
// Change 3 to the number of times you want to apply the exclusion

function exclude_from_lazyload_by_attribute( $html ){
	$html = preg_replace( '/width="350"/i', 'data-no-lazy="" width="350"', $html, 3 );
    return $html;
}

add_filter( 'rocket_buffer', __NAMESPACE__ . '\exclude_from_lazyload_by_attribute' , 17 );
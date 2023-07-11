<?php
/**
 * Plugin Name: WP Rocket | Clean product related translations (WPML)
 * Description: Clears the cache of product translations and its categories when updating a product, to keep the translations in sync
 * Plugin URI:  https://github.com/wp-media/wp-rocket-helpers/
 * Author:      WP Rocket Support Team
 * Author URI:  http://wp-rocket.me/
 * License:     GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright SAS WP MEDIA 2022
 */

namespace WP_Rocket\Helpers\htaccess\wp_rocket_clean_product_related_translations;

// Standard plugin security, keep this line in place.
defined( 'ABSPATH' ) or die();


function purge_custom_post_urls( $purge_urls, $post ) {

    if ( empty( $purge_urls ) || ! is_array( $purge_urls ) ) {
        return $purge_urls;
    }

    // get current languages
    $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
   
      if ( !empty( $languages ) && $post->post_type == 'product' ) {
                      
          foreach( $languages as $l ) {
              
              $id = icl_object_id($post->ID, 'product', false, $l['language_code']);
              if($id != $post->ID) {
                  
                  // add the product translation
                  $purge_urls[] = get_permalink($id);
                  
                  // Add homepage URL for each language
                  $homepage_url = get_home_url( null, '', $l['language_code'] );
                  $urls_to_purge[] = $homepage_url;
                  
                 // add the categories of the product
                 $product_categories = wp_get_post_terms( $id, 'product_cat' );
                
                 foreach ( $product_categories as $category ) {
                     $category_url = get_term_link( $category->term_id, 'product_cat' );
                     $purge_urls[] = $category_url;
                 }

              }
          }
      }

    /**
     * Return modified purge set to filter.
     */
    return $purge_urls;
}

add_filter( 'rocket_post_purge_urls', __NAMESPACE__ . '\purge_custom_post_urls', 10, 2 );
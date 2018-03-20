<?php
/*
* @package           cf-giphy-search
*
* @wordpress-plugin
* Plugin Name:       Giphy Search
* Plugin URI:        https://www.cantusfirm.us/giphy-search
* Description:       Using the Giphy API, provides a search interface for GIFs within the WordPress post editor
* Version:           1.0.0
* Author:            Cantus Firmus LLC
* Author URI:        http://www.cantusfirm.us
* License:           mit
* License URI:       https://opensource.org/licenses/MIT
* Text Domain:       cf-giphy-search
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'WP_GIPHY_SEARCH_FILE' ) ) {
	define( 'WP_GIPHY_SEARCH_FILE', __FILE__ );
}


require_once( dirname( WP_GIPHY_SEARCH_FILE ) . '/includes/class.giphy_search.php' );

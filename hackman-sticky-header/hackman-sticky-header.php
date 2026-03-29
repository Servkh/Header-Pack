<?php
/**
 * Plugin Name: Hackman Sticky Header for Elementor
 * Plugin URI:  https://github.com/servkh/header-pack
 * Description: A pixel-perfect, scroll-aware sticky header styled for Hackman Paving, Inc. Fully compatible with Elementor and Elementor Pro Theme Builder.
 * Version:     1.0.0
 * Author:      Servkh
 * License:     GPL-2.0+
 * Text Domain: hackman-sticky-header
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HSH_VERSION',     '1.0.0' );
define( 'HSH_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'HSH_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'HSH_PLUGIN_FILE', __FILE__ );

require_once HSH_PLUGIN_DIR . 'includes/class-hsh-assets.php';
require_once HSH_PLUGIN_DIR . 'includes/class-hsh-elementor.php';
require_once HSH_PLUGIN_DIR . 'includes/class-hsh-settings.php';
require_once HSH_PLUGIN_DIR . 'includes/class-hsh-nav-walker.php';

/**
 * Bootstrap the plugin after all plugins are loaded so Elementor is available.
 */
add_action( 'plugins_loaded', function () {
	HSH_Assets::init();
	HSH_Elementor::init();
	HSH_Settings::init();
} );

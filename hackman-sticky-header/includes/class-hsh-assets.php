<?php
/**
 * Enqueue front-end CSS and JS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HSH_Assets {

	public static function init() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
	}

	public static function enqueue() {
		// Only load when the Elementor frontend is present.
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		wp_enqueue_style(
			'hsh-header',
			HSH_PLUGIN_URL . 'assets/css/header.css',
			[],
			HSH_VERSION
		);

		wp_enqueue_script(
			'hsh-sticky',
			HSH_PLUGIN_URL . 'assets/js/sticky-header.js',
			[],
			HSH_VERSION,
			true   // Load in footer so DOM is ready.
		);

		// Pass settings from admin panel to the script.
		$options = get_option( 'hsh_settings', [] );
		wp_localize_script( 'hsh-sticky', 'hshConfig', [
			'selector'        => ! empty( $options['selector'] )        ? $options['selector']          : '.hsh-header',
			'scrollThreshold' => ! empty( $options['scroll_threshold'] ) ? (int) $options['scroll_threshold'] : 80,
			'animationSpeed'  => ! empty( $options['animation_speed'] )  ? (int) $options['animation_speed']  : 300,
		] );
	}
}

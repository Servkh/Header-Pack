<?php
/**
 * Admin settings page — lets users tweak selector, threshold, speed, etc.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HSH_Settings {

	const PAGE_SLUG    = 'hackman-sticky-header';
	const OPTION_GROUP = 'hsh_settings_group';
	const OPTION_NAME  = 'hsh_settings';

	public static function init() {
		add_action( 'admin_menu',    [ __CLASS__, 'add_menu' ] );
		add_action( 'admin_init',    [ __CLASS__, 'register_settings' ] );
	}

	public static function add_menu() {
		add_options_page(
			esc_html__( 'Hackman Sticky Header', 'hackman-sticky-header' ),
			esc_html__( 'Hackman Sticky Header', 'hackman-sticky-header' ),
			'manage_options',
			self::PAGE_SLUG,
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function register_settings() {
		register_setting( self::OPTION_GROUP, self::OPTION_NAME, [
			'sanitize_callback' => [ __CLASS__, 'sanitize' ],
		] );

		add_settings_section( 'hsh_main', esc_html__( 'Sticky Behaviour', 'hackman-sticky-header' ), '__return_false', self::PAGE_SLUG );

		add_settings_field( 'selector', esc_html__( 'Header CSS Selector', 'hackman-sticky-header' ), [ __CLASS__, 'field_selector' ], self::PAGE_SLUG, 'hsh_main' );
		add_settings_field( 'scroll_threshold', esc_html__( 'Scroll Threshold (px)', 'hackman-sticky-header' ), [ __CLASS__, 'field_threshold' ], self::PAGE_SLUG, 'hsh_main' );
		add_settings_field( 'animation_speed', esc_html__( 'Animation Speed (ms)', 'hackman-sticky-header' ), [ __CLASS__, 'field_speed' ], self::PAGE_SLUG, 'hsh_main' );
	}

	public static function sanitize( $input ) {
		return [
			'selector'        => isset( $input['selector'] )        ? sanitize_text_field( $input['selector'] )        : '.hsh-header',
			'scroll_threshold'=> isset( $input['scroll_threshold'] ) ? absint( $input['scroll_threshold'] )             : 80,
			'animation_speed' => isset( $input['animation_speed'] )  ? absint( $input['animation_speed'] )              : 300,
		];
	}

	private static function get( $key, $default = '' ) {
		$opts = get_option( self::OPTION_NAME, [] );
		return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
	}

	public static function field_selector() {
		$val = self::get( 'selector', '.hsh-header' );
		echo '<input type="text" name="' . self::OPTION_NAME . '[selector]" value="' . esc_attr( $val ) . '" class="regular-text" />';
		echo '<p class="description">' . esc_html__( 'CSS selector for your header element. Default: .hsh-header', 'hackman-sticky-header' ) . '</p>';
	}

	public static function field_threshold() {
		$val = self::get( 'scroll_threshold', 80 );
		echo '<input type="number" min="0" max="500" name="' . self::OPTION_NAME . '[scroll_threshold]" value="' . esc_attr( $val ) . '" class="small-text" /> px';
		echo '<p class="description">' . esc_html__( 'Pixels scrolled before the sticky effect activates.', 'hackman-sticky-header' ) . '</p>';
	}

	public static function field_speed() {
		$val = self::get( 'animation_speed', 300 );
		echo '<input type="number" min="0" max="2000" name="' . self::OPTION_NAME . '[animation_speed]" value="' . esc_attr( $val ) . '" class="small-text" /> ms';
		echo '<p class="description">' . esc_html__( 'Slide animation duration in milliseconds.', 'hackman-sticky-header' ) . '</p>';
	}

	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Hackman Sticky Header Settings', 'hackman-sticky-header' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
			<hr>
			<h2><?php esc_html_e( 'How to use', 'hackman-sticky-header' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Option A — Widget: Open any Elementor page, search for "Hackman Header" widget and drag it in. Customise logo, menu and colours in the panel.', 'hackman-sticky-header' ); ?></li>
				<li><?php esc_html_e( 'Option B — Section toggle: If you have already built your header with a standard Elementor section/container, go to Advanced → Hackman Sticky Header and enable the toggle. The section will receive the .hsh-header class automatically.', 'hackman-sticky-header' ); ?></li>
				<li><?php esc_html_e( 'Option C — Manual: Add the CSS class hsh-header to any Elementor section via Advanced → CSS Classes.', 'hackman-sticky-header' ); ?></li>
			</ol>
		</div>
		<?php
	}
}

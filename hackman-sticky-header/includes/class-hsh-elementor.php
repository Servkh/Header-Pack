<?php
/**
 * Elementor-specific integration.
 *
 * Registers a custom widget so users can drag-and-drop the full Hackman
 * header into any Elementor canvas (page, header template, etc.).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HSH_Elementor {

	public static function init() {
		// Widget registration requires Elementor to be active.
		add_action( 'elementor/widgets/register', [ __CLASS__, 'register_widgets' ] );

		// Allow the .hsh-header CSS class to be added via Elementor's
		// Advanced → CSS Classes field without being stripped.
		add_filter( 'elementor/element/after_section_end', [ __CLASS__, 'add_sticky_controls' ], 10, 3 );
	}

	/**
	 * Register the Hackman Header widget.
	 *
	 * @param \Elementor\Widgets_Manager $manager
	 */
	public static function register_widgets( $manager ) {
		require_once HSH_PLUGIN_DIR . 'includes/widget-hackman-header.php';
		$manager->register( new Widget_Hackman_Header() );
	}

	/**
	 * Add a "Sticky Header" toggle inside the Elementor Section / Container
	 * Advanced panel so editors can enable the behaviour without touching code.
	 *
	 * @param \Elementor\Element_Base $element
	 * @param string                  $section_id
	 * @param array                   $args
	 */
	public static function add_sticky_controls( $element, $section_id, $args ) {
		$types = [ 'section', 'container' ];
		if ( ! in_array( $element->get_type(), $types, true ) ) {
			return;
		}
		if ( 'section_advanced' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'hsh_sticky_section',
			[
				'label' => esc_html__( 'Hackman Sticky Header', 'hackman-sticky-header' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'hsh_enable_sticky',
			[
				'label'        => esc_html__( 'Enable Sticky Behaviour', 'hackman-sticky-header' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hackman-sticky-header' ),
				'label_off'    => esc_html__( 'No', 'hackman-sticky-header' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Header slides down when scrolling up and hides when scrolling down.', 'hackman-sticky-header' ),
			]
		);

		$element->end_controls_section();

		// Inject the .hsh-header CSS class dynamically based on the switcher value.
		add_filter( 'elementor/element/get_child_type', function ( $child_type ) {
			return $child_type;
		} );

		// Apply the class via render_attributes so it ends up in the DOM.
		add_action( 'elementor/frontend/section/before_render', function ( $element ) {
			self::maybe_add_header_class( $element );
		} );
		add_action( 'elementor/frontend/container/before_render', function ( $element ) {
			self::maybe_add_header_class( $element );
		} );
	}

	/**
	 * Add .hsh-header to the element wrapper when the toggle is on.
	 *
	 * @param \Elementor\Element_Base $element
	 */
	public static function maybe_add_header_class( $element ) {
		$settings = $element->get_settings_for_display();
		if ( ! empty( $settings['hsh_enable_sticky'] ) && 'yes' === $settings['hsh_enable_sticky'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'hsh-header' );
		}
	}
}

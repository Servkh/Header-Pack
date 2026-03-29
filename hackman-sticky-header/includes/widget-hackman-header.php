<?php
/**
 * Elementor widget: Hackman Header
 *
 * Renders the complete Hackman Paving, Inc. header — logo, nav, CTA button —
 * with all styling baked in.  Drop it onto any Elementor canvas.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Widget_Hackman_Header extends \Elementor\Widget_Base {

	public function get_name()  { return 'hackman_header'; }
	public function get_title() { return esc_html__( 'Hackman Header', 'hackman-sticky-header' ); }
	public function get_icon()  { return 'eicon-site-logo'; }
	public function get_categories() { return [ 'general' ]; }
	public function get_keywords()   { return [ 'header', 'hackman', 'sticky', 'nav', 'navigation' ]; }

	/* ------------------------------------------------------------------ */
	/*  Controls                                                            */
	/* ------------------------------------------------------------------ */

	protected function register_controls() {

		// ── Logo ────────────────────────────────────────────────────────
		$this->start_controls_section( 'section_logo', [
			'label' => esc_html__( 'Logo', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'logo_image', [
			'label'   => esc_html__( 'Logo Image', 'hackman-sticky-header' ),
			'type'    => \Elementor\Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
		] );

		$this->add_control( 'logo_text_line1', [
			'label'   => esc_html__( 'Company Name', 'hackman-sticky-header' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'HACKMAN',
		] );

		$this->add_control( 'logo_text_line2', [
			'label'   => esc_html__( 'Tagline', 'hackman-sticky-header' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'PAVING, INC.',
		] );

		$this->add_control( 'logo_url', [
			'label'         => esc_html__( 'Logo Link URL', 'hackman-sticky-header' ),
			'type'          => \Elementor\Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-site.com', 'hackman-sticky-header' ),
			'default'       => [ 'url' => home_url( '/' ) ],
			'show_external' => false,
		] );

		$this->end_controls_section();

		// ── Navigation ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_nav', [
			'label' => esc_html__( 'Navigation', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'nav_menu', [
			'label'   => esc_html__( 'WordPress Menu', 'hackman-sticky-header' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $this->get_available_menus(),
			'default' => '',
			'description' => esc_html__( 'Select a registered WordPress navigation menu.', 'hackman-sticky-header' ),
		] );

		$this->end_controls_section();

		// ── CTA Button ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_cta', [
			'label' => esc_html__( 'CTA Button', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'cta_text', [
			'label'   => esc_html__( 'Button Label', 'hackman-sticky-header' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'Free Estimate',
		] );

		$this->add_control( 'cta_url', [
			'label'         => esc_html__( 'Button URL', 'hackman-sticky-header' ),
			'type'          => \Elementor\Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-site.com/contact', 'hackman-sticky-header' ),
			'default'       => [ 'url' => '#contact' ],
			'show_external' => true,
		] );

		$this->end_controls_section();

		// ── Style: Header ───────────────────────────────────────────────
		$this->start_controls_section( 'section_style_header', [
			'label' => esc_html__( 'Header', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'header_bg_color', [
			'label'     => esc_html__( 'Background Color', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#6b1a1a',
			'selectors' => [ '{{WRAPPER}} .hsh-widget-header' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'header_padding', [
			'label'      => esc_html__( 'Padding', 'hackman-sticky-header' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'default'    => [
				'top'    => '14', 'right'  => '40',
				'bottom' => '14', 'left'   => '40',
				'unit'   => 'px', 'isLinked' => false,
			],
			'selectors' => [ '{{WRAPPER}} .hsh-widget-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		// ── Style: Logo ─────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_logo', [
			'label' => esc_html__( 'Logo Text', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'logo_color', [
			'label'     => esc_html__( 'Color', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .hsh-logo-text' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'logo_typography',
			'selector' => '{{WRAPPER}} .hsh-logo-company',
		] );

		$this->end_controls_section();

		// ── Style: Nav ──────────────────────────────────────────────────
		$this->start_controls_section( 'section_style_nav', [
			'label' => esc_html__( 'Navigation', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'nav_color', [
			'label'     => esc_html__( 'Link Color', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .hsh-nav a'               => 'color: {{VALUE}};',
				'{{WRAPPER}} .hsh-nav .hsh-arrow svg'  => 'fill: {{VALUE}};',
			],
		] );

		$this->add_control( 'nav_hover_color', [
			'label'     => esc_html__( 'Link Hover Color', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#f0c040',
			'selectors' => [
				'{{WRAPPER}} .hsh-nav a:hover'                        => 'color: {{VALUE}};',
				'{{WRAPPER}} .hsh-nav > li:hover > a'                 => 'color: {{VALUE}};',
				'{{WRAPPER}} .hsh-nav > li:hover > a .hsh-arrow svg'  => 'fill: {{VALUE}};',
			],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'nav_typography',
			'selector' => '{{WRAPPER}} .hsh-nav a',
		] );

		$this->add_control( 'dropdown_bg', [
			'label'     => esc_html__( 'Dropdown Background', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#4a1010',
			'selectors' => [ '{{WRAPPER}} .hsh-nav .hsh-dropdown' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		// ── Style: CTA Button ───────────────────────────────────────────
		$this->start_controls_section( 'section_style_cta', [
			'label' => esc_html__( 'CTA Button', 'hackman-sticky-header' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'cta_bg', [
			'label'     => esc_html__( 'Background', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#1a1a1a',
			'selectors' => [ '{{WRAPPER}} .hsh-cta' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'cta_color', [
			'label'     => esc_html__( 'Text Color', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .hsh-cta' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'cta_hover_bg', [
			'label'     => esc_html__( 'Hover Background', 'hackman-sticky-header' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#333333',
			'selectors' => [ '{{WRAPPER}} .hsh-cta:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'cta_typography',
			'selector' => '{{WRAPPER}} .hsh-cta',
		] );

		$this->add_responsive_control( 'cta_padding', [
			'label'      => esc_html__( 'Padding', 'hackman-sticky-header' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [
				'top' => '14', 'right' => '28', 'bottom' => '14', 'left' => '28',
				'unit' => 'px', 'isLinked' => false,
			],
			'selectors' => [ '{{WRAPPER}} .hsh-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	/* ------------------------------------------------------------------ */
	/*  Helpers                                                             */
	/* ------------------------------------------------------------------ */

	private function get_available_menus() {
		$menus   = wp_get_nav_menus();
		$options = [ '' => esc_html__( '— Select Menu —', 'hackman-sticky-header' ) ];
		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}
		return $options;
	}

	/* ------------------------------------------------------------------ */
	/*  Render                                                              */
	/* ------------------------------------------------------------------ */

	protected function render() {
		$s = $this->get_settings_for_display();

		$logo_href = ! empty( $s['logo_url']['url'] ) ? esc_url( $s['logo_url']['url'] ) : home_url( '/' );
		$cta_href  = ! empty( $s['cta_url']['url'] )  ? esc_url( $s['cta_url']['url'] )  : '#';
		$cta_target = ! empty( $s['cta_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';

		$logo_img = ! empty( $s['logo_image']['url'] )
			? '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="' . esc_attr( $s['logo_text_line1'] ) . '" class="hsh-logo-img" />'
			: $this->get_default_logo_svg();

		?>
		<header class="hsh-widget-header" role="banner">
			<div class="hsh-inner">

				<!-- Logo -->
				<a href="<?php echo $logo_href; ?>" class="hsh-logo" aria-label="<?php echo esc_attr( $s['logo_text_line1'] . ' ' . $s['logo_text_line2'] ); ?>">
					<?php echo $logo_img; ?>
					<div class="hsh-logo-text">
						<span class="hsh-logo-company"><?php echo esc_html( $s['logo_text_line1'] ); ?></span>
						<span class="hsh-logo-tagline"><?php echo esc_html( $s['logo_text_line2'] ); ?></span>
					</div>
				</a>

				<!-- Navigation -->
				<nav class="hsh-nav-wrap" aria-label="<?php esc_attr_e( 'Primary Navigation', 'hackman-sticky-header' ); ?>">
					<?php $this->render_nav( $s ); ?>
					<!-- Mobile toggle -->
					<button class="hsh-mobile-toggle" aria-expanded="false" aria-controls="hsh-mobile-nav" aria-label="<?php esc_attr_e( 'Toggle menu', 'hackman-sticky-header' ); ?>">
						<span></span><span></span><span></span>
					</button>
				</nav>

				<!-- CTA -->
				<a href="<?php echo $cta_href; ?>"<?php echo $cta_target; ?> class="hsh-cta">
					<?php echo esc_html( $s['cta_text'] ); ?>
				</a>

			</div><!-- .hsh-inner -->
		</header><!-- .hsh-widget-header -->
		<?php
	}

	private function render_nav( $s ) {
		if ( ! empty( $s['nav_menu'] ) ) {
			// Use the WordPress menu selected in the control.
			wp_nav_menu( [
				'menu'            => (int) $s['nav_menu'],
				'container'       => false,
				'menu_class'      => 'hsh-nav',
				'menu_id'         => 'hsh-primary-nav',
				'depth'           => 2,
				'fallback_cb'     => false,
				'walker'          => new HSH_Nav_Walker(),
			] );
		} else {
			// Fallback demo nav when no menu is selected.
			echo '<ul class="hsh-nav" id="hsh-primary-nav">';
			$demo_items = [
				'Home'     => home_url( '/' ),
				'About'    => '#about',
				'Services' => '#services',
				'Gallery'  => '#gallery',
				'FAQs'     => '#faqs',
				'Contact'  => '#contact',
			];
			foreach ( $demo_items as $label => $url ) {
				echo '<li class="menu-item"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
			}
			echo '</ul>';
		}
	}

	private function get_default_logo_svg() {
		return '<svg class="hsh-logo-icon" viewBox="0 0 48 56" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<rect width="48" height="56" rx="2" fill="rgba(255,255,255,0.15)"/>
			<text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"
				font-family="Arial, sans-serif" font-weight="900" font-size="28" fill="#ffffff">H</text>
		</svg>';
	}
}

<?php
/**
 * Theme Customizer Settings and Live Previews
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Remove default WordPress customizer sections
 */
function custom_theme_remove_customizer_sections( $wp_customize ) {
	$wp_customize->remove_section( 'title_tagline' );
}
add_action( 'customize_register', 'custom_theme_remove_customizer_sections', 20 );

/**
 * Register theme Customizer controls, sections, and panels
 */
function lightshadestudioworks_register_full_customizer( $wp_customize ) {
	// 1. Add Section
	$wp_customize->add_section(
		'lightshadestudioworks_theme_colors',
		array(
			'title'    => __( 'Theme Color Palette', 'lightshadestudioworks' ),
			'priority' => 30,
		)
	);

	// 2. Add Scheme Selector
	$wp_customize->add_setting(
		'color_scheme_select',
		array(
			'default'           => 'scheme_4',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'color_scheme_select',
		array(
			'label'    => __( 'Select Base Color Scheme', 'lightshadestudioworks' ),
			'section'  => 'lightshadestudioworks_theme_colors',
			'type'     => 'select',
			'choices'  => array(
				'scheme_1' => 'Scheme 1 (Pastel)',
				'scheme_2' => 'Scheme 2 (Dark)',
				'scheme_3' => 'Scheme 3 (Ocean)',
				'scheme_4' => 'Scheme 4 (Golden Ember)',
			),
		)
	);

	// 3. Add Primary / Secondary / Accent Controls
	$colors = array(
		'primary_60'   => 'Primary (60%)',
		'secondary_30' => 'Secondary (30%)',
		'accent_10'    => 'Accent (10%)',
	);
	foreach ( $colors as $id => $label ) {
		$default_value = ( $id == 'primary_60' ) ? '#ffffff' : ( ( $id == 'secondary_30' ) ? '#000000' : '#c5a059' );
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default_value,
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'    => $label,
					'section'  => 'lightshadestudioworks_theme_colors',
					'settings' => $id,
				)
			)
		);
	}

	// 4. Add Neutral Color Controls
	$neutral_colors = array(
		'neutral_white' => 'Neutral White',
		'neutral_black' => 'Neutral Black',
		'neutral_grey'  => 'Neutral Grey',
	);
	foreach ( $neutral_colors as $id => $label ) {
		$default_value = ( $id == 'neutral_white' ) ? '#dcdcdc' : ( ( $id == 'neutral_black' ) ? '#454545' : '#f2f2f7' );
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default_value,
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'    => $label,
					'section'  => 'lightshadestudioworks_theme_colors',
					'settings' => $id,
				)
			)
		);
	}

	// BUTTON HOVER EFFECTS..................
	$wp_customize->add_section(
		'lssw_button_settings',
		array(
			'title'    => __( 'Button Settings', 'lightshadestudioworks' ),
			'priority' => 50,
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_hover_effect',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'lssw_btn_hover_effect',
		array(
			'label'    => __( 'Select Hover Effect', 'lightshadestudioworks' ),
			'section'  => 'lssw_button_settings',
			'type'     => 'select',
			'choices'  => array(
				'none'                    => 'Default (None)',
				'internal-fill'           => 'Internal Fill',
				'magnetic'                => 'Magnetic Border Expansion',
				'shutter-reveal'          => 'Shutter Reveal',
				'pulse-wave'              => 'Pulse Wave',
				'staggered-radial-ripple' => 'Staggered Radial Ripple',
			),
		)
	);

	// Conditionally define LSW_Customize_Heading_Control if it doesn't exist yet
	if ( ! class_exists( 'LSW_Customize_Heading_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
		class LSW_Customize_Heading_Control extends WP_Customize_Control {
			public $type = 'heading';
			public function render_content() {
				echo '<h3 style="margin: 20px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc; text-transform: uppercase; font-size: 13px;">' . esc_html( $this->label ) . '</h3>';
			}
		}
	}

	// --- INTERNAL FILL CONTROLS ---
	$wp_customize->add_setting( 'lssw_btn_internal_fill_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_internal_fill_heading_ctrl',
			array(
				'label'           => 'Internal Fill',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_internal_fill_heading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_internal_fill_normal_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_internal_fill_normal_subheading_ctrl',
			array(
				'label'           => 'Normal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_internal_fill_normal_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_internal_fill_normal_bg',
		array(
			'default'           => '#333',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_internal_fill_normal_bg',
			array(
				'label'           => __( 'Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_internal_fill_normal_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_internal_fill_normal_text',
			array(
				'label'           => __( 'Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_internal_fill_hover_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_internal_fill_hover_subheading_ctrl',
			array(
				'label'           => 'Hover',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_internal_fill_hover_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_internal_fill_hover_bg',
		array(
			'default'           => '#ff3e3e',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_internal_fill_hover_bg',
			array(
				'label'           => __( 'Hover Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'internal-fill';
				},
			)
		)
	);

	// --- MAGNETIC BORDER EXPANSION CONTROLS ---
	$wp_customize->add_setting( 'lssw_btn_magnetic_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_magnetic_heading_ctrl',
			array(
				'label'           => 'Magnetic Border Expansion',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_magnetic_heading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_magnetic_normal_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_magnetic_normal_subheading_ctrl',
			array(
				'label'           => 'Normal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_magnetic_normal_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_normal_bg',
		array(
			'default'           => '#1a1a1a',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_magnetic_normal_bg',
			array(
				'label'           => __( 'Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_normal_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_magnetic_normal_text',
			array(
				'label'           => __( 'Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_magnetic_hover_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_magnetic_hover_subheading_ctrl',
			array(
				'label'           => 'Hover',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_magnetic_hover_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_hover_bg',
		array(
			'default'           => '#007bff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_magnetic_hover_bg',
			array(
				'label'           => __( 'Hover Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_hover_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_magnetic_hover_text',
			array(
				'label'           => __( 'Hover Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_magnetic_border_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_magnetic_border_subheading_ctrl',
			array(
				'label'           => 'Border',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_magnetic_border_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_border_inset',
		array(
			'default'           => '-2px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'lssw_btn_magnetic_border_inset',
		array(
			'label'           => __( 'Inset', 'lightshadestudioworks' ),
			'section'         => 'lssw_button_settings',
			'type'            => 'text',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
			},
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_magnetic_border_color',
		array(
			'default'           => '#007bff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_magnetic_border_color',
			array(
				'label'           => __( 'Border Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'magnetic';
				},
			)
		)
	);

	// --- SHUTTER REVEAL CONTROLS ---
	$wp_customize->add_setting( 'lssw_btn_shutter_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_shutter_heading_ctrl',
			array(
				'label'           => 'Shutter Reveal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_shutter_heading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_shutter_normal_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_shutter_normal_subheading_ctrl',
			array(
				'label'           => 'Normal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_shutter_normal_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_shutter_normal_bg',
		array(
			'default'           => '#222',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_shutter_normal_bg',
			array(
				'label'           => __( 'Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_shutter_normal_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_shutter_normal_text',
			array(
				'label'           => __( 'Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_shutter_hover_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_shutter_hover_subheading_ctrl',
			array(
				'label'           => 'Hover',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_shutter_hover_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_shutter_hover_bg',
		array(
			'default'           => '#007bff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_shutter_hover_bg',
			array(
				'label'           => __( 'Hover Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'shutter-reveal';
				},
			)
		)
	);

	// --- PULSE WAVE CONTROLS ---
	$wp_customize->add_setting( 'lssw_btn_pulse_wave_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_heading_ctrl',
			array(
				'label'           => 'Internal Fill',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_pulse_wave_heading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_pulse_wave_normal_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_normal_subheading_ctrl',
			array(
				'label'           => 'Normal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_pulse_wave_normal_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_pulse_wave_normal_bg',
		array(
			'default'           => '#2d3436',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_normal_bg',
			array(
				'label'           => __( 'Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_pulse_wave_normal_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_normal_text',
			array(
				'label'           => __( 'Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_pulse_wave_hover_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_hover_subheading_ctrl',
			array(
				'label'           => 'Hover',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_pulse_wave_hover_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_pulse_wave_hover_bg',
		array(
			'default'           => '#0984e3',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_hover_bg',
			array(
				'label'           => __( 'Hover Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_pulse_wave_pulse_bg',
		array(
			'default'           => '#ff0000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_pulse_wave_pulse_bg',
			array(
				'label'           => __( 'Pulse Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_pulse_wave_hover_letter_spacing',
		array(
			'default'           => '4px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'lssw_btn_pulse_wave_hover_letter_spacing',
		array(
			'label'           => __( 'Hover Letter Spacing', 'lightshadestudioworks' ),
			'section'         => 'lssw_button_settings',
			'type'            => 'text',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'pulse-wave';
			},
		)
	);

	// --- STAGGERED RADIAL RIPPLE CONTROLS ---
	$wp_customize->add_setting( 'lssw_btn_staggered_ripple_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_heading_ctrl',
			array(
				'label'           => 'Staggered Radial Ripple',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_staggered_ripple_heading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_staggered_ripple_normal_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_normal_subheading_ctrl',
			array(
				'label'           => 'Normal',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_staggered_ripple_normal_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_staggered_ripple_normal_bg',
		array(
			'default'           => '#0077be',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_normal_bg',
			array(
				'label'           => __( 'Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_staggered_ripple_normal_text',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_normal_text',
			array(
				'label'           => __( 'Text Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_staggered_ripple_first_bg',
		array(
			'default'           => 'rgba(255, 255, 255, 0.25)',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_first_bg',
			array(
				'label'           => __( 'First Ripple Layer Background', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_staggered_ripple_second_bg',
		array(
			'default'           => 'rgba(255, 255, 255, 0.15)',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_second_bg',
			array(
				'label'           => __( 'Second Ripple Layer Background', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting( 'lssw_btn_staggered_ripple_hover_subheading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		new LSW_Customize_Heading_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_hover_subheading_ctrl',
			array(
				'label'           => 'Hover',
				'section'         => 'lssw_button_settings',
				'settings'        => 'lssw_btn_staggered_ripple_hover_subheading',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'lssw_btn_staggered_ripple_hover_bg',
		array(
			'default'           => '#005f99',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lssw_btn_staggered_ripple_hover_bg',
			array(
				'label'           => __( 'Hover Background Color', 'lightshadestudioworks' ),
				'section'         => 'lssw_button_settings',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'lssw_btn_hover_effect' )->value() === 'staggered-radial-ripple';
				},
			)
		)
	);

	// FROM ANOTHER CUSTOMISER SETTINGS............

	if ( ! class_exists( 'LSW_Reset_Customizer_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
		class LSW_Reset_Customizer_Control extends WP_Customize_Control {
			public $type = 'lsw_reset_button';
			public function render_content() {
				$nonce = wp_create_nonce( 'lsw_reset_customizer_nonce' );
				?>
				<script>
					window.lsw_reset_obj = {
						nonce: '<?php echo esc_js( $nonce ); ?>'
					};
				</script>
				<div style="margin-top: 15px; padding: 12px; border: 1px dashed #e2e8f0; border-radius: 8px; background: #f8fafc; box-shadow: inset 0 1px 2px rgba(0,0,0,0.025);">
					<span class="customize-control-title" style="margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 13px;"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span class="description customize-control-description" style="margin-bottom: 12px; display: block; font-size: 12px; line-height: 1.4; color: #64748b;"><?php echo esc_html( $this->description); ?></span>
					<?php endif; ?>
					<button type="button" class="button button-link lsw-reset-customizer-btn" style="width: 100%; border: 1px solid #ef4444; border-radius: 6px; background-color: #fef2f2; color: #ef4444; font-weight: 600; height: 34px; line-height: 32px; text-decoration: none; text-align: center; display: block; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor='#fef2f2'">
						<?php esc_html_e( 'Reset Theme Settings', 'lightshadestudioworks' ); ?>
					</button>
				</div>
				<?php
			}
		}
	}

	$wp_customize->add_section(
		'navbar_layout_section',
		array(
			'title'    => 'Header Navbar Layouts',
			'priority' => 30,
		)
	);
	// Layout Choice
	$wp_customize->add_setting(
		'navbar_layout_choice',
		array(
			'default'           => 'lsw_menu_layout_1',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_layout_choice',
		array(
			'label'   => 'Select Navbar Layout',
			'section' => 'navbar_layout_section',
			'type'    => 'radio',
			'choices' => array(
				'lsw_menu_layout_1' => 'Standard Minimalist',
				'lsw_menu_layout_2' => 'Sleek Minimalist Navbar',
				'lsw_menu_layout_3' => 'Classic Inline Right',
				'lsw_menu_layout_4' => 'Modern Bold Minimalist',
				'lsw_menu_layout_5' => 'Tabed Overlap',
			),
		)
	);

	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'sleek_navbar_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'sleek_navbar_heading_ctrl',
				array(
					'label'           => 'Sleek Minimalist Navbar styles',
					'section'         => 'navbar_layout_section',
					'settings'        => 'sleek_navbar_heading',
					'active_callback' => function ( $control ) {
						return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_2';
					},
				)
			)
		);
	}
	$wp_customize->add_setting(
		'sleek_navbar_hover_color',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sleek_navbar_hover_color',
			array(
				'label'           => 'Hover Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_2';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'sleek_navbar_hover_line_color',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sleek_navbar_hover_line_color',
			array(
				'label'           => 'Hover Bottom Line Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_2';
				},
			)
		)
	);

	// Modern Bold Minimalist Styles (placed in navbar_layout_section)
	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'modern_navbar_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'modern_navbar_heading_ctrl',
				array(
					'label'           => 'Modern Bold Minimalist styles',
					'section'         => 'navbar_layout_section',
					'settings'        => 'modern_navbar_heading',
					'active_callback' => function ( $control ) {
						return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_4';
					},
				)
			)
		);
	}

	$wp_customize->add_setting(
		'modern_navbar_hover_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'modern_navbar_hover_color',
			array(
				'label'           => 'Hover Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_4';
				},
			)
		)
	);

	$wp_customize->add_setting(
		'modern_navbar_hover_line_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'modern_navbar_hover_line_color',
			array(
				'label'           => 'Hover Bottom Line Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_layout_choice' )->value() === 'lsw_menu_layout_4';
				},
			)
		)
	);

	// Common Hover Text Color for Standard Minimalist, Classic Inline Right, and Tabed Overlap
	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'common_navbar_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'common_navbar_heading_ctrl',
				array(
					'label'           => 'Navbar Hover Styles',
					'section'         => 'navbar_layout_section',
					'settings'        => 'common_navbar_heading',
					'active_callback' => function ( $control ) {
						return in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
					},
				)
			)
		);
	}

	$wp_customize->add_setting(
		'common_navbar_hover_color',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'common_navbar_hover_color',
			array(
				'label'           => 'Hover Text Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
				},
			)
		)
	);

	// --- NAVBAR SETTINGS HEADING ---
	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'navbar_settings_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'navbar_settings_header_label',
				array(
					'label'    => 'Navbar Settings',
					'section'  => 'navbar_layout_section',
					'settings' => 'navbar_settings_heading',
				)
			)
		);
	}

	// Brand type choice: Custom Logo or Site Title
	$wp_customize->add_setting(
		'navbar_brand_type',
		array(
			'default'           => 'custom_logo',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_brand_type',
		array(
			'label'   => 'Brand Display',
			'section' => 'navbar_layout_section',
			'type'    => 'radio',
			'choices' => array(
				'custom_logo' => 'Navbar Custom Logo',
				'site_title'  => 'Site Title',
			),
		)
	);

	// Logo Upload (shown when Custom Logo is selected)
	$wp_customize->add_setting( 'navbar_custom_logo', array( 'sanitize_callback' => 'absint' ) );
	if ( class_exists( 'WP_Customize_Cropped_Image_Control' ) ) {
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				'navbar_custom_logo',
				array(
					'label'           => 'Navbar Custom Logo',
					'section'         => 'navbar_layout_section',
					'width'           => 400,
					'height'          => 200,
					'active_callback' => 'is_navbar_custom_logo_enabled',
				)
			)
		);
	}

	$wp_customize->add_setting( 'navbar_logo_width', array( 'default' => 80, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'navbar_logo_width',
		array(
			'label'           => 'Logo Width (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_navbar_custom_logo_enabled',
		)
	);

	// Site title text (shown when Site Title is selected)
	$wp_customize->add_setting(
		'navbar_site_title',
		array(
			'default'           => get_bloginfo( 'name' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title',
		array(
			'label'           => 'Site Title',
			'section'         => 'navbar_layout_section',
			'type'            => 'text',
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	// Site Title Typography (shown when Site Title is selected)
	$navbar_font_choices = array(
		'system-ui, -apple-system, sans-serif'         => 'System Default (Optimized)',
		'sans-serif'                                   => 'Sans Serif (Generic)',
		'serif'                                        => 'Serif (Generic)',
		'monospace'                                    => 'Monospace (Generic)',
		'Arial, Helvetica, sans-serif'                 => 'Arial',
		'Verdana, Geneva, sans-serif'                  => 'Verdana',
		'Trebuchet MS, sans-serif'                     => 'Trebuchet MS',
		'Georgia, serif'                               => 'Georgia',
		'Times New Roman, serif'                       => 'Times New Roman',
		'Courier New, monospace'                       => 'Courier New',
		'Inter, system-ui, sans-serif'                 => 'Inter (Modern)',
		'Segoe UI, Tahoma, sans-serif'                 => 'Segoe UI',
		'Helvetica Neue, Helvetica, Arial, sans-serif' => 'Helvetica Neue',
		'Palatino Linotype, serif'                     => 'Palatino',
	);

	$wp_customize->add_setting(
		'navbar_site_title_font_family',
		array(
			'default'           => 'Inter, system-ui, sans-serif',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_font_family',
		array(
			'label'           => 'Font Family',
			'section'         => 'navbar_layout_section',
			'type'            => 'select',
			'choices'         => $navbar_font_choices,
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_font_size',
		array(
			'default'           => 24,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_font_size',
		array(
			'label'           => 'Font Size (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_font_weight',
		array(
			'default'           => '700',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_font_weight',
		array(
			'label'           => 'Font Weight',
			'section'         => 'navbar_layout_section',
			'type'            => 'select',
			'choices'         => array(
				'300' => 'Light (300)',
				'400' => 'Normal (400)',
				'500' => 'Medium (500)',
				'600' => 'Semi Bold (600)',
				'700' => 'Bold (700)',
				'800' => 'Extra Bold (800)',
				'900' => 'Black (900)',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_font_style',
		array(
			'default'           => 'normal',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_font_style',
		array(
			'label'           => 'Font Style',
			'section'         => 'navbar_layout_section',
			'type'            => 'select',
			'choices'         => array(
				'normal' => 'Normal',
				'italic' => 'Italic',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_letter_spacing',
		array(
			'default'           => '0',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_letter_spacing',
		array(
			'label'           => 'Letter Spacing (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'input_attrs'     => array(
				'step' => '0.1',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_word_spacing',
		array(
			'default'           => '0',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_word_spacing',
		array(
			'label'           => 'Word Spacing (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'input_attrs'     => array(
				'step' => '0.1',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_line_height',
		array(
			'default'           => '1.2',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_line_height',
		array(
			'label'           => 'Line Height',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'input_attrs'     => array(
				'step' => '0.1',
				'min'  => '0.5',
				'max'  => '3',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_text_transform',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'navbar_site_title_text_transform',
		array(
			'label'           => 'Text Transform',
			'section'         => 'navbar_layout_section',
			'type'            => 'select',
			'choices'         => array(
				'none'       => 'None',
				'uppercase'  => 'Uppercase',
				'lowercase'  => 'Lowercase',
				'capitalize' => 'Capitalize',
			),
			'active_callback' => 'is_navbar_site_title_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_site_title_color',
		array(
			'default'           => '#111827',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_site_title_color',
			array(
				'label'           => 'Site Title Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => 'is_navbar_site_title_enabled',
			)
		)
	);

	// Spacing
	$padding_sides = array( 'top', 'right', 'bottom', 'left' );
	foreach ( $padding_sides as $side ) {
		$id = 'navbar_padding_' . $side;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => 'Padding ' . ucfirst( $side ) . ' (px)',
				'section' => 'navbar_layout_section',
				'type'    => 'number',
			)
		);
	}

	$wp_customize->add_setting(
		'navbar_bg_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_bg_color',
			array(
				'label'   => 'Navbar Background Color',
				'section' => 'navbar_layout_section',
			)
		)
	);
	$wp_customize->add_setting( 'navbar_menu_gap', array( 'default' => 12, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'navbar_menu_gap',
		array(
			'label'   => 'Menu Item Gap (px)',
			'section' => 'navbar_layout_section',
			'type'    => 'number',
		)
	);

	// --- BUTTON STYLES HEADING ---
	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'button_styles_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'button_styles_header_label',
				array(
					'label'           => 'Button Styles',
					'section'         => 'navbar_layout_section',
					'settings'        => 'button_styles_heading',
					'active_callback' => 'is_button_enabled',
				)
			)
		);
	}

	// 1. The Switcher (Checkbox)
	$wp_customize->add_setting(
		'navbar_show_button',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'navbar_show_button',
		array(
			'label'   => 'Show CTA Button',
			'section' => 'navbar_layout_section',
			'type'    => 'checkbox',
		)
	);

	// 2. Button Settings (with active_callback)
	$wp_customize->add_setting(
		'navbar_btn_text',
		array(
			'default'           => 'Book Now',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_text',
		array(
			'label'           => 'Button Text',
			'section'         => 'navbar_layout_section',
			'type'            => 'text',
			'active_callback' => 'is_button_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_btn_url',
		array(
			'default'           => home_url( '/' ),
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_url',
		array(
			'label'           => 'Button URL',
			'section'         => 'navbar_layout_section',
			'type'            => 'url',
			'active_callback' => 'is_button_enabled',
		)
	);

	$wp_customize->add_setting(
		'navbar_btn_link_target',
		array(
			'default'           => '_self',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_link_target',
		array(
			'label'   => 'Open Button Link In',
			'section' => 'navbar_layout_section',
			'type'    => 'select',
			'choices' => array(
				'_self'  => 'Same Tab',
				'_blank' => 'New Tab',
			),
			'active_callback' => 'is_button_enabled',
		)
	);

	// Background Color
	$wp_customize->add_setting(
		'navbar_btn_bg',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_btn_bg',
			array(
				'label'           => 'Button Background Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => 'is_button_enabled',
			)
		)
	);

	// Box Shadow - Shadow X Position
	$wp_customize->add_setting(
		'navbar_btn_shadow_x',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_shadow_x',
		array(
			'label'           => 'Shadow X Position (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_button_enabled',
		)
	);

	// Shadow Y Position
	$wp_customize->add_setting(
		'navbar_btn_shadow_y',
		array(
			'default'           => 10,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_shadow_y',
		array(
			'label'           => 'Shadow Y Position (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_button_enabled',
		)
	);

	// Shadow Blur
	$wp_customize->add_setting(
		'navbar_btn_shadow_blur',
		array(
			'default'           => 15,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_shadow_blur',
		array(
			'label'           => 'Shadow Blur (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_button_enabled',
		)
	);

	// Shadow Color
	$wp_customize->add_setting(
		'navbar_btn_shadow_color',
		array(
			'default'           => '#bfdbfe',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_btn_shadow_color',
			array(
				'label'           => 'Shadow Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => 'is_button_enabled',
			)
		)
	);

	// Button Border Radius
	$wp_customize->add_setting(
		'navbar_btn_radius',
		array(
			'default'           => 9999,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_radius',
		array(
			'label'           => 'Button Border Radius (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => 'is_button_enabled',
		)
	);

	// --- HOVER STYLE HEADING ---
	if ( class_exists( 'LSW_Customize_Heading_Control' ) ) {
		$wp_customize->add_setting( 'hover_style_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			new LSW_Customize_Heading_Control(
				$wp_customize,
				'hover_style_header_label',
				array(
					'label'           => 'Hover Style',
					'section'         => 'navbar_layout_section',
					'settings'        => 'hover_style_heading',
					'active_callback' => function ( $control ) {
						return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
					},
				)
			)
		);
	}

	// Hover Effect Switcher
	$wp_customize->add_setting(
		'navbar_btn_hover_enabled',
		array(
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_hover_enabled',
		array(
			'label'           => 'Enable Hover Effect',
			'section'         => 'navbar_layout_section',
			'type'            => 'checkbox',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
			},
		)
	);

	$wp_customize->add_setting(
		'navbar_btn_hover_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_btn_hover_text_color',
			array(
				'label'           => 'Hover Text Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
				},
			)
		)
	);

	// Hover Box Shadow Settings
	$wp_customize->add_setting(
		'navbar_btn_hover_shadow_x',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_hover_shadow_x',
		array(
			'label'           => 'Hover Shadow X Position (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
			},
		)
	);

	$wp_customize->add_setting(
		'navbar_btn_hover_shadow_y',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_hover_shadow_y',
		array(
			'label'           => 'Hover Shadow Y Position (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
			},
		)
	);
	$wp_customize->add_setting(
		'navbar_btn_hover_shadow_blur',
		array(
			'default'           => 15,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'navbar_btn_hover_shadow_blur',
		array(
			'label'           => 'Hover Shadow Blur (px)',
			'section'         => 'navbar_layout_section',
			'type'            => 'number',
			'active_callback' => function ( $control ) {
				return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
			},
		)
	);
	$wp_customize->add_setting(
		'navbar_btn_hover_shadow_color',
		array(
			'default'           => '#bfdbfe',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'navbar_btn_hover_shadow_color',
			array(
				'label'           => 'Hover Shadow Color',
				'section'         => 'navbar_layout_section',
				'active_callback' => function ( $control ) {
					return $control->manager->get_setting( 'navbar_show_button' )->value() && in_array( $control->manager->get_setting( 'navbar_layout_choice' )->value(), array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
				},
			)
		)
	);
	// --- FOOTER SETTINGS ---
	$wp_customize->add_section(
		'footer_style_section',
		array(
			'title'    => __( 'Footer Style', 'lightshadestudioworks' ),
			'priority' => 40,
		)
	);

	// 2. Background Color Setting
	$wp_customize->add_setting(
		'footer_bg_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'footer_bg_color',
			array(
				'label'   => __( 'Footer Background Color', 'lightshadestudioworks' ),
				'section' => 'footer_style_section',
			)
		)
	);

	// 2b. Footer Text Color Setting
	$wp_customize->add_setting(
		'footer_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'footer_text_color',
			array(
				'label'   => __( 'Footer Text Color', 'lightshadestudioworks' ),
				'section' => 'footer_style_section',
			)
		)
	);

	// 3. ADD PADDING CONTROLS (Top, Right, Bottom, Left)
	$padding_sides = array( 'top', 'right', 'bottom', 'left' );
	foreach ( $padding_sides as $side ) {
		$id = 'footer_padding_' . $side;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => 8,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => 'Footer Padding ' . ucfirst( $side ) . ' (px)',
				'section' => 'footer_style_section',
				'type'    => 'number',
			)
		);
	}

	// 4. Display Footer Text Setting
	$wp_customize->add_setting(
		'display_footer_text',
		array(
			'default'           => 'center',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'display_footer_text',
		array(
			'label'   => __( 'Display Footer Text', 'lightshadestudioworks' ),
			'section' => 'footer_style_section',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Left', 'lightshadestudioworks' ),
				'center' => __( 'Center', 'lightshadestudioworks' ),
				'right'  => __( 'Right', 'lightshadestudioworks' ),
			),
		)
	);

	// 5. Footer Text Content Setting
	$wp_customize->add_setting(
		'footer_text',
		array(
			'default'           => '© 2026 Light Shade Studio Works | All Rights Reserved | By Najubudeen Wordpress Developer',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'footer_text',
		array(
			'label'       => __( 'Footer Text', 'lightshadestudioworks' ),
			'section'     => 'footer_style_section',
			'type'        => 'text',
			'description' => __( 'Enter the footer text (supports simple HTML)', 'lightshadestudioworks' ),
		)
	);

	// General Section
	$wp_customize->add_section(
		'lsw_general_section',
		array(
			'title'    => 'General',
			'priority' => 15,
		)
	);

	$wp_customize->add_setting(
		'global_active_link_color',
		array(
			'default'           => '#c5a059',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'global_active_link_color',
			array(
				'label'   => 'Global Active Link Color',
				'section' => 'lsw_general_section',
			)
		)
	);

	// Reset Settings Control
	$wp_customize->add_setting(
		'lsw_reset_settings',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	if ( class_exists( 'LSW_Reset_Customizer_Control' ) ) {
		$wp_customize->add_control(
			new LSW_Reset_Customizer_Control(
				$wp_customize,
				'lsw_reset_settings',
				array(
					'label'       => 'Reset Theme Settings',
					'description' => 'Restore all layout, typography, colors, and other customizer settings back to their factory default values.',
					'section'     => 'lsw_general_section',
					'settings'    => 'lsw_reset_settings',
				)
			)
		);
	}

	// 1. Add Section
	$wp_customize->add_section(
		'site_layout_section',
		array(
			'title'    => 'Site Typography',
			'priority' => 20,
		)
	);

	// 2. Container Width Control
	$wp_customize->add_setting( 'site_container_width', array( 'default' => 1200, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'site_container_width',
		array(
			'label'   => 'Max Container Width (px)',
			'section' => 'site_layout_section',
			'type'    => 'number',
		)
	);

	// 2b. Container Padding Controls (Top, Right, Bottom, Left)
	$container_padding_defaults = array(
		'top'    => 0,
		'right'  => 20,
		'bottom' => 0,
		'left'   => 20,
	);
	foreach ( $container_padding_defaults as $side => $default ) {
		$id = 'site_container_padding_' . $side;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => 'Container Padding ' . ucfirst( $side ) . ' (px)',
				'section' => 'site_layout_section',
				'type'    => 'number',
			)
		);
	}

	// 3. Body Font Family Control
	$wp_customize->add_setting( 'site_font_family', array( 'default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'site_font_family',
		array(
			'label'   => 'Body Font Family',
			'section' => 'site_layout_section',
			'type'    => 'select',
			'choices' => array(
				'system-ui, -apple-system, sans-serif' => 'System Default (Optimized)',
				'sans-serif'                           => 'Sans Serif (Generic)',
				'serif'                                => 'Serif (Generic)',
				'monospace'                            => 'Monospace (Generic)',
				'Arial, Helvetica, sans-serif'         => 'Arial',
				'Verdana, Geneva, sans-serif'          => 'Verdana',
				'Trebuchet MS, sans-serif'             => 'Trebuchet MS',
				'Georgia, serif'                       => 'Georgia',
				'Times New Roman, serif'               => 'Times New Roman',
				'Courier New, monospace'               => 'Courier New',
				'Inter, system-ui, sans-serif'         => 'Inter (Modern)',
				'Segoe UI, Tahoma, sans-serif'         => 'Segoe UI',
				'Helvetica Neue, Helvetica, Arial, sans-serif' => 'Helvetica Neue',
				'Palatino Linotype, serif'             => 'Palatino',
			),
		)
	);

	// 3b. Heading Font Family Control
	$wp_customize->add_setting( 'site_heading_font_family', array( 'default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'site_heading_font_family',
		array(
			'label'   => 'Heading Font Family',
			'section' => 'site_layout_section',
			'type'    => 'select',
			'choices' => array(
				'system-ui, -apple-system, sans-serif' => 'System Default (Optimized)',
				'sans-serif'                           => 'Sans Serif (Generic)',
				'serif'                                => 'Serif (Generic)',
				'monospace'                            => 'Monospace (Generic)',
				'Arial, Helvetica, sans-serif'         => 'Arial',
				'Verdana, Geneva, sans-serif'          => 'Verdana',
				'Trebuchet MS, sans-serif'             => 'Trebuchet MS',
				'Georgia, serif'                       => 'Georgia',
				'Times New Roman, serif'               => 'Times New Roman',
				'Courier New, monospace'               => 'Courier New',
				'Inter, system-ui, sans-serif'         => 'Inter (Modern)',
				'Segoe UI, Tahoma, sans-serif'         => 'Segoe UI',
				'Helvetica Neue, Helvetica, Arial, sans-serif' => 'Helvetica Neue',
				'Palatino Linotype, serif'             => 'Palatino',
			),
		)
	);

	// 4. Typography Size Controls
	$typography_defaults = array(
		'body_font_size' => '16px',
		'h1_font_size'   => '36px',
		'h2_font_size'   => '30px',
		'h3_font_size'   => '24px',
		'h4_font_size'   => '20px',
		'h5_font_size'   => '18px',
		'h6_font_size'   => '16px',
	);

	foreach ( $typography_defaults as $setting_id => $default_size ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $default_size,
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$label_text = ( $setting_id === 'body_font_size' ) ? 'Body Font Size' : strtoupper( str_replace( '_font_size', '', $setting_id ) ) . ' Font Size';

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'       => __( $label_text, 'lightshadestudioworks' ),
				'description' => __( 'Enter value with unit (e.g., 16px, 1rem, 2.5rem)', 'lightshadestudioworks' ),
				'section'     => 'site_layout_section',
				'type'        => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'lightshadestudioworks_register_full_customizer' );

/**
 * Check if CTA button is enabled callback helper
 */
function is_button_enabled( $control ) {
	return $control->manager->get_setting( 'navbar_show_button' )->value() == true;
}

/**
 * Check if Navbar Custom Logo brand type is selected
 */
function is_navbar_custom_logo_enabled( $control ) {
	return $control->manager->get_setting( 'navbar_brand_type' )->value() === 'custom_logo';
}

/**
 * Check if Site Title brand type is selected
 */
function is_navbar_site_title_enabled( $control ) {
	return $control->manager->get_setting( 'navbar_brand_type' )->value() === 'site_title';
}

/**
 * Check if the active layout supports shared cta hover layout choices
 */
function is_shared_cta_hover_layout( $control ) {
	if ( ! is_button_enabled( $control ) ) {
		return false;
	}

	$layout = $control->manager->get_setting( 'navbar_layout_choice' )->value();
	return in_array( $layout, array( 'lsw_menu_layout_1', 'lsw_menu_layout_3', 'lsw_menu_layout_5' ), true );
}

/**
 * Enqueue live preview customizer scripts
 */
function lightshadestudioworks_theme_customizer_live_preview() {
	wp_enqueue_script( 'customize-preview' );

	$schemes      = array(
		'scheme_1' => array( '#f0f8ff', '#005a8c', '#ff4444', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_2' => array( '#d2ebd0', '#3b6b3a', '#f1c40f', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_3' => array( '#f3f6f6', '#36777d', '#33cca7', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_4' => array( '#ffffff', '#000000', '#c5a059', '#dcdcdc', '#454545', '#f2f2f7' ),
	);
	$json_schemes = json_encode( $schemes );

	$script = "(function() {
	if (!window.wp || !window.wp.customize) {
		return;
	}
	const schemes = {$json_schemes};

	function updatePreviewVars(colors) {
		document.documentElement.style.setProperty('--color-60', colors[0]);
		document.documentElement.style.setProperty('--color-30', colors[1]);
		document.documentElement.style.setProperty('--color-10', colors[2]);
		document.documentElement.style.setProperty('--lsw-color-neutral-white', colors[3]);
		document.documentElement.style.setProperty('--lsw-color-neutral-black', colors[4]);
		document.documentElement.style.setProperty('--lsw-color-neutral-grey', colors[5]);
	}

	wp.customize('color_scheme_select', function(value) {
		value.bind(function(newval) {
			const colors = schemes[newval];
			if (!colors) return;
			updatePreviewVars(colors);
		});
	});

	wp.customize('primary_60', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--color-60', newval);
		});
	});

	wp.customize('secondary_30', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--color-30', newval);
		});
	});

	wp.customize('accent_10', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--color-10', newval);
		});
	});
	wp.customize('neutral_white', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-color-neutral-white', newval);
		});
	});
	wp.customize('neutral_black', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-color-neutral-black', newval);
		});
	});
	wp.customize('neutral_grey', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-color-neutral-grey', newval);
		});
	});

	wp.customize('body_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-body-size', newval);
		});
	});
	wp.customize('h1_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h1-size', newval);
		});
	});
	wp.customize('h2_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h2-size', newval);
		});
	});
	wp.customize('h3_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h3-size', newval);
		});
	});
	wp.customize('h4_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h4-size', newval);
		});
	});
	wp.customize('h5_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h5-size', newval);
		});
	});
	wp.customize('h6_font_size', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--lsw-h6-size', newval);
		});
	});

	wp.customize('navbar_bg_color', function(value) {
		value.bind(function(newval) {
			const navbar = document.querySelector('.lsw-navbar-container');
			if (navbar) {
				navbar.style.backgroundColor = newval;
			}
		});
	});
	wp.customize('footer_bg_color', function(value) {
		value.bind(function(newval) {
			const footer = document.getElementById('footer');
			if (footer) {
				footer.style.backgroundColor = newval;
			}
		});
	});
	wp.customize('display_footer_text', function(value) {
		value.bind(function(newval) {
			const footerContainer = document.querySelector('#footer .lsw-max-width-container');
			if (footerContainer) {
				footerContainer.style.textAlign = newval;
			}
		});
	});

	wp.customize('footer_text_color', function(value) {
		value.bind(function(newval) {
			const footer = document.getElementById('footer');
			const footerContainer = document.querySelector('#footer .lsw-max-width-container');
			if (footer) {
				footer.style.setProperty('color', newval, 'important');
			}
			if (footerContainer) {
				footerContainer.style.setProperty('color', newval, 'important');
			}
		});
	});

	wp.customize('footer_text', function(value) {
		value.bind(function(newval) {
			const footerContainer = document.querySelector('#footer .lsw-max-width-container');
			if (footerContainer) {
				footerContainer.innerHTML = newval;
			}
		});
	});

	function updateButtonPreview() {
		const buttons = document.querySelectorAll('.lsw-navbar-button');
		if (!buttons.length) return;

		const bgColor = wp.customize('navbar_btn_bg').get();
		const shadowX = wp.customize('navbar_btn_shadow_x').get() || 0;
		const shadowY = wp.customize('navbar_btn_shadow_y').get() || 10;
		const shadowBlur = wp.customize('navbar_btn_shadow_blur').get() || 15;
		const shadowColor = wp.customize('navbar_btn_shadow_color').get() || '#bfdbfe';
		const borderRadius = wp.customize('navbar_btn_radius').get() || 9999;
		const btnText = wp.customize('navbar_btn_text').get() || 'Book Now';

		buttons.forEach(btn => {
			btn.style.backgroundColor = bgColor;
			btn.style.boxShadow = shadowX + 'px ' + shadowY + 'px ' + shadowBlur + 'px ' + shadowColor;
			btn.style.borderRadius = borderRadius + 'px';
			btn.textContent = btnText;
		});
	}

	wp.customize('navbar_btn_text', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_bg', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_shadow_x', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_shadow_y', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_shadow_blur', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_shadow_color', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	wp.customize('navbar_btn_radius', function(value) {
		value.bind(function(newval) {
			updateButtonPreview();
		});
	});

	function updateButtonHoverPreview() {
		const styleId = 'navbar-btn-hover-style';
		let styleEl = document.getElementById(styleId);
		if (!styleEl) {
			styleEl = document.createElement('style');
			styleEl.id = styleId;
			document.head.appendChild(styleEl);
		}
		const enabled = wp.customize('navbar_btn_hover_enabled').get();
		if (!enabled) {
			styleEl.innerHTML = '';
			return;
		}
		const x = wp.customize('navbar_btn_hover_shadow_x').get() || 0;
		const y = wp.customize('navbar_btn_hover_shadow_y').get() || 0;
		const blur = wp.customize('navbar_btn_hover_shadow_blur').get() || 15;
		const color = wp.customize('navbar_btn_hover_shadow_color').get() || '#bfdbfe';
		const textColor = wp.customize('navbar_btn_hover_text_color').get() || '#ffffff';
		styleEl.innerHTML = `.lsw-navbar-button:hover { box-shadow: \${x}px \${y}px \${blur}px \${color} !important; color: \${textColor} !important; }`;
	}

	wp.customize('navbar_btn_hover_enabled', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	wp.customize('navbar_btn_hover_shadow_x', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	wp.customize('navbar_btn_hover_shadow_y', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	wp.customize('navbar_btn_hover_shadow_blur', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	wp.customize('navbar_btn_hover_shadow_color', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	wp.customize('navbar_btn_hover_text_color', function(value) {
		value.bind(function(newval) {
			updateButtonHoverPreview();
		});
	});
	
	wp.customize('lssw_btn_hover_effect', function(value) {
		value.bind(function(newval) {
			const buttons = document.querySelectorAll('.wp-block-button');
			buttons.forEach(btn => {
				btn.classList.remove('btn-effect-none', 'btn-effect-internal-fill', 'btn-effect-magnetic', 'btn-effect-shutter-reveal', 'btn-effect-pulse-wave', 'btn-effect-staggered-radial-ripple');
				
				if (newval !== 'none') {
					btn.classList.add('btn-effect-none');
					btn.classList.add('btn-effect-' + newval);
				}
			});
			
			const linkId = 'lssw-btn-preview-css';
			let link = document.getElementById(linkId);
			if (!link) {
				link = document.createElement('link');
				link.id = linkId;
				link.rel = 'stylesheet';
				document.head.appendChild(link);
			}
			
			const paths = {
				'internal-fill': 'style-internal-fill.css',
				'magnetic': 'style-magnatic-border-expansion.css',
				'shutter-reveal': 'style-shutter-reveal.css',
				'pulse-wave': 'style-pulse-wave.css',
				'staggered-radial-ripple': 'style-staggered-radial-ripple.css'
			};
			
			if (paths[newval]) {
				link.href = '<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/' + paths[newval];
			} else {
				link.href = '';
			}
		});
	});

	function updateCustomizerStyles() {
		const styleId = 'customizer-dynamic-navbar-styles';
		let styleEl = document.getElementById(styleId);
		if (!styleEl) {
			styleEl = document.createElement('style');
			styleEl.id = styleId;
			document.head.appendChild(styleEl);
		}
		
		const sleekHoverColor = wp.customize('sleek_navbar_hover_color').get() || '#2563eb';
		const sleekHoverLine = wp.customize('sleek_navbar_hover_line_color').get() || '#2563eb';
		const modernHoverColor = wp.customize('modern_navbar_hover_color').get() || '#000000';
		const modernHoverLine = wp.customize('modern_navbar_hover_line_color').get() || '#000000';
		const commonHoverColor = wp.customize('common_navbar_hover_color').get() || '#2563eb';
		const globalActiveLink = wp.customize('global_active_link_color').get() || '#c5a059';
		
		styleEl.innerHTML = `
			:root {
				--sleek-nav-hover-color: \${sleekHoverColor};
				--sleek-nav-hover-line-color: \${sleekHoverLine};
				--modern-nav-hover-color: \${modernHoverColor};
				--modern-nav-hover-line-color: \${modernHoverLine};
				--common-nav-hover-color: \${commonHoverColor};
				--global-active-link-color: \${globalActiveLink};
			}
			a:active,
			.lsw-active-link {
				color: \${globalActiveLink} !important;
			}
			.lsw-nav-menu-link:hover {
				color: \${commonHoverColor} !important;
			}
		`;
	}
	
	wp.customize('sleek_navbar_hover_color', function(value) { value.bind(updateCustomizerStyles); });
	wp.customize('sleek_navbar_hover_line_color', function(value) { value.bind(updateCustomizerStyles); });
	wp.customize('modern_navbar_hover_color', function(value) { value.bind(updateCustomizerStyles); });
	wp.customize('modern_navbar_hover_line_color', function(value) { value.bind(updateCustomizerStyles); });
	wp.customize('common_navbar_hover_color', function(value) { value.bind(updateCustomizerStyles); });
	wp.customize('global_active_link_color', function(value) { value.bind(updateCustomizerStyles); });

	updateCustomizerStyles();
	updateButtonHoverPreview();
	updateButtonPreview();
})();";

	wp_add_inline_script( 'customize-preview', $script );
}
add_action( 'customize_preview_init', 'lightshadestudioworks_theme_customizer_live_preview' );

/**
 * Enqueue settings customizer controls helpers
 */
function lightshadestudioworks_theme_customizer_controls_preview() {
	wp_enqueue_script( 'customize-controls' );

	$schemes = array(
		'scheme_1' => array( '#f0f8ff', '#005a8c', '#ff4444', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_2' => array( '#d2ebd0', '#3b6b3a', '#f1c40f', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_3' => array( '#f3f6f6', '#36777d', '#33cca7', '#dcdcdc', '#454545', '#f2f2f7' ),
		'scheme_4' => array( '#ffffff', '#000000', '#c5a059', '#dcdcdc', '#454545', '#f2f2f7' ),
	);
	$json_schemes = json_encode( $schemes );

	$script = "(function() {
	if (!window.wp || !window.wp.customize) {
		return;
	}
	const schemes = {$json_schemes};

	wp.customize('color_scheme_select', function(value) {
		value.bind(function(newval) {
			const colors = schemes[newval];
			if (!colors) return;

			['primary_60', 'secondary_30', 'accent_10', 'neutral_white', 'neutral_black', 'neutral_grey'].forEach(function(id, index) {
				const control = wp.customize.control(id);
				if (!control) return;

				control.setting.set(colors[index]);

				const \$input = control.container.find('input.wp-color-picker');
				if (\$input.length && typeof \$input.wpColorPicker === 'function') {
					\$input.wpColorPicker('color', colors[index]);
				} else {
					control.container.find('input[type=text]').val(colors[index]);
				}
			});
		});
	});

	window.lswResetCustomizerSettings = function() {
		if (confirm('Are you sure you want to reset all theme settings? This will delete all custom layout, color, and typography configurations and restore defaults.')) {
			const btn = document.querySelector('.lsw-reset-customizer-btn');
			if (btn) {
				btn.disabled = true;
				btn.style.color = '#94a3b8';
				btn.style.borderColor = '#cbd5e1';
				btn.style.backgroundColor = '#f1f5f9';
				btn.innerText = 'Resetting...';
			}
			
			jQuery.post(ajaxurl, {
				action: 'lsw_reset_customizer_settings',
				nonce: window.lsw_reset_obj ? window.lsw_reset_obj.nonce : ''
			}, function(response) {
				if (response.success) {
					window.location.reload();
				} else {
					alert('Reset failed: ' + (response.data || 'Unknown error'));
					if (btn) {
						btn.disabled = false;
						btn.style.color = '#ef4444';
						btn.style.borderColor = '#ef4444';
						btn.style.backgroundColor = '#fef2f2';
						btn.innerText = 'Reset Theme Settings';
					}
				}
			}).fail(function() {
				alert('Reset failed. Please try again.');
				if (btn) {
					btn.disabled = false;
					btn.style.color = '#ef4444';
					btn.style.borderColor = '#ef4444';
					btn.style.backgroundColor = '#fef2f2';
					btn.innerText = 'Reset Theme Settings';
				}
			});
		}
	};
	
	jQuery(document).ready(function($) {
		$(document).on('click', '.lsw-reset-customizer-btn', function(e) {
			e.preventDefault();
			window.lswResetCustomizerSettings();
		});
	});
})();";

	wp_add_inline_script( 'customize-controls', $script );
}
add_action( 'customize_controls_enqueue_scripts', 'lightshadestudioworks_theme_customizer_controls_preview' );

/**
 * Filter menu link attributes to add custom classes
 */
function add_additional_class_on_a( $classes, $item, $args ) {
	if ( isset( $args->link_class ) ) {
		$classes['class'] = $args->link_class;
	}
	return $classes;
}
add_filter( 'nav_menu_link_attributes', 'add_additional_class_on_a', 10, 3 );

/**
 * Sanitize checkboxes helper callback
 */
function my_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

<?php
/**
 * Theme Dynamic CSS and Styles Enqueuing
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue standard theme style.css
 */
function lsw_enqueue_styles() {
	wp_enqueue_style( 'lsw-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'lsw_enqueue_styles' );

/**
 * Enqueue typography custom styles from customizer
 */
function lightshadestudioworks_enqueue_typography_css() {
	$body_sz = get_theme_mod( 'body_font_size', '16px' );
	$h1_sz   = get_theme_mod( 'h1_font_size', '36px' );
	$h2_sz   = get_theme_mod( 'h2_font_size', '30px' );
	$h3_sz   = get_theme_mod( 'h3_font_size', '24px' );
	$h4_sz   = get_theme_mod( 'h4_font_size', '20px' );
	$h5_sz   = get_theme_mod( 'h5_font_size', '18px' );
	$h6_sz   = get_theme_mod( 'h6_font_size', '16px' );

	$css = "
	:root {
		--lsw-body-size: {$body_sz};
		--lsw-h1-size: {$h1_sz};
		--lsw-h2-size: {$h2_sz};
		--lsw-h3-size: {$h3_sz};
		--lsw-h4-size: {$h4_sz};
		--lsw-h5-size: {$h5_sz};
		--lsw-h6-size: {$h6_sz};
	}

	.site-main, .site-content, .entry-content, .wp-block-post-content, .editor-styles-wrapper {
		font-size: var(--lsw-body-size) !important;
	}
	.site-main h1, .site-content h1, .entry-content h1, .wp-block-post-content h1, .editor-styles-wrapper h1 {
		font-size: var(--lsw-h1-size) !important;
	}
	.site-main h2, .site-content h2, .entry-content h2, .wp-block-post-content h2, .editor-styles-wrapper h2 {
		font-size: var(--lsw-h2-size) !important;
	}
	.site-main h3, .site-content h3, .entry-content h3, .wp-block-post-content h3, .editor-styles-wrapper h3 {
		font-size: var(--lsw-h3-size) !important;
	}
	.site-main h4, .site-content h4, .entry-content h4, .wp-block-post-content h4, .editor-styles-wrapper h4 {
		font-size: var(--lsw-h4-size) !important;
	}
	.site-main h5, .site-content h5, .entry-content h5, .wp-block-post-content h5, .editor-styles-wrapper h5 {
		font-size: var(--lsw-h5-size) !important;
	}
	.site-main h6, .site-content h6, .entry-content h6, .wp-block-post-content h6, .editor-styles-wrapper h6 {
		font-size: var(--lsw-h6-size) !important;
	}
	";

	wp_register_style( 'lightshadestudioworks-typography-vars', false );
	wp_enqueue_style( 'lightshadestudioworks-typography-vars' );
	wp_add_inline_style( 'lightshadestudioworks-typography-vars', $css );
}
add_action( 'wp_enqueue_scripts', 'lightshadestudioworks_enqueue_typography_css' );
add_action( 'admin_enqueue_scripts', 'lightshadestudioworks_enqueue_typography_css' );
add_action( 'enqueue_block_editor_assets', 'lightshadestudioworks_enqueue_typography_css' );

/**
 * Retrieve active color scheme values
 */
function lightshadestudioworks_get_color_scheme_values() {
	$scheme  = get_theme_mod( 'color_scheme_select', 'scheme_1' );
	$schemes = array(
		'scheme_1' => array( '#F4F4F4', '#8A6344', '#EEC537', '#ECECEB', '#FFFFFF', '#8A6344', '#57534E', '#FFFFFF', '#1E1E1E', '#57534E' ),
		'scheme_2' => array( '#F4F4F4', '#EDD28B', '#1B87EA', '#FAF9F5', '#ECE8E1', '#1E1E1E', '#57534E', '#FAF9F5', '#1E1E1E', '#57534E' ),
		'scheme_3' => array( '#1B1B25', '#34364B', '#7EAD1F', '#21212F', '#2E3043', '#FFFFFF', '#D1D3E0', '#34364B', '#1B1B25', '#21212F' ),
		'scheme_4' => array( '#E2CBAF', '#869E6A', '#C76026', '#DFC6AA', '#EFE3D3', '#1F1914', '#332A22', '#FFFFFF', '#1F1914', '#DFC6AA' ),
		'scheme_5' => array( '#F8F7F5', '#F79A1F', '#EB4F47', '#FFF7EB', '#FAFAFA', '#1C1917', '#57534E', '#FFFFFF', '#1C1917', '#F79A1F' ),
	);

	$defaults = isset( $schemes[ $scheme ] ) ? $schemes[ $scheme ] : $schemes['scheme_1'];
	$saved_scheme = get_theme_mod( 'active_color_scheme_applied', '' );

	// If scheme selection has changed or is not yet saved, use active scheme defaults
	if ( $saved_scheme !== $scheme ) {
		return $defaults;
	}

	$c60           = get_theme_mod( 'primary_60', $defaults[0] );
	$c30           = get_theme_mod( 'secondary_30', $defaults[1] );
	$c10           = get_theme_mod( 'accent_10', $defaults[2] );
	$sec_l1        = get_theme_mod( 'secondary_light_1', $defaults[3] );
	$sec_l2        = get_theme_mod( 'secondary_light_2', $defaults[4] );
	$text_heading  = get_theme_mod( 'text_heading', $defaults[5] );
	$text_body     = get_theme_mod( 'text_body', $defaults[6] );
	$neutral_white = get_theme_mod( 'neutral_white', $defaults[7] );
	$neutral_black = get_theme_mod( 'neutral_black', $defaults[8] );
	$neutral_grey  = get_theme_mod( 'neutral_grey', $defaults[9] );

	return array( $c60, $c30, $c10, $sec_l1, $sec_l2, $text_heading, $text_body, $neutral_white, $neutral_black, $neutral_grey );
}

/**
 * Automatically update individual color theme mods when Base Color Scheme is saved
 */
function lightshadestudioworks_sync_color_scheme_on_save() {
	$scheme  = get_theme_mod( 'color_scheme_select', 'scheme_1' );
	$schemes = array(
		'scheme_1' => array( '#F4F4F4', '#8A6344', '#EEC537', '#ECECEB', '#FFFFFF', '#8A6344', '#57534E', '#FFFFFF', '#1E1E1E', '#57534E' ),
		'scheme_2' => array( '#F4F4F4', '#EDD28B', '#1B87EA', '#FAF9F5', '#ECE8E1', '#1E1E1E', '#57534E', '#FAF9F5', '#1E1E1E', '#57534E' ),
		'scheme_3' => array( '#1B1B25', '#34364B', '#7EAD1F', '#21212F', '#2E3043', '#FFFFFF', '#D1D3E0', '#34364B', '#1B1B25', '#21212F' ),
		'scheme_4' => array( '#E2CBAF', '#869E6A', '#C76026', '#DFC6AA', '#EFE3D3', '#1F1914', '#332A22', '#FFFFFF', '#1F1914', '#DFC6AA' ),
		'scheme_5' => array( '#F8F7F5', '#F79A1F', '#EB4F47', '#FFF7EB', '#FAFAFA', '#1C1917', '#57534E', '#FFFFFF', '#1C1917', '#F79A1F' ),
	);

	if ( isset( $schemes[ $scheme ] ) ) {
		$defaults = $schemes[ $scheme ];
		set_theme_mod( 'primary_60', $defaults[0] );
		set_theme_mod( 'secondary_30', $defaults[1] );
		set_theme_mod( 'accent_10', $defaults[2] );
		set_theme_mod( 'secondary_light_1', $defaults[3] );
		set_theme_mod( 'secondary_light_2', $defaults[4] );
		set_theme_mod( 'text_heading', $defaults[5] );
		set_theme_mod( 'text_body', $defaults[6] );
		set_theme_mod( 'neutral_white', $defaults[7] );
		set_theme_mod( 'neutral_black', $defaults[8] );
		set_theme_mod( 'neutral_grey', $defaults[9] );
		set_theme_mod( 'active_color_scheme_applied', $scheme );
	}
}
add_action( 'customize_save_after', 'lightshadestudioworks_sync_color_scheme_on_save' );

/**
 * Enqueue dynamic color variable styles
 */
function lightshadestudioworks_enqueue_dynamic_css() {
	$scheme = get_theme_mod( 'color_scheme_select', 'scheme_1' );
	list( $c60, $c30, $c10, $sec_l1, $sec_l2, $text_heading, $text_body, $neutral_white, $neutral_black, $neutral_grey ) = lightshadestudioworks_get_color_scheme_values();

	$is_scheme_3 = ( 'scheme_3' === $scheme );
	$is_scheme_4 = ( 'scheme_4' === $scheme );
	$is_scheme_5 = ( 'scheme_5' === $scheme );

	// Card, input and surface styling according to 60-30-10 reference images
	$card_bg      = $is_scheme_3 ? '#34364B' : '#FFFFFF';
	$input_bg     = $is_scheme_3 ? '#34364B' : '#FFFFFF';
	$input_text   = $is_scheme_3 ? '#FFFFFF' : $text_body;
	$input_border = $is_scheme_3 ? '#474960' : ( $is_scheme_4 ? '#DFC6AA' : '#E5E7EB' );
	$placeholder  = $is_scheme_3 ? '#8E92A8' : '#9CA3AF';

	$css = ":root, html, body, .editor-styles-wrapper, .block-editor-iframe__body, .block-editor__container, .widgets-editor, .widgets-editor .editor-styles-wrapper, .edit-widgets, .edit-widgets .block-editor-wrapper, .wp-block-widgets { 
		--color-60: {$c60}; 
		--color-30: {$c30}; 
		--color-10: {$c10};
		--color-secondary-light-1: {$sec_l1};
		--color-secondary-light-2: {$sec_l2};
		--color-text-heading: {$text_heading};
		--color-text-body: {$text_body};
		--lsw-color-secondary-light-1: {$sec_l1};
		--lsw-color-secondary-light-2: {$sec_l2};
		--lsw-color-text-heading: {$text_heading};
		--lsw-color-text-body: {$text_body};
		--lsw-color-neutral-white: {$neutral_white};
		--lsw-color-neutral-black: {$neutral_black};
		--lsw-color-neutral-grey: {$neutral_grey};
		--color-neutral-white: {$neutral_white};
		--color-neutral-black: {$neutral_black};
		--color-neutral-grey: {$neutral_grey};
		--lsw-card-bg: {$card_bg};
		--lsw-input-bg: {$input_bg};
		--lsw-input-text: {$input_text};
		--lsw-input-border: {$input_border};
		--global-active-link-color: {$c10};
	}

	/* Canvas & Base Page Background */
	body, #wrapper, #container, .site-container {
		background-color: {$c60} !important;
		color: {$text_body};
	}

	/* Gutenberg Palette Utility Classes */
	.has-base-60-background-color { background-color: {$c60} !important; }
	.has-base-60-color { color: {$c60} !important; }
	.has-base-30-background-color { background-color: {$c30} !important; }
	.has-base-30-color { color: {$c30} !important; }
	.has-base-10-background-color { background-color: {$c10} !important; }
	.has-base-10-color { color: {$c10} !important; }
	.has-secondary-light-1-background-color { background-color: {$sec_l1} !important; }
	.has-secondary-light-1-color { color: {$sec_l1} !important; }
	.has-secondary-light-2-background-color { background-color: {$sec_l2} !important; }
	.has-secondary-light-2-color { color: {$sec_l2} !important; }
	.has-text-heading-color { color: {$text_heading} !important; }
	.has-text-body-color { color: {$text_body} !important; }
	.has-neutral-white-background-color { background-color: {$neutral_white} !important; }
	.has-neutral-white-color { color: {$neutral_white} !important; }
	.has-neutral-black-background-color { background-color: {$neutral_black} !important; }
	.has-neutral-black-color { color: {$neutral_black} !important; }
	.has-neutral-grey-background-color { background-color: {$neutral_grey} !important; }
	.has-neutral-grey-color { color: {$neutral_grey} !important; }

	/* Text Heading & Body Default Color Rules */
	.has-text-heading-color,
	.has-base-60-background-color h1,
	.has-base-60-background-color h2,
	.has-base-60-background-color h3,
	.has-base-60-background-color h4,
	.has-base-60-background-color h5,
	.has-base-60-background-color h6,
	.has-base-60-background-color .wp-block-heading,
	.has-secondary-light-1-background-color h1,
	.has-secondary-light-1-background-color h2,
	.has-secondary-light-1-background-color h3,
	.has-secondary-light-1-background-color h4,
	.has-secondary-light-1-background-color h5,
	.has-secondary-light-1-background-color h6,
	.has-secondary-light-1-background-color .wp-block-heading,
	.has-secondary-light-2-background-color h1,
	.has-secondary-light-2-background-color h2,
	.has-secondary-light-2-background-color h3,
	.has-secondary-light-2-background-color h4,
	.has-secondary-light-2-background-color h5,
	.has-secondary-light-2-background-color h6,
	.has-secondary-light-2-background-color .wp-block-heading,
	.has-base-30-color.wp-block-heading,
	h1.has-base-30-color,
	h2.has-base-30-color,
	h3.has-base-30-color,
	h4.has-base-30-color {
		color: {$text_heading} !important;
	}

	/* Card Surfaces: Axis Folio */
	.portfolio-item-card,
	div[id] .portfolio-item-card,
	[id^='af-'] .portfolio-item-card,
	#wrapper .portfolio-item-card,
	#wrapper [id^='af-'] .portfolio-item-card,
	#wrapper div[id] .portfolio-item-card,
	body #wrapper div[id] .portfolio-item-card,
	body #wrapper div[id] .portfolio-item-card:hover {
		background: {$card_bg} !important;
		background-color: {$card_bg} !important;
		border-radius: 8px !important;
	}
	.portfolio-item-card .portfolio-title,
	.portfolio-item-card h2,
	.portfolio-item-card h3,
	.portfolio-item-card h4 {
		color: {$text_heading} !important;
	}
	.portfolio-item-card .portfolio-description,
	.portfolio-item-card p,
	.portfolio-item-card .portfolio-tags {
		color: {$text_body} !important;
	}

	/* Card Surfaces: Query Loop / Blog Posts */
	.wp-block-query .wp-block-post-template > li,
	.wp-block-query .wp-block-post-template .wp-block-group {
		background-color: {$card_bg} !important;
		border-radius: 8px;
		transition: all 0.3s ease;
		overflow: hidden;
	}
	" . ( $is_scheme_3 ? "
	.wp-block-query .wp-block-post-template > li,
	.wp-block-query .wp-block-post-template .wp-block-group {
		padding: 0px;
	}
	.wp-block-query .wp-block-post-template h3,
	.wp-block-query .wp-block-post-template h3 a,
	.wp-block-query .wp-block-post-template .wp-block-post-title a {
		color: #ffffff !important;
	}
	.wp-block-query .wp-block-post-template p {
		color: {$text_body} !important;
	}
	" : "" ) . "
	" . ( $is_scheme_5 ? "
	.wp-block-query .wp-block-post-template .wp-block-post-time-to-read,
	.wp-block-query .wp-block-post-template p.has-small-font-size,
	.wp-block-query .wp-block-post-template .wp-block-group > div:first-child p {
		color: #57534E !important;
	}
	" : "" ) . "

	/* Blog Links & Pagination */
	.wp-block-post-excerpt__more-link,
	.wp-block-query-pagination a,
	.wp-block-query-pagination-numbers .page-numbers.current,
	.has-base-10-color,
	.has-base-10-color a {
		color: {$c10} !important;
	}
	.wp-block-query-pagination-numbers .page-numbers.current {
		border-bottom: 2px solid {$c10} !important;
	}

	/* Reach Out Info Cards (Home Page) */
	.lsw-reach-out-card {
		background-color: " . ( $is_scheme_3 ? '#34364B' : ( $is_scheme_5 ? '#FFF7EB' : ( $is_scheme_4 ? '#EFE3D3' : '#FFFFFF' ) ) ) . " !important;
		border-radius: 8px;
		padding: 30px 20px !important;
		box-shadow: 0 4px 12px rgba(0,0,0,0.05);
	}
	.lsw-reach-out-card h3 {
		color: {$text_heading} !important;
	}
	.lsw-reach-out-card p {
		color: {$text_body} !important;
	}

	/* Form & Button Dynamic Styling */
	.wpcf7 input[type='text'],
	.wpcf7 input[type='email'],
	.wpcf7 textarea,
	.wpcf7-form input[type='text'],
	.wpcf7-form input[type='email'],
	.wpcf7-form textarea {
		background-color: {$input_bg} !important;
		border: 1px solid {$input_border} !important;
		border-radius: 6px !important;
		padding: 14px 18px !important;
		width: 100% !important;
		color: {$input_text} !important;
		outline: none;
	}
	.wpcf7 input[type='text']::placeholder,
	.wpcf7 input[type='email']::placeholder,
	.wpcf7 textarea::placeholder {
		color: {$placeholder} !important;
	}
	.wpcf7-form input[type='submit'],
	.wpcf7 input[type='submit'],
	.wpcf7-submit,
	.wp-block-button__link.has-base-10-background-color,
	.wp-block-button__link {
		background-color: {$c10} !important;
		color: #ffffff !important;
		border: none !important;
	}

	/* Floating Navbar Styling */
	.lsw-navbar-container,
	.lsw-navbar-wrapper nav {
		background-color: #ffffff !important;
	}
	.lsw-nav-menu-link,
	.lsw-navbar-container a {
		color: #111111 !important;
	}
	.lsw-active-link,
	.current-menu-item > a,
	.current_page_item > a {
		color: {$c10} !important;
		border-bottom-color: {$c10} !important;
	}
	.hover-underline::after {
		background-color: {$c10} !important;
	}";

	// Scheme 1 (Warm Urban Minimal): Force Learn/Discover/Explore links and Read More links to warm brown #8A6344
	if ( 'scheme_1' === $scheme ) {
		$css .= "
	/* Scheme 1: Learn, Discover, Explore action links */
	.has-base-10-color a,
	p.has-base-10-color a {
		color: #8A6344 !important;
	}
	/* Scheme 1: Stories Read More links */
	.wp-block-post-excerpt__more-link {
		color: #8A6344 !important;
	}
	";
	}

	// Scheme 2 (Golden Hour Executive): Targeted color overrides
	if ( 'scheme_2' === $scheme ) {
		$css .= "
	/* Scheme 2: 'Ten years of mastering light...' tagline */
	p.has-base-30-color.has-medium-font-size,
	.has-base-30-color.has-medium-font-size {
		color: #57534E !important;
	}

	/* Scheme 2: Learn, Discover, Explore action links */
	.has-base-10-color a,
	p.has-base-10-color a {
		color: #EDD28B !important;
	}

	/* Scheme 2: Stories post list - post title headings */
	.wp-block-query .wp-block-post-title,
	.wp-block-query .wp-block-post-title a {
		color: #1E1E1E !important;
	}

	/* Scheme 2: Stories Read More links */
	.wp-block-post-excerpt__more-link {
		color: #57534E !important;
	}
	";

		// Page-specific heading color: #C5A059 — injected only for the relevant page
		$heading_pages = array( 'about', 'portfolio', 'portfolio-gallery', 'blog', 'contact',
			'wedding-celebrations', 'portrait-sessions', 'candid-moments', 'real-estate-spaces' );

		if ( ! is_admin() && is_page( $heading_pages ) ) {
			if ( is_page( 'blog' ) ) {
				// Blog: headings #C5A059 but post list titles stay dark
				$css .= "
	/* Scheme 2 - Blog: section headings */
	.wp-block-heading {
		color: #ffffffff !important;
	}
	/* Blog: restore post title headings in query loop */
	.wp-block-query .wp-block-post-title,
	.wp-block-query .wp-block-post-title a {
		color: #1E1E1E !important;
	}
	";
			} else {
				// All other pages: all headings #C5A059
				$css .= "
	/* Scheme 2 - Page headings */
	.wp-block-heading {
		color: #ffffffff !important;
	}
	";

			}
		}
	}

	// Scheme 3 (Midnight Botanical): Targeted color overrides
	if ( 'scheme_3' === $scheme ) {
		$css .= "
	/* Scheme 3 - Home: 'Capturing timeless moments with Precision' color #FFFFFF */
	.lsw-scheme-3.lsw-page-home .wp-block-cover h1,
	.lsw-scheme-3.lsw-page-home h1.wp-block-heading,
	.lsw-scheme-3.lsw-page-home .wp-block-heading.has-secondary-light-2-color,
	.lsw-scheme-3.lsw-page-home .wp-block-heading.has-base-10-color,
	.lsw-scheme-3.home .wp-block-cover h1,
	.lsw-scheme-3.home h1.wp-block-heading,
	.lsw-scheme-3.front-page .wp-block-cover h1,
	.lsw-scheme-3.front-page h1.wp-block-heading {
		color: #FFFFFF !important;
	}

	/* Scheme 3 - Home: 'A decade of dedicated experience behind the lens...' subtitle text color #FFFFFF */
	.lsw-scheme-3.lsw-page-home .wp-block-cover p.has-base-60-color,
	.lsw-scheme-3.lsw-page-home p.has-base-60-color,
	.lsw-scheme-3.home .wp-block-cover p.has-base-60-color,
	.lsw-scheme-3.front-page .wp-block-cover p.has-base-60-color {
		color: #FFFFFF !important;
	}

	/* Scheme 3 - Home: 'Ten years of mastering light...' text color #FFFFFF */
	.lsw-scheme-3 p.has-base-30-color.has-medium-font-size,
	.lsw-scheme-3 .has-base-30-color.has-medium-font-size {
		color: #FFFFFF !important;
	}

	/* Scheme 3 - Home: Icons color for Our History, Our Philosophy, Our Collaborative Approach -> #7EAD1F */
	.lsw-scheme-3.lsw-page-home .wp-block-icon,
	.lsw-scheme-3.lsw-page-home .wp-block-icon svg,
	.lsw-scheme-3.lsw-page-home .wp-block-icon svg path,
	.lsw-scheme-3.home .wp-block-icon,
	.lsw-scheme-3.home .wp-block-icon svg,
	.lsw-scheme-3.home .wp-block-icon svg path,
	.lsw-scheme-3.front-page .wp-block-icon,
	.lsw-scheme-3.front-page .wp-block-icon svg,
	.lsw-scheme-3.front-page .wp-block-icon svg path {
		color: #7EAD1F !important;
		fill: #7EAD1F !important;
	}

	/* Scheme 3 - Home: Background color of inside axis folio cards -> #34364B */
	.lsw-scheme-3 .portfolio-item-card,
	.lsw-scheme-3 div[id] .portfolio-item-card,
	.lsw-scheme-3 [id^='af-'] .portfolio-item-card,
	#wrapper .lsw-scheme-3 .portfolio-item-card,
	#wrapper .lsw-scheme-3 [id^='af-'] .portfolio-item-card,
	#wrapper .lsw-scheme-3 div[id] .portfolio-item-card,
	body #wrapper .lsw-scheme-3 div[id] .portfolio-item-card,
	body #wrapper .lsw-scheme-3 .portfolio-item-card:hover,
	body #wrapper .lsw-scheme-3 [id^='af-'] .portfolio-item-card:hover,
	body #wrapper .lsw-scheme-3 div[id] .portfolio-item-card:hover {
		background-color: #34364B !important;
		background: #34364B !important;
	}

	/* Scheme 3 - Home: Text inside tags -> #D1D3E0 */
	.lsw-scheme-3.lsw-page-home .portfolio-tags .tag,
	.lsw-scheme-3.lsw-page-home .portfolio-tags span,
	.lsw-scheme-3.home .portfolio-tags .tag,
	.lsw-scheme-3.home .portfolio-tags span,
	.lsw-scheme-3.front-page .portfolio-tags .tag,
	.lsw-scheme-3.front-page .portfolio-tags span,
	#wrapper .lsw-scheme-3 .portfolio-tags .tag,
	#wrapper .lsw-scheme-3 .portfolio-tags span,
	body #wrapper .lsw-scheme-3 .portfolio-tags .tag,
	body #wrapper .lsw-scheme-3 .portfolio-tags span {
		color: #D1D3E0 !important;
	}

	/* Scheme 3 - About: Shutter icon's color -> #7EAD1F */
	.lsw-scheme-3.lsw-page-about .custom-shutter-container svg,
	.lsw-scheme-3.lsw-page-about .custom-shutter-container svg path,
	.lsw-scheme-3 .custom-shutter-container svg,
	.lsw-scheme-3 .custom-shutter-container svg path {
		color: #7EAD1F !important;
		fill: #7EAD1F !important;
	}

	/* Scheme 3 - Portfolio Gallery: All cards color of axis folio -> #34364B */
	.lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-item-card,
	.lsw-scheme-3.lsw-page-portfolio-gallery div[id] .portfolio-item-card,
	#wrapper .lsw-scheme-3.lsw-page-portfolio-gallery div[id] .portfolio-item-card,
	body #wrapper .lsw-scheme-3.lsw-page-portfolio-gallery div[id] .portfolio-item-card,
	body #wrapper .lsw-scheme-3.lsw-page-portfolio-gallery div[id] .portfolio-item-card:hover {
		background-color: #34364B !important;
		background: #34364B !important;
	}

	/* Scheme 3 - Portfolio Gallery: Tags background color in axis folio -> #1B1B25 and text color -> #D1D3E0 */
	.lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags .tag,
	.lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags span,
	#wrapper .lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags .tag,
	#wrapper .lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags span,
	body #wrapper .lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags .tag,
	body #wrapper .lsw-scheme-3.lsw-page-portfolio-gallery .portfolio-tags span {
		background-color: #1B1B25 !important;
		background: #1B1B25 !important;
		color: #D1D3E0 !important;
	}
	";
	}


	wp_register_style( 'lightshadestudioworks-dynamic-vars', false );
	wp_enqueue_style( 'lightshadestudioworks-dynamic-vars' );
	wp_add_inline_style( 'lightshadestudioworks-dynamic-vars', $css );
}
add_action( 'wp_enqueue_scripts', 'lightshadestudioworks_enqueue_dynamic_css' );
add_action( 'admin_enqueue_scripts', 'lightshadestudioworks_enqueue_dynamic_css' );
add_action( 'enqueue_block_editor_assets', 'lightshadestudioworks_enqueue_dynamic_css' );

/**
 * Generate container, font and footer dynamic CSS
 */
function lsw_generate_dynamic_css() {
	$width             = get_theme_mod( 'site_container_width', 1200 );
	$container_pt      = get_theme_mod( 'site_container_padding_top', 0 );
	$container_pr      = get_theme_mod( 'site_container_padding_right', 20 );
	$container_pb      = get_theme_mod( 'site_container_padding_bottom', 0 );
	$container_pl      = get_theme_mod( 'site_container_padding_left', 20 );
	$font              = get_theme_mod( 'site_font_family', 'Inter' );
	$heading_font      = get_theme_mod( 'site_heading_font_family', 'Inter' );
	$footer_bg         = get_theme_mod( 'footer_bg_color', '#ffffff' );
	$footer_text_color = get_theme_mod( 'footer_text_color', '#ffffff' );

	$css = "
		.lsw-max-width-container { 
			max-width: {$width}px !important; 
			margin-left: auto; 
			margin-right: auto;
			padding: {$container_pt}px {$container_pr}px {$container_pb}px {$container_pl}px;
		}
		body { font-family: {$font}; }
		h1, h2, h3, h4, h5, h6, .wp-block-heading { font-family: {$heading_font}; }
		#footer {
			background-color: {$footer_bg} !important;
		}
		#footer .lsw-max-width-container {
			color: {$footer_text_color} !important;
		}
	";

	$hover_enabled    = get_theme_mod( 'navbar_btn_hover_enabled', 1 );
	$hover_text_color = get_theme_mod( 'navbar_btn_hover_text_color', '#ffffff' );
	if ( $hover_enabled ) {
		$hx    = get_theme_mod( 'navbar_btn_hover_shadow_x', 0 );
		$hy    = get_theme_mod( 'navbar_btn_hover_shadow_y', 0 );
		$hblur = get_theme_mod( 'navbar_btn_hover_shadow_blur', 15 );
		$hcolor = get_theme_mod( 'navbar_btn_hover_shadow_color', '#bfdbfe' );
		$css  .= "
			.lsw-navbar-button:hover {
				box-shadow: {$hx}px {$hy}px {$hblur}px {$hcolor} !important;
				color: {$hover_text_color} !important;
			}
		";
	}

	$sleek_hover_color        = get_theme_mod( 'sleek_navbar_hover_color', '#2563eb' );
	$sleek_hover_line_color   = get_theme_mod( 'sleek_navbar_hover_line_color', '#2563eb' );
	$modern_hover_color       = get_theme_mod( 'modern_navbar_hover_color', '#000000' );
	$modern_hover_line_color  = get_theme_mod( 'modern_navbar_hover_line_color', '#000000' );
	$common_hover_color       = get_theme_mod( 'common_navbar_hover_color', '#2563eb' );
	$global_active_link_color = get_theme_mod( 'global_active_link_color', '#c5a059' );

	$css .= "
		:root {
			--sleek-nav-hover-color: {$sleek_hover_color};
			--sleek-nav-hover-line-color: {$sleek_hover_line_color};
			--modern-nav-hover-color: {$modern_hover_color};
			--modern-nav-hover-line-color: {$modern_hover_line_color};
			--common-nav-hover-color: {$common_hover_color};
			--global-active-link-color: {$global_active_link_color};
		}
		a:active,
		.lsw-active-link {
			color: var(--global-active-link-color, #c5a059) !important;
		}
		.lsw-nav-menu-link:hover {
			color: var(--common-nav-hover-color, #2563eb) !important;
		}
	";

	if ( get_theme_mod( 'lsw_scroll_to_top_enabled', 1 ) ) {
		$stt_bg           = get_theme_mod( 'lsw_scroll_to_top_bg_color', '#c5a059' );
		$stt_text         = get_theme_mod( 'lsw_scroll_to_top_text_color', '#ffffff' );
		$stt_hover_bg     = get_theme_mod( 'lsw_scroll_to_top_hover_bg_color', '#b08d4a' );
		$stt_pt           = get_theme_mod( 'lsw_scroll_to_top_padding_top', 12 );
		$stt_pr           = get_theme_mod( 'lsw_scroll_to_top_padding_right', 12 );
		$stt_pb           = get_theme_mod( 'lsw_scroll_to_top_padding_bottom', 12 );
		$stt_pl           = get_theme_mod( 'lsw_scroll_to_top_padding_left', 12 );
		$stt_size         = get_theme_mod( 'lsw_scroll_to_top_size', 48 );
		$stt_radius       = get_theme_mod( 'lsw_scroll_to_top_border_radius', 50 );
		$stt_bottom       = get_theme_mod( 'lsw_scroll_to_top_bottom_offset', 24 );
		$stt_right        = get_theme_mod( 'lsw_scroll_to_top_right_offset', 24 );
		$stt_padding      = "{$stt_pt}px {$stt_pr}px {$stt_pb}px {$stt_pl}px";

		$css .= "
			#lsw-scroll-to-top {
				position: fixed;
				bottom: {$stt_bottom}px;
				right: {$stt_right}px;
				z-index: 9999;
				display: flex;
				align-items: center;
				justify-content: center;
				width: {$stt_size}px;
				height: {$stt_size}px;
				padding: {$stt_padding};
				border: none;
				border-radius: {$stt_radius}px;
				background-color: {$stt_bg};
				color: {$stt_text};
				cursor: pointer;
				opacity: 0;
				visibility: hidden;
				transform: translateY(12px);
				transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.2s ease;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			}
			#lsw-scroll-to-top.is-visible {
				opacity: 1;
				visibility: visible;
				transform: translateY(0);
			}
			#lsw-scroll-to-top:hover,
			#lsw-scroll-to-top:focus {
				background-color: {$stt_hover_bg};
				outline: none;
			}
			#lsw-scroll-to-top:focus-visible {
				outline: 2px solid {$stt_text};
				outline-offset: 2px;
			}
			#lsw-scroll-to-top svg {
				width: 20px;
				height: 20px;
				fill: currentColor;
				pointer-events: none;
			}
		";
	}

	wp_add_inline_style( 'lsw-style', $css );
}
add_action( 'wp_enqueue_scripts', 'lsw_generate_dynamic_css', 20 );

/**
 * Enqueue button hover effects CSS and inject inline configurations
 */
function lssw_enqueue_button_effect_css() {
	$effect = get_theme_mod( 'lssw_btn_hover_effect', 'none' );

	$map = array(
		'internal-fill'           => 'style-internal-fill.css',
		'magnetic'                => 'style-magnatic-border-expansion.css',
		'shutter-reveal'          => 'style-shutter-reveal.css',
		'pulse-wave'              => 'style-pulse-wave.css',
		'staggered-radial-ripple' => 'style-staggered-radial-ripple.css',
	);

	if ( isset( $map[ $effect ] ) ) {
		$handle = 'lssw-btn-' . $effect;
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . '/assets/css/' . $map[ $effect ],
			array(),
			filemtime( get_template_directory() . '/assets/css/' . $map[ $effect ] )
		);

		$custom_css = '';
		if ( $effect === 'internal-fill' ) {
			$normal_bg   = get_theme_mod( 'lssw_btn_internal_fill_normal_bg', '#333' );
			$normal_text = get_theme_mod( 'lssw_btn_internal_fill_normal_text', '#ffffff' );
			$hover_bg    = get_theme_mod( 'lssw_btn_internal_fill_hover_bg', '#ff3e3e' );

			$custom_css = "
				.btn-class { background-color: {$normal_bg} !important; }
				.btn-class a { color: {$normal_text} !important; }
				.btn-class::before { background-color: {$hover_bg} !important; }
			";
		} elseif ( $effect === 'magnetic' ) {
			$normal_bg    = get_theme_mod( 'lssw_btn_magnetic_normal_bg', '#1a1a1a' );
			$normal_text  = get_theme_mod( 'lssw_btn_magnetic_normal_text', '#ffffff' );
			$hover_bg     = get_theme_mod( 'lssw_btn_magnetic_hover_bg', '#007bff' );
			$hover_text   = get_theme_mod( 'lssw_btn_magnetic_hover_text', '#ffffff' );
			$border_inset = get_theme_mod( 'lssw_btn_magnetic_border_inset', '-2px' );
			$border_color = get_theme_mod( 'lssw_btn_magnetic_border_color', '#007bff' );

			if ( is_numeric( $border_inset ) ) {
				$border_inset .= 'px';
			}

			$custom_css = "
				.btn-class { background-color: {$normal_bg} !important; }
				.btn-class a { color: {$normal_text} !important; }
				.btn-class:hover { background-color: {$hover_bg} !important; }
				.btn-class:hover a { color: {$hover_text} !important; }
				.btn-class::before { inset: {$border_inset} !important; border-color: {$border_color} !important; }
			";
		} elseif ( $effect === 'shutter-reveal' ) {
			$normal_bg   = get_theme_mod( 'lssw_btn_shutter_normal_bg', '#222' );
			$normal_text = get_theme_mod( 'lssw_btn_shutter_normal_text', '#ffffff' );
			$hover_bg    = get_theme_mod( 'lssw_btn_shutter_hover_bg', '#007bff' );

			$custom_css = "
				.btn-class { background-color: {$normal_bg} !important; }
				.btn-class a { color: {$normal_text} !important; }
				.btn-class::before { background-color: {$hover_bg} !important; }
			";
		} elseif ( $effect === 'pulse-wave' ) {
			$normal_bg      = get_theme_mod( 'lssw_btn_pulse_wave_normal_bg', '#2d3436' );
			$normal_text    = get_theme_mod( 'lssw_btn_pulse_wave_normal_text', '#ffffff' );
			$hover_bg       = get_theme_mod( 'lssw_btn_pulse_wave_hover_bg', '#0984e3' );
			$pulse_bg       = get_theme_mod( 'lssw_btn_pulse_wave_pulse_bg', '#ff0000' );
			$hover_spacing  = get_theme_mod( 'lssw_btn_pulse_wave_hover_letter_spacing', '4px' );

			if ( is_numeric( $hover_spacing ) ) {
				$hover_spacing .= 'px';
			}

			$custom_css = "
				.btn-class { background-color: {$normal_bg} !important; }
				.btn-class a { color: {$normal_text} !important; }
				.btn-class:hover { background-color: {$hover_bg} !important; }
				.btn-class:hover a { letter-spacing: {$hover_spacing} !important; }
				.btn-class::after { background-color: {$pulse_bg} !important; }
			";
		} elseif ( $effect === 'staggered-radial-ripple' ) {
			$normal_bg    = get_theme_mod( 'lssw_btn_staggered_ripple_normal_bg', '#00a2ff' );
			$normal_text  = get_theme_mod( 'lssw_btn_staggered_ripple_normal_text', '#ffffff' );
			$first_bg     = get_theme_mod( 'lssw_btn_staggered_ripple_first_bg', 'rgba(255, 255, 255, 0.25)' );
			$second_bg    = get_theme_mod( 'lssw_btn_staggered_ripple_second_bg', 'rgba(255, 255, 255, 0.15)' );
			$hover_bg     = get_theme_mod( 'lssw_btn_staggered_ripple_hover_bg', '#00a2ff' );

			$custom_css = "
				.btn-class { background-color: {$normal_bg} !important; }
				.btn-class a { color: {$normal_text} !important; }
				.btn-class::before { background: {$first_bg} !important; }
				.btn-class::after { background: {$second_bg} !important; }
				.btn-class:hover { background-color: {$hover_bg} !important; }
			";
		}

		if ( ! empty( $custom_css ) ) {
			wp_add_inline_style( $handle, $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'lssw_enqueue_button_effect_css' );
add_action( 'admin_enqueue_scripts', 'lssw_enqueue_button_effect_css' );
add_action( 'enqueue_block_editor_assets', 'lssw_enqueue_button_effect_css' );

/**
 * Build inline CSS for the navbar site title typography controls.
 *
 * @return string
 */
function lsw_get_navbar_site_title_style() {
	$font_family    = get_theme_mod( 'navbar_site_title_font_family', 'Inter, system-ui, sans-serif' );
	$font_size      = absint( get_theme_mod( 'navbar_site_title_font_size', 24 ) );
	$font_weight    = get_theme_mod( 'navbar_site_title_font_weight', '700' );
	$font_style     = get_theme_mod( 'navbar_site_title_font_style', 'normal' );
	$letter_spacing = get_theme_mod( 'navbar_site_title_letter_spacing', '0' );
	$word_spacing   = get_theme_mod( 'navbar_site_title_word_spacing', '0' );
	$line_height    = get_theme_mod( 'navbar_site_title_line_height', '1.2' );
	$text_transform = get_theme_mod( 'navbar_site_title_text_transform', 'none' );
	$color          = get_theme_mod( 'navbar_site_title_color', '#111827' );

	$allowed_weights    = array( '300', '400', '500', '600', '700', '800', '900' );
	$allowed_styles     = array( 'normal', 'italic' );
	$allowed_transforms = array( 'none', 'uppercase', 'lowercase', 'capitalize' );

	if ( ! in_array( $font_weight, $allowed_weights, true ) ) {
		$font_weight = '700';
	}
	if ( ! in_array( $font_style, $allowed_styles, true ) ) {
		$font_style = 'normal';
	}
	if ( ! in_array( $text_transform, $allowed_transforms, true ) ) {
		$text_transform = 'none';
	}

	return sprintf(
		'font-family: %1$s; font-size: %2$dpx; font-weight: %3$s; font-style: %4$s; letter-spacing: %5$spx; word-spacing: %6$spx; line-height: %7$s; text-transform: %8$s; color: %9$s;',
		esc_attr( $font_family ),
		$font_size,
		esc_attr( $font_weight ),
		esc_attr( $font_style ),
		esc_attr( $letter_spacing ),
		esc_attr( $word_spacing ),
		esc_attr( $line_height ),
		esc_attr( $text_transform ),
		esc_attr( $color )
	);
}

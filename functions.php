<?php
/**
 * Theme Functions and Main Bootstrap
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// 1. Core Libraries and walker imports
require_once get_template_directory() . '/inc/class-tailwind-nav-walker.php';

// 2. Load Modular Features
require_once get_template_directory() . '/inc/dynamic-css.php';
require_once get_template_directory() . '/inc/shortcodes.php';
require_once get_template_directory() . '/inc/setup-wizard.php';
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Setup theme defaults and register support for various WordPress features.
 */
function lightshadestudioworks_setup() {
	load_theme_textdomain( 'lightshadestudioworks', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-list',
			'comment-form',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'block-templates' );
	add_editor_style( 'editor-style.css' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'woocommerce' );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1920;
	}

	register_nav_menus(
		array(
			'main-menu' => esc_html__( 'Main Menu', 'lightshadestudioworks' ),
		)
	);
}
add_action( 'after_setup_theme', 'lightshadestudioworks_setup' );

/**
 * Enqueue theme core stylesheets
 */
function lightshadestudioworks_resources() {
	// 1. Enqueue standard style.css (only contains theme headers)
	wp_enqueue_style( 'theme-main-activation', get_stylesheet_uri() );

	// 2. Enqueue the Tailwind compiled utilities stylesheet
	wp_enqueue_style(
		'theme-tailwind-utilities',
		get_template_directory_uri() . '/assets/css/lightshadestudioworks-tailwindcss.css',
		array( 'theme-main-activation' ),
		filemtime( get_template_directory() . '/assets/css/lightshadestudioworks-tailwindcss.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'lightshadestudioworks_resources' );

/**
 * Display a thank you notice in dashboard for admin users
 */
function lightshadestudioworks_notice() {
	$user_id = get_current_user_id();
	if ( ! $user_id || ! current_user_can( 'manage_options' ) || get_user_meta( $user_id, 'lightshadestudioworks_notice_dismissed_2026', true ) ) {
		return;
	}
	$dismiss_url = add_query_arg(
		array(
			'lightshadestudioworks_dismiss' => '1',
			'lightshadestudioworks_nonce'   => wp_create_nonce( 'lightshadestudioworks_dismiss_notice' ),
		),
		admin_url()
	);
	echo '<div class="notice notice-info"><p><a href="' . esc_url( $dismiss_url ) . '" class="alignright" style="text-decoration:none"><big>' . esc_html__( '×', 'lightshadestudioworks' ) . '</big></a><big><strong>' . esc_html__( '📝 Thank you for using lightshadestudioworks!', 'lightshadestudioworks' ) . '</strong></big><p>' . esc_html__( 'Powering over 10k websites! Buy me a sandwich! 🥪', 'lightshadestudioworks' ) . '</p><a href="https://github.com/webguyio/lightshadestudioworks/issues/57" class="button-primary" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'How do you use lightshadestudioworks?', 'lightshadestudioworks' ) . '</strong></a> <a href="https://opencollective.com/lightshadestudioworks" class="button-primary" style="background-color:green;border-color:green" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Donate', 'lightshadestudioworks' ) . '</strong></a> <a href="https://wordpress.org/support/theme/lightshadestudioworks/reviews/#new-post" class="button-primary" style="background-color:purple;border-color:purple" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Review', 'lightshadestudioworks' ) . '</strong></a> <a href="https://github.com/webguyio/lightshadestudioworks/issues" class="button-primary" style="background-color:orange;border-color:orange" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Support', 'lightshadestudioworks' ) . '</strong></a></p></div>';
}
add_action( 'admin_notices', 'lightshadestudioworks_notice' );

/**
 * Handle notice dismissal ajax/GET action
 */
function lightshadestudioworks_notice_dismissed() {
	$user_id = get_current_user_id();
	if ( isset( $_GET['lightshadestudioworks_dismiss'], $_GET['lightshadestudioworks_nonce'] ) && wp_verify_nonce( $_GET['lightshadestudioworks_nonce'], 'lightshadestudioworks_dismiss_notice' ) && current_user_can( 'manage_options' ) ) {
		add_user_meta( $user_id, 'lightshadestudioworks_notice_dismissed_2026', 'true', true );
	}
}
add_action( 'admin_init', 'lightshadestudioworks_notice_dismissed' );

/**
 * Add helper JS in wp_footer to identify platform (iOS/Android/Desktop)
 */
function lightshadestudioworks_footer() {
	?>
	<script>
		(function() {
			const ua = navigator.userAgent.toLowerCase();
			const html = document.documentElement;
			if (/(iphone|ipod|ipad)/.test(ua)) {
				html.classList.add('ios', 'mobile');
			} else if (/android/.test(ua)) {
				html.classList.add('android', 'mobile');
			} else {
				html.classList.add('desktop');
			}
			if (/chrome/.test(ua) && !/edg|brave/.test(ua)) {
				html.classList.add('chrome');
			} else if (/safari/.test(ua) && !/chrome/.test(ua)) {
				html.classList.add('safari');
			} else if (/edg/.test(ua)) {
				html.classList.add('edge');
			} else if (/firefox/.test(ua)) {
				html.classList.add('firefox');
			} else if (/brave/.test(ua)) {
				html.classList.add('brave');
			} else if (/opr|opera/.test(ua)) {
				html.classList.add('opera');
			}
		})();
	</script>
	<?php
}
add_action( 'wp_footer', 'lightshadestudioworks_footer' );

/**
 * Set custom document title separator
 */
function lightshadestudioworks_document_title_separator( $sep ) {
	$sep = esc_html( '|' );
	return $sep;
}
add_filter( 'document_title_separator', 'lightshadestudioworks_document_title_separator' );

/**
 * Filter document title to return placeholder when empty
 */
function lightshadestudioworks_title( $title ) {
	if ( $title == '' ) {
		return esc_html( '...' );
	} else {
		return wp_kses_post( $title );
	}
}
add_filter( 'the_title', 'lightshadestudioworks_title' );

/**
 * Output dynamic body schema type tags
 */
function lightshadestudioworks_schema_type() {
	$schema = 'https://schema.org/';
	if ( is_single() ) {
		$type = 'Article';
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} else {
		$type = 'WebPage';
	}
	echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Filter menu link elements to output schema url item property
 */
function lightshadestudioworks_schema_url( $atts ) {
	$atts['itemprop'] = 'url';
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'lightshadestudioworks_schema_url', 10 );

if ( ! function_exists( 'lightshadestudioworks_wp_body_open' ) ) {
	function lightshadestudioworks_wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Echo skip to main content link at body top
 */
function lightshadestudioworks_skip_link() {
	echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'lightshadestudioworks' ) . '</a>';
}
add_action( 'wp_body_open', 'lightshadestudioworks_skip_link', 5 );

/**
 * Add custom links style when using standard read more links
 */
function lightshadestudioworks_read_more_link() {
	if ( ! is_admin() ) {
		return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'lightshadestudioworks' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
	}
}
add_filter( 'the_content_more_link', 'lightshadestudioworks_read_more_link' );

/**
 * Filter excerpt read more link
 */
function lightshadestudioworks_excerpt_read_more_link( $more ) {
	if ( ! is_admin() ) {
		global $post;
		return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'lightshadestudioworks' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
	}
}
add_filter( 'excerpt_more', 'lightshadestudioworks_excerpt_read_more_link' );

/**
 * Optimize intermediate image sizes
 */
function lightshadestudioworks_image_insert_override( $sizes ) {
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );
	return $sizes;
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'lightshadestudioworks_image_insert_override' );

/**
 * Register sidebar widgets area
 */
function lightshadestudioworks_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Widget Area', 'lightshadestudioworks' ),
			'id'            => 'primary-widget-area',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'lightshadestudioworks_widgets_init' );

/**
 * Print pingback link tag in document head
 */
function lightshadestudioworks_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'lightshadestudioworks_pingback_header' );

/**
 * Enqueue comment reply script
 */
function lightshadestudioworks_enqueue_comment_reply_script() {
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'lightshadestudioworks_enqueue_comment_reply_script' );

/**
 * Callback to output custom pings list
 */
function lightshadestudioworks_custom_pings( $comment ) {
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?></li>
	<?php
}

/**
 * Filter comment count details
 */
function lightshadestudioworks_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$get_comments     = get_comments( 'status=approve&post_id=' . $id );
		$comments_by_type = separate_comments( $get_comments );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}
add_filter( 'get_comments_number', 'lightshadestudioworks_comment_count', 0 );

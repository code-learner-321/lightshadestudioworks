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
 * Enqueue scroll-to-top button script when enabled
 */
function lsw_enqueue_scroll_to_top_script() {
	if ( ! get_theme_mod( 'lsw_scroll_to_top_enabled', 1 ) ) {
		return;
	}

	$script_path = get_template_directory() . '/assets/js/scroll-to-top.js';
	wp_enqueue_script(
		'lsw-scroll-to-top',
		get_template_directory_uri() . '/assets/js/scroll-to-top.js',
		array(),
		file_exists( $script_path ) ? filemtime( $script_path ) : null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'lsw_enqueue_scroll_to_top_script' );

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
 * Add a dynamic meta description to the document head.
 */
function lightshadestudioworks_meta_description() {
	$description = '';

	if ( is_singular() ) {
		global $post;
		if ( has_excerpt( $post ) ) {
			$description = get_the_excerpt( $post );
		} else {
			$description = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
			$description = wp_trim_words( $description, 30, '...' );
		}
	} elseif ( is_front_page() || is_home() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( is_archive() ) {
		$description = get_the_archive_description();
	} elseif ( is_search() ) {
		$description = sprintf( esc_html__( 'Search results for %s on %s.', 'lightshadestudioworks' ), get_search_query(), get_bloginfo( 'name' ) );
	} elseif ( is_404() ) {
		$description = esc_html__( 'The requested page could not be found.', 'lightshadestudioworks' );
	}

	if ( empty( $description ) ) {
		$description = get_bloginfo( 'description' );
	}

	$description = trim( wp_kses( $description, array() ) );
	$description = wp_trim_words( $description, 30, '...' );

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'lightshadestudioworks_meta_description', 1 );

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


// SPEED OPTIMIZATION CODE...........
// 1. Defer JavaScript Files
function add_defer_attribute_to_scripts( $tag, $handle, $src ) {
    // DO NOT defer jquery-core or jquery-migrate because plugins depend on them instantly.
    // List custom non-core scripts here if you want to defer them safely:
    $scripts_to_defer = array( 'custom-non-jquery-script' );

    if ( in_array( $handle, $scripts_to_defer, true ) ) {
        return '<script src="' . esc_url( $src ) . '" defer></script>' . "\n";
    }
    return $tag;
}	
add_filter( 'script_loader_tag', 'add_defer_attribute_to_scripts', 10, 3 );

/**
 * 4. Performance Optimization: Asynchronously Load Render-Blocking CSS Files
 * (Keeps your high PageSpeed score by optimizing Tailwind and core blocks)
 */
function optimize_block_render_css( $tag, $handle, $href, $media ) {
    $css_to_optimize = array(
        'lightshadestudioworks-tailwindcss', 
        'lightshadestudioworks-style',         
        'styles',                            
        'styles-css',                        
        'wp-block-cover',                    
        'wp-block-cover-css'                 
    );

    if ( in_array( $handle, $css_to_optimize, true ) ) {
        return '<link rel="preload" href="' . esc_url( $href ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' .
               '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>' . "\n";
    }

    return $tag;
}
add_filter( 'style_loader_tag', 'optimize_block_render_css', 10, 4 );

/**
 * 5. Global jQuery Alias Safety Net
 */
function force_jquery_global_alias() {
    ?>
    <script>
        window.jQuery = window.jQuery || {};
        window.$ = window.jQuery;
    </script>
    <?php
}
add_action( 'wp_head', 'force_jquery_global_alias', 1 );


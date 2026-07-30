<?php
/**
 * Theme Setup Wizard and Default Pages/Posts Setup
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Require TGM Plugin Activation library
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

// Require Gutenberg prebuilt content templates
require_once get_template_directory() . '/inc/page-templates.php';
require_once get_template_directory() . '/inc/post-templates.php';
require_once get_template_directory() . '/inc/sub-page-templates.php';

/**
 * Register required plugins via TGMPA
 */
function lssd_install_plugins() {
	$plugins = array(
		array(
			'name'     => 'Axis Folio',
			'slug'     => 'axis-folio',
			'source'   => 'axis-folio.zip',
			'required' => true,
		),
		array(
			'name'     => 'What Snap App',
			'slug'     => 'what-snap-app',
			'source'   => 'what-snap-app.zip',
			'required' => true,
		),
		array(
			'name'     => 'Carousel Block',
			'slug'     => 'b-carousel-block',
			'required' => true,
		),
		array(
			'name'     => 'Contact Form 7',
			'slug'     => 'contact-form-7',
			'required' => true,
		),
	);

	$config = array(
		'id'           => 'lssd-theme',
		'default_path' => get_template_directory() . '/inc/plugins/',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'has_notices'  => true,
		'is_automatic' => true,
		'dismiss_msg'  => '',
    	'message'      => '' 
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'lssd_install_plugins' );

/**
 * Check if a plugin is active helper
 */
function lsw_is_plugin_active( $plugin_basename ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( $plugin_basename );
}

/**
 * Check if all required plugins are ready
 */
function lsw_required_plugins_ready() {
	$required_plugins = array(
		'axis-folio/axis-folio.php',
		'b-carousel-block/b-carousel-block.php',
	);

	$active_plugins = get_option( 'active_plugins', array() );

	foreach ( $required_plugins as $plugin_basename ) {
		if ( lsw_is_plugin_active( $plugin_basename ) ) {
			continue;
		}

		$plugin_slug = dirname( $plugin_basename );
		if ( $plugin_slug === '.' || $plugin_slug === '' ) {
			return false;
		}

		$found = false;
		foreach ( $active_plugins as $active_plugin ) {
			if ( strpos( $active_plugin, $plugin_slug . '/' ) === 0 ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return false;
		}
	}

	return true;
}

/**
 * Check if required plugins are installed and active
 */
function lsw_is_plugins_installed() {
	return lsw_required_plugins_ready();
}

/**
 * Check if prebuilt default pages already exist
 */
function lsw_default_pages_exist() {
	$page_slugs = array(
		'home',
		'about',
		'portfolio',
		'portfolio-gallery',
		'blog',
		'contact',
	);

	foreach ( $page_slugs as $slug ) {
		if ( ! get_page_by_path( $slug, OBJECT, 'page' ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Redirect helper that falls back to JS/meta refresh when headers were already sent
 */
function lsw_safe_redirect_fallback( $url ) {
	if ( ! empty( $url ) && headers_sent() ) {
		echo '<script type="text/javascript">window.location.href = ' . wp_json_encode( $url ) . ';</script>';
		echo '<noscript><meta http-equiv="refresh" content="0;url=' . esc_url( $url ) . '" /></noscript>';
		exit;
	}

	wp_safe_redirect( $url );
	exit;
}

/**
 * Set theme activation flag on switch
 */
function lsw_set_activation_flag() {
	set_transient( 'lsw_theme_just_activated', true, 60 );
	delete_option( 'lsw_setup_skipped' );
}
add_action( 'after_switch_theme', 'lsw_set_activation_flag' );

/**
 * Redirect user to setup wizard/TGMPA after theme activation
 */
function lsw_redirect_after_activation() {
	if ( isset( $_GET['lsw_skip_setup'] ) && current_user_can( 'manage_options' ) ) {
		update_option( 'lsw_setup_skipped', true );
		delete_transient( 'lsw_theme_just_activated' );
		return;
	}

	if ( ! get_transient( 'lsw_theme_just_activated' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

	if ( in_array( $current_page, array( 'lsw-setup-wizard', 'lsw-default-pages' ), true ) ) {
		delete_transient( 'lsw_theme_just_activated' );
		return;
	}

	if ( lsw_required_plugins_ready() ) {
		delete_transient( 'lsw_theme_just_activated' );
		lsw_safe_redirect_fallback( admin_url( 'admin.php?page=lsw-setup-wizard' ) );
	}

	if ( $current_page !== 'tgmpa-install-plugins' ) {
		lsw_safe_redirect_fallback( admin_url( 'themes.php?page=tgmpa-install-plugins' ) );
	}
}
add_action( 'admin_init', 'lsw_redirect_after_activation' );

/**
 * Check if Axis Folio plugin is installed and active
 */
function lsw_is_axis_folio_installed() {
	return lsw_is_plugin_active( 'axis-folio/axis-folio.php' );
}

/**
 * Inject the setup wizard notice into dashboard admin notices
 */
function lsw_inject_setup_button_into_tgm() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( get_option( 'lsw_setup_skipped' ) || ( function_exists( 'lsw_default_pages_exist' ) && lsw_default_pages_exist() ) ) {
		return;
	}

	$current_page = sanitize_key( $_REQUEST['page'] ?? '' );

	if ( in_array( $current_page, array( 'lsw-setup-wizard', 'lsw-default-pages' ), true ) ) {
		return;
	}

	if ( ! lsw_required_plugins_ready() ) {
		return;
	}

	echo '<div class="notice notice-success is-dismissible" style="margin-top: 20px;">';
	echo '<p><strong>' . esc_html__( 'Plugins are ready!', 'lightshadestudioworks' ) . '</strong> ' . esc_html__( 'Continue to the setup wizard to create your default pages.', 'lightshadestudioworks' ) . '</p>';
	echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=lsw-setup-wizard' ) ) . '" class="button button-primary">' . esc_html__( 'Create Default Pages', 'lightshadestudioworks' ) . '</a> <a href="' . esc_url( admin_url( 'index.php?lsw_skip_setup=1' ) ) . '" class="button">' . esc_html__( 'Skip to Dashboard', 'lightshadestudioworks' ) . '</a></p>';
	echo '</div>';
}
add_action( 'tgmpa_admin_notices', 'lsw_inject_setup_button_into_tgm', 0 );
add_action( 'admin_notices', 'lsw_inject_setup_button_into_tgm', 0 );

/**
 * Register setup wizard pages under theme menu
 */
function lsw_register_all_menu_pages() {
	add_submenu_page( 'themes.php', 'Theme Setup', 'Theme Setup', 'manage_options', 'lsw-setup-wizard', 'lsw_setup_wizard_content' );
	add_management_page( 'Default Pages', 'Default Pages', 'manage_options', 'lsw-default-pages', 'lsw_default_pages_content' );
}
add_action( 'admin_menu', 'lsw_register_all_menu_pages' );

/**
 * Content view for the main theme setup wizard
 */
function lsw_setup_wizard_content() {
	if ( ! lsw_required_plugins_ready() ) {
		lsw_safe_redirect_fallback( admin_url( 'themes.php?page=tgmpa-install-plugins' ) );
	}

	if ( lsw_default_pages_exist() ) {
		lsw_safe_redirect_fallback( admin_url( 'index.php?lsw_skip_setup=1' ) );
	}
	?>
	<div style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; padding-top: 50px;">
		<div class="wrap" style="max-width: 600px; width: 100%; text-align: center; background: #fff; padding: 40px; border: 1px solid #ccd0d4; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h1>Ready to finalize?</h1>
			<p>Click below to automatically create prebuilt pages for this theme.</p>
			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'lsw_setup_action', 'lsw_nonce' ); ?>
				<p>
					<input type="submit" name="lsw_run_setup" class="button button-primary button-hero" value="Create Pages & Finish">
					<a href="<?php echo esc_url( admin_url( 'index.php?lsw_skip_setup=1' ) ); ?>" class="button button-hero" style="margin-left: 10px;">Skip to Dashboard</a>
				</p>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Content view for manual pages installation
 */
function lsw_default_pages_content() {
	$created = ( isset( $_GET['status'] ) && $_GET['status'] == 'created' );
	?>
	<div class="wrap">
		<h1>Default Pre-built Pages Setup</h1>
		<?php if ( $created ) : ?>
			<div class="updated">
				<p>Pages created successfully! <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>">View your pages</a></p>
			</div>
		<?php else : ?>
			<p>If you missed creating the default pages during theme setup, you can do so here.</p>
			<form method="post">
				<?php wp_nonce_field( 'lsw_setup_action', 'lsw_nonce' ); ?>
				<input type="submit" name="lsw_run_manual_setup" class="button button-primary" value="Create Default Pre-built Pages">
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Download and sideload an image from a URL, saving a tag to prevent duplicate downloads
 */
function lsw_import_image_from_url( $url, $title = '' ) {
	if ( empty( $url ) ) {
		return null;
	}

	// Check if image already exists via mapping the original URL string value
	$existing = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'     => '_source_imagekit_url',
					'value'   => $url,
					'compare' => '=',
				),
			),
		)
	);

	if ( $existing->have_posts() ) {
		return $existing->post->ID;
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Download file to temp directory
	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) {
		return null;
	}

	$file_array = array(
		'name'     => basename( parse_url( $url, PHP_URL_PATH ) ),
		'tmp_name' => $tmp,
	);

	// Sideload into media library
	$attachment_id = media_handle_sideload( $file_array, 0, $title );

	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp );
		return null;
	}

	// Save metadata tracking tag to prevent continuous duplications on repeated trigger clicks
	update_post_meta( $attachment_id, '_source_imagekit_url', $url );

	return $attachment_id;
}

/**
 * Filter to override/inject custom Gutenberg blocks for portfolio pages
 */
function lightshadestudioworks_force_portfolio_content( $content ) {
	global $post;

	if ( ! is_singular( 'page' ) || ! $post || $post->post_name !== 'portfolio' ) {
		return $content;
	}

	$new_content    = lightshadestudioworks_render_portfolio();
	$portfolio_page = get_page_by_path( 'portfolio', OBJECT, 'page' );

	if ( $portfolio_page && $portfolio_page->post_content !== $new_content ) {
		wp_update_post(
			array(
				'ID'           => $portfolio_page->ID,
				'post_content' => $new_content,
			)
		);
	}

	return do_blocks( $new_content );
}
add_filter( 'the_content', 'lightshadestudioworks_force_portfolio_content', 9999 );

/**
 * Create or update the theme's main navigation menu
 */
function lsw_create_or_update_main_menu() {
	if ( ! function_exists( 'wp_get_nav_menu_object' ) || ! function_exists( 'wp_create_nav_menu' ) || ! function_exists( 'wp_update_nav_menu_item' ) ) {
		return;
	}

	$menu_name = 'lssw_menu';
	$pages     = array(
		'Home'              => 'home',
		'About'             => 'about',
		'Portfolio'         => 'portfolio',
		'Portfolio Gallery' => 'portfolio-gallery',
		'Blog'              => 'blog',
		'Contact'           => 'contact',
	);

	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu_exists ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		if ( is_wp_error( $menu_id ) ) {
			return;
		}
	} else {
		$menu_id = $menu_exists->term_id;
	}

	$existing_items      = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish' ) );
	$existing_object_ids = array();

	if ( ! empty( $existing_items ) && ! is_wp_error( $existing_items ) ) {
		foreach ( $existing_items as $item ) {
			if ( ! empty( $item->object_id ) ) {
				$existing_object_ids[ (int) $item->object_id ] = (int) $item->ID;
			}
		}
	}

	foreach ( $pages as $title => $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page ) {
			continue;
		}

		$object_id = (int) $page->ID;
		if ( isset( $existing_object_ids[ $object_id ] ) ) {
			continue;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $title,
				'menu-item-object-id' => $object_id,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	$locations['main-menu'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_setup_theme', 'lsw_create_or_update_main_menu' );
add_action( 'after_switch_theme', 'lsw_create_or_update_main_menu' );

/**
 * Automatically create default pages and blog posts with mock content and settings
 */
function lightshadestudioworks_create_default_pages() {
	$home_content      = lightshadestudioworks_render_home();
	$about             = lightshadestudioworks_render_about();
	$portfolio         = lightshadestudioworks_render_portfolio();
	$portfolio_gallery = lightshadestudioworks_render_portfolio_gallery();
	$blog              = lightshadestudioworks_render_blog();
	$contact           = lightshadestudioworks_render_contact();
	$philosophy        = lightshadestudioworks_render_philosophy();

	$wedding_celebrations = lightshadestudioworks_render_wedding_celebration();
	$portrait_sessions    = lightshadestudioworks_render_portrait_sessions();
	$candid_moments       = lightshadestudioworks_render_candid_moments();
	$real_estate_spaces   = lightshadestudioworks_render_real_estate_spaces();

	$pages = array(
		'Home'                         => array(
			'slug'    => 'home',
			'content' => $home_content,
		),
		'About'                        => array(
			'slug'    => 'about',
			'content' => $about,
		),
		'Portfolio'                    => array(
			'slug'    => 'portfolio',
			'content' => $portfolio,
		),
		'Portfolio Gallery'            => array(
			'slug'    => 'portfolio-gallery',
			'content' => $portfolio_gallery,
		),
		'Blog'                         => array(
			'slug'    => 'blog',
			'content' => $blog,
		),
		'The Philosophy / Our Journey' => array(
			'slug'    => 'the-philosophy-our-journey',
			'content' => $philosophy,
		),
		'Contact'                      => array(
			'slug'    => 'contact',
			'content' => $contact,
		),
		'Wedding Celebrations'         => array(
			'slug'    => 'wedding-celebrations',
			'content' => $wedding_celebrations,
		),
		'Portrait Sessions'            => array(
			'slug'    => 'portrait-sessions',
			'content' => $portrait_sessions,
		),
		'Candid Moments'               => array(
			'slug'    => 'candid-moments',
			'content' => $candid_moments,
		),
		'Real Estate Spaces'           => array(
			'slug'    => 'real-estate-spaces',
			'content' => $real_estate_spaces,
		),
	);

	foreach ( $pages as $title => $data ) {
		$existing_page = get_page_by_path( $data['slug'], OBJECT, 'page' );

		$post_data = array(
			'post_title'   => $title,
			'post_name'    => $data['slug'],
			'post_content' => $data['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);

		if ( $existing_page ) {
			$post_data['ID'] = $existing_page->ID;
			$page_id         = wp_update_post( $post_data );
		} else {
			$page_id = wp_insert_post( $post_data );
		}

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			if ( $title === 'Home' ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );
			}
		}
	}

	$home_post1 = lightshadestudioworks_render_posts1_home();
	$home_post2 = lightshadestudioworks_render_posts2_home();
	$home_post3 = lightshadestudioworks_render_posts3_home();
	$home_post4 = lightshadestudioworks_render_posts4_home();
	$home_post5 = lightshadestudioworks_render_posts5_home();

	$blog_posts = array(
		array(
			'title'   => 'Ten Years in Photography and the Evolution of Style',
			'slug'    => 'ten-years-photography-evolution-style',
			'content' => $home_post1,
			'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2147607770.jpg',
		),
		array(
			'title'   => 'The Art of Candid Shots and Unscripted Beauty',
			'slug'    => 'the-art-of-candid-shots-and-unscripted-beauty',
			'content' => $home_post2,
			'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2149887737.jpg',
		),
		array(
			'title'   => 'Preparing Your Property for a Real Estate Shoot',
			'slug'    => 'preparing-your-property-for-a-real-estate-shoot',
			'content' => $home_post3,
			'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/61.jpg',
		),
		array(
			'title'   => 'Finding the Right Light for Every Portrait Session',
			'slug'    => 'finding-the-right-light-for-every-portrait-session',
			'content' => $home_post4,
			'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2149438606.jpg',
		),
		array(
			'title'   => 'Chasing the Grain: Why I’m Falling Back in Love with Film Photography',
			'slug'    => 'chasing-the-grain-why-im-falling-back-in-love-with-film-photography',
			'content' => $home_post5,
			'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2150506094.jpg',
		),
	);

	foreach ( $blog_posts as $post_item ) {
		$existing_post = get_page_by_path( $post_item['slug'], OBJECT, 'post' );

		$post_args = array(
			'post_title'   => $post_item['title'],
			'post_name'    => $post_item['slug'],
			'post_content' => $post_item['content'],
			'post_status'  => 'publish',
			'post_type'    => 'post',
		);

		if ( $existing_post ) {
			$post_args['ID'] = $existing_post->ID;
			$post_id         = wp_update_post( $post_args );
		} else {
			$post_id = wp_insert_post( $post_args );
		}

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$featured_img_id = lsw_import_image_from_url( $post_item['img_url'], $post_item['title'] );
			if ( $featured_img_id ) {
				set_post_thumbnail( $post_id, $featured_img_id );
			}
		}
	}

	lsw_create_or_update_main_menu();
	update_option( 'lsw_pages_created', true );
	lsw_safe_redirect_fallback( admin_url() );
}

/**
 * Handle POST form submissions for setup actions
 */
function lsw_handle_form_submissions() {
	if ( isset( $_POST['lsw_run_setup'] ) || isset( $_POST['lsw_run_manual_setup'] ) ) {
		if ( ! isset( $_POST['lsw_nonce'] ) || ! wp_verify_nonce( $_POST['lsw_nonce'], 'lsw_setup_action' ) ) {
			wp_die( 'Security check failed.' );
		}

		lightshadestudioworks_create_default_pages();
		delete_transient( 'lsw_theme_just_activated' );

		$redirect_url = isset( $_POST['lsw_run_setup'] )
			? admin_url( 'admin.php?page=lsw-setup-wizard&status=created' )
			: admin_url( 'tools.php?page=lsw-default-pages&status=created' );

		lsw_safe_redirect_fallback( $redirect_url );
	}
}
add_action( 'admin_init', 'lsw_handle_form_submissions' );

/**
 * Ajax handler for resetting theme customizer options
 */
function lsw_reset_customizer_settings_handler() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lsw_reset_customizer_nonce' ) ) {
		wp_send_json_error( 'Security check failed.' );
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( 'Permission denied.' );
	}
	remove_theme_mods();
	wp_send_json_success( 'Settings successfully reset.' );
}
add_action( 'wp_ajax_lsw_reset_customizer_settings', 'lsw_reset_customizer_settings_handler' );

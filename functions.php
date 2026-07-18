<?php
// ADDING PREBUILD PAGES AND TGM PLUGINDIR............................
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
add_action('tgmpa_register', 'lssd_install_plugins');

function lssd_install_plugins()
{
    $plugins = array(
        array(
            'name'      => 'Axis Folio',
            'slug'      => 'axis-folio',
            'source'    => 'axis-folio.zip',
            'required'  => true,
        ),
        // Add the bPlugins Carousel Block
        array(
            'name'      => 'Carousel Block',
            'slug'      => 'b-carousel-block', // Ensure this matches the folder name exactly
            'required'  => true,
        ),
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
    );

    $config = array(
        'id'           => 'lssd-theme',
        'default_path' => get_template_directory() . '/inc/plugins/',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'has_notices'  => false,
        'is_automatic' => false,
    );

    tgmpa($plugins, $config);
}
function lsw_is_plugin_active($plugin_basename)
{
    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    return is_plugin_active($plugin_basename);
}

function lsw_required_plugins_ready()
{
    $required_plugins = array(
        'axis-folio/axis-folio.php',
        'b-carousel-block/b-carousel-block.php',
    );

    $active_plugins = get_option('active_plugins', array());

    foreach ($required_plugins as $plugin_basename) {
        if (lsw_is_plugin_active($plugin_basename)) {
            continue;
        }

        $plugin_slug = dirname($plugin_basename);
        if ($plugin_slug === '.' || $plugin_slug === '') {
            return false;
        }

        $found = false;
        foreach ($active_plugins as $active_plugin) {
            if (strpos($active_plugin, $plugin_slug . '/') === 0) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }
    }

    return true;
}

function lsw_is_plugins_installed()
{
    return lsw_required_plugins_ready();
}

function lsw_default_pages_exist()
{
    $page_slugs = array(
        'home',
        'about',
        'portfolio',
        'portfolio-gallery',
        'blog',
        'contact',
    );

    foreach ($page_slugs as $slug) {
        if (!get_page_by_path($slug, OBJECT, 'page')) {
            return false;
        }
    }

    return true;
}
/**
 * Redirect helper that falls back to JS/meta refresh when headers were already sent.
 */
function lsw_safe_redirect_fallback($url)
{
    if (!empty($url) && headers_sent()) {
        // Use a JS redirect when headers already sent to avoid header() warnings
        echo '<script type="text/javascript">window.location.href = ' . wp_json_encode($url) . ';</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . esc_url($url) . '" /></noscript>';
        exit;
    }

    wp_safe_redirect($url);
    exit;
}
// adding page 
add_action('after_switch_theme', 'lsw_set_activation_flag');
function lsw_set_activation_flag()
{
    set_transient('lsw_theme_just_activated', true, 60);
    delete_option('lsw_setup_skipped');
}
add_action('admin_init', 'lsw_redirect_after_activation');
function lsw_redirect_after_activation()
{
    if (isset($_GET['lsw_skip_setup']) && current_user_can('manage_options')) {
        update_option('lsw_setup_skipped', true);
        delete_transient('lsw_theme_just_activated');
        return;
    }

    if (!get_transient('lsw_theme_just_activated') || !current_user_can('manage_options')) {
        return;
    }

    $current_page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';

    if (in_array($current_page, array('lsw-setup-wizard', 'lsw-default-pages'), true)) {
        delete_transient('lsw_theme_just_activated');
        return;
    }

    if (lsw_required_plugins_ready()) {
        delete_transient('lsw_theme_just_activated');
        lsw_safe_redirect_fallback(admin_url('admin.php?page=lsw-setup-wizard'));
    }

    if ($current_page !== 'tgmpa-install-plugins') {
        lsw_safe_redirect_fallback(admin_url('themes.php?page=tgmpa-install-plugins'));
    }
}
function lsw_is_axis_folio_installed()
{
    return lsw_is_plugin_active('axis-folio/axis-folio.php');
}
add_action('tgmpa_admin_notices', 'lsw_inject_setup_button_into_tgm', 0);
add_action('admin_notices', 'lsw_inject_setup_button_into_tgm', 0);

function lsw_inject_setup_button_into_tgm()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // If default pages already exist or setup was skipped, do not prompt
    if (get_option('lsw_setup_skipped') || (function_exists('lsw_default_pages_exist') && lsw_default_pages_exist())) {
        return;
    }

    $current_page = sanitize_key($_REQUEST['page'] ?? '');

    // Don't show notice on setup wizard pages
    if (in_array($current_page, array('lsw-setup-wizard', 'lsw-default-pages'), true)) {
        return;
    }

    // Only show if required plugins are ready
    if (!lsw_required_plugins_ready()) {
        return;
    }

    echo '<div class="notice notice-success is-dismissible" style="margin-top: 20px;">';
    echo '<p><strong>' . esc_html__('Plugins are ready!', 'lightshadestudioworks') . '</strong> ' . esc_html__('Continue to the setup wizard to create your default pages.', 'lightshadestudioworks') . '</p>';
    echo '<p><a href="' . esc_url(admin_url('admin.php?page=lsw-setup-wizard')) . '" class="button button-primary">' . esc_html__('Create Default Pages', 'lightshadestudioworks') . '</a> <a href="' . esc_url(admin_url('index.php?lsw_skip_setup=1')) . '" class="button">' . esc_html__('Skip to Dashboard', 'lightshadestudioworks') . '</a></p>';
    echo '</div>';
}
add_action('admin_menu', 'lsw_register_all_menu_pages');
function lsw_register_all_menu_pages()
{
    add_submenu_page('themes.php', 'Theme Setup', 'Theme Setup', 'manage_options', 'lsw-setup-wizard', 'lsw_setup_wizard_content');
    add_management_page('Default Pages', 'Default Pages', 'manage_options', 'lsw-default-pages', 'lsw_default_pages_content');
}
function lsw_setup_wizard_content()
{
    if (!lsw_required_plugins_ready()) {
        lsw_safe_redirect_fallback(admin_url('themes.php?page=tgmpa-install-plugins'));
    }

    if (lsw_default_pages_exist()) {
        lsw_safe_redirect_fallback(admin_url('index.php?lsw_skip_setup=1'));
    }
?>
    <div style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; padding-top: 50px;">
        <div class="wrap" style="max-width: 600px; width: 100%; text-align: center; background: #fff; padding: 40px; border: 1px solid #ccd0d4; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h1>Ready to finalize?</h1>
            <p>Click below to automatically create prebuilt pages for this theme.</p>
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('lsw_setup_action', 'lsw_nonce'); ?>
                <p>
                    <input type="submit" name="lsw_run_setup" class="button button-primary button-hero" value="Create Pages & Finish">
                    <a href="<?php echo esc_url(admin_url('index.php?lsw_skip_setup=1')); ?>" class="button button-hero" style="margin-left: 10px;">Skip to Dashboard</a>
                </p>
            </form>
        </div>
    </div>
<?php
}
function lsw_default_pages_content()
{
    $created = (isset($_GET['status']) && $_GET['status'] == 'created');
?>
    <div class="wrap">
        <h1>Default Pre-built Pages Setup</h1>
        <?php if ($created) : ?>
            <div class="updated">
                <p>Pages created successfully! <a href="<?php echo admin_url('edit.php?post_type=page'); ?>">View your pages</a></p>
            </div>
        <?php else : ?>
            <p>If you missed creating the default pages during theme setup, you can do so here.</p>
            <form method="post">
                <?php wp_nonce_field('lsw_setup_action', 'lsw_nonce'); ?>
                <input type="submit" name="lsw_run_manual_setup" class="button button-primary" value="Create Default Pre-built Pages">
            </form>
        <?php endif; ?>
    </div>
<?php
}


require_once get_template_directory() . '/inc/page-templates.php';
require_once get_template_directory() . '/inc/post-templates.php';
require_once get_template_directory() . '/inc/sub-page-templates.php';
function lsw_import_image_from_url($url, $title = '')
{
    if (empty($url)) return null;

    // Check if image already exists via mapping the original URL string value
    $existing = new WP_Query([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'meta_query'     => [[
            'key'     => '_source_imagekit_url',
            'value'   => $url,
            'compare' => '='
        ]]
    ]);

    if ($existing->have_posts()) {
        return $existing->post->ID;
    }

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Download file to temp directory
    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        return null;
    }

    $file_array = array(
        'name'     => basename(parse_url($url, PHP_URL_PATH)),
        'tmp_name' => $tmp
    );

    // Sideload into media library
    $attachment_id = media_handle_sideload($file_array, 0, $title);

    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return null;
    }

    // Save metadata tracking tag to prevent continuous duplications on repeated trigger clicks
    update_post_meta($attachment_id, '_source_imagekit_url', $url);

    return $attachment_id;
}
function lightshadestudioworks_force_portfolio_content($content)
{
    global $post;

    if (!is_singular('page') || !$post || $post->post_name !== 'portfolio') {
        return $content;
    }

    $new_content = lightshadestudioworks_render_portfolio();
    $portfolio_page = get_page_by_path('portfolio', OBJECT, 'page');

    if ($portfolio_page && $portfolio_page->post_content !== $new_content) {
        wp_update_post(array(
            'ID'           => $portfolio_page->ID,
            'post_content' => $new_content,
        ));
    }

    return do_blocks($new_content);
}
add_filter('the_content', 'lightshadestudioworks_force_portfolio_content', 9999);

function lsw_create_or_update_main_menu()
{
    if (!function_exists('wp_get_nav_menu_object') || !function_exists('wp_create_nav_menu') || !function_exists('wp_update_nav_menu_item')) {
        return;
    }

    $menu_name = 'lssw_menu';
    $pages = array(
        'Home' => 'home',
        'About' => 'about',
        'Portfolio' => 'portfolio',
        'Portfolio Gallery' => 'portfolio-gallery',
        'Blog' => 'blog',
        'Contact' => 'contact',
    );

    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) {
            return;
        }
    } else {
        $menu_id = $menu_exists->term_id;
    }

    $existing_items = wp_get_nav_menu_items($menu_id, array('post_status' => 'publish'));
    $existing_object_ids = array();

    if (!empty($existing_items) && !is_wp_error($existing_items)) {
        foreach ($existing_items as $item) {
            if (!empty($item->object_id)) {
                $existing_object_ids[(int) $item->object_id] = (int) $item->ID;
            }
        }
    }

    foreach ($pages as $title => $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if (!$page) {
            continue;
        }

        $object_id = (int) $page->ID;
        if (isset($existing_object_ids[$object_id])) {
            continue;
        }

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title'     => $title,
            'menu-item-object-id' => $object_id,
            'menu-item-object'    => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish',
        ));
    }

    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) {
        $locations = array();
    }

    $locations['main-menu'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

add_action('after_setup_theme', 'lsw_create_or_update_main_menu');
add_action('after_switch_theme', 'lsw_create_or_update_main_menu');

function lightshadestudioworks_create_default_pages()
{
    // 1. Prepare HTML Content directly
    $home_content = lightshadestudioworks_render_home();
    $about = lightshadestudioworks_render_about();
    $portfolio = lightshadestudioworks_render_portfolio();
    $portfolio_gallery = lightshadestudioworks_render_portfolio_gallery();
    $blog = lightshadestudioworks_render_blog();
    $contact = lightshadestudioworks_render_contact();
    $philosophy = lightshadestudioworks_render_philosophy();

    $wedding_celebrations = lightshadestudioworks_render_wedding_celebration();
    $portrait_sessions = lightshadestudioworks_render_portrait_sessions();
    $candid_moments = lightshadestudioworks_render_candid_moments();
    $real_estate_spaces = lightshadestudioworks_render_real_estate_spaces();

    // 2. Define Pages
    $pages = [
        'Home' => [
            'slug'    => 'home',
            'content' => $home_content
        ],
        'About' => [
            'slug'    => 'about',
            'content' => $about
        ],
        'Portfolio' => [
            'slug'    => 'portfolio',
            'content' => $portfolio
        ],
        'Portfolio Gallery' => [
            'slug'    => 'portfolio-gallery',
            'content' => $portfolio_gallery
        ],
        'Blog' => [
            'slug'    => 'blog',
            'content' => $blog
        ],
        'The Philosophy / Our Journey' => [
            'slug'    => 'the-philosophy-our-journey',
            'content' => $philosophy
        ],
        'Contact' => [
            'slug'    => 'contact',
            'content' => $contact
        ],
        'Wedding Celebrations' => [
            'slug'    => 'wedding-celebrations',
            'content' => $wedding_celebrations
        ],
        'Portrait Sessions' => [
            'slug'    => 'portrait-sessions',
            'content' => $portrait_sessions
        ],
        'Candid Moments' => [
            'slug'    => 'candid-moments',
            'content' => $candid_moments
        ],
        'Real Estate Spaces' => [
            'slug'    => 'real-estate-spaces',
            'content' => $real_estate_spaces
        ]
    ];
           

    // 3. Insert/Update Pages
    foreach ($pages as $title => $data) {
        $existing_page = get_page_by_path($data['slug'], OBJECT, 'page');

        $post_data = [
            'post_title'   => $title,
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page'
        ];

        if ($existing_page) {
            $post_data['ID'] = $existing_page->ID;
            $page_id = wp_update_post($post_data);
        } else {
            $page_id = wp_insert_post($post_data);
        }

        if ($page_id && !is_wp_error($page_id)) {
            $created_page_ids[$title] = $page_id;

            // If this is the "Home" page, set it as the front page
            if ($title === 'Home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }
    // 6. Define and Add 5 Blog Posts with ImageKit Featured Images
    // Note: Swap these URLs out with your exact ImageKit paths.
    $home_post1 = lightshadestudioworks_render_posts1_home();
    $home_post2 = lightshadestudioworks_render_posts2_home();
    $home_post3 = lightshadestudioworks_render_posts3_home();
    $home_post4 = lightshadestudioworks_render_posts4_home();
    $home_post5 = lightshadestudioworks_render_posts5_home();

    $blog_posts = [
        [
            'title'   => 'Ten Years in Photography and the Evolution of Style',
            'slug'    => 'ten-years-photography-evolution-style',
            'content' => $home_post1,
            'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2147607770.jpg'
        ],
        [
            'title'   => 'The Art of Candid Shots and Unscripted Beauty',
            'slug'    => 'the-art-of-candid-shots-and-unscripted-beauty',
            'content' => $home_post2,
            'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2149887737.jpg'
        ],
        [
            'title'   => 'Preparing Your Property for a Real Estate Shoot',
            'slug'    => 'preparing-your-property-for-a-real-estate-shoot',
            'content' => $home_post3,
            'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/61.jpg'
        ],
        [
            'title'   => 'Finding the Right Light for Every Portrait Session',
            'slug'    => 'finding-the-right-light-for-every-portrait-session',
            'content' => $home_post4,
            'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2149438606.jpg'
        ],
        [
            'title'   => 'Chasing the Grain: Why I’m Falling Back in Love with Film Photography',
            'slug'    => 'chasing-the-grain-why-im-falling-back-in-love-with-film-photography',
            'content' => $home_post5,
            'img_url' => 'https://ik.imagekit.io/fme1zlpfb/lightshadestudioworks/2150506094.jpg'
        ]
    ];

    foreach ($blog_posts as $post_item) {
        $existing_post = get_page_by_path($post_item['slug'], OBJECT, 'post');

        $post_args = [
            'post_title'   => $post_item['title'],
            'post_name'    => $post_item['slug'],
            'post_content' => $post_item['content'],
            'post_status'  => 'publish',
            'post_type'    => 'post'
        ];

        if ($existing_post) {
            $post_args['ID'] = $existing_post->ID;
            $post_id = wp_update_post($post_args);
        } else {
            $post_id = wp_insert_post($post_args);
        }

        // Handle ImageKit Featured Image Assignment
        if ($post_id && !is_wp_error($post_id)) {
            $featured_img_id = lsw_import_image_from_url($post_item['img_url'], $post_item['title']);
            if ($featured_img_id) {
                set_post_thumbnail($post_id, $featured_img_id);
            }
        }
    }

    // 5. Generate 'lssw_menu' Navigation and Bind to Theme Main Menu Location
    lsw_create_or_update_main_menu();

    update_option('lsw_pages_created', true);

    // After page creation, send to Dashboard
    lsw_safe_redirect_fallback(admin_url());
}
add_action('admin_init', 'lsw_handle_form_submissions');
function lsw_handle_form_submissions()
{
    if (isset($_POST['lsw_run_setup']) || isset($_POST['lsw_run_manual_setup'])) {
        // ... (Keep your nonce check here) ...

        lightshadestudioworks_create_default_pages();

        // CRITICAL: Clear the flag so redirect loop stops
        delete_transient('lsw_theme_just_activated');

        $redirect_url = isset($_POST['lsw_run_setup'])
            ? admin_url('admin.php?page=lsw-setup-wizard&status=created')
            : admin_url('tools.php?page=lsw-default-pages&status=created');

        lsw_safe_redirect_fallback($redirect_url);
    }
}

// ADDING PREBUILD PAGES AND TGM PLUGINDIR ENDS............................









add_action('after_setup_theme', 'lightshadestudioworks_setup');


require_once get_template_directory() . '/inc/class-tailwind-nav-walker.php';

function lightshadestudioworks_setup()
{
    load_theme_textdomain('lightshadestudioworks', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-list', 'comment-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'));
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('block-templates');
    add_editor_style('editor-style.css');
    add_theme_support('appearance-tools');
    add_theme_support('woocommerce');
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'lightshadestudioworks')));
}

add_action('admin_notices', 'lightshadestudioworks_notice');
function lightshadestudioworks_notice()
{
    $user_id = get_current_user_id();
    if (!$user_id || !current_user_can('manage_options') || get_user_meta($user_id, 'lightshadestudioworks_notice_dismissed_2026', true)) {
        return;
    }
    $dismiss_url = add_query_arg(array('lightshadestudioworks_dismiss' => '1', 'lightshadestudioworks_nonce' => wp_create_nonce('lightshadestudioworks_dismiss_notice')), admin_url());
    echo '<div class="notice notice-info"><p><a href="' . esc_url($dismiss_url) . '" class="alignright" style="text-decoration:none"><big>' . esc_html__('×', 'lightshadestudioworks') . '</big></a><big><strong>' . esc_html__('📝 Thank you for using lightshadestudioworks!', 'lightshadestudioworks') . '</strong></big><p>' . esc_html__('Powering over 10k websites! Buy me a sandwich! 🥪', 'lightshadestudioworks') . '</p><a href="https://github.com/webguyio/lightshadestudioworks/issues/57" class="button-primary" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__('How do you use lightshadestudioworks?', 'lightshadestudioworks') . '</strong></a> <a href="https://opencollective.com/lightshadestudioworks" class="button-primary" style="background-color:green;border-color:green" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__('Donate', 'lightshadestudioworks') . '</strong></a> <a href="https://wordpress.org/support/theme/lightshadestudioworks/reviews/#new-post" class="button-primary" style="background-color:purple;border-color:purple" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__('Review', 'lightshadestudioworks') . '</strong></a> <a href="https://github.com/webguyio/lightshadestudioworks/issues" class="button-primary" style="background-color:orange;border-color:orange" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__('Support', 'lightshadestudioworks') . '</strong></a></p></div>';
}

add_action('admin_init', 'lightshadestudioworks_notice_dismissed');
function lightshadestudioworks_notice_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['lightshadestudioworks_dismiss'], $_GET['lightshadestudioworks_nonce']) && wp_verify_nonce($_GET['lightshadestudioworks_nonce'], 'lightshadestudioworks_dismiss_notice') && current_user_can('manage_options')) {
        add_user_meta($user_id, 'lightshadestudioworks_notice_dismissed_2026', 'true', true);
    }
}

add_action('wp_footer', 'lightshadestudioworks_footer');
function lightshadestudioworks_footer()
{
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

add_filter('document_title_separator', 'lightshadestudioworks_document_title_separator');
function lightshadestudioworks_document_title_separator($sep)
{
    $sep = esc_html('|');
    return $sep;
}

add_filter('the_title', 'lightshadestudioworks_title');
function lightshadestudioworks_title($title)
{
    if ($title == '') {
        return esc_html('...');
    } else {
        return wp_kses_post($title);
    }
}

function lightshadestudioworks_schema_type()
{
    $schema = 'https://schema.org/';
    if (is_single()) {
        $type = "Article";
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . esc_url($schema) . esc_attr($type) . '"';
}

add_filter('nav_menu_link_attributes', 'lightshadestudioworks_schema_url', 10);
function lightshadestudioworks_schema_url($atts)
{
    $atts['itemprop'] = 'url';
    return $atts;
}

if (!function_exists('lightshadestudioworks_wp_body_open')) {
    function lightshadestudioworks_wp_body_open()
    {
        do_action('wp_body_open');
    }
}

add_action('wp_body_open', 'lightshadestudioworks_skip_link', 5);
function lightshadestudioworks_skip_link()
{
    echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__('Skip to the content', 'lightshadestudioworks') . '</a>';
}

add_filter('the_content_more_link', 'lightshadestudioworks_read_more_link');
function lightshadestudioworks_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">' . sprintf(__('...%s', 'lightshadestudioworks'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}

add_filter('excerpt_more', 'lightshadestudioworks_excerpt_read_more_link');
function lightshadestudioworks_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . sprintf(__('...%s', 'lightshadestudioworks'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}

add_filter('big_image_size_threshold', '__return_false');
add_filter('intermediate_image_sizes_advanced', 'lightshadestudioworks_image_insert_override');
function lightshadestudioworks_image_insert_override($sizes)
{
    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}

add_action('widgets_init', 'lightshadestudioworks_widgets_init');
function lightshadestudioworks_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar Widget Area', 'lightshadestudioworks'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('wp_head', 'lightshadestudioworks_pingback_header');
function lightshadestudioworks_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('comment_form_before', 'lightshadestudioworks_enqueue_comment_reply_script');
function lightshadestudioworks_enqueue_comment_reply_script()
{
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

function lightshadestudioworks_custom_pings($comment)
{
?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?></li>
    <?php
}

add_filter('get_comments_number', 'lightshadestudioworks_comment_count', 0);
function lightshadestudioworks_comment_count($count)
{
    if (!is_admin()) {
        global $id;
        $get_comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($get_comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

// Enqueue stylesheets
function lightshadestudioworks_resources()
{
    // 1. Enqueue standard style.css (only contains theme headers)
    wp_enqueue_style('theme-main-activation', get_stylesheet_uri());

    // 2. Enqueue the Tailwind compiled utilities stylesheet
    wp_enqueue_style(
        'theme-tailwind-utilities',
        get_template_directory_uri() . '/assets/css/lightshadestudioworks-tailwindcss.css',
        array('theme-main-activation'),
        filemtime(get_template_directory() . '/assets/css/lightshadestudioworks-tailwindcss.css')
    );
}
add_action('wp_enqueue_scripts', 'lightshadestudioworks_resources');


// Enqueue block editor styles.............................................
function lightshadestudioworks_render_my_custom_block($attributes, $content)
{
    ob_start();
    include __DIR__ . '/blocks/my-custom-block/render.php';
    return ob_get_clean();
}
function lightshadestudioworks_render_my_custom_block_two($attributes, $content)
{
    ob_start();
    include __DIR__ . '/blocks/my-custom-block-two/render.php';
    return ob_get_clean();
}
function lightshadestudioworks_register_blocks()
{
    // This points to the folder containing block.json
    register_block_type(
        __DIR__ . '/blocks/my-custom-block',
        array(
            'render_callback' => 'lightshadestudioworks_render_my_custom_block',
        )
    );
    register_block_type(
        __DIR__ . '/blocks/my-custom-block-two',
        array(
            'render_callback' => 'lightshadestudioworks_render_my_custom_block_two',
        )
    );
}
add_action('init', 'lightshadestudioworks_register_blocks');





// CUSTOMIZER SETTINGS....................................................................
add_action('after_setup_theme', 'lightshadestudioworks_theme_setups');
function lightshadestudioworks_theme_setups()
{
    load_theme_textdomain('lightshadestudioworks', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-list', 'comment-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'));
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');
    add_theme_support('appearance-tools');
    register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'lightshadestudioworks')));
}

add_action('customize_register', 'lightshadestudioworks_register_full_customizer');
function lightshadestudioworks_register_full_customizer($wp_customize)
{
    // 1. Add Section
    $wp_customize->add_section('lightshadestudioworks_theme_colors', array(
        'title'    => __('Theme Color Palette', 'lightshadestudioworks'),
        'priority' => 30,
    ));

    // 2. Add Scheme Selector
    $wp_customize->add_setting('color_scheme_select', array(
        'default'           => 'scheme_4',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('color_scheme_select', array(
        'label'    => __('Select Base Color Scheme', 'lightshadestudioworks'),
        'section'  => 'lightshadestudioworks_theme_colors',
        'type'     => 'select',
        'choices'  => [
            'scheme_1' => 'Scheme 1 (Pastel)',
            'scheme_2' => 'Scheme 2 (Dark)',
            'scheme_3' => 'Scheme 3 (Ocean)',
            'scheme_4' => 'Scheme 4 (Golden Ember)',
        ],
    ));

    // 3. Add Primary / Secondary / Accent Controls
    $colors = ['primary_60' => 'Primary (60%)', 'secondary_30' => 'Secondary (30%)', 'accent_10' => 'Accent (10%)'];
    foreach ($colors as $id => $label) {
        $default_value = ($id == 'primary_60') ? '#ffffff' : (($id == 'secondary_30') ? '#000000' : '#c5a059');
        $wp_customize->add_setting($id, array(
            'default'           => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array(
            'label'    => $label,
            'section'  => 'lightshadestudioworks_theme_colors',
            'settings' => $id,
        )));
    }

    // 4. Add Neutral Color Controls
    $neutral_colors = [
        'neutral_white' => 'Neutral White',
        'neutral_black' => 'Neutral Black',
        'neutral_grey'  => 'Neutral Grey',
    ];
    foreach ($neutral_colors as $id => $label) {
        $default_value = ($id == 'neutral_white') ? '#dcdcdc' : (($id == 'neutral_black') ? '#454545' : '#f2f2f7');
        $wp_customize->add_setting($id, array(
            'default'           => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array(
            'label'    => $label,
            'section'  => 'lightshadestudioworks_theme_colors',
            'settings' => $id,
        )));
    }
}
function lightshadestudioworks_enqueue_typography_css()
{
    // Fetch customizer options with safe defaults
    $body_sz = get_theme_mod('body_font_size', '16px');
    $h1_sz   = get_theme_mod('h1_font_size', '36px');
    $h2_sz   = get_theme_mod('h2_font_size', '30px');
    $h3_sz   = get_theme_mod('h3_font_size', '24px');
    $h4_sz   = get_theme_mod('h4_font_size', '20px');
    $h5_sz   = get_theme_mod('h5_font_size', '18px');
    $h6_sz   = get_theme_mod('h6_font_size', '16px');

    // Build the structural override rule definitions
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

    wp_register_style('lightshadestudioworks-typography-vars', false);
    wp_enqueue_style('lightshadestudioworks-typography-vars');
    wp_add_inline_style('lightshadestudioworks-typography-vars', $css);
}
// Hook into both front-end, old widgets editor and the modern blocks editor layout
add_action('wp_enqueue_scripts', 'lightshadestudioworks_enqueue_typography_css');
add_action('admin_enqueue_scripts', 'lightshadestudioworks_enqueue_typography_css');
add_action('enqueue_block_editor_assets', 'lightshadestudioworks_enqueue_typography_css');


function lightshadestudioworks_get_color_scheme_values()
{
    $scheme = get_theme_mod('color_scheme_select', 'scheme_4');
    $schemes = array(
        'scheme_1' => ['#f0f8ff', '#005a8c', '#ff4444', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_2' => ['#d2ebd0', '#3b6b3a', '#f1c40f', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_3' => ['#f3f6f6', '#36777d', '#33cca7', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_4' => ['#ffffff', '#000000', '#c5a059', '#dcdcdc', '#454545', '#f2f2f7'],
    );

    $defaults = isset($schemes[$scheme]) ? $schemes[$scheme] : $schemes['scheme_4'];

    $c60 = get_theme_mod('primary_60', $defaults[0]);
    $c30 = get_theme_mod('secondary_30', $defaults[1]);
    $c10 = get_theme_mod('accent_10', $defaults[2]);
    $neutral_white = get_theme_mod('neutral_white', $defaults[3]);
    $neutral_black = get_theme_mod('neutral_black', $defaults[4]);
    $neutral_grey = get_theme_mod('neutral_grey', $defaults[5]);

    return array($c60, $c30, $c10, $neutral_white, $neutral_black, $neutral_grey);
}

// 3. Dynamic CSS Injection (Your working implementation)
function lightshadestudioworks_enqueue_dynamic_css()
{
    list($c60, $c30, $c10, $neutral_white, $neutral_black, $neutral_grey) = lightshadestudioworks_get_color_scheme_values();

    // Combined selectors to ensure coverage on all editors
    $css = ":root, html, body, .editor-styles-wrapper, .block-editor-iframe__body, .block-editor__container, .widgets-editor, .widgets-editor .editor-styles-wrapper, .edit-widgets, .edit-widgets .block-editor-wrapper, .wp-block-widgets { 
        --color-60: {$c60}; 
        --color-30: {$c30}; 
        --color-10: {$c10};
        --lsw-color-neutral-white: {$neutral_white};
        --lsw-color-neutral-black: {$neutral_black};
        --lsw-color-neutral-grey: {$neutral_grey};
    }";

    wp_register_style('lightshadestudioworks-dynamic-vars', false);
    wp_enqueue_style('lightshadestudioworks-dynamic-vars');
    wp_add_inline_style('lightshadestudioworks-dynamic-vars', $css);
}

add_action('wp_enqueue_scripts', 'lightshadestudioworks_enqueue_dynamic_css');
add_action('admin_enqueue_scripts', 'lightshadestudioworks_enqueue_dynamic_css');
add_action('enqueue_block_editor_assets', 'lightshadestudioworks_enqueue_dynamic_css');

add_action('customize_preview_init', 'lightshadestudioworks_theme_customizer_live_preview');
add_action('customize_controls_enqueue_scripts', 'lightshadestudioworks_theme_customizer_controls_preview');

function lightshadestudioworks_theme_customizer_live_preview()
{
    wp_enqueue_script('customize-preview');

    $schemes = [
        'scheme_1' => ['#f0f8ff', '#005a8c', '#ff4444', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_2' => ['#d2ebd0', '#3b6b3a', '#f1c40f', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_3' => ['#f3f6f6', '#36777d', '#33cca7', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_4' => ['#ffffff', '#000000', '#c5a059', '#dcdcdc', '#454545', '#f2f2f7']
    ];
    $json_schemes = json_encode($schemes);

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
            // Target the specific container class used in all layouts
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

    // Button Settings Live Preview
    function updateButtonPreview() {
        // Get all CTA buttons in navbar
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

    // Hover Effect Preview
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
        styleEl.innerHTML = `.lsw-navbar-button:hover { box-shadow: \${x}px \${y}px \${blur}px \${color} !important; }`;
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

    // Ensure hover preview updates on initial load
    updateButtonHoverPreview();

    // Live Preview custom styles
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
        const globalActiveLink = wp.customize('global_active_link_color').get() || '#2563eb';
        
        styleEl.innerHTML = `
            :root {
                --sleek-nav-hover-color: \${sleekHoverColor};
                --sleek-nav-hover-line-color: \${sleekHoverLine};
                --modern-nav-hover-color: \${modernHoverColor};
                --modern-nav-hover-line-color: \${modernHoverLine};
                --global-active-link-color: \${globalActiveLink};
            }
            a:active,
            .lsw-active-link {
                color: \${globalActiveLink} !important;
            }
        `;
    }
    
    wp.customize('sleek_navbar_hover_color', function(value) { value.bind(updateCustomizerStyles); });
    wp.customize('sleek_navbar_hover_line_color', function(value) { value.bind(updateCustomizerStyles); });
    wp.customize('modern_navbar_hover_color', function(value) { value.bind(updateCustomizerStyles); });
    wp.customize('modern_navbar_hover_line_color', function(value) { value.bind(updateCustomizerStyles); });
    wp.customize('global_active_link_color', function(value) { value.bind(updateCustomizerStyles); });
    
    updateCustomizerStyles();

})();";

    wp_add_inline_script('customize-preview', $script);
}

function lightshadestudioworks_theme_customizer_controls_preview()
{
    wp_enqueue_script('customize-controls');

    $schemes = [
        'scheme_1' => ['#f0f8ff', '#005a8c', '#ff4444', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_2' => ['#d2ebd0', '#3b6b3a', '#f1c40f', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_3' => ['#f3f6f6', '#36777d', '#33cca7', '#dcdcdc', '#454545', '#f2f2f7'],
        'scheme_4' => ['#ffffff', '#000000', '#c5a059', '#dcdcdc', '#454545', '#f2f2f7']
    ];
    $json_schemes = json_encode($schemes);

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

    wp_add_inline_script('customize-controls', $script);
}




function add_additional_class_on_a($classes, $item, $args)
{
    if (isset($args->link_class)) {
        $classes['class'] = $args->link_class;
    }
    return $classes;
}
add_filter('nav_menu_link_attributes', 'add_additional_class_on_a', 10, 3);


function lightshadestudioworks_customizer_settings($wp_customize)
{
    if (! class_exists('LSW_Customize_Heading_Control') && class_exists('WP_Customize_Control')) {
        class LSW_Customize_Heading_Control extends WP_Customize_Control
        {
            public $type = 'heading';
            public function render_content()
            {
                echo '<h3 style="margin: 20px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc; text-transform: uppercase; font-size: 13px;">' . esc_html($this->label) . '</h3>';
            }
        }
    }

    if (! class_exists('LSW_Reset_Customizer_Control') && class_exists('WP_Customize_Control')) {
        class LSW_Reset_Customizer_Control extends WP_Customize_Control
        {
            public $type = 'lsw_reset_button';
            public function render_content()
            {
                $nonce = wp_create_nonce('lsw_reset_customizer_nonce');
    ?>
                <script>
                    window.lsw_reset_obj = {
                        nonce: '<?php echo esc_js($nonce); ?>'
                    };
                </script>
                <div style="margin-top: 15px; padding: 12px; border: 1px dashed #e2e8f0; border-radius: 8px; background: #f8fafc; box-shadow: inset 0 1px 2px rgba(0,0,0,0.025);">
                    <span class="customize-control-title" style="margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 13px;"><?php echo esc_html($this->label); ?></span>
                    <?php if (! empty($this->description)) : ?>
                        <span class="description customize-control-description" style="margin-bottom: 12px; display: block; font-size: 12px; line-height: 1.4; color: #64748b;"><?php echo esc_html($this->description); ?></span>
                    <?php endif; ?>
                    <button type="button" class="button button-link lsw-reset-customizer-btn" style="width: 100%; border: 1px solid #ef4444; border-radius: 6px; background-color: #fef2f2; color: #ef4444; font-weight: 600; height: 34px; line-height: 32px; text-decoration: none; text-align: center; display: block; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor='#fef2f2'">
                        <?php esc_html_e('Reset Theme Settings', 'lightshadestudioworks'); ?>
                    </button>
                </div>
<?php
            }
        }
    }

    $wp_customize->add_section('navbar_layout_section', array(
        'title'    => 'Header Navbar Layouts',
        'priority' => 30,
    ));
    // Layout Choice
    $wp_customize->add_setting('navbar_layout_choice', array(
        'default'           => 'lsw_menu_layout_1',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('navbar_layout_choice', array(
        'label'    => 'Select Navbar Layout',
        'section'  => 'navbar_layout_section',
        'type'     => 'radio',
        'choices'  => array(
            'lsw_menu_layout_1' => 'Standard Minimalist',
            'lsw_menu_layout_2' => 'Sleek Minimalist Navbar',
            'lsw_menu_layout_3' => 'Classic Inline Right',
            'lsw_menu_layout_4' => 'Modern Bold Minimalist',
            'lsw_menu_layout_5' => 'Tabed Overlap',
        ),
    ));

    // --- NAVBAR SETTINGS HEADING ---
    if (class_exists('LSW_Customize_Heading_Control')) {
        $wp_customize->add_setting('navbar_settings_heading', array('sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control(new LSW_Customize_Heading_Control($wp_customize, 'navbar_settings_header_label', array(
            'label'    => 'Navbar Settings',
            'section'  => 'navbar_layout_section',
            'settings' => 'navbar_settings_heading',
        )));
    }

    // Logo Upload
    $wp_customize->add_setting('navbar_custom_logo', array('sanitize_callback' => 'absint'));
    if (class_exists('WP_Customize_Cropped_Image_Control')) {
        $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'navbar_custom_logo', array(
            'label'    => 'Navbar Custom Logo',
            'section'  => 'navbar_layout_section',
            'width'    => 400,
            'height'   => 200,
        )));
    }

    $wp_customize->add_setting('navbar_logo_width', array('default' => 150, 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('navbar_logo_width', array('label' => 'Logo Width (px)', 'section' => 'navbar_layout_section', 'type' => 'number'));

    // Spacing
    $padding_sides = ['top', 'right', 'bottom', 'left'];
    foreach ($padding_sides as $side) {
        $id = 'navbar_padding_' . $side;
        $wp_customize->add_setting($id, array(
            'default'           => 0, // Default 0 so the user starts fresh
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control($id, array(
            'label'   => 'Padding ' . ucfirst($side) . ' (px)',
            'section' => 'navbar_layout_section',
            'type'    => 'number'
        ));
    }
    // Add this inside lightshadestudioworks_customizer_settings()
    $wp_customize->add_setting('navbar_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage', // Enables live preview
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_bg_color', array(
        'label'    => 'Navbar Background Color',
        'section'  => 'navbar_layout_section',
    )));
    $wp_customize->add_setting('navbar_menu_gap', array('default' => 32, 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('navbar_menu_gap', array('label' => 'Menu Item Gap (px)', 'section' => 'navbar_layout_section', 'type' => 'number'));

    // --- BUTTON STYLES HEADING ---
    if (class_exists('LSW_Customize_Heading_Control')) {
        $wp_customize->add_setting('button_styles_heading', array('sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control(new LSW_Customize_Heading_Control($wp_customize, 'button_styles_header_label', array(
            'label'    => 'Button Styles',
            'section'  => 'navbar_layout_section',
            'settings' => 'button_styles_heading',
        )));
    }

    // 1. The Switcher (Checkbox)
    $wp_customize->add_setting('navbar_show_button', array(
        'default'           => 1,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('navbar_show_button', array(
        'label'    => 'Show CTA Button',
        'section'  => 'navbar_layout_section',
        'type'     => 'checkbox',
    ));

    // Callback function to check if button is enabled
    function is_button_enabled($control)
    {
        return $control->manager->get_setting('navbar_show_button')->value() == true;
    }

    // 2. Button Settings (with active_callback)


    // Text Field
    $wp_customize->add_setting('navbar_btn_text', array(
        'default'           => 'Book Now',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_text', array(
        'label'   => 'Button Text',
        'section' => 'navbar_layout_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('navbar_btn_url', array(
        'default'           => home_url('/'),
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_url', array(
        'label'   => 'Button URL',
        'section' => 'navbar_layout_section',
        'type'    => 'url'
    ));

    $wp_customize->add_setting('navbar_btn_link_target', array(
        'default'           => '_self',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_link_target', array(
        'label'   => 'Open Button Link In',
        'section' => 'navbar_layout_section',
        'type'    => 'select',
        'choices' => array(
            '_self'  => 'Same Tab',
            '_blank' => 'New Tab',
        ),
    ));

    // Background Color
    $wp_customize->add_setting('navbar_btn_bg', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'navbar_btn_bg',
        array(
            'label'   => 'Button Background Color',
            'section' => 'navbar_layout_section'
        )
    ));

    // Box Shadow - Shadow X Position
    $wp_customize->add_setting('navbar_btn_shadow_x', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_shadow_x', array(
        'label'   => 'Shadow X Position (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number'
    ));

    // Shadow Y Position
    $wp_customize->add_setting('navbar_btn_shadow_y', array(
        'default'           => 10,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_shadow_y', array(
        'label'   => 'Shadow Y Position (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number'
    ));

    // Shadow Blur
    $wp_customize->add_setting('navbar_btn_shadow_blur', array(
        'default'           => 15,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_shadow_blur', array(
        'label'   => 'Shadow Blur (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number'
    ));

    // Shadow Color
    $wp_customize->add_setting('navbar_btn_shadow_color', array(
        'default'           => '#bfdbfe',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'navbar_btn_shadow_color',
        array(
            'label'   => 'Shadow Color',
            'section' => 'navbar_layout_section'
        )
    ));

    // Button Border Radius
    // Button Border Radius
    $wp_customize->add_setting('navbar_btn_radius', array(
        'default'           => 9999,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('navbar_btn_radius', array(
        'label'   => 'Button Border Radius (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number'
    ));

    // --- HOVER STYLE HEADING ---
    if (class_exists('LSW_Customize_Heading_Control')) {
        $wp_customize->add_setting('hover_style_heading', array('sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control(new LSW_Customize_Heading_Control($wp_customize, 'hover_style_header_label', array(
            'label'    => 'Hover Style',
            'section'  => 'navbar_layout_section',
            'settings' => 'hover_style_heading',
        )));
    }

    // Hover Effect Switcher
    $wp_customize->add_setting('navbar_btn_hover_enabled', array(
        'default'           => 1,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('navbar_btn_hover_enabled', array(
        'label'    => 'Enable Hover Effect',
        'section'  => 'navbar_layout_section',
        'type'     => 'checkbox',
    ));

    // Hover Box Shadow Settings
    $wp_customize->add_setting('navbar_btn_hover_shadow_x', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('navbar_btn_hover_shadow_x', array(
        'label'   => 'Hover Shadow X Position (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number',
    ));

    $wp_customize->add_setting('navbar_btn_hover_shadow_y', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('navbar_btn_hover_shadow_y', array(
        'label'   => 'Hover Shadow Y Position (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number',
    ));

    $wp_customize->add_setting('navbar_btn_hover_shadow_blur', array(
        'default'           => 15,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('navbar_btn_hover_shadow_blur', array(
        'label'   => 'Hover Shadow Blur (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number',
    ));

    $wp_customize->add_setting('navbar_btn_hover_shadow_color', array(
        'default'           => '#bfdbfe',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_btn_hover_shadow_color', array(
        'label'   => 'Hover Shadow Color',
        'section' => 'navbar_layout_section',
    )));



    // --- FOOTER SETTINGS ---
    $wp_customize->add_section('footer_style_section', array(
        'title'    => __('Footer Style', 'lightshadestudioworks'),
        'priority' => 40,
    ));

    // 2. Background Color Setting
    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
        'label'    => __('Footer Background Color', 'lightshadestudioworks'),
        'section'  => 'footer_style_section',
    )));

    // 2b. Footer Text Color Setting
    $wp_customize->add_setting('footer_text_color', array(
        'default'           => '#111827',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', array(
        'label'    => __('Footer Text Color', 'lightshadestudioworks'),
        'section'  => 'footer_style_section',
    )));

    // 3. ADD PADDING CONTROLS (Top, Right, Bottom, Left)
    $padding_sides = ['top', 'right', 'bottom', 'left'];
    foreach ($padding_sides as $side) {
        $id = 'footer_padding_' . $side;
        $wp_customize->add_setting($id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control($id, array(
            'label'   => 'Footer Padding ' . ucfirst($side) . ' (px)',
            'section' => 'footer_style_section',
            'type'    => 'number'
        ));
    }

    // 4. Display Footer Text Setting
    $wp_customize->add_setting('display_footer_text', array(
        'default'           => 'left',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('display_footer_text', array(
        'label'    => __('Display Footer Text', 'lightshadestudioworks'),
        'section'  => 'footer_style_section',
        'type'     => 'radio',
        'choices'  => array(
            'left'   => __('Left', 'lightshadestudioworks'),
            'center' => __('Center', 'lightshadestudioworks'),
            'right'  => __('Right', 'lightshadestudioworks'),
        ),
    ));

    // 5. Footer Text Content Setting
    $wp_customize->add_setting('footer_text', array(
        'default'           => '© 2026 Light Shade Studio Works',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_text', array(
        'label'       => __('Footer Text', 'lightshadestudioworks'),
        'section'     => 'footer_style_section',
        'type'        => 'text',
        'description' => __('Enter the footer text (supports simple HTML)', 'lightshadestudioworks'),
    ));

    // General Section
    $wp_customize->add_section('lsw_general_section', array(
        'title'    => 'General',
        'priority' => 15,
    ));

    $wp_customize->add_setting('global_active_link_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_active_link_color', array(
        'label'    => 'Global Active Link Color',
        'section'  => 'lsw_general_section',
    )));

    // Reset Settings Control
    $wp_customize->add_setting('lsw_reset_settings', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    if (class_exists('LSW_Reset_Customizer_Control')) {
        $wp_customize->add_control(new LSW_Reset_Customizer_Control($wp_customize, 'lsw_reset_settings', array(
            'label'       => 'Reset Theme Settings',
            'description' => 'Restore all layout, typography, colors, and other customizer settings back to their factory default values.',
            'section'     => 'lsw_general_section',
            'settings'    => 'lsw_reset_settings',
        )));
    }

    // Sleek Minimalist Navbar Styles (placed in navbar_layout_section)
    if (class_exists('LSW_Customize_Heading_Control')) {
        $wp_customize->add_setting('sleek_navbar_heading', array('sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control(new LSW_Customize_Heading_Control($wp_customize, 'sleek_navbar_heading_ctrl', array(
            'label'           => 'Sleek Minimalist Navbar styles',
            'section'         => 'navbar_layout_section',
            'settings'        => 'sleek_navbar_heading',
            'active_callback' => function ($control) {
                return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_2';
            }
        )));
    }

    $wp_customize->add_setting('sleek_navbar_hover_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sleek_navbar_hover_color', array(
        'label'           => 'Hover Color',
        'section'         => 'navbar_layout_section',
        'active_callback' => function ($control) {
            return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_2';
        }
    )));

    $wp_customize->add_setting('sleek_navbar_hover_line_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sleek_navbar_hover_line_color', array(
        'label'           => 'Hover Bottom Line Color',
        'section'         => 'navbar_layout_section',
        'active_callback' => function ($control) {
            return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_2';
        }
    )));

    // Modern Bold Minimalist Styles (placed in navbar_layout_section)
    if (class_exists('LSW_Customize_Heading_Control')) {
        $wp_customize->add_setting('modern_navbar_heading', array('sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control(new LSW_Customize_Heading_Control($wp_customize, 'modern_navbar_heading_ctrl', array(
            'label'           => 'Modern Bold Minimalist styles',
            'section'         => 'navbar_layout_section',
            'settings'        => 'modern_navbar_heading',
            'active_callback' => function ($control) {
                return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_4';
            }
        )));
    }

    $wp_customize->add_setting('modern_navbar_hover_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'modern_navbar_hover_color', array(
        'label'           => 'Hover Color',
        'section'         => 'navbar_layout_section',
        'active_callback' => function ($control) {
            return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_4';
        }
    )));

    $wp_customize->add_setting('modern_navbar_hover_line_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'modern_navbar_hover_line_color', array(
        'label'           => 'Hover Bottom Line Color',
        'section'         => 'navbar_layout_section',
        'active_callback' => function ($control) {
            return $control->manager->get_setting('navbar_layout_choice')->value() === 'lsw_menu_layout_4';
        }
    )));
}
// Sanitization helper
function my_sanitize_checkbox($checked)
{
    return ((isset($checked) && true == $checked) ? true : false);
}
add_action('customize_register', 'lightshadestudioworks_customizer_settings');



// SITE LAYOUT SETTINGS...................................
function lsw_site_layout_customizer($wp_customize)
{
    // 1. Add Section
    $wp_customize->add_section('site_layout_section', array(
        'title'    => 'Site Layout & Typography',
        'priority' => 20,
    ));

    // 2. Container Width Control
    $wp_customize->add_setting('site_container_width', array('default' => 1200, 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('site_container_width', array('label' => 'Max Container Width (px)', 'section' => 'site_layout_section', 'type' => 'number'));

    // 3. Body Font Family Control
    $wp_customize->add_setting('site_font_family', array('default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('site_font_family', array(
        'label'    => 'Body Font Family',
        'section'  => 'site_layout_section',
        'type'     => 'select',
        'choices'  => array(
            // System Stacks
            'system-ui, -apple-system, sans-serif' => 'System Default (Optimized)',
            'sans-serif'                           => 'Sans Serif (Generic)',
            'serif'                                => 'Serif (Generic)',
            'monospace'                            => 'Monospace (Generic)',

            // Popular Web-Safe/System Stacks
            'Arial, Helvetica, sans-serif'         => 'Arial',
            'Verdana, Geneva, sans-serif'          => 'Verdana',
            'Trebuchet MS, sans-serif'             => 'Trebuchet MS',
            'Georgia, serif'                       => 'Georgia',
            'Times New Roman, serif'               => 'Times New Roman',
            'Courier New, monospace'               => 'Courier New',

            // Modern Professional Stacks
            'Inter, system-ui, sans-serif'         => 'Inter (Modern)',
            'Segoe UI, Tahoma, sans-serif'         => 'Segoe UI',
            'Helvetica Neue, Helvetica, Arial, sans-serif' => 'Helvetica Neue',
            'Palatino Linotype, serif'             => 'Palatino'
        )
    ));

    // 3b. Heading Font Family Control
    $wp_customize->add_setting('site_heading_font_family', array('default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('site_heading_font_family', array(
        'label'    => 'Heading Font Family',
        'section'  => 'site_layout_section',
        'type'     => 'select',
        'choices'  => array(
            // System Stacks
            'system-ui, -apple-system, sans-serif' => 'System Default (Optimized)',
            'sans-serif'                           => 'Sans Serif (Generic)',
            'serif'                                => 'Serif (Generic)',
            'monospace'                            => 'Monospace (Generic)',

            // Popular Web-Safe/System Stacks
            'Arial, Helvetica, sans-serif'         => 'Arial',
            'Verdana, Geneva, sans-serif'          => 'Verdana',
            'Trebuchet MS, sans-serif'             => 'Trebuchet MS',
            'Georgia, serif'                       => 'Georgia',
            'Times New Roman, serif'               => 'Times New Roman',
            'Courier New, monospace'               => 'Courier New',

            // Modern Professional Stacks
            'Inter, system-ui, sans-serif'         => 'Inter (Modern)',
            'Segoe UI, Tahoma, sans-serif'         => 'Segoe UI',
            'Helvetica Neue, Helvetica, Arial, sans-serif' => 'Helvetica Neue',
            'Palatino Linotype, serif'             => 'Palatino'
        )
    ));

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

    foreach ($typography_defaults as $setting_id => $default_size) {
        $wp_customize->add_setting($setting_id, array(
            'default'           => $default_size,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));

        $label_text = ($setting_id === 'body_font_size') ? 'Body Font Size' : strtoupper(str_replace('_font_size', '', $setting_id)) . ' Font Size';

        $wp_customize->add_control($setting_id, array(
            'label'       => __($label_text, 'lightshadestudioworks'),
            'description' => __('Enter value with unit (e.g., 16px, 1rem, 2.5rem)', 'lightshadestudioworks'),
            'section'     => 'site_layout_section',
            'type'        => 'text',
        ));
    }
}
add_action('customize_register', 'lsw_site_layout_customizer');

// 4. Inject Dynamic CSS
// 1. Ensure you know the handle used for your main CSS
function lsw_enqueue_styles()
{
    // Let's assume the handle is 'lsw-style'
    wp_enqueue_style('lsw-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'lsw_enqueue_styles');

// 2. Inject CSS using that same handle
function lsw_generate_dynamic_css()
{
    $width = get_theme_mod('site_container_width', 1200);
    $font  = get_theme_mod('site_font_family', 'Inter');
    $heading_font = get_theme_mod('site_heading_font_family', 'Inter');
    $footer_bg = get_theme_mod('footer_bg_color', '#ffffff');
    $footer_text_color = get_theme_mod('footer_text_color', '#111827');

    $css = "
        // .site-container { 
        //     max-width: {$width}px !important; 
        //     margin-left: auto; 
        //     margin-right: auto; 
        // }
        /* Changed from ID to Class for better reusability */
        .lsw-max-width-container { 
            max-width: {$width}px !important; 
            margin-left: auto; 
            margin-right: auto; 
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

    $hover_enabled = get_theme_mod('navbar_btn_hover_enabled', 1);
    if ($hover_enabled) {
        $hx = get_theme_mod('navbar_btn_hover_shadow_x', 0);
        $hy = get_theme_mod('navbar_btn_hover_shadow_y', 0);
        $hblur = get_theme_mod('navbar_btn_hover_shadow_blur', 15);
        $hcolor = get_theme_mod('navbar_btn_hover_shadow_color', '#bfdbfe');
        $css .= "
            .lsw-navbar-button:hover {
                box-shadow: {$hx}px {$hy}px {$hblur}px {$hcolor} !important;
            }
        ";
    }

    $sleek_hover_color = get_theme_mod('sleek_navbar_hover_color', '#2563eb');
    $sleek_hover_line_color = get_theme_mod('sleek_navbar_hover_line_color', '#2563eb');
    $modern_hover_color = get_theme_mod('modern_navbar_hover_color', '#000000');
    $modern_hover_line_color = get_theme_mod('modern_navbar_hover_line_color', '#000000');
    $global_active_link_color = get_theme_mod('global_active_link_color', '#2563eb');

    $css .= "
        :root {
            --sleek-nav-hover-color: {$sleek_hover_color};
            --sleek-nav-hover-line-color: {$sleek_hover_line_color};
            --modern-nav-hover-color: {$modern_hover_color};
            --modern-nav-hover-line-color: {$modern_hover_line_color};
            --global-active-link-color: {$global_active_link_color};
        }
        a:active,
        .lsw-active-link {
            color: var(--global-active-link-color, #2563eb) !important;
        }
    ";

    // THE HANDLE HERE MUST MATCH THE HANDLE IN WP_ENQUEUE_STYLE
    wp_add_inline_style('lsw-style', $css);
}
add_action('wp_enqueue_scripts', 'lsw_generate_dynamic_css', 20);

// footer sections...............
add_action('customize_register', 'lsw_footer_customizer_settings');
function lsw_footer_customizer_settings($wp_customize)
{
    // 1. Add Footer Section
    $wp_customize->add_section('footer_style_section', array(
        'title'    => __('Footer Style', 'lightshadestudioworks'),
        'priority' => 40,
    ));

    // 2. Add Background Color Setting
    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage', // Required for live preview
    ));

    // 3. Add Control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
        'label'    => __('Footer Background Color', 'lightshadestudioworks'),
        'section'  => 'footer_style_section',
    )));
}

add_action('wp_ajax_lsw_reset_customizer_settings', 'lsw_reset_customizer_settings_handler');
function lsw_reset_customizer_settings_handler()
{
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'lsw_reset_customizer_nonce')) {
        wp_send_json_error('Security check failed.');
    }
    if (! current_user_can('edit_theme_options')) {
        wp_send_json_error('Permission denied.');
    }
    remove_theme_mods();
    wp_send_json_success('Settings successfully reset.');
}

// CONTACT FORM7 ................
function lssw_setup_auto_contact_form() {
    if (!class_exists('WPCF7_ContactForm')) return;

    $title = 'Lighst Shade Studio Works Contact Form';
    $existing = get_posts(['post_type' => 'wpcf7_contact_form', 'title' => $title, 'posts_per_page' => 1]);

    if (empty($existing)) {
        // 1. Define fields
        $template = '<p>Name:<br />[text* your-name]</p>' .
                    '<p>Email:<br />[email* your-email]</p>' .
                    '<p>Message:<br />[textarea your-message]</p>' .
                    '<p>[submit "Send"]</p>';

        // 2. Create the post
        $post_id = wp_insert_post([
            'post_title'   => $title,
            'post_status'  => 'publish',
            'post_type'    => 'wpcf7_contact_form',
        ]);

        // 3. Instantiate the CF7 object for this post
        $cf7 = WPCF7_ContactForm::get_instance($post_id);
        
        // 4. Set the properties (this triggers the CF7 internal parser)
        $cf7->set_properties(['form' => $template]);
        $cf7->set_properties(['mail' => [
            'subject'   => 'New Message',
            'sender'    => '[your-email]',
            'recipient' => get_option('admin_email'),
            'body'      => "Name: [your-name]\nEmail: [your-email]\n\nMessage: [your-message]",
            'additional_headers' => 'Reply-To: [your-email]',
        ]]);
        $cf7->set_properties(['messages' => [
            'mail_sent_ok' => 'Thank you for your message.'
        ]]);

        // 5. CRITICAL: Save the object
        // This is what removes the "Configuration Errors" warning
        $cf7->save();
    }
}
add_action('init', 'lssw_setup_auto_contact_form');

/**
 * Custom shortcode
 */
function lssw_render_auto_contact_form() {
    $form = get_posts(['post_type' => 'wpcf7_contact_form', 'title' => 'Lighst Shade Studio Works Contact Form', 'posts_per_page' => 1]);
    return !empty($form) ? do_shortcode('[contact-form-7 id="' . $form[0]->ID . '"]') : '';
}
add_shortcode('my_contact_form', 'lssw_render_auto_contact_form');
<?php
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
        'default'           => 'scheme_1',
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
        ],
    ));

    // 3. Add Individual Color Controls
    $colors = ['primary_60' => 'Primary (60%)', 'secondary_30' => 'Secondary (30%)', 'accent_10' => 'Accent (10%)'];
    foreach ($colors as $id => $label) {
        $wp_customize->add_setting($id, array(
            'default'           => ($id == 'primary_60') ? '#ffe9ec' : (($id == 'secondary_30') ? '#f4f4f4' : '#000000'),
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

function lightshadestudioworks_get_color_scheme_values()
{
    $scheme = get_theme_mod('color_scheme_select', 'scheme_1');
    $schemes = array(
        'scheme_1' => ['#ffe9ec', '#f4f4f4', '#000000'],
        'scheme_2' => ['#2d3436', '#636e72', '#ffffff'],
        'scheme_3' => ['#0984e3', '#74b9ff', '#ffffff'],
    );

    $defaults = isset($schemes[$scheme]) ? $schemes[$scheme] : $schemes['scheme_1'];

    $c60 = get_theme_mod('primary_60', $defaults[0]);
    $c30 = get_theme_mod('secondary_30', $defaults[1]);
    $c10 = get_theme_mod('accent_10', $defaults[2]);

    return array($c60, $c30, $c10);
}

// 3. Dynamic CSS Injection (Your working implementation)
function lightshadestudioworks_enqueue_dynamic_css()
{
    list($c60, $c30, $c10) = lightshadestudioworks_get_color_scheme_values();

    // Combined selectors to ensure coverage on all editors
    $css = ":root, html, body, .editor-styles-wrapper, .block-editor-iframe__body, .block-editor__container, .widgets-editor, .widgets-editor .editor-styles-wrapper, .edit-widgets, .edit-widgets .block-editor-wrapper, .wp-block-widgets { 
        --color-60: {$c60}; 
        --color-30: {$c30}; 
        --color-10: {$c10}; 
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
        'scheme_1' => ['#ffe9ec', '#f4f4f4', '#000000'],
        'scheme_2' => ['#2d3436', '#636e72', '#ffffff'],
        'scheme_3' => ['#0984e3', '#74b9ff', '#ffffff']
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
})();";

    wp_add_inline_script('customize-preview', $script);
}

function lightshadestudioworks_theme_customizer_controls_preview()
{
    wp_enqueue_script('customize-controls');

    $schemes = [
        'scheme_1' => ['#ffe9ec', '#f4f4f4', '#000000'],
        'scheme_2' => ['#2d3436', '#636e72', '#ffffff'],
        'scheme_3' => ['#0984e3', '#74b9ff', '#ffffff']
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

            ['primary_60', 'secondary_30', 'accent_10'].forEach(function(id, index) {
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
            'lsw_menu_layout_3' => 'Vertical Sidebar',
            'lsw_menu_layout_4' => 'Floating Capsule',
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

    // Background Color
    $wp_customize->add_setting('navbar_btn_bg', array(
        'default'           => '#000000',
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
    $wp_customize->add_setting('navbar_btn_radius', array(
        'default'           => 9999,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage'
    ));
    $wp_customize->add_control('navbar_btn_radius', array(
        'label'   => 'Button Border Radius (px)',
        'section' => 'navbar_layout_section',
        'type'    => 'number'
    ));


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

    // 3. Font Family Control
    $wp_customize->add_setting('site_font_family', array('default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('site_font_family', array(
        'label'    => 'Global Font Family',
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

    $css = "
        .site-container { 
            max-width: {$width}px !important; 
            margin-left: auto; 
            margin-right: auto; 
        }
        /* Changed from ID to Class for better reusability */
        .lsw-max-width-container { 
            max-width: {$width}px !important; 
            margin-left: auto; 
            margin-right: auto; 
        }
        body { font-family: {$font}; }
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

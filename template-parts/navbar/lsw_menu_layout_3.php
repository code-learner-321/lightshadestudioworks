<?php
$pt = get_theme_mod('navbar_padding_top', 0);
$pr = get_theme_mod('navbar_padding_right', 0);
$pb = get_theme_mod('navbar_padding_bottom', 0);
$pl = get_theme_mod('navbar_padding_left', 0);

$padding_css = "{$pt}px {$pr}px {$pb}px {$pl}px";
$gap_value = get_theme_mod('navbar_menu_gap', 32);
$nav_bg = get_theme_mod('navbar_bg_color', '#ffffff'); // Get the new color

// Retrieve brand display settings from Customizer
$brand_type     = get_theme_mod('navbar_brand_type', 'custom_logo');
$custom_logo_id = get_theme_mod('navbar_custom_logo');
$logo_width     = get_theme_mod('navbar_logo_width', 80);
$site_title     = get_theme_mod('navbar_site_title', get_bloginfo('name'));
$site_title_style = function_exists('lsw_get_navbar_site_title_style') ? lsw_get_navbar_site_title_style() : '';

$cta_text   = get_theme_mod('navbar_btn_text', 'Book Now');
$cta_url    = get_theme_mod('navbar_btn_url', home_url('/'));
$cta_bg     = get_theme_mod('navbar_btn_bg', '#2563eb');
$cta_radius = get_theme_mod('navbar_btn_radius', 9999);
$cta_target = get_theme_mod('navbar_btn_link_target', '_self');
$cta_target = in_array($cta_target, array('_self', '_blank'), true) ? $cta_target : '_self';
$cta_rel    = '_blank' === $cta_target ? 'noopener noreferrer' : '';

$s_x     = get_theme_mod('navbar_btn_shadow_x', 0);
$s_y     = get_theme_mod('navbar_btn_shadow_y', 10);
$s_blur  = get_theme_mod('navbar_btn_shadow_blur', 15);
$s_color = get_theme_mod('navbar_btn_shadow_color', '#bfdbfe');

$shadow_css = "{$s_x}px {$s_y}px {$s_blur}px {$s_color}";
?>
<header style="background-color: <?php echo esc_attr($nav_bg); ?>;" class="lsw-navbar-container w-full flex items-center justify-between border-b border-gray-100">
    <div style="padding: <?php echo $padding_css; ?>;" class="lsw-max-width-container w-full mx-auto flex justify-between items-center h-20">
        <!-- Logo (Left) -->
        <div class="flex-shrink-0 flex items-center">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php if ('site_title' === $brand_type) : ?>
                    <span style="<?php echo esc_attr($site_title_style); ?>"><?php echo esc_html($site_title); ?></span>
                <?php elseif ($custom_logo_id) : ?>
                    <?php echo wp_get_attachment_image($custom_logo_id, 'full', false, array(
                        'style' => 'width: ' . esc_attr($logo_width) . 'px; height: auto;',
                        'class' => 'h-auto'
                    )); ?>
                <?php else : ?>
                    <span style="<?php echo esc_attr($site_title_style); ?>"><?php echo esc_html($site_title ? $site_title : get_bloginfo('name')); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <!-- Navigation + CTA (Right) -->
        <div class="flex items-center space-x-10">
            <!-- FIXED: Added 'flex items-center' to the nav element -->
            <nav style="gap: <?php echo esc_attr($gap_value); ?>px;" class="hidden md:flex items-center font-medium text-gray-700">
                <?php
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location'    => 'main-menu',
                        'container'         => false,
                        'items_wrap'        => '%3$s',
                        'link_class'        => 'hover:text-indigo-600 transition lsw-nav-menu-link',
                        'active_link_class' => 'text-indigo-600',
                        'walker'            => new Tailwind_Nav_Walker()
                    ));
                }
                ?>
            </nav>

            <?php if (get_theme_mod('navbar_show_button', 0)) : ?>
                <div class="hidden md:block">
                    <a href="<?php echo esc_url($cta_url); ?>"
                        target="<?php echo esc_attr($cta_target); ?>"
                        rel="<?php echo esc_attr($cta_rel); ?>"
                        class="lsw-navbar-button text-white px-6 py-2 font-semibold transition duration-300"
                        style="background-color: <?php echo esc_attr($cta_bg); ?>; 
                  border-radius: <?php echo esc_attr($cta_radius); ?>px;
                  box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Mobile Trigger -->
            <button id="menu-trigger" class="md:hidden text-gray-900 focus:outline-none">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-nav" class="hidden md:hidden bg-white p-8 border-b border-gray-100">
    <nav class="flex flex-col space-y-6 text-xl font-medium text-gray-800 text-center">
        <?php
        if (has_nav_menu('main-menu')) {
            wp_nav_menu(array(
                'theme_location'    => 'main-menu',
                'container'         => false,
                'items_wrap'        => '%3$s',
                'link_class'        => 'lsw-nav-menu-link',
                'walker'            => new Tailwind_Nav_Walker()
            ));
        }
        ?>
        <?php if (get_theme_mod('navbar_show_button', 0)) : ?>
                <div class="block lg:hidden">
                    <a href="<?php echo esc_url($cta_url); ?>"
                        target="<?php echo esc_attr($cta_target); ?>"
                        rel="<?php echo esc_attr($cta_rel); ?>"
                        class="lsw-navbar-button text-white px-6 py-2 font-semibold transition duration-300"
                        style="background-color: <?php echo esc_attr($cta_bg); ?>; 
                  border-radius: <?php echo esc_attr($cta_radius); ?>px;
                  box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>
    </nav>
</div>

<script>
    document.getElementById('menu-trigger').addEventListener('click', () => {
        document.getElementById('mobile-nav').classList.toggle('hidden');
    });
</script>
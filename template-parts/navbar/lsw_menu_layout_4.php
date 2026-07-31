<style>
    .hover-underline {
        display: inline-block;
        position: relative;
    }

    .hover-underline::after {
        content: '';
        position: absolute;
        width: 100%;
        transform: scaleX(0);
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: var(--modern-nav-hover-line-color, #000);
        transform-origin: bottom right;
        transition: transform 0.25s ease-out;
    }

    .hover-underline:hover::after {
        transform: scaleX(1);
        transform-origin: bottom left;
    }

    .hover-underline:hover {
        color: var(--modern-nav-hover-color, #000) !important;
    }

    /* Hidden state for mobile menu */
    .hidden-menu {
        display: none;
    }
</style>


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
<header style="background-color: <?php echo esc_attr($nav_bg); ?>;" class="lsw-navbar-container border-b border-black">
    <div style="padding: <?php echo $padding_css; ?>;" class="lsw-max-width-container mx-auto h-24 flex items-center justify-between">

        <div class="flex items-center space-x-12">
            <div class="flex-shrink-0">
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

            <nav style="gap: <?php echo esc_attr($gap_value); ?>px;" class="hidden lg:flex">
                <?php
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location'    => 'main-menu',
                        'container'         => false,
                        'items_wrap'        => '%3$s',
                        'link_class'        => 'hover-underline text-sm font-bold uppercase tracking-widest text-black',
                        'active_link_class' => 'text-indigo-600',
                        'walker'            => new Tailwind_Nav_Walker()
                    ));
                }
                ?>
            </nav>
        </div>

        <?php if (get_theme_mod('navbar_show_button', 0)) : ?>
            <div class="hidden lg:block">
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

        <button
            type="button"
            id="mobile-menu-button"
            class="lg:hidden text-gray-900 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 rounded-md p-1"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="mobile-menu">
            <!-- Completely hidden visually via inline styles, but readable by bots & screen readers -->
            <span style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;">Open navigation menu</span>

            <!-- Only the hamburger icon will display on your mobile screen -->
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>


</header>
<div id="mobile-menu" class="hidden-menu lg:hidden border-t border-black bg-white px-6 py-8 flex-col space-y-6">
    <?php
    if (has_nav_menu('main-menu')) {
        wp_nav_menu(array(
            'theme_location'    => 'main-menu',
            'container'         => false,
            'items_wrap'        => '%3$s',
            'link_class'        => 'text-lg font-bold uppercase tracking-widest text-black',
            'active_link_class' => 'text-indigo-600',
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
</div>
<script>
    const mobileToggleBtn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    if (mobileToggleBtn && menu) {
        mobileToggleBtn.addEventListener('click', () => {
            if (menu.classList.contains('hidden-menu')) {
                menu.classList.remove('hidden-menu');
                menu.classList.add('flex');
            } else {
                menu.classList.add('hidden-menu');
                menu.classList.remove('flex');
            }
            const expanded = mobileToggleBtn.getAttribute('aria-expanded') === 'true';
            mobileToggleBtn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        });
    }
</script>
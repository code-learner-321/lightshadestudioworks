<style>
    /* Double line animation from opposite directions */
    .nav-link {
        position: relative;
        padding-bottom: 4px;
        transition: all 0.3s ease;
    }

    .nav-link::before,
    .nav-link::after {
        content: '';
        position: absolute;
        height: 2px;
        bottom: 0;
        background-color: var(--sleek-nav-hover-line-color, #2563eb);
        transition: width 0.4s ease;
        width: 0;
    }

    /* Line from left */
    .nav-link::before {
        left: 50%;
    }

    /* Line from right */
    .nav-link::after {
        right: 50%;
    }

    .nav-link:hover::before,
    .nav-link:hover::after {
        width: 50%;
    }

    .nav-link:hover {
        color: var(--sleek-nav-hover-color, #2563eb);
        text-shadow: 0 0 8px color-mix(in srgb, var(--sleek-nav-hover-color, #2563eb) 20%, transparent);
    }

    /* Animated Border Button Styles */
    .animated-border-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .animated-border-btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: 0.5s;
    }

    .animated-border-btn:hover::before {
        left: 100%;
    }

    .animated-border-btn:hover {
        box-shadow: 0 0 20px rgba(37, 99, 235, 0.5);
        transform: scale(1.05);
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

// Retrieve logo ID from Customizer
$custom_logo_id = get_theme_mod('navbar_custom_logo');
$logo_width = get_theme_mod('navbar_logo_width', 150); // Optional: add this setting to your customizer

$cta_text   = get_theme_mod('navbar_btn_text', 'Book Now');
$cta_bg     = get_theme_mod('navbar_btn_bg', '#2563eb');
$cta_radius = get_theme_mod('navbar_btn_radius', 9999);

$s_x     = get_theme_mod('navbar_btn_shadow_x', 0);
$s_y     = get_theme_mod('navbar_btn_shadow_y', 10);
$s_blur  = get_theme_mod('navbar_btn_shadow_blur', 15);
$s_color = get_theme_mod('navbar_btn_shadow_color', '#bfdbfe');

$shadow_css = "{$s_x}px {$s_y}px {$s_blur}px {$s_color}";
?>
<nav class="lsw-navbar-container border-b border-gray-100" style="background-color: <?php echo esc_attr($nav_bg); ?>;">
    <div style="padding: <?php echo $padding_css; ?>;" class="mx-auto">
        <div class="lsw-max-width-container flex justify-between items-center h-20">

            <div class="flex-shrink-0 flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if ($custom_logo_id) : ?>
                        <?php echo wp_get_attachment_image($custom_logo_id, 'full', false, array(
                            'style' => 'width: ' . esc_attr($logo_width) . 'px; height: auto;',
                            'class' => 'h-auto'
                        )); ?>
                    <?php else : ?>
                        <span class="text-2xl font-bold text-gray-900">Your Brand Name</span>
                    <?php endif; ?>
                </a>
            </div>

            <div style="gap: <?php echo esc_attr($gap_value); ?>px;" class="hidden md:flex items-center">
                <?php
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location'    => 'main-menu',
                        'container'         => false,
                        'items_wrap'        => '%3$s',
                        'link_class'        => 'nav-link text-gray-600 font-medium',
                        'active_link_class' => 'text-amber-500 font-bold',

                        'walker'            => new Tailwind_Nav_Walker()
                    ));
                }
                ?>
            </div>

            <?php if (get_theme_mod('navbar_show_button', 1)) : ?>
                <div class="hidden md:block">
                    <a href="#"
                        class="lsw-navbar-button text-white px-6 py-2 font-semibold transition duration-300"
                        style="background-color: <?php echo esc_attr($cta_bg); ?>; 
                  border-radius: <?php echo esc_attr($cta_radius); ?>px;
                  box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <?php
            if (has_nav_menu('main-menu')) {
                wp_nav_menu(array(
                    'theme_location'    => 'main-menu',
                    'container'         => false,
                    'items_wrap'        => '%3$s',
                    'link_class'        => 'block px-3 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition-colors',
                    'active_link_class' => 'text-amber-500 font-bold',

                    'walker'            => new Tailwind_Nav_Walker()
                ));
            }
            ?>
            <div class="pt-4">
                <?php if (get_theme_mod('navbar_show_button', 1)) : ?>
                <div class="block lg:hidden">
                    <a href="#"
                        class="lsw-navbar-button text-white px-6 py-2 font-semibold transition duration-300"
                        style="background-color: <?php echo esc_attr($cta_bg); ?>; 
                  border-radius: <?php echo esc_attr($cta_radius); ?>px;
                  box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
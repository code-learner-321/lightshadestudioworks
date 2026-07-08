<?php
// Fetch values from customizer
$pt = get_theme_mod('navbar_padding_top', 0);
$pr = get_theme_mod('navbar_padding_right', 0);
$pb = get_theme_mod('navbar_padding_bottom', 0);
$pl = get_theme_mod('navbar_padding_left', 0);

// Construct the CSS string
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
<nav class="lsw-navbar-container shadow-md" style="background-color: <?php echo esc_attr($nav_bg); ?>;">
    <div style="padding: <?php echo $padding_css; ?>;" class="mx-auto px-4 sm:px-6 lg:px-8">
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

            <div class="hidden md:flex items-center" style="gap: <?php echo esc_attr($gap_value); ?>px;">
                <?php
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location'    => 'main-menu',
                        'container'         => false,
                        'items_wrap'        => '%3$s',

                        // Custom arguments handled directly by our updated Walker:
                        'link_class'        => 'nav-link text-gray-600 font-medium hover:text-blue-600 transition-colors',
                        'active_link_class' => 'text-blue-600',

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

    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <?php
            if (has_nav_menu('main-menu')) {
                wp_nav_menu(array(
                    'theme_location'    => 'main-menu',
                    'container'         => false,
                    'items_wrap'        => '%3$s',
                    'link_class'        => 'block px-3 py-2 text-gray-600 hover:bg-blue-50 rounded-md',
                    'active_link_class' => 'text-blue-600',

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
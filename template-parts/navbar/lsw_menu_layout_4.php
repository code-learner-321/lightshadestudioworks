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
<header style="background-color: <?php echo esc_attr($nav_bg); ?>;" class="lsw-navbar-container border-b border-black">
    <div style="padding: <?php echo $padding_css; ?>;" class="lsw-max-width-container mx-auto h-24 flex items-center justify-between">

        <div class="flex items-center space-x-12">
            <div class="flex-shrink-0">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if ($custom_logo_id) : ?>
                        <?php echo wp_get_attachment_image($custom_logo_id, 'full', false, array(
                            'style' => 'width: ' . esc_attr($logo_width) . 'px; height: auto;',
                            'class' => 'h-auto'
                        )); ?>
                    <?php else : ?>
                        <span class="text-3xl font-black tracking-tighter uppercase">Brand</span>
                    <?php endif; ?>
                </a>
            </div>

            <nav style="gap: <?php echo esc_attr($gap_value); ?>px;" class="hidden md:flex">
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

        <button id="hamburger" class="md:hidden flex flex-col space-y-1.5 focus:outline-none">
            <span class="block w-8 h-0.5 bg-black"></span>
            <span class="block w-8 h-0.5 bg-black"></span>
            <span class="block w-8 h-0.5 bg-black"></span>
        </button>
    </div>

    
</header>
<div id="mobile-menu" class="hidden-menu md:hidden border-t border-black bg-white px-6 py-8 flex-col space-y-6">
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
<script>
    const btn = document.getElementById('hamburger');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        // Toggle the visibility
        if (menu.classList.contains('hidden-menu')) {
            menu.classList.remove('hidden-menu');
            menu.classList.add('flex');
        } else {
            menu.classList.add('hidden-menu');
            menu.classList.remove('flex');
        }
    });
</script>
<?php
    $pt = get_theme_mod('navbar_padding_top', 0);
    $pr = get_theme_mod('navbar_padding_right', 0);
    $pb = get_theme_mod('navbar_padding_bottom', 0);
    $pl = get_theme_mod('navbar_padding_left', 0);

    $gap_value = get_theme_mod('navbar_menu_gap', 32);
    $custom_logo_id = get_theme_mod('navbar_custom_logo');
    $logo_width = get_theme_mod('navbar_logo_width', 150);

    $cta_text   = get_theme_mod('navbar_btn_text', 'Book Now');
    $cta_bg     = get_theme_mod('navbar_btn_bg', '#2563eb');
    $cta_radius = get_theme_mod('navbar_btn_radius', 9999);

    $s_x     = get_theme_mod('navbar_btn_shadow_x', 0);
    $s_y     = get_theme_mod('navbar_btn_shadow_y', 10);
    $s_blur  = get_theme_mod('navbar_btn_shadow_blur', 15);
    $s_color = get_theme_mod('navbar_btn_shadow_color', '#bfdbfe');

    $shadow_css = "{$s_x}px {$s_y}px {$s_blur}px {$s_color}";
?>

<header class="absolute top-0 left-0 right-0 z-30 pt-6">
    <div class="max-w-6xl mx-auto px-6 bg-white rounded-2xl shadow-lg flex items-center justify-between py-4 border border-gray-100">
        
        <div class="font-bold text-2xl text-gray-900">
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
        
        <nav style="gap: <?php echo esc_attr($gap_value); ?>px;" class="hidden lg:flex space-x-8 text-sm font-semibold text-gray-500">
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

        <?php if (get_theme_mod('navbar_show_button', 1)) : ?>
            <div class="hidden lg:block">
                <a href="#"
                    class="lsw-navbar-button text-white px-6 py-2 font-semibold transition duration-300"
                    style="background-color: <?php echo esc_attr($cta_bg); ?>; 
                  border-radius: <?php echo esc_attr($cta_radius); ?>px;
                  box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                    <?php echo esc_html($cta_text); ?>
                </a>
            </div>
        <?php endif; ?>

        <button id="menu-btn" class="lg:hidden text-2xl p-2">☰</button>
    </div>
</header>

<div class="pt-24 md:pt-28">
    <div id="container" class="site-container"></div>
</div>

<div id="mobile-menu" class="fixed inset-0 bg-white z-40 hidden flex-col items-center justify-center gap-8 p-6 transition-opacity duration-300">
    <button id="close-btn" class="absolute top-8 right-8 text-4xl font-light text-gray-400 hover:text-black">&times;</button>
    
    <nav class="flex flex-col items-center gap-6">
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
    </nav>

    <?php if (get_theme_mod('navbar_show_button', 1)) : ?>
        <div class="block lg:hidden">
            <a href="#"
                class="lsw-navbar-button text-white px-8 py-3 font-semibold transition duration-300"
                style="background-color: <?php echo esc_attr($cta_bg); ?>; 
              border-radius: <?php echo esc_attr($cta_radius); ?>px;
              box-shadow: <?php echo esc_attr($shadow_css); ?>;">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
    const menu = document.getElementById('mobile-menu');
    const openBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');

    openBtn.addEventListener('click', () => {
        menu.classList.remove('hidden');
        menu.classList.add('flex');
    });

    closeBtn.addEventListener('click', () => {
        menu.classList.add('hidden');
        menu.classList.remove('flex');
    });
</script>
<?php
$fpt = get_theme_mod('footer_padding_top', 20);
$fpr = get_theme_mod('footer_padding_right', 20);
$fpb = get_theme_mod('footer_padding_bottom', 20);
$fpl = get_theme_mod('footer_padding_left', 20);

$footer_padding = "{$fpt}px {$fpr}px {$fpb}px {$fpl}px";
$footer_bg = get_theme_mod('footer_bg_color', '#ffffff');

?>
</main>
</div>
</div>
<footer id="footer" role="contentinfo" style="background-color: <?php echo esc_attr($footer_bg); ?>;">
    <div id="navbar-max-content" style="padding: <?php echo esc_attr($footer_padding); ?>;">
        &copy; <?php echo esc_html(date_i18n(__('Y', 'lightshadestudioworks'))); ?> <?php echo esc_html(get_bloginfo('name')); ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
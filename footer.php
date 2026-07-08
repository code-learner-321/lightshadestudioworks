<?php
$fpt = get_theme_mod('footer_padding_top', 20);
$fpr = get_theme_mod('footer_padding_right', 20);
$fpb = get_theme_mod('footer_padding_bottom', 20);
$fpl = get_theme_mod('footer_padding_left', 20);

$footer_padding = "{$fpt}px {$fpr}px {$fpb}px {$fpl}px";
$footer_bg = get_theme_mod('footer_bg_color', '#ffffff');
$footer_text_align = get_theme_mod('display_footer_text', 'left');
$footer_text = get_theme_mod('footer_text', '© 2026 Light Shade Studio Works');

?>
</main>
</div>
</div>
<footer id="footer" role="contentinfo" style="background-color: <?php echo esc_attr($footer_bg); ?>;">
    <div class="lsw-max-width-container" style="padding: <?php echo esc_attr($footer_padding); ?>; text-align: <?php echo esc_attr($footer_text_align); ?>;">
        <?php echo wp_kses_post($footer_text); ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
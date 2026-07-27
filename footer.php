<?php
$fpt = get_theme_mod('footer_padding_top', 8);
$fpr = get_theme_mod('footer_padding_right', 8);
$fpb = get_theme_mod('footer_padding_bottom', 8);
$fpl = get_theme_mod('footer_padding_left', 8);

$footer_padding = "{$fpt}px {$fpr}px {$fpb}px {$fpl}px";
$footer_bg = get_theme_mod('footer_bg_color', '#000000');
$footer_text_align = get_theme_mod('display_footer_text', 'center');
$footer_text = get_theme_mod('footer_text', '© 2026 Light Shade Studio Works');

?>
</main>
</div>
</div>
<footer id="footer" role="contentinfo" style="background-color: <?php echo esc_attr($footer_bg); ?> !important;">
    <div class="lsw-max-width-container" style="padding: <?php echo esc_attr($footer_padding); ?>; text-align: <?php echo esc_attr($footer_text_align); ?>;">
        <?php echo wp_kses_post($footer_text); ?>
    </div>
</footer>
<?php
if ( get_theme_mod( 'lsw_scroll_to_top_enabled', 1 ) ) {
	get_template_part( 'template-parts/scroll-to-top' );
}
?>
<?php wp_footer(); ?>
</body>

</html>
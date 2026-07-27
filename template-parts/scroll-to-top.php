<?php
/**
 * Scroll to Top Button Template Part
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$scroll_offset = absint( get_theme_mod( 'lsw_scroll_to_top_scroll_offset', 300 ) );
?>
<button
	id="lsw-scroll-to-top"
	type="button"
	aria-label="<?php esc_attr_e( 'Scroll to top', 'lightshadestudioworks' ); ?>"
	data-scroll-offset="<?php echo esc_attr( $scroll_offset ); ?>"
>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
		<path d="M12 4l-8 8h5v8h6v-8h5z"/>
	</svg>
</button>

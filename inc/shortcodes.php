<?php
/**
 * Theme Shortcodes and Block Filters
 *
 * @package lightshadestudioworks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Automatically create and configure the Contact Form 7 form
 */
function lssw_setup_auto_contact_form() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return;
	}

	$title    = 'Lighst Shade Studio Works Contact Form';
	$existing = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'title'          => $title,
			'posts_per_page' => 1,
		)
	);

	if ( empty( $existing ) ) {
		// 1. Define fields
		$template = '<p>Name:<br />[text* your-name]</p>' .
			'<p>Email:<br />[email* your-email]</p>' .
			'<p>Message:<br />[textarea your-message]</p>' .
			'<p>[submit "Send"]</p>';

		// 2. Create the post
		$post_id = wp_insert_post(
			array(
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => 'wpcf7_contact_form',
			)
		);

		// 3. Instantiate the CF7 object for this post
		$cf7 = WPCF7_ContactForm::get_instance( $post_id );

		// 4. Set the properties (this triggers the CF7 internal parser)
		$cf7->set_properties( array( 'form' => $template ) );
		$cf7->set_properties(
			array(
				'mail' => array(
					'subject'            => 'New Message',
					'sender'             => '[your-email]',
					'recipient'          => get_option( 'admin_email' ),
					'body'               => "Name: [your-name]\nEmail: [your-email]\n\nMessage: [your-message]",
					'additional_headers' => 'Reply-To: [your-email]',
				),
			)
		);
		$cf7->set_properties(
			array(
				'messages' => array(
					'mail_sent_ok' => 'Thank you for your message.',
				),
			)
		);

		// 5. CRITICAL: Save the object
		$cf7->save();
	}
}
add_action( 'init', 'lssw_setup_auto_contact_form' );

/**
 * Shortcode to render auto-created contact form
 */
function lssw_render_auto_contact_form() {
	$form = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'title'          => 'Lighst Shade Studio Works Contact Form',
			'posts_per_page' => 1,
		)
	);
	return ! empty( $form ) ? do_shortcode( '[contact-form-7 id="' . $form[0]->ID . '"]' ) : '';
}
add_shortcode( 'my_contact_form', 'lssw_render_auto_contact_form' );

/**
 * Add custom classes to core button blocks depending on the active theme hover effect
 */
function lssw_custom_class_to_button_block( $block_content, $block ) {
	if ( 'core/button' === $block['blockName'] ) {
		$effect    = get_theme_mod( 'lssw_btn_hover_effect', 'none' );
		$processor = new WP_HTML_Tag_Processor( $block_content );

		if ( $processor->next_tag( array( 'class_name' => 'wp-block-button' ) ) ) {
			$processor->add_class( 'btn-class' );

			// Only add effect classes if the setting is NOT 'none'
			if ( 'none' !== $effect ) {
				$processor->add_class( 'btn-effect-none' );
				$processor->add_class( 'btn-effect-' . $effect );
			}

			return $processor->get_updated_html();
		}
	}
	return $block_content;
}
add_filter( 'render_block', 'lssw_custom_class_to_button_block', 10, 2 );

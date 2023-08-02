<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var YITH_WAPO_Addon $addon
 * @var int $x
 * @var string $option_image
 * @var string $hide_option_images
 * @var string $addon_options_images_position
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$setting_hide_images = get_option( 'yith_wapo_hide_images' );

if ( $addon->get_option( 'show_image', $x ) && '' !== $option_image && ! $hide_option_images && 'yes' !== $setting_hide_images ) : ?>

	<div class="image position-<?php echo esc_attr( $addon_options_images_position ); ?>">
		<img src="<?php echo esc_attr( $option_image ); ?>">
	</div>

<?php endif; ?>

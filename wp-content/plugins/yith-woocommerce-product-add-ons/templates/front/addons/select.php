<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$selected = $addon->get_option( 'default', $x ) === 'yes' ? 'selected="selected"' : '';

?>

<option value="<?php echo esc_attr( $x ); ?>" <?php echo esc_attr( $selected ); ?>
    data-price="<?php echo esc_attr( $price ); ?>"
	data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
	data-price-type="<?php echo esc_attr( $price_type ); ?>"
	data-price-method="<?php echo esc_attr( $price_method ); ?>"
	data-first-free-enabled="<?php echo esc_attr( $addon->get_setting( 'first_options_selected', 'no' ) ); ?>"
	data-first-free-options="<?php echo esc_attr( $addon->get_setting( 'first_free_options', 0 ) ); ?>"
	data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
	data-image="<?php echo esc_attr( $option_image ); ?>"
	data-replace-image="<?php echo esc_attr( $image_replacement ); ?>"
	data-description="<?php echo wp_kses_post( $addon->get_option( 'description', $x ) ); ?>">
	<?php echo wp_kses_post( $addon->get_option( 'label', $x ) ); ?>
	<?php echo ! $hide_option_prices ? wp_kses_post( $addon->get_option_price_html( $x ) ) : ''; ?>
</option>

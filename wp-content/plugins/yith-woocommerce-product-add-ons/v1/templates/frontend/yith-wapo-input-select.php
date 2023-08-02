<?php
/**
 * Input field template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'ywapo_select_option', 'ywapo_price_' . esc_attr( $price_type ) );

$selected = $checked ? 'selected' : '';

echo sprintf(
	'<option id="%s" class="%s" data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" value="%s" data-image-url="%s" data-description="%s" data-image="%s" %s >%s</option>',
	esc_attr( $control_id ),
	esc_attr( implode( ' ', $classes ) ),
	esc_attr( $type_id ),
	esc_attr( $price_calculated ),
	esc_attr( $price_type ),
	esc_attr( $key ),
	esc_attr( $key ),
	esc_attr( $image_url ),
	esc_attr( $description ),
	esc_attr( $image_url ),
	esc_attr( $selected ),
	wp_kses_post( $span_label . $price_html )
);

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

$input_classes = array( 'ywapo_input ywapo_input_' . $type, 'ywapo_price_' . esc_attr( $price_type ) );

$value = ( $checked ? $key : '' );

$before_label .= $price_html . $yith_wapo_frontend->get_tooltip( stripslashes( $tooltip ) );

echo '<div class="ywapo_input_container ywapo_input_container_labels ywapo_input_container_' . esc_attr( $type ) . ' ' . ( $checked ? 'ywapo_selected' : '' ) . ' ">';

echo sprintf(
	'%s<input id="%s" data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" type="hidden" name="%s[%s]" value="%s" %s class="%s" %s %s %s/>%s',
	wp_kses_post( $before_label ),
	esc_attr( $control_id ),
	esc_attr( $type_id ),
	esc_attr( $price_calculated ),
	esc_attr( $price_type ),
	esc_attr( $key ),
	esc_attr( $name ),
	esc_attr( $key ),
	esc_attr( $value ),
	( $checked ? 'checked' : '' ),
	esc_attr( implode( ' ', $input_classes ) ),
	esc_attr( $min_html ),
	esc_attr( $max_html ),
	esc_attr( $disabled ),
	wp_kses_post( $after_label )
);

if ( '' !== $description ) {
	echo '<p class="wapo_option_description">' . wp_kses_post( $description ) . '</p>';
}

echo '</div>';

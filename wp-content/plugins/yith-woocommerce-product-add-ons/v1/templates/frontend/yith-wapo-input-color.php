<?php
/**
 * Input field template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 *
 * @var String $description
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class_container = 'ywapo_input_container_' . $type;
$input_classes   = array( 'ywapo_input ywapo_input_' . $type, 'ywapo_price_' . esc_attr( $price_type ) );

$index = $key;

/* price position fix */

$after_label .= $price_html . $yith_wapo_frontend->get_tooltip( stripslashes( $tooltip ) );

/* value fix */
$input_classes[] = 'ywapo_colorpicker';
$type_type       = 'hidden';

echo '<div class="ywapo_input_container ' . esc_attr( $class_container ) . '">';

echo sprintf(
	'<input data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" type="%s" name="%s[%s]" value="%s" %s class="%s" %s %s %s/>',
	esc_attr( $type_id ),
	esc_attr( $price_calculated ),
	esc_attr( $price_type ),
	esc_attr( $index ),
	esc_attr( $type_type ),
	esc_attr( $name ),
	esc_attr( $key ),
	esc_attr( $value ),
	( $checked ? 'checked' : '' ),
	esc_attr( implode( ' ', $input_classes ) ),
	esc_attr( $min_html ),
	esc_attr( $max_html ),
	esc_attr( $disabled )
);

echo sprintf( '%s<input type="text" class="wp-color-picker" />%s', wp_kses_post( $before_label ), wp_kses_post( $after_label ) );

if ( '' !== $description ) {
	echo '<p class="wapo_option_description">' . wp_kses_post( $description ) . '</p>';
}

echo '</div>';

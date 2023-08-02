<?php
/**
 * Input field template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 *
 * @var String $type
 * @var String $control_id
 * @var String $after_label
 * @var String $before_label
 * @var Mixed $value
 * @var Float $price_type
 * @var Int $key
 * @var Boolean $hidelabel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class_container = 'ywapo_input_container_' . $type;
$input_classes   = array( 'ywapo_input ywapo_input_' . $type, 'ywapo_price_' . esc_attr( $price_type ) );
$input_type      = $type;

$index = $key;

/* price position fix */

if ( $hidelabel ) {
	$before_label = '';
	$after_label  = '';
}

if ( 'radio' === $input_type || 'checkbox' === $input_type ) {
	$after_label .= '<label for="' . $control_id . '" style="cursor: pointer;">' . $price_html . $yith_wapo_frontend->get_tooltip( stripslashes( $tooltip ) ) . '</label>';
} else {
	$before_label .= '<label for="' . $control_id . '" style="cursor: pointer;">' . $price_html . $yith_wapo_frontend->get_tooltip( stripslashes( $tooltip ) ) . '</label>';
}

/* value fix */
if ( 'radio' === $input_type ) {
	$value = $key;
	$key   = '';
} elseif ( 'date' === $input_type ) {
	$input_classes[] = 'ywapo_datepicker';
	$input_type      = 'text';
}

echo '<div class="ywapo_input_container ' . esc_attr( $class_container ) . '">';

echo sprintf(
	'%s<input id="%s" placeholder="%s" data-typeid="%s" data-price_x="%s" data-price="%s" data-pricetype="%s" data-index="%s" type="%s" name="%s[%s]" value="%s" %s class="%s" %s %s %s %s %s %s />%s',
	wp_kses_post( $before_label ),
	esc_attr( $control_id ),
	esc_attr( $placeholder ),
	esc_attr( $type_id ),
	esc_attr( $price ),
	esc_attr( $price_calculated ),
	esc_attr( $price_type ),
	esc_attr( $index ),
	esc_attr( $input_type ),
	esc_attr( $name ),
	esc_attr( $key ),
	esc_attr( $value ),
	( $checked ? 'checked' : '' ),
	esc_attr( implode( ' ', $input_classes ) ),
	esc_attr( $min_html ),
	wp_kses_post( $max_html ),
	wp_kses_post( $max_length ),
	esc_attr( $required ? 'required="required"' : '' ),
	esc_attr( $disabled ),
	esc_attr( $step ),
	wp_kses_post( $after_label )
);

if ( 'file' === esc_attr( $type ) ) {
	echo '<div><img alt="" class="preview" src="" style="max-width: 200px; display: none;" /></div>';
}

if ( ! empty( $description ) ) {
	echo '<p class="wapo_option_description">' . wp_kses_post( $description ) . '</p>';
}

echo '</div>';

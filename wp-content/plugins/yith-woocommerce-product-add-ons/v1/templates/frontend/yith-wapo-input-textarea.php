<?php
/**
 * Text area template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'ywapo_input ywapo_input_' . $type, 'ywapo_price_' . esc_attr( $price_type ) );
$editor  = apply_filters( 'yith_wapo_enable_textarea_editor', get_option( 'yith_wapo_settings_enable_textarea_editor' ) === 'yes' );

$textarea_name = esc_attr( $name ) . '[' . $key . ']';

?>

<div class="ywapo_input_container ywapo_input_container_<?php echo esc_attr( $type ); ?>">

	<?php

		echo wp_kses_post( $hidelabel ? '' : $before_label );
		echo wp_kses_post( $price_html );
		echo wp_kses_post( $yith_wapo_frontend->get_tooltip( $tooltip ) );

	if ( $editor ) {

		wp_editor(
			esc_html( $value ),
			esc_attr( $name ) . '_' . $key,
			array(
				'editor_class'  => implode( ' ', $classes ),
				'textarea_name' => $textarea_name,
			)
		);

	} else {

		echo sprintf(
			'<textarea placeholder="%s" data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" name="%s[%s]" cols="20" rows="4" %s class="%s" %s %s>%s</textarea>',
			esc_attr( $placeholder ),
			esc_attr( $type_id ),
			esc_attr( $price_calculated ),
			esc_attr( $price_type ),
			esc_attr( $key ),
			esc_attr( $name ),
			esc_attr( $key ),
			wp_kses_post( $max_length ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $disabled ),
			esc_attr( $required ? 'required="required"' : '' ),
			esc_html( $value )
		);

	}

	?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').attr('placeholder','<?php echo esc_attr( $placeholder ); ?>');
			jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').attr('data-typeid','<?php echo esc_attr( $type_id ); ?>');
			jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').attr('data-price','<?php echo esc_attr( $price_calculated ); ?>');
			jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').attr('data-pricetype','<?php echo esc_attr( $price_type ); ?>');
			jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').attr('data-index','<?php echo esc_attr( $key ); ?>');
			<?php
			if ( $required ) :
				?>
				jQuery('#<?php echo esc_attr( $name . '_' . $key ); ?>').prop( 'required', true );<?php endif; ?>
		});
	</script>

	<?php echo $hidelabel ? '' : wp_kses_post( $after_label ); ?>

	<?php if ( '' !== $description ) : ?>
		<p class="wapo_option_description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>

</div>

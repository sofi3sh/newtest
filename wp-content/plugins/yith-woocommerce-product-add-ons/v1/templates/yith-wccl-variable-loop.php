<?php
/**
 * Variable product add to cart in loop
 *
 * @author  Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="variations_form cart in_loop" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-active_variation="" data-product_variations="<?php echo esc_attr( $data_product_variations ); ?>">
	<?php
	foreach ( $attributes as $name => $options ) :

		// check for default attribute.
		if ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
			$selected_value = $selected_attributes[ sanitize_title( $name ) ];
		} else {
			$selected_value = '';
		}

		?>
		<div class="<?php echo 'variations ' . esc_attr( $name ); ?>">

			<select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>"
				name="attribute_<?php echo esc_attr( $name ); ?>"
				data-attribute_name="attribute_<?php echo esc_attr( $name ); ?>"
				<?php
				if ( isset( $attributes_types[ $name ] ) ) {
					echo 'data-type="' . esc_attr( $attributes_types[ $name ] ) . '"'; }
				?>
				data-default_value="<?php echo esc_attr( $selected_value ); ?>">
				<option value=""><?php echo wp_kses_post( apply_filters( 'yith_wccl_empty_option_loop_label', __( 'Choose an option', 'yith-woocommerce-color-label-variations' ) ) ); ?></option>
				<?php

				if ( is_array( $options ) ) {

					// Get terms if this is a taxonomy - ordered.
					if ( taxonomy_exists( $name ) ) {

						$terms = wc_get_product_terms( $product_id, $name, array( 'fields' => 'all' ) );

						foreach ( $terms as $tterm ) {
							if ( ! in_array( $tterm->slug, $options, true ) ) {
								continue;
							}
							$value   = ywccl_get_term_meta( $tterm->term_id, $name . '_yith_wccl_value' );
							$tooltip = ywccl_get_term_meta( $tterm->term_id, $name . '_yith_wccl_tooltip' );
							echo '<option value="' . esc_attr( $tterm->slug ) . '"' . selected( $selected_value, $tterm->slug, false ) . ' data-value="'
								. esc_attr( $value ) . '" data-tooltip="'
								. esc_attr( $tooltip ) . '">' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $tterm->name ) )
								. '</option>';
						}
					} else {

						foreach ( $options as $option ) {
							echo '<option value="' . esc_attr( $option ) . '"' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
						}
					}
				}
				?>
			</select>
		</div>
	<?php endforeach; ?>
</div>

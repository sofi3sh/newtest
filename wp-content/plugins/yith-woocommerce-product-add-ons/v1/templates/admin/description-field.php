<?php
/**
 * Add description field to add/edit products attribute
 *
 * @author  Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( $edit ) : ?>

	<tr class="form-field form-required">
		<th scope="row" valign="top">
			<label for="attribute_public"><?php echo esc_html__( 'Description', 'yith-woocommerce-color-label-variations' ); ?></label>
		</th>
		<td>
			<textarea name="attribute_description" id="attribute_description"><?php
			if ( $att_description ) {
				echo wp_kses_post( $att_description );
			}
			?></textarea>
			<p class="description"><?php echo esc_html__( 'Description for product attributes.', 'yith-woocommerce-color-label-variations' ); ?></p>
		</td>
	</tr>

<?php else : ?>

	<div class="form-field">
		<label for="attribute_description"><?php echo esc_html__( 'Description', 'yith-woocommerce-color-label-variations' ); ?></label>
		<textarea name="attribute_description" id="attribute_description"><?php
		if ( $att_description ) {
			echo wp_kses_post( $att_description );
		}
		?></textarea>
		<p class="description"><?php echo esc_html__( 'Description for product attributes.', 'yith-woocommerce-color-label-variations' ); ?></p>
	</div>

<?php endif; ?>

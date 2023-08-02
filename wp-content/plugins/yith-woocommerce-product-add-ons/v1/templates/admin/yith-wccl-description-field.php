<?php
/**
 * Add description field to add/edit products attribute
 *
 * @author  Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 *
 * @var string $edit
 * @var string $value
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( $edit ) : ?>

	<tr class="form-field form-required">
		<th scope="row">
			<label for="attribute_public"><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?></label>
		</th>
		<td>
			<textarea name="attribute_description" id="attribute_description">
			<?php
			if ( $value ) {
				echo esc_html( $value );
			}
			?>
			</textarea>
			<p class="description"><?php echo esc_html__( 'Product attribute description.', 'yith-woocommerce-product-add-ons' ); ?></p>
		</td>
	</tr>

<?php else : ?>

	<div class="form-field">
		<label for="attribute_description"><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?></label>
		<textarea name="attribute_description" id="attribute_description">
		<?php
		if ( $value ) {
			echo esc_html( $value );
		}
		?>
		</textarea>
		<p class="description"><?php echo esc_html__( 'Product attribute description.', 'yith-woocommerce-product-add-ons' ); ?></p>
	</div>

<?php endif; ?>

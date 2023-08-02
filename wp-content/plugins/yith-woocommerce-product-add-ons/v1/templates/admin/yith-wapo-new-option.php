<?php
/**
 * Admin Products Options New Option
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

?>
<tr>
	<td class="option-sort"><i class="dashicons dashicons-move"></i></td>
	<td>
		<div id="option-image-new" class="option-image">
			<input class="opt-image" type="hidden" name="options[image][]" size="60" value="">
			<p class="save-first"><?php echo esc_html__( 'Save to set image!', 'yith-woocommerce-product-add-ons' ); ?></p>
		</div>
	</td>
	<td>
		<div class="option-label">
			<small><?php echo esc_html__( 'Option Label', 'yith-woocommerce-product-add-ons' ); ?> (<?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>)</small>
			<input type="text" name="options[label][]" value="" />
		</div>
		<div class="option-description">
			<small><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[description][]" value="" />
		</div>
		<div class="option-placeholder">
			<small><?php echo esc_html__( 'Placeholder', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[placeholder][]" value="" />
		</div>
		<div class="option-tooltip">
			<small><?php echo esc_html__( 'Tooltip', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[tooltip][]" value="" />
		</div>
		<div class="option-price">
			<small><?php echo esc_html__( 'Price', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[price][]" value="" placeholder="0" />
		</div>
		<div class="option-type">
			<small><?php echo esc_html__( 'Amount', 'yith-woocommerce-product-add-ons' ); ?></small>
			<select name="options[type][]">
				<option value="fixed"><?php echo esc_html__( 'Fixed amount', 'yith-woocommerce-product-add-ons' ); ?></option>
				<option value="percentage"><?php echo esc_html__( '% markup', 'yith-woocommerce-product-add-ons' ); ?></option>
				<option value="calculated_multiplication"><?php echo esc_html__( 'Price multiplied by value', 'yith-woocommerce-product-add-ons' ); ?></option>
				<option value="calculated_character_count"><?php echo esc_html__( 'Price multiplied by string length', 'yith-woocommerce-product-add-ons' ); ?></option>
			</select>
		</div>
		<div class="option-min">
			<small><?php echo esc_html__( 'Min', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[min][]" value="" placeholder="0" />
		</div>
		<div class="option-max">
			<small><?php echo esc_html__( 'Max', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[max][]" value="" placeholder="0" />
		</div>
		<div class="option-default">
			<small><?php echo esc_html__( 'Checked', 'yith-woocommerce-product-add-ons' ); ?><br /></small>
			<input type="checkbox" name="options[default][]" value="<?php echo esc_attr( $i ?? '' ); ?>" class="new_default" />
		</div>
		<div class="option-required">
			<small><?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?><br /></small>
			<input type="checkbox" name="options[required][]" value="<?php echo esc_attr( $i ?? '' ); ?>" class="new_required" />
		</div>
	</td>
	<td>
		<div class="option-actions">
			<br />
			<a class="button remove-row" title="<?php echo esc_attr__( 'Delete', 'yith-woocommerce-product-add-ons' ); ?>"><span class="dashicons dashicons-dismiss" style="line-height: 27px;"></span></a>
		</div>
	</td>
</tr>

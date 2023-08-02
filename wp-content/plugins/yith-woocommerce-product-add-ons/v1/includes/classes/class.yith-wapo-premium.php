<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'YITH_WAPO_Premium' ) ) {

	/**
	 * WAPO Premium class
	 */
	class YITH_WAPO_Premium {

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) {
				add_action( 'yith_wapo_excluded_products_template', array( $this, 'excluded_products_template' ), 10, 1 );
				add_action( 'yith_wapo_type_options_template', array( $this, 'type_options_template' ), 10, 1 );
				add_action( 'yith_wapo_depend_variations_template', array( $this, 'depend_variations_template' ), 10, 2 );
				add_action( 'yith_wapo_addon_operator_template', array( $this, 'addon_operator_template' ), 10, 1 );
				add_action( 'yith_wapo_addon_options_template', array( $this, 'addon_options_template' ), 10, 1 );
			}
		}

		/**
		 * Exclude Products Template
		 *
		 * @param YITH_WAPO_Group $group Group.
		 */
		public function excluded_products_template( $group ) { ?>
			<tr>
				<th scope="row"><label for="products_id"><?php echo esc_html__( 'Excluded Products', 'yith-woocommerce-product-add-ons' ); ?></label></th>
				<td><?php yith_wapo_multi_products_select( 'products_exclude_id[]', $group->products_exclude_id ); ?></td>
			</tr>
			<?php
		}

		/**
		 * Type Options Template
		 *
		 * @param string $field_type Field type.
		 */
		public function type_options_template( $field_type = '' ) {
			?>
			<option value="checkbox" <?php selected( $field_type, 'checkbox' ); ?>><?php echo esc_html__( 'Checkbox', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="radio" <?php selected( $field_type, 'radio' ); ?>><?php echo esc_html__( 'Radio Button', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="text" <?php selected( $field_type, 'text' ); ?>><?php echo esc_html__( 'Text', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="color" <?php selected( $field_type, 'color' ); ?>><?php echo esc_html__( 'Color', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="date" <?php selected( $field_type, 'date' ); ?>><?php echo esc_html__( 'Date', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="labels" <?php selected( $field_type, 'labels' ); ?>><?php echo esc_html__( 'Labels', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="multiple_labels" <?php selected( $field_type, 'multiple_labels' ); ?>><?php echo esc_html__( 'Multiple Labels', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="number" <?php selected( $field_type, 'number' ); ?>><?php echo esc_html__( 'Number', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="select" <?php selected( $field_type, 'select' ); ?>><?php echo esc_html__( 'Select', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="textarea" <?php selected( $field_type, 'textarea' ); ?>><?php echo esc_html__( 'Textarea', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="file" <?php selected( $field_type, 'file' ); ?>><?php echo esc_html__( 'File', 'yith-woocommerce-product-add-ons' ); ?></option>
			<?php
		}

		/**
		 * Depend Variations Template
		 *
		 * @param YITH_WAPO_Type  $type Type.
		 * @param YITH_WAPO_Group $group Group.
		 */
		public function depend_variations_template( $type, $group ) {
			if ( isset( $type ) ) {
				$depend_variations_array = explode( ',', $type->depend_variations );
			} else {
				$depend_variations_array = array(); }
			?>
			<label for="variations">
				<?php echo esc_html__( 'Variations Requirements', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Show this add-on to users only if they have first selected one of the following variations.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</label>
			<?php
				$allowed_products = $group->products_id;
				// WPML.
			if ( class_exists( 'SitePress' ) ) {
				$products_array   = explode( ',', $group->products_id );
				$allowed_products = array();
				foreach ( $products_array as $key_p => $value_p ) {
					$wpml_active_languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
					foreach ( $wpml_active_languages as $lang_key => $lang_value ) {
						$allowed_products[] = wpml_object_id_filter( $value_p, 'product', true, $lang_key );
					}
				}
				$allowed_products = implode( ',', $allowed_products );
			}
			?>
			<select name="depend_variations[]" class="depend-select2" multiple="multiple" placeholder="<?php echo esc_html__( 'Choose required variations', 'yith-woocommerce-product-add-ons' ); ?>...">
				<?php YITH_WAPO_Admin::echo_product_chosen_list( $allowed_products, $group->categories_id, $depend_variations_array ); ?>
			</select>
			<?php
		}

		/**
		 * Addon Operator Template
		 *
		 * @param YITH_WAPO_Type $type Type.
		 */
		public function addon_operator_template( $type ) {
			?>
			<label for="depend">
				<?php echo esc_html__( 'Operator', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Select the operator for Options Requirements. Default: OR', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</label>
			<select name="operator">
				<option value="or">OR</option>
				<option value="and" <?php selected( $type->operator, 'and' ); ?>>AND</option>
			</select>
			<?php
		}

		/**
		 * Addon Options Template
		 *
		 * @param array $options Options.
		 */
		public function addon_options_template( $options ) {
			$field_first_options_free       = $options['field_first_options_free'];
			$field_max_item_selected        = $options['field_max_item_selected'];
			$field_minimum_product_quantity = $options['field_minimum_product_quantity'];
			$field_max_input_values_amount  = $options['field_max_input_values_amount'];
			$field_min_input_values_amount  = $options['field_min_input_values_amount'];
			$field_qty_individually         = $options['field_qty_individually'];
			$field_change_featured_image    = $options['field_change_featured_image'];
			$field_calculate_quantity_sum   = $options['field_calculate_quantity_sum'];
			$field_required                 = $options['field_required'];
			$field_required_all_options     = $options['field_required_all_options'];
			$field_collapsed                = $options['field_collapsed'];
			?>
			<div class="first_options_free">
				<?php echo esc_html__( 'The first', 'yith-woocommerce-product-add-ons' ); ?>
				<input name="first_options_free" type="number" value="<?php echo esc_html( $field_first_options_free ); ?>" class="regular-text" min="0">
				<?php echo esc_html__( 'options are free', 'yith-woocommerce-product-add-ons' ); ?>
			</div>
			<div class="max_item_selected">
				<input name="max_item_selected" type="number" value="<?php echo esc_html( $field_max_item_selected ); ?>" class="regular-text" min="0">
				<?php echo esc_html__( 'Limit selectable elements', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Set the maximum number of elements that users can select for this add-on, 0 means no limits (works only with checkboxes)', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<?php if ( apply_filters( 'wapo_enable_minimum_product_quantity', false ) ) : ?>
				<div class="minimum_product_quantity">
					<input name="minimum_product_quantity" type="number" value="<?php echo esc_html( $field_minimum_product_quantity ); ?>" class="regular-text" min="0">
					<?php echo esc_html__( 'Minimum product quantity', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				</div>
			<?php endif; ?>
			<div class="max_input_values_amount">
				<input name="max_input_values_amount" type="number" value="<?php echo esc_html( $field_max_input_values_amount ); ?>" class="regular-text" min="0">
				<?php echo esc_html__( 'Max input values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Set the maximum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<div class="min_input_values_amount">
				<input name="min_input_values_amount" type="number" value="<?php echo esc_html( $field_min_input_values_amount ); ?>" class="regular-text" min="0">
				<?php echo esc_html__( 'Min input values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Set the minimum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<div class="sold_individually">
				<input type="checkbox" name="sold_individually" value="1" <?php echo $field_qty_individually ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'Sold individually', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Check this box, if you want the selected add-ons not to increase if the product quantity changes.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</div>
			<div class="change_featured_image">
				<input type="checkbox" name="change_featured_image" value="1" <?php echo $field_change_featured_image ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'Replace the product image', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Check this box, if you want that the selected add-ons replace the product image.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</div>
			<div class="calculate_quantity_sum">
				<input type="checkbox" name="calculate_quantity_sum" value="1" <?php echo $field_calculate_quantity_sum ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'Calculate quantity by values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Check this box, if you want that the quantity input will be updated with the sum of all add-ons values.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</div>
			<div class="required">
				<input type="checkbox" name="required" value="1" <?php echo $field_required ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Check this option if you want the add-ons to be selected', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<div class="required_all_options">
				<input type="checkbox" name="required_all_options" value="1" <?php echo $field_required_all_options ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'All options required', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'Check this option if you want that the add-ons have all options required.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<div class="collapsed">
				<input type="checkbox" name="collapsed" value="1" <?php echo $field_collapsed ? 'checked="checked"' : ''; ?>>
				<?php echo esc_html__( 'Collapsed by default', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_html__( 'If not selected it will take settings in Admin > YITH Plugins > Product Add-ons', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
			</div>
			<?php
		}

	}

}

new YITH_WAPO_Premium();

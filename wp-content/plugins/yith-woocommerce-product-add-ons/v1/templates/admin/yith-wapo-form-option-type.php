<?php
/**
 * Admin Type Form
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 *
 * @var String $group
 * @var String $type
 */

defined( 'ABSPATH' ) || exit;

$is_edit                        = isset( $type );
$act                            = 'new';
$priority                       = 0;
$field_type                     = '';
$field_image_url                = YITH_WAPO_URL . '/v1/assets/img/placeholder.png';
$field_image                    = '';
$field_id_img_class             = 'form-add';
$field_label                    = '';
$field_description              = '';
$field_required                 = false;
$field_required_all_options     = true;
$field_collapsed                = false;
$field_qty_individually         = false;
$field_first_options_free       = 0;
$field_max_item_selected        = 0;
$field_minimum_product_quantity = 0;
$field_max_input_values_amount  = 0;
$field_min_input_values_amount  = 0;
$field_change_featured_image    = false;
$field_calculate_quantity_sum   = false;
$field_description              = '';
$field_priority                 = '';

$dependencies_query = YITH_WAPO_Admin::getDependeciesQuery( $wpdb, $group, $type, $is_edit );

if ( $is_edit ) {
	$act            = 'update';
	$field_priority = $type->priority;
	$field_type     = $type->type;
	if ( $type->image ) {
		$field_image_url = $type->image;
		$field_image     = $type->image;
	}
	$field_id_img_class             = $type->id;
	$field_label                    = $type->label;
	$field_required                 = $type->required;
	$field_required_all_options     = $type->required_all_options;
	$field_collapsed                = $type->collapsed;
	$field_description              = $type->description;
	$field_qty_individually         = $type->sold_individually;
	$field_first_options_free       = $type->first_options_free;
	$field_max_item_selected        = $type->max_item_selected;
	$field_minimum_product_quantity = $type->minimum_product_quantity;
	$field_max_input_values_amount  = $type->max_input_values_amount;
	$field_min_input_values_amount  = $type->min_input_values_amount;
	$field_change_featured_image    = $type->change_featured_image;
	$field_calculate_quantity_sum   = $type->calculate_quantity_sum;
}
?>

<form action="edit.php?post_type=product&page=yith_wapo_group_addons" method="post" class="<?php echo esc_attr( $field_type ); ?>">
	<?php if ( $is_edit ) : ?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $type->id ); ?>">
	<?php endif; ?>

	<input type="hidden" name="act" value="<?php echo esc_attr( $act ); ?>">
	<input type="hidden" name="class" value="YITH_WAPO_Type">
	<input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">
	<input type="hidden" name="priority" value="<?php echo esc_attr( $field_priority ); ?>">
	<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>">

	<div class="form-left"
		<?php
		if ( ! $is_edit ) {
			echo ' style="margin: 5px 0px;"';
		}
		?>
	>
		<div class="form-row">

			<div class="type">
				<?php
				if ( $is_edit ) :
					?>
					<label for="label"><?php echo esc_html__( 'Type', 'yith-woocommerce-product-add-ons' ); ?></label><?php endif; ?>
				<select name="type">
					<?php do_action( 'yith_wapo_type_options_template', $field_type ); ?>
				</select>
			</div>

		</div>
		<?php if ( $is_edit ) : ?>
			<div class="form-row">

				<div class="image">
					<label for="image"><?php echo esc_html__( 'Image', 'yith-woocommerce-product-add-ons' ); ?></label>
					<input class="image" type="hidden" name="image" size="60" value="<?php echo esc_attr( $field_image ); ?>">
					<img class="thumb image image-upload" src="<?php echo esc_attr( $field_image_url ); ?>" height="100"/>
					<span class="dashicons dashicons-no remove"></span>
				</div>

			</div>
		<?php endif; ?>
	</div>

	<div class="form-right">

		<?php if ( $is_edit ) : ?>

			<div class="form-row">

				<div class="label">
					<label for="label"><?php echo esc_html__( 'Title', 'yith-woocommerce-product-add-ons' ); ?></label>
					<input name="label" type="text" value="<?php echo esc_attr( stripslashes( $field_label ) ); ?>" class="regular-text">
				</div>

				<div class="variations">
					<?php do_action( 'yith_wapo_depend_variations_template', $type, $group ); ?>
				</div>

				<div class="operator">
					<?php do_action( 'yith_wapo_addon_operator_template', $type ); ?>
				</div>

				<div class="depend">
					<label for="depend"><?php echo esc_html__( 'Options Requirements', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
						<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Show this add-on only after selecting the following options.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span></label>
					<select name="depend[]" class="depend-select2" multiple="multiple"
							placeholder="<?php echo esc_html__( 'Choose required add-ons', 'yith-woocommerce-product-add-ons' ); ?>...">
						<?php
						$dependencies = $wpdb->get_results( $dependencies_query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						foreach ( $dependencies as $key => $item ) {
							if ( '' !== $item->label ) {
								$depend_array   = explode( ',', $type->depend );
								$options_values = maybe_unserialize( $item->options );
								if ( isset( $options_values['label'] ) ) {
									foreach ( $options_values['label'] as $option_key => $option_value ) {
										$attribute_value = 'option_' . $item->id . '_' . $option_key;
										echo '<option value="' . esc_attr( $attribute_value ) . '" ' . ( in_array( $attribute_value, $depend_array, true ) ? 'selected="selected"' : '' ) . '>' . esc_html( $item->label ) . ' [ ' . esc_html( $option_value ) . ' ]</option>';
									}
								}
							}
						}
						?>
					</select>
				</div>

			</div>
			<div class="form-row">

				<div class="description">
					<label for="description"><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?></label>
					<textarea name="description" id="description" rows="3" style="width: 100%; height: 120px; margin-top: 3px;"><?php echo esc_html( stripslashes( $field_description ) ); ?></textarea>
				</div>

			</div>
			<div class="form-row">
				<?php
				do_action(
					'yith_wapo_addon_options_template',
					array(
						'field_first_options_free'       => $field_first_options_free,
						'field_max_item_selected'        => $field_max_item_selected,
						'field_minimum_product_quantity' => $field_minimum_product_quantity,
						'field_max_input_values_amount'  => $field_max_input_values_amount,
						'field_min_input_values_amount'  => $field_min_input_values_amount,
						'field_qty_individually'         => $field_qty_individually,
						'field_change_featured_image'    => $field_change_featured_image,
						'field_calculate_quantity_sum'   => $field_calculate_quantity_sum,
						'field_required'                 => $field_required,
						'field_required_all_options'     => $field_required_all_options,
						'field_collapsed'                => $field_collapsed,
					)
				);
				?>
			</div>

		<?php endif; ?>

	</div>

	<div class="clear"></div>

	<?php if ( $is_edit ) : ?>

		<div class="form-row">
			<div class="options">
				<table class="wp-list-table widefat fixed yith_wapo_option_table">
					<thead>
					<tr>
						<th class="option-sort"><?php echo esc_html__( 'Sort', 'yith-woocommerce-product-add-ons' ); ?></th>
						<th class="option-image"><?php echo esc_html__( 'Image', 'yith-woocommerce-product-add-ons' ); ?></th>
						<th class="option-label"><?php echo esc_html__( 'Settings', 'yith-woocommerce-product-add-ons' ); ?></th>
						<th class="option-actions"><?php echo esc_html__( 'Actions', 'yith-woocommerce-product-add-ons' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					$i             = 0;
					$array_options = maybe_unserialize( $type->options );
					if ( isset( $array_options['label'] ) && is_array( $array_options['label'] ) ) {
						$array_default    = $array_options['default'] ?? array();
						$array_required   = $array_options['required'] ?? array();
						$array_hidelabel  = $array_options['hidelabel'] ?? array();
						$array_hideoption = $array_options['hideoption'] ?? array();
						foreach ( $array_options['label'] as $key => $value ) :
							if ( ! isset( $array_options['description'][ $i ] ) ) {
								$array_options['description'][ $i ] = '';
							}
							if ( ! isset( $array_options['placeholder'][ $i ] ) ) {
								$array_options['placeholder'][ $i ] = '';
							}
							if ( ! isset( $array_options['tooltip'][ $i ] ) ) {
								$array_options['tooltip'][ $i ] = '';
							}
							?>
							<tr class="yith_wapo_option_row">
								<td class="option-sort"><i class="dashicons dashicons-move"></i></td>
								<td>
									<div id="option-image-<?php echo esc_attr( $i ); ?>" class="option-image">
										<div class="image">
											<?php
											$isset_img = isset( $array_options['image'] ) && isset( $array_options['image'][ $i ] ) && '' !== $array_options['image'][ $i ];
											$image_url = $isset_img ? $array_options['image'][ $i ] : '';
											$image_alt = $isset_img && isset( $array_options['image_alt'][ $i ] ) && apply_filters( 'yith_wapo_display_image_alt', true ) ? $array_options['image_alt'][ $i ] : '';
											?>
											<input class="opt-image" type="hidden" name="options[image][]" size="60"  value="<?php echo esc_attr( $image_url ); ?>">
											<input class="opt-image-alt" type="hidden" name="options[image_alt][]"  value="<?php echo esc_attr( $image_alt ); ?>">
											<img class="thumb opt-image opt-image-upload"
												src="<?php echo $image_url ? esc_attr( $image_url ) : esc_attr( YITH_WAPO_URL ) . '/v1/assets/img/placeholder.png'; ?>"
												alt="<?php echo esc_attr( $image_alt ); ?>" height="100"/>
											<span class="dashicons dashicons-no opt-remove"></span>
										</div>
									</div>
								</td>
								<td>
									<div class="option-label">
										<small><?php echo esc_html__( 'Option Label', 'yith-woocommerce-product-add-ons' ); ?>
											(<?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>
											)</small>
										<input type="text" name="options[label][]" value="<?php echo esc_attr( stripslashes( htmlspecialchars( $array_options['label'][ $i ] ) ) ); ?>"/>
									</div>
									<div class="option-description">
										<small><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[description][]" value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options['description'][ $i ] ) ) ); ?>"/>
									</div>
									<div class="option-placeholder">
										<small><?php echo esc_html__( 'Placeholder', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[placeholder][]" value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options['placeholder'][ $i ] ) ) ); ?>"/>
									</div>
									<div class="option-tooltip">
										<small><?php echo esc_html__( 'Tooltip', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[tooltip][]" value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options['tooltip'][ $i ] ) ) ); ?>"/>
									</div>
									<div class="clear"></div>

									<?php if ( apply_filters( 'yith_wapo_wpml_direct_translation', false ) && function_exists( 'icl_object_id' ) ) : ?>
										<div style="background-color: #fed;">
											<?php
											$languages    = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
											$wpml_options = get_option( 'icl_sitepress_settings' );
											$default_lang = $wpml_options['default_language'];
											foreach ( $languages as $key => $value ) :
												if ( $key !== $default_lang ) :
													$array_options[ 'label_' . $key ][ $i ]       = $array_options[ 'label_' . $key ][ $i ] ?? '';
													$array_options[ 'description_' . $key ][ $i ] = $array_options[ 'description_' . $key ][ $i ] ?? '';
													$array_options[ 'placeholder_' . $key ][ $i ] = $array_options[ 'placeholder_' . $key ][ $i ] ?? '';
													$array_options[ 'tooltip_' . $key ][ $i ]     = $array_options[ 'tooltip_' . $key ][ $i ] ?? '';
													?>
													<div class="option-label">
														<small><?php echo esc_html__( 'Option Label', 'yith-woocommerce-product-add-ons' ) . ' <strong>(' . esc_html( strtoupper( $key ) ) . ')</strong>'; ?></small>
														<input type="text" name="options[label_<?php echo esc_attr( $key ); ?>][]" value="<?php echo esc_attr( stripslashes( htmlspecialchars( $array_options[ 'label_' . $key ][ $i ] ) ) ); ?>"/>
													</div>
													<div class="option-description">
														<small><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ) . ' <strong>(' . esc_html( strtoupper( $key ) ) . ')</strong>'; ?></small>
														<input type="text"
															name="options[description_<?php echo esc_attr( $key ); ?>][]"
															value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options[ 'description_' . $key ][ $i ] ) ) ); ?>"/>
													</div>
													<div class="option-placeholder">
														<small><?php echo esc_html__( 'Placeholder', 'yith-woocommerce-product-add-ons' ) . ' <strong>(' . esc_html( strtoupper( $key ) ) . ')</strong>'; ?></small>
														<input type="text"
															name="options[placeholder_<?php echo esc_attr( $key ); ?>][]"
															value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options[ 'placeholder_' . $key ][ $i ] ) ) ); ?>"/>
													</div>
													<div class="option-tooltip">
														<small><?php echo esc_html__( 'Tooltip', 'yith-woocommerce-product-add-ons' ) . ' <strong>(' . esc_attr( strtoupper( $key ) ) . ')</strong>'; ?></small>
														<input type="text" name="options[tooltip_<?php echo esc_attr( $key ); ?>][]" value="<?php echo esc_attr( htmlspecialchars( stripslashes( $array_options[ 'tooltip_' . $key ][ $i ] ) ) ); ?>"/>
													</div>
													<?php
												endif;
											endforeach;
											?>
											<div class="clear"></div>
										</div>
									<?php endif; ?>

									<div class="option-price">
										<small><?php echo esc_html__( 'Price', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[price][]" value="<?php echo esc_attr( $array_options['price'][ $i ] ); ?>" placeholder="0"/>
									</div>
									<div class="option-type">
										<small><?php echo esc_html__( 'Amount', 'yith-woocommerce-product-add-ons' ); ?></small>
										<select name="options[type][]">
											<option value="fixed" <?php echo isset( $array_options['type'][ $i ] ) && 'fixed' === $array_options['type'][ $i ] ? 'selected="selected"' : ''; ?>>
												<?php echo esc_html__( 'Fixed', 'yith-woocommerce-product-add-ons' ); ?>
											</option>
											<option value="percentage" <?php echo isset( $array_options['type'][ $i ] ) && 'percentage' === $array_options['type'][ $i ] ? 'selected="selected"' : ''; ?>>
												<?php echo esc_html__( '% markup', 'yith-woocommerce-product-add-ons' ); ?>
											</option>
											<option value="calculated_multiplication" <?php echo isset( $array_options['type'][ $i ] ) && 'calculated_multiplication' === $array_options['type'][ $i ] ? 'selected="selected"' : ''; ?>>
												<?php echo esc_html__( 'Multiplied by option numeric value', 'yith-woocommerce-product-add-ons' ); ?>
											</option>
											<option value="calculated_character_count" <?php echo isset( $array_options['type'][ $i ] ) && 'calculated_character_count' === $array_options['type'][ $i ] ? 'selected="selected"' : ''; ?>>
												<?php echo esc_html__( 'Multiplied by option string length', 'yith-woocommerce-product-add-ons' ); ?>
											</option>
										</select>
									</div>
									<div class="option-min">
										<small><?php echo esc_html__( 'Min', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[min][]" value="<?php echo esc_attr( $array_options['min'][ $i ] ?? '' ); ?>" placeholder="0"/>
									</div>
									<div class="option-max">
										<small><?php echo esc_html__( 'Max', 'yith-woocommerce-product-add-ons' ); ?></small>
										<input type="text" name="options[max][]" value="<?php echo esc_attr( $array_options['max'][ $i ] ?? '' ); ?>" placeholder="0"/>
									</div>
									<div class="option-default">
										<small><?php echo esc_html__( 'Checked', 'yith-woocommerce-product-add-ons' ); ?>
											<br/></small>
										<input type="checkbox" name="options[default][]" value="<?php echo esc_attr( $i ); ?>"
											<?php
											foreach ( $array_default as $key_def => $value_def ) {
												echo intval( $value_def ) === $i ? 'checked="checked"' : '';
											}
											?>
										/>
									</div>
									<div class="option-required">
										<small><?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>
											<br/></small>
										<input type="checkbox" name="options[required][]" value="<?php echo esc_attr( $i ); ?>"
											<?php
											foreach ( $array_required as $key_def => $value_def ) {
												echo intval( $value_def ) === $i ? 'checked="checked"' : '';
											}
											?>
										/>
									</div>
									<div class="option-hidelabel">
										<small><?php echo esc_html__( 'Hide Label', 'yith-woocommerce-product-add-ons' ); ?>
											<br/></small>
										<input type="checkbox" name="options[hidelabel][]" value="<?php echo esc_attr( $i ); ?>"
											<?php
											foreach ( $array_hidelabel as $key_def => $value_def ) {
												echo intval( $value_def ) === $i ? 'checked="checked"' : '';
											}
											?>
										/>
									</div>
									<div class="option-hideoption">
										<small><?php echo esc_html__( 'Hide Option', 'yith-woocommerce-product-add-ons' ); ?>
											<br/></small>
										<input type="checkbox" name="options[hideoption][]" value="<?php echo esc_attr( $i ); ?>"
											<?php
											foreach ( $array_hideoption as $key_def => $value_def ) {
												echo intval( $value_def ) === $i ? 'checked="checked"' : '';
											}
											?>
										/>
									</div>
								</td>
								<td>
									<div class="option-actions">
										<br/>
										<a class="button duplicate-row" title="<?php echo esc_html__( 'Duplicate', 'yith-woocommerce-product-add-ons' ); ?>">
											<span class="dashicons dashicons-admin-page" style="line-height: 27px;"></span>
										</a>
										<br/>
										<a class="button remove-row" title="<?php echo esc_html__( 'Delete', 'yith-woocommerce-product-add-ons' ); ?>">
											<span class="dashicons dashicons-dismiss" style="line-height: 27px;"></span>
										</a>
									</div>
								</td>
							</tr>
							<?php
							$i ++;
						endforeach;
					}
					?>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="8">
							<a class="button add_option"><span class="dashicons dashicons-plus" style="line-height: 28px;"></span> <?php echo esc_html__( 'Add new option', 'yith-woocommerce-product-add-ons' ); ?>
							</a>
						</td>
					</tr>
					</tfoot>
				</table>
			</div>
		</div>

	<?php endif; ?>

	<div class="form-row">
		<div class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( $is_edit ? __( 'Save this add-on', 'yith-woocommerce-product-add-ons' ) : __( 'Continue', 'yith-woocommerce-product-add-ons' ) ); ?>">
			<?php if ( ! $is_edit ) : ?>
				<a href="#" class="button cancel"><?php echo esc_html__( 'Cancel', 'yith-woocommerce-product-add-ons' ); ?></a>
			<?php endif; ?>
		</div>
	</div>

</form>

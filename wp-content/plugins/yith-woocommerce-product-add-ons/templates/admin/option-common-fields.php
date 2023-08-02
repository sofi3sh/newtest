<?php
/**
 * Option Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var int $x
 * @var string $addon_type
 * @var YITH_WAPO_Addon $addon
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$price_type = $addon->get_option( 'price_type', $x, 'fixed' );

?>

<?php if ( 'product' !== $addon_type ) : ?>

	<div class="col-left">

		<?php if ( 'color' === $addon_type ) : ?>
			<!-- Option field -->
			<div class="field-wrap">
				<label for="option-color-type-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Show as', 'yith-woocommerce-product-add-ons' ); ?></label>
				<div class="field color-show-as">
					<?php
						$option_color_type = $addon->get_option( 'color_type', $x, 'single' );
						yith_plugin_fw_get_field(
							array(
								'id'      => 'option-color-type-' . $x,
								'class'   => 'option-color-type',
								'name'    => 'options[color_type][]',
								'type'    => 'select',
								'value'   => $option_color_type,
								'options' => array(
									'dingle' => __( 'Single color swatch', 'yith-woocommerce-product-add-ons' ),
									'double' => __( 'Double color swatch', 'yith-woocommerce-product-add-ons' ),
									'image'  => __( 'Image swatch', 'yith-woocommerce-product-add-ons' ),
								),
							),
							true
						);
					?>
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label for="option-label-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Label', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<input type="text" name="options[label][]" id="option-label-<?php echo esc_attr( $x ); ?>" value="<?php echo esc_html( $addon->get_option( 'label', $x ) ); ?>">
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap">
			<label for="option-description-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<input type="text" name="options[description][]" id="option-description-<?php echo esc_attr( $x ); ?>" value="<?php echo esc_html( $addon->get_option( 'description', $x ) ); ?>">
			</div>
		</div>
		<!-- End option field -->

	</div>

	<div class="col-right">

		<?php if ( 'color' === $addon_type ) : ?>
			<!-- Option field -->
			<div class="field-wrap color">
				<label for="option-color-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Color', 'yith-woocommerce-product-add-ons' ); ?></label>
				<div class="field">
					<?php
					yith_plugin_fw_get_field(
						array(
							'id'            => 'option-color-' . $x,
							'name'          => 'options[color][]',
							'type'          => 'colorpicker',
							'alpha_enabled' => true,
							'default'       => '#AA0000',
							'value'         => $addon->get_option( 'color', $x, '#AA0000' ),
						),
						true
					);
					?>
				</div>
			</div>
			<!-- End option field -->
			<!-- Option field -->
			<div class="field-wrap color_b" style="display: none;">
				<label for="option-color-b-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Color', 'yith-woocommerce-product-add-ons' ); ?></label>
				<div class="field">
					<?php
					yith_plugin_fw_get_field(
						array(
							'id'            => 'option-color-b-' . $x,
							'name'          => 'options[color_b][]',
							'type'          => 'colorpicker',
							'alpha_enabled' => true,
							'default'       => '#AA0000',
							'value'         => $addon->get_option( 'color_b', $x, '#AA0000' ),
						),
						true
					);
					?>
				</div>
			</div>
			<!-- End option field -->
			<!-- Option field -->
			<div class="field-wrap color_image" style="display: none;">
				<label for="option-color-image-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Image', 'yith-woocommerce-product-add-ons' ); ?>:</label>
				<div class="field">
					<?php
					yith_plugin_fw_get_field(
						array(
							'id'    => 'option-color-image-' . $x,
							'name'  => 'options[color_image][' . $x . ']',
							'type'  => 'upload',
							'value' => $addon->get_option( 'color_image', $x ),
						),
						true
					);
					?>
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

		<?php if ( 'select' !== $addon_type ) : ?>
			<!-- Option field -->
			<div class="field-wrap">
				<label for="option-tooltip-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Tooltip', 'yith-woocommerce-product-add-ons' ); ?>:</label>
				<div class="field">
					<input type="text" name="options[tooltip][]" id="option-tooltip-<?php echo esc_attr( $x ); ?>" value="<?php echo esc_html( $addon->get_option( 'tooltip', $x ) ); ?>">
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

	</div>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-show-image-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Add image', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-show-image-' . $x,
					'class' => 'enabler',
					'name'  => 'options[show_image][' . $x . ']',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'show_image', $x ),
				),
				true
			);
			?>
			<span class="description">
				<?php echo esc_html__( 'Enable to upload an image for this option.', 'yith-woocommerce-product-add-ons' ); ?>
				<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
					<br />
					<?php echo wp_kses_post( __( 'You can use this image to <b>replace the default product image</b> (enabling the option in Display Settings tab).', 'yith-woocommerce-product-add-ons' ) ); ?>
				<?php endif; ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-show-image-<?php echo esc_attr( $x ); ?>" style="display: none;">
		<label for="option-image-<?php echo esc_attr( $x ); ?>"><?php echo esc_html__( 'Image', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-image-' . $x,
					'class' => 'option-image',
					'name'  => 'options[image][]',
					'type'  => 'upload',
					'value' => $addon->get_option( 'image', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

<?php else : ?>

	<!-- Option field -->
	<input type="hidden" name="options[label][]" value="Product name">
	<!-- End option field -->

<?php endif; ?>

<!-- Option field -->
<div class="field-wrap">
	<label><?php echo esc_html__( 'Price', 'yith-woocommerce-product-add-ons' ); ?>:</label>
	<div class="field">
		<?php
		$option_price_method = $addon->get_option( 'price_method', $x, 'free' );
		$price_options       = array(
			'free'     => __( 'Product price doesn\'t change - set option as free', 'yith-woocommerce-product-add-ons' ),
			'increase' => __( 'Increase the main product price', 'yith-woocommerce-product-add-ons' ),
			'decrease' => __( 'Discount the main product price', 'yith-woocommerce-product-add-ons' ),
		);
		if ( 'number' === $addon_type ) {
			$price_options = array(
				'free'            => __( 'Product price doesn\'t change - set option as free', 'yith-woocommerce-product-add-ons' ),
				'increase'        => __( 'Increase the main product price', 'yith-woocommerce-product-add-ons' ),
				'decrease'        => __( 'Discount the main product price', 'yith-woocommerce-product-add-ons' ),
				'value_x_product' => __( 'Value multiplied by product price', 'yith-woocommerce-product-add-ons' ),
			);
		}
		if ( 'product' === $addon_type ) {
			$option_price_method = $addon->get_option( 'price_method', $x, 'product' );
			$price_options       = array(
				'free'     => __( 'Product price doesn\'t change - set option as free', 'yith-woocommerce-product-add-ons' ),
				'increase' => __( 'Increase the main product price', 'yith-woocommerce-product-add-ons' ),
				'decrease' => __( 'Discount the main product price', 'yith-woocommerce-product-add-ons' ),
				'product'  => __( 'Use price of linked product', 'yith-woocommerce-product-add-ons' ),
				'discount' => __( 'Discount price of linked product', 'yith-woocommerce-product-add-ons' ),
			);
		}
		yith_plugin_fw_get_field(
			array(
				'id'      => 'option-price-method-' . $x,
				'class'   => 'option-price-method',
				'name'    => 'options[price_method][' . $x . ']',
				'type'    => 'select',
				'value'   => $option_price_method,
				'options' => $price_options,
			),
			true
		);
		?>
	</div>
</div>
<!-- End option field -->

<!-- Option field -->
<div id="option-cost-<?php echo esc_attr( $x ); ?>" class="field-wrap option-cost"
	style="<?php echo 'increase' !== $option_price_method && 'decrease' !== $option_price_method && 'discount' !== $option_price_method ? 'display: none;' : ''; ?>">
	<label><?php echo esc_html__( 'Option cost', 'yith-woocommerce-product-add-ons' ); ?>:</label>
	<div class="field">
		<small class="option-price-method-increase" style="<?php echo 'decrease' === $option_price_method || 'discount' === $option_price_method ? 'display: none;' : ''; ?>">
			<?php echo esc_html__( 'REGULAR', 'yith-woocommerce-product-add-ons' ); ?>
		</small>
		<small class="option-price-method-decrease" style="<?php echo 'increase' === $option_price_method ? 'display: none;' : ''; ?>">
			<?php echo esc_html__( 'DISCOUNT', 'yith-woocommerce-product-add-ons' ); ?>
		</small>
		<input type="text" name="options[price][]" id="option-price" value="<?php echo esc_html( $addon->get_option( 'price', $x ) ); ?>" class="mini">
	</div>
	<div class="field option-price-sale" style="<?php echo 'multiplied' === $price_type || 'decrease' === $option_price_method || 'discount' === $option_price_method ? 'display: none;' : ''; ?>">
		<small><?php echo esc_html__( 'SALE', 'yith-woocommerce-product-add-ons' ); ?></small>
		<input type="text" name="options[price_sale][]" id="option-price-sale" value="<?php echo esc_html( $addon->get_option( 'price_sale', $x ) ); ?>" class="mini">
	</div>
	<div class="field">
		<?php
		$price_options = array(
			'fixed'      => __( 'Fixed amount', 'yith-woocommerce-product-add-ons' ),
			'percentage' => __( 'Percentage', 'yith-woocommerce-product-add-ons' ),
		);
		if ( 'number' === $addon_type ) {
			$price_options['multiplied'] = __( 'Price multiplied by value', 'yith-woocommerce-product-add-ons' );
		}
		if ( 'text' === $addon_type || 'textarea' === $addon_type ) {
			$price_options['characters'] = __( 'Price multiplied by string length', 'yith-woocommerce-product-add-ons' );
		}
		yith_plugin_fw_get_field(
			array(
				'id'      => 'option-price-type',
				'name'    => 'options[price_type][' . $x . ']',
				'type'    => 'select',
				'value'   => $addon->get_option( 'price_type', $x, 'fixed' ),
				'options' => $price_options,
			),
			true
		);
		?>
	</div>
</div>
<!-- End option field -->

<?php if ( in_array( $addon_type, array( 'checkbox', 'color', 'label', 'product', 'radio', 'select' ), true ) ) : ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-default"><?php echo esc_html__( 'Selected by default', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-default-' . $x,
					'name'  => 'options[default][' . $x . ']',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'default', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

<?php endif; ?>

<?php if ( 'select' !== $addon_type && 'date' !== $addon_type && 'radio' !== $addon_type ) : ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-required-' . $x,
					'name'  => 'options[required][' . $x . ']',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'required', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

<?php endif; ?>

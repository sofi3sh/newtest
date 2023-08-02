<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$option_product = $addon->get_option( 'product', $x );
$_product       = wc_get_product( $option_product );
if ( $_product instanceof WC_Product ) {
	$_product_name  = $_product->get_title();
	if ( $_product instanceof WC_Product_Variation ) {
		$variation = new WC_Product_Variation( $option_product );
		$var_attributes = implode(" / ", $variation->get_variation_attributes() );
		$_product_name = $_product_name . ' - ' . $var_attributes;
	}
	$_product_price = $_product->get_price();
	$_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $option_product ), 'thumbnail' );
	$price_method   = $addon->get_option( 'price_method', $x );
	$price_type = '';

	if ( 'product' !== $price_method ) {
		$price_type     = $addon->get_option( 'price_type', $x );
	}

	$selected = $addon->get_option( 'default', $x ) === 'yes' ? 'selected' : '';
	$checked  = $addon->get_option( 'default', $x ) === 'yes' ? 'checked="checked"' : '';
	$instock  = $_product->is_in_stock();

	$option_price      = ! empty( $price_sale ) ? $price_sale : $price;
	$option_price_html = '';
	if ( 'product' === $price_method ) {
		$option_price      = $_product_price;
		$option_price      = $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() );
		$option_price_html = $addon->get_setting( 'hide_products_prices' ) !== 'yes' ? '<small class="option-price">' . wc_price( $option_price ) . '</small>' : '';

	} elseif ( 'discount' === $price_method ) {
		$option_price          = $_product_price;
		$option_discount_value = floatval( $addon->get_option( 'price', $x ) );
		$price_sale            = $option_price - $option_discount_value;
		$option_price          = $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() );
		if ( 'percentage' === $price_type ) {
			$price_sale = $option_price - ( ( $option_price / 100 ) * $option_discount_value );
		}
		$option_price_html = $addon->get_setting( 'hide_products_prices' ) !== 'yes' ?
			'<small class="option-price"><del>' . wc_price( $option_price ) . '</del> ' . wc_price( $price_sale ) . '</small>' : '';
	} else {
		$option_price_html = $addon->get_option_price_html( $x );
	}

	?>

	<div id="yith-wapo-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
	     class="yith-wapo-option selection-<?php echo esc_attr( $selection_type ); ?>
		<?php echo esc_attr( $selected ); ?>"
	     data-replace-image="<?php echo esc_attr( $image_replacement ); ?>">

		<?php
		if ( 'left' === $addon_options_images_position ) {
			include YITH_WAPO_DIR . '/templates/front/option-image.php'; }
		?>

		<input type="checkbox"
		       id="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
		       class="yith-proteo-standard-checkbox"
		       name="yith_wapo[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
		       value="<?php echo 'product-' . esc_attr( $_product->get_id() ) . '-1'; ?>"
		       data-price="<?php echo esc_attr( $option_price ); ?>"
		       data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
		       data-price-type="<?php echo esc_attr( $price_type ); ?>"
		       data-price-method="<?php echo esc_attr( $price_method ); ?>"
		       data-first-free-enabled="<?php echo esc_attr( $addon->get_setting( 'first_options_selected', 'no' ) ); ?>"
		       data-first-free-options="<?php echo esc_attr( $addon->get_setting( 'first_free_options', 0 ) ); ?>"
		       data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
			<?php echo ! $instock ? 'disabled="disabled"' : ''; ?>
			<?php echo esc_attr( $checked ); ?>
			   style="display: none;">

		<label for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" style="<?php echo esc_attr( $options_width_css ); ?>" <?php echo ! $instock ? 'class="disabled"' : ''; ?>>
			<img src="<?php echo esc_attr( $_product_image[0] ); ?>" data-id="<?php echo esc_attr( $option_product ); ?>">
			<div>

				<!-- PRODUCT NAME -->
				<?php echo wp_kses_post( $_product_name ); ?>
				<?php
				if ( $addon->get_setting( 'show_sku' ) === 'yes' && $_product->get_sku() !== '' ) {
					echo '<div><small style="font-size: 11px;">SKU: ' . esc_html( $_product->get_sku() ) . '</small></div>'; }
				?>

				<br />

				<!-- PRICE -->
				<?php echo ! $hide_option_prices ? wp_kses_post( $option_price_html ) : ''; ?>

				<!-- STOCK -->
				<?php
				$stock_class  = '';
				$stock_style  = '';
				$stock_status = '';
				if ( $instock ) {
					$stock_class = 'in-stock';
					$stock_style = 'style="margin-bottom: 10px"';
					if ( $_product->get_manage_stock() ) {
						$stock_status = $_product->get_stock_quantity() . ' ' . esc_html__( 'in stock', 'yith-woocommerce-product-add-ons' );
					} else {
						$stock_status = esc_html__( 'In stock', 'yith-woocommerce-product-add-ons' );
					}
				} else {
					$stock_class  = 'out-of-stock';
					$stock_status = esc_html__( 'Out of stock', 'yith-woocommerce-product-add-ons' );
				}
				$stock_qty = $_product->get_manage_stock() ? $_product->get_stock_quantity() : false;
				if ( $addon->get_setting( 'show_stock' ) ) {
					echo '<div ' . esc_attr( $stock_style ) . '><small class="stock ' . esc_attr( $stock_class ) . '" style="font-size: 11px;">' . esc_html( $stock_status ) . '</small></div>';
				}
				?>

				<?php if ( $_product->get_stock_status() === 'instock' ) : ?>

					<?php if ( $addon->get_setting( 'show_add_to_cart' ) === 'yes' ) : ?>
						<div class="option-add-to-cart">
							<?php if ( $addon->get_setting( 'show_quantity' ) === 'yes' ) : ?>
								<input type="number" class="wapo-product-qty" data-product-id="<?php echo esc_attr( $_product->get_id() ); ?>" name="qty" value="1"
								       style="min-width: 50px; width: 50px; margin-right: 10px; float: left;">
							<?php endif; ?>
							<a href="?add-to-cart=<?php echo esc_attr( $_product->get_id() ); ?>&quantity=1" class="button add_to_cart_button">
								<?php echo esc_html__( 'Add to cart', 'yith-woocommerce-product-add-ons' ); ?>
							</a>
						</div>
					<?php endif; ?>

				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</label>

		<?php
		if ( 'right' === $addon_options_images_position ) {
			include YITH_WAPO_DIR . '/templates/front/option-image.php'; }
		?>

		<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
			<span class="tooltip">
				<span><?php echo esc_attr( $addon->get_option( 'tooltip', $x ) ); ?></span>
			</span>
		<?php endif; ?>

		<?php
		if ( 'above' === $addon_options_images_position ) {
			include YITH_WAPO_DIR . '/templates/front/option-image.php'; }
		?>

		<?php if ( '' !== $option_description ) : ?>
			<p class="description">
				<?php echo wp_kses_post( $option_description ); ?>
			</p>
		<?php endif; ?>

	</div>
<?php
}

<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var object $addon
 * @var int    $x
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title">
	<span class="icon"></span>
	<?php echo esc_html__( 'PRODUCT', 'yith-woocommerce-product-add-ons' ); ?> -
	<?php echo esc_html( $addon->get_option( 'label', $x ) ); ?>
</div>

<div class="fields">

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-product"><?php echo esc_html__( 'Product', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-product-' . $x,
					'name'  => 'options[product][]',
					'type'  => 'ajax-products',
					'value' => $addon->get_option( 'product', $x ),
					'data'  => array(
						'action'   => 'woocommerce_json_search_products_and_variations',
						'security' => wp_create_nonce( 'search-products' ),
					),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<?php require YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

</div>

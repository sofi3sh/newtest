<?php
/**
 * Block Rules Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var YITH_WAPO_Block $block
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$show_in                 = $block->get_rule( 'show_in' );
$show_show_in_products   = 'categories' !== $show_in && 'all' !== $show_in && '' !== $show_in;
$show_show_in_categories = 'categories' === $show_in;

$show_exclude_products            = 'all' === $show_in || 'products' === $show_in || 'categories' === $show_in;
$show_exclude_products_products   = $block->get_rule( 'exclude_products' ) === 'yes';
$show_exclude_products_categories = $block->get_rule( 'exclude_products' ) === 'yes';

?>

<div id="block-rules" style="display: none;">

	<!-- Option field -->
	<div class="field-wrap">
		<label for="yith-wapo-block-rule-show-in"><?php echo esc_html__( 'Show this block of options in', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'yith-wapo-block-rule-show-in',
						'name'    => 'block_rule_show_in',
						'type'    => 'radio',
						'value'   => $block->get_rule( 'show_in', 'all' ),
						'options' => array(
							'all'      => __( 'All products', 'yith-woocommerce-product-add-ons' ),
							'products' => __( 'Specific products and categories', 'yith-woocommerce-product-add-ons' ),
							// 'categories'	=> __( 'Products of specific categories', 'yith-woocommerce-product-add-ons' ), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
						),
					),
					true
				);
				?>
			<span class="description"><?php echo esc_html__( 'Choose to show these options in all products or only specific products or categories.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap yith-wapo-block-rule-show-in-products" style="<?php echo $show_show_in_products ? '' : 'display: none;'; ?>">
		<label for="yith-wapo-block-rule-show-in-products"><?php echo esc_html__( 'Products to include', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
				yith_plugin_fw_get_field(
					array(
						'id'       => 'yith-wapo-block-rule-show-in-products',
						'name'     => 'block_rule_show_in_products',
						'type'     => 'ajax-products',
						'multiple' => true,
						'value'    => $block->get_rule( 'show_in_products' ),
						'data'     => array(
							'action'   => 'woocommerce_json_search_products_and_variations',
							'security' => wp_create_nonce( 'search-products' ),
						),
					),
					true
				);
				?>
			<span class="description"><?php echo esc_html__( 'Choose in which products to show this block.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap yith-wapo-block-rule-show-in-products" style="<?php echo $show_show_in_products ? '' : 'display: none;'; ?>">
		<label for="yith-wapo-block-rule-show-in-categories"><?php echo esc_html__( 'Categories to include', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
				yith_plugin_fw_get_field(
					array(
						'id'       => 'yith-wapo-block-rule-show-in-categories',
						'name'     => 'block_rule_show_in_categories',
						'type'     => 'ajax-terms',
						'multiple' => true,
						'value'    => $block->get_rule( 'show_in_categories' ),
						'data'     => array(
							'placeholder' => __( 'Search for categories', 'yith-woocommerce-product-add-ons' ) . '&hellip;',
							'taxonomy'    => 'product_cat',
						),
					),
					true
				);
				?>
			<span class="description"><?php echo esc_html__( 'Choose in which product categories to show this block.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>

		<!-- Option field -->
		<div class="field-wrap yith-wapo-block-rule-exclude-products" style="<?php echo $show_exclude_products ? '' : 'display: none;'; ?>">
			<label for="yith-wapo-block-rule-exclude-products"><?php echo esc_html__( 'Exclude products', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<?php
					yith_plugin_fw_get_field(
						array(
							'id'    => 'yith-wapo-block-rule-exclude-products',
							'name'  => 'block_rule_exclude_products',
							'type'  => 'onoff',
							'value' => $block->get_rule( 'exclude_products' ),
						),
						true
					);
				?>
				<span class="description"><?php echo esc_html__( 'Enable if you want to hide these options in some products.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap yith-wapo-block-rule-exclude-products-products" style="<?php echo $show_exclude_products_products ? '' : 'display: none;'; ?>">
			<label for="yith-wapo-block-rule-exclude-products-products"><?php echo esc_html__( 'Products to exclude', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<?php
					yith_plugin_fw_get_field(
						array(
							'id'       => 'yith-wapo-block-rule-exclude-products-products',
							'name'     => 'block_rule_exclude_products_products',
							'type'     => 'ajax-products',
							'multiple' => true,
							'value'    => $block->get_rule( 'exclude_products_products' ),
							'data'     => array(
								'action'   => 'woocommerce_json_search_products_and_variations',
								'security' => wp_create_nonce( 'search-products' ),
							),
						),
						true
					);
				?>
				<span class="description"><?php echo esc_html__( 'Choose the products to exclude.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap yith-wapo-block-rule-exclude-products-categories" style="<?php echo $show_exclude_products_categories ? '' : 'display: none;'; ?>">
			<label for="yith-wapo-block-rule-exclude-products-categories"><?php echo esc_html__( 'Categories to exclude', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<?php
					yith_plugin_fw_get_field(
						array(
							'id'       => 'yith-wapo-block-rule-exclude-products-categories',
							'name'     => 'block_rule_exclude_products_categories',
							'type'     => 'ajax-terms',
							'multiple' => true,
							'value'    => $block->get_rule( 'exclude_products_categories' ),
							'data'     => array(
								'placeholder' => __( 'Search for categories', 'yith-woocommerce-product-add-ons' ) . '&hellip;',
								'taxonomy'    => 'product_cat',
							),
						),
						true
					);
				?>
				<span class="description"><?php echo esc_html__( 'Choose the categories to exclude.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap">
			<label for="yith-wapo-block-rule-show-to"><?php echo esc_html__( 'Show options to', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<?php
					global $wp_roles;
					$show_to_user_roles = array();
				foreach ( $wp_roles->roles as $key => $value ) {
					$show_to_user_roles[ $key ] = $value['name'];
				}

					$show_to_options_array = array(
						'all'          => __( 'All users', 'yith-woocommerce-product-add-ons' ),
						'guest_users'  => __( 'Only to guest users', 'yith-woocommerce-product-add-ons' ),
						'logged_users' => __( 'Only to logged-in users', 'yith-woocommerce-product-add-ons' ),
						'user_roles'   => __( 'Only to specified user roles', 'yith-woocommerce-product-add-ons' ),
					);

					if ( function_exists( 'yith_wcmbs_get_plans' ) ) {
						$show_to_options_array['membership'] = __( 'Only to users with a membership plan', 'yith-woocommerce-product-add-ons' );
						$plan_ids                            = yith_wcmbs_get_plans( array( 'fields' => 'ids' ) );
						$plans                               = array_combine( $plan_ids, array_map( 'get_the_title', $plan_ids ) );
					}

					yith_plugin_fw_get_field(
						array(
							'id'      => 'yith-wapo-block-rule-show-to',
							'name'    => 'block_rule_show_to',
							'type'    => 'radio',
							'value'   => $block->get_rule( 'show_to', 'all' ),
							'options' => $show_to_options_array,
						),
						true
					);
				?>
				<span class="description"><?php echo esc_html__( 'Choose to show these options to all users, or only to specific user roles or members of a membership plan.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap yith-wapo-block-rule-show-to-user-roles" style="<?php echo $block->get_rule( 'show_to' ) === 'user_roles' ? '' : 'display: none;'; ?>">
			<label><?php echo esc_html__( 'User roles', 'yith-woocommerce-product-add-ons' ); ?>:</label>
			<div class="field">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'yith-wapo-block-rule-show-to-user-roles',
						'name'    => 'block_rule_show_to_user_roles',
						'type'    => 'select-buttons',
						'value'   => $block->get_rule( 'show_to_user_roles' ),
						'options' => $show_to_user_roles,
					),
					true
				);
				?>
			</div>
		</div>
		<!-- End option field -->

		<?php if ( function_exists( 'yith_wcmbs_get_plans' ) ) : ?>
			<!-- Option field -->
			<div class="field-wrap yith-wapo-block-rule-show-to-membership" style="<?php echo $block->get_rule( 'show_to' ) === 'membership' ? '' : 'display: none;'; ?>">
				<label><?php echo esc_html__( 'Membership plan', 'yith-woocommerce-product-add-ons' ); ?>:</label>
				<div class="field">
					<?php
					yith_plugin_fw_get_field(
						array(
							'id'      => 'yith-wapo-block-rule-show-to-membership',
							'name'    => 'block_rule_show_to_membership',
							'type'    => 'select',
							'value'   => $block->get_rule( 'show_to_membership' ),
							'options' => $plans,
						),
						true
					);
					?>
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

	<?php endif; ?>

</div>

<script type="text/javascript">

	jQuery('.yith-wapo').on('change', '#yith-wapo-block-rule-show-to', function() {
		if ( jQuery(this).val() == 'user_roles' ) {
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-user-roles').fadeIn();
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-membership').hide();
		} else if ( jQuery(this).val() == 'membership' ) {
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-user-roles').hide();
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-membership').fadeIn();
		} else {
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-user-roles').fadeOut();
			jQuery('.field-wrap.yith-wapo-block-rule-show-to-membership').fadeOut();
		}
	});

</script>

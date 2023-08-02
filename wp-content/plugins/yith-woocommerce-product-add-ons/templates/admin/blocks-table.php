<?php
/**
 * Blocks Table Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;

$blocks = yith_wapo_get_blocks();
$nonce  = wp_create_nonce( 'wapo_action' );

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith_wapo_panel_blocks" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<?php if ( count( $blocks ) > 0 ) : ?>

				<div class="list-table-title">
					<h2><?php echo esc_html__( 'Blocks list', 'yith-woocommerce-product-add-ons' ); ?></h2>
					<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button"><?php echo esc_html__( 'Add block', 'yith-woocommerce-product-add-ons' ); ?></a>
				</div>

				<table class="form-table wp-list-table widefat fixed striped table-view-list">
					<thead>
						<tr class="list-table">
							<th class="name"><?php echo esc_html__( 'Name', 'yith-woocommerce-product-add-ons' ); ?></th>
							<!--<th class="priority"><?php echo esc_html__( 'Priority', 'yith-woocommerce-product-add-ons' ); ?></th>-->
							<th class="products"><?php echo esc_html__( 'Show on products:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<th class="categories"><?php echo esc_html__( 'Show on categories:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
								<th class="exc-products"><?php echo esc_html__( 'Exclude products:', 'yith-woocommerce-product-add-ons' ); ?></th>
								<th class="exc-categories"><?php echo esc_html__( 'Exclude categories:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<?php endif; ?>
							<?php if ( class_exists( 'YITH_Vendors' ) ) : ?>
								<th class="vendor"><?php echo esc_html__( 'Vendor:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<?php endif; ?>
							<th class="active"><?php echo esc_html__( 'Active', 'yith-woocommerce-product-add-ons' ); ?></th>
						</tr>
					</thead>
					<tbody id="sortable-blocks">
						<?php foreach ( $blocks as $key => $block ) : ?>

							<tr id="block-<?php echo esc_attr( $block->id ); ?>" data-id="<?php echo esc_attr( $block->id ); ?>" data-priority="<?php echo esc_attr( $block->priority ); ?>">
								<td class="name">
									<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo esc_attr( $block->id ); ?>">
										<?php echo esc_html( empty( $block->name ) ? '-' : $block->name ); ?>
									</a>
								</td>
								<td class="products">
									<?php
									$included_products = (array) $block->get_rule( 'show_in_products' );
									if ( is_array( $included_products ) ) {
										foreach ( $included_products as $key => $value ) {
											if ( $value > 0 ) {
												$_product = wc_get_product( $value );
												if ( is_object( $_product ) ) {
													echo '<div><a href="' . esc_attr( $_product->get_permalink() ) . '" target="_blank">'
														. esc_html( $_product->get_name() ) . ' (#' . esc_html( $_product->get_id() ) . ')</a></div>';
												}
											}
										}
									} else {
										echo '-';
									}
									?>
								</td>
								<td class="categories">
									<?php
									$included_categories = (array) $block->get_rule( 'show_in_categories' );
									if ( is_array( $included_categories ) ) {
										foreach ( $included_categories as $key => $value ) {
											$category = get_term_by( 'id', $value, 'product_cat' );
											if ( is_object( $category ) ) {
												echo '<div><a href="' . esc_attr( get_term_link( $category->term_id, 'product_cat' ) ) . '" target="_blank">'
													. esc_html( $category->name ) . ' (#' . esc_html( $category->term_id ) . ')</div>';
											}
										}
									} else {
										echo '-';
									}
									?>
								</td>
								<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
									<td class="exc-products">
										<?php
										$included_products = (array) $block->get_rule( 'exclude_products_products' );
										if ( is_array( $included_products ) ) {
											foreach ( $included_products as $key => $value ) {
												if ( $value > 0 ) {
													$_product = wc_get_product( $value );
													if ( is_object( $_product ) ) {
														echo '<div><a href="' . esc_attr( $_product->get_permalink() ) . '" target="_blank">'
															. esc_html( $_product->get_title() ) . ' (#' . esc_html( $_product->get_id() ) . ')</a></div>';
													}
												}
											}
										} else {
											echo '-';
										}
										?>
									</td>
									<td class="exc-categories">
										<?php
										$excluded_categories = (array) $block->get_rule( 'exclude_products_categories' );
										if ( is_array( $excluded_categories ) ) {
											foreach ( $excluded_categories as $key => $value ) {
												$category = get_term_by( 'id', $value, 'product_cat' );
												if ( is_object( $category ) ) {
													echo '<div><a href="' . esc_attr( get_term_link( $category->term_id, 'product_cat' ) ) . '" target="_blank">'
														. esc_html( $category->name ) . ' (#' . esc_html( $category->term_id ) . ')</div>';
												}
											}
										} else {
											echo '-';
										}
										?>
									</td>
								<?php endif; ?>
								<?php if ( class_exists( 'YITH_Vendors' ) ) : ?>
									<td class="vendor">
										<?php
										if ( $block->vendor_id > 0 ) {
											$vendor = yith_get_vendor( $block->vendor_id, 'vendor' );
											if ( is_object( $vendor ) && $vendor->is_valid() ) {
												$vendor_url  = get_edit_term_link( $vendor->id, $vendor->taxonomy );
												?>
												<a href="<?php echo esc_url( $vendor_url ) ?>" target="_blank"><?php echo esc_html( stripslashes( $vendor->name ) );?></a>
												<?php
											} else {
												echo '-';
											}
										} else {
											echo '-';
										}
										?>
									</td>
								<?php endif; ?>
								<td class="active">
									<div class="actions" style="display: none;">
										<?php
											$actions = array(
												'edit'   => array(
													'title' => __( 'Edit', 'yith-woocommerce-product-add-ons' ),
													'action' => 'edit',
													'url' => add_query_arg(
														array(
															'page'     => 'yith_wapo_panel',
															'tab'      => 'blocks',
															'block_id' => $block->id,
														),
														admin_url( 'admin.php' )
													),
												),
												'duplicate' => array(
													'title' => __( 'Duplicate', 'yith-woocommerce-product-add-ons' ),
													'action' => 'duplicate',
													'icon' => 'clone',
													'url'  => add_query_arg(
														array(
															'page'        => 'yith_wapo_panel',
															'wapo_action' => 'duplicate-block',
															'block_id'    => $block->id,
															'nonce'       => $nonce,
														),
														admin_url( 'admin.php' )
													),
												),
												'delete' => array(
													'title' => __( 'Delete', 'yith-woocommerce-product-add-ons' ),
													'action' => 'delete',
													'icon' => 'trash',
													'url'  => add_query_arg(
														array(
															'page'        => 'yith_wapo_panel',
															'wapo_action' => 'remove-block',
															'block_id'    => $block->id,
															'nonce'       => $nonce,
														),
														admin_url( 'admin.php' )
													),
													'confirm_data' => array(
														'title'               => __( 'Confirm delete', 'yith-woocommerce-product-add-ons' ),
														'message'             => __( 'Are you sure to delete this block?', 'yith-woocommerce-product-add-ons' ),
														'confirm-button'      => _x( 'Yes, delete', 'Delete confirmation action', 'yith-woocommerce-product-add-ons' ),
														'confirm-button-type' => 'delete',
													),
												),
												'move'   => array(
													'title' => __( 'Move', 'yith-woocommerce-product-add-ons' ),
													'action' => 'move',
													'icon' => 'drag',
													'url'  => '#',
												),
											);

											yith_plugin_fw_get_action_buttons( $actions, true );
											?>
									</div>
									<?php
										yith_plugin_fw_get_field(
											array(
												'id'    => 'yith-wapo-active-block-' . $block->id,
												'type'  => 'onoff',
												'value' => '1' === $block->visibility ? 'yes' : 'no',
											),
											true
										);
									?>
								</td>
							</tr>

						<?php endforeach; ?>
					</tbody>
				</table>


			<?php else : ?>

				<div id="empty-state">
					<img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/empty-state.png">
					<p>
						<?php echo esc_html__( 'You have no options blocks created yet.', 'yith-woocommerce-product-add-ons' ); ?><br />
						<?php echo esc_html__( 'Now build your first block!', 'yith-woocommerce-product-add-ons' ); ?>
					</p>
					<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button"><?php echo esc_html__( 'Add block', 'yith-woocommerce-product-add-ons' ); ?></a>
				</div>

			<?php endif; ?>

			<?php if ( yith_wapo_previous_version_exists() ) : ?>
				<a href="edit.php?post_type=product&page=yith_wapo_groups&yith_wapo_v2=no&nonce=<?php echo esc_attr( wp_create_nonce( 'switch_version' ) ); ?>" class="yith-update-button">
					<?php echo esc_html__( 'Switch to the 1.x version', 'yith-woocommerce-product-add-ons' ); ?>
				</a>
				<?php
				else :
					update_option( 'yith_wapo_settings_disable_wccl', 'yes' );
				endif;
				?>
		</div>
	</div>
</div>

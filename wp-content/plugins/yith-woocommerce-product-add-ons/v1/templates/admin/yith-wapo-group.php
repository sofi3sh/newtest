<?php
/**
 * Admin Products Options Group
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wpdb, $woocommerce;

$group_id = isset( $_REQUEST['id'] ) && $_REQUEST['id'] > 0 ? sanitize_key( $_REQUEST['id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$group    = new YITH_WAPO_Group( $group_id );

$vendor_user = YITH_WAPO::get_current_multivendor();

$show_vendor_column = YITH_WAPO::$is_vendor_installed && ( ! isset( $vendor_user ) || ( isset( $vendor_user ) && is_object( $vendor_user ) && ! $vendor_user->has_limited_access() ) );

$is_less_than_2_7 = version_compare( WC()->version, '2.7', '<' );
?>

<div id="group" class="wrap wapo-plugin">

	<h1>
		<?php
		if ( $group->id > 0 ) {
			echo esc_html__( 'Group', 'yith-woocommerce-product-add-ons' ) . ': ' . esc_html( $group->name );
			echo '<a href="edit.php?post_type=product&page=yith_wapo_group_addons&id=' . esc_attr( $group->id ) . '" class="page-title-action">' . esc_html__( 'Manage Add-ons', 'yith-woocommerce-product-add-ons' ) . '&raquo;</a>';
		} else {
			echo esc_html__( 'New group', 'yith-woocommerce-product-add-ons' ); }
		?>

	</h1>

	<form id="group-form" action="edit.php?post_type=product&page=yith_wapo_group" method="post">

		<input type="hidden" name="id" value="<?php echo esc_attr( $group->id ); ?>">
		<input type="hidden" name="act" value="<?php echo $group->id > 0 ? 'update' : 'new'; ?>">
		<input type="hidden" name="class" value="YITH_WAPO_Group">
		<input type="hidden" name="types-order" value="">
		<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>">

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="name"><?php echo esc_html__( 'Group name', 'yith-woocommerce-product-add-ons' ); ?></label></th>
					<td><input name="name" type="text" value="<?php echo esc_attr( $group->name ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="products_id"><?php echo esc_html__( 'Products', 'yith-woocommerce-product-add-ons' ); ?></label></th>
					<td><?php yith_wapo_multi_products_select( 'products_id[]', $group->products_id ); ?></td>
				</tr>
				<?php do_action( 'yith_wapo_excluded_products_template', array( $group ) ); ?>
				<tr>
					<th scope="row"><label for="categories_id"><?php echo esc_html__( 'Categories', 'yith-woocommerce-product-add-ons' ); ?></label></th>
					<td>
						<select name="categories_id[]" class="categories_id-select2" multiple="multiple" placeholder="<?php echo esc_attr__( 'Applied to...', 'yith-woocommerce-product-add-ons' ); ?>">
							<?php
							$categories_array = explode( ',', $group->categories_id );
							echo_product_categories_childs_of( 0, 0, $categories_array );

							/**
							 * Echo Product Categories Childs of
							 *
							 * @param int   $id               ID.
							 * @param int   $tabs             Tabs.
							 * @param array $categories_array Category array.
							 */
							function echo_product_categories_childs_of( $id = 0, $tabs = 0, $categories_array = array() ) {

								$categories = get_categories(
									array(
										'taxonomy' => 'product_cat',
										'parent'   => $id,
										'orderby'  => 'name',
										'order'    => 'ASC',
									)
								);
								foreach ( $categories as $key => $value ) {
									echo '<option value="' . esc_attr( $value->term_id ) . '" ' . ( in_array( (string) $value->term_id, $categories_array, true ) ? 'selected="selected"' : '' ) . '>' . esc_html( str_repeat( '&#8212;', $tabs ) . ' ' . $value->name ) . '</option>';
									$childs = get_categories(
										array(
											'taxonomy' => 'product_cat',
											'parent'   => $value->term_id,
											'orderby'  => 'name',
											'order'    => 'ASC',
										)
									);
									if ( count( $childs ) > 0 ) {
										echo_product_categories_childs_of( $value->term_id, $tabs + 1, $categories_array );
									}
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="priority"><?php echo esc_html__( 'Priority Order', 'yith-woocommerce-product-add-ons' ); ?></label></th>
					<td><input name="priority" type="number" value="<?php echo esc_attr( $group->priority ); ?>" class="small-text"></td>
				</tr>
				<?php if ( YITH_WAPO::$is_vendor_installed && $show_vendor_column ) : ?>
					<tr>
						<th scope="row"><label for="vendor_id"><?php echo esc_html__( 'Vendor', 'yith-woocommerce-product-add-ons' ); ?></label></th>
						<td>
							<select name="vendor_id">
								<option value="0" <?php selected( $group->visibility, 0 ); ?>><?php echo esc_html__( 'None', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?></option>
								<?php YITH_WAPO_Group::printOptionsVendorList( $group->vendor_id ); ?>
							</select>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<th scope="row"><label for="visibility"><?php echo esc_html__( 'Visibility', 'yith-woocommerce-product-add-ons' ); ?></label></th>
					<td>
						<select name="visibility">
							<option value="0" <?php selected( $group->visibility, 0 ); ?>><?php echo esc_html__( 'Hidden', 'yith-woocommerce-product-add-ons' ); ?></option>
							<option value="1" <?php selected( $group->visibility, 1 ); ?>><?php echo esc_html__( 'Administrators only', 'yith-woocommerce-product-add-ons' ); ?></option>
							<option value="9" 
							<?php
							selected( $group->visibility, 9 );
							selected( $group->id, 0 );
							?>
							><?php echo esc_html__( 'Public', 'yith-woocommerce-product-add-ons' ); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" form="group-form" class="button button-primary" value="<?php echo esc_html__( 'Save group', 'yith-woocommerce-product-add-ons' ); ?>">
		</p>

	</form>

</div>

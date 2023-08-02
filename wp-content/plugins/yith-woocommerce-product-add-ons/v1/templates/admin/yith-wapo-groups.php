<?php
/**
 * Admin Products Options Groups list
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

$vendor_user        = YITH_WAPO::get_current_multivendor();
$vendor_check       = isset( $vendor_user ) && is_object( $vendor_user ) && $vendor_user->has_limited_access() ? 'AND vendor_id=' . $vendor_user->id : '';
$show_vendor_column = YITH_WAPO::$is_vendor_installed && ( ! isset( $vendor_user ) || ( isset( $vendor_user ) && is_object( $vendor_user ) && ! $vendor_user->has_limited_access() ) );

?>

<div id="wapo-groups" class="wrap wapo-plugin">

	<h1>
		<?php echo esc_html__( 'Groups', 'yith-woocommerce-product-add-ons' ); ?>
		<a href="edit.php?post_type=product&page=yith_wapo_group&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>" class="page-title-action"><?php echo esc_html__( 'Add new', 'yith-woocommerce-product-add-ons' ); ?></a>
	</h1>

	<p style="margin-bottom: 30px;"><?php echo esc_html__( 'Complete list of product option groups.', 'yith-woocommerce-product-add-ons' ); ?></p>

	<?php

	for ( $visibility = 9; $visibility >= 0; $visibility-- ) :

		$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_groups WHERE visibility='$visibility' $vendor_check AND del='0' ORDER BY priority, name ASC";
		$rows  = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		if ( count( $rows ) === 0 ) {
			continue;
		}

		?>

		<p>
		<?php

		switch ( $visibility ) {
			case 0:
				echo '<span class="dashicons dashicons-hidden" style="margin: -1px 5px 0px 0px;"></span> <strong>' . esc_html__( 'Hidden groups', 'yith-woocommerce-product-add-ons' ) . '</strong>';
				break;
			case 1:
				echo '<span class="dashicons dashicons-lock" style="margin: -1px 5px 0px 0px;"></span> <strong>' . esc_html__( 'Administrators only', 'yith-woocommerce-product-add-ons' ) . '</strong>';
				break;
			case 9:
				echo '<span class="dashicons dashicons-visibility" style="margin: -1px 5px 0px 0px;"></span> <strong>' . esc_html__( 'Public groups', 'yith-woocommerce-product-add-ons' ) . '</strong>';
				break;
			default:
				echo '<span class="dashicons dashicons-visibility" style="margin: -1px 5px 0px 0px;"></span> <strong>' . esc_html__( 'Public groups', 'yith-woocommerce-product-add-ons' ) . '</strong>';
				break;
		}

		?>
		</p>

		<table class="wp-list-table widefat fixed striped posts" style="margin-bottom: 30px;">
			<tr>
				<th style="width: 200px;"><?php echo esc_html__( 'Name', 'yith-woocommerce-product-add-ons' ); ?></th>
				<th style="width: 80px;"><?php echo esc_html__( 'Add-ons', 'yith-woocommerce-product-add-ons' ); ?></th>
				<th><?php echo esc_html__( 'Products', 'yith-woocommerce-product-add-ons' ); ?></th>
				<th><?php echo esc_html__( 'Categories', 'yith-woocommerce-product-add-ons' ); ?></th>
				<!--<th><?php echo esc_html__( 'Attributes', 'yith-woocommerce-product-add-ons' ); ?></th>-->
				<?php if ( $show_vendor_column ) : ?>
					<th><?php echo esc_html__( 'Vendor', 'yith-woocommerce-product-add-ons' ); ?></th>
				<?php endif; ?>
				<th style="width: 80px;"><?php echo esc_html__( 'Visibility', 'yith-woocommerce-product-add-ons' ); ?></th>
				<th style="width: 50px;"><?php echo esc_html__( 'Priority', 'yith-woocommerce-product-add-ons' ); ?></th>
				<th style="width: 200px;"><?php echo esc_html__( 'Actions', 'yith-woocommerce-product-add-ons' ); ?></th>
			</tr>

			<?php

			foreach ( $rows as $key => $value ) :
				?>

				<tr>
					<td>
						<span class="dashicons dashicons-category" style="margin: 5px 5px 0px 0px;"></span>
						<?php echo esc_html( $value->name ); ?>
					</td>
					<td>
						<a href="edit.php?post_type=product&page=yith_wapo_group_addons&id=<?php echo esc_attr( $value->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>">
							<?php echo esc_html( yith_wapo_get_addons_number_by_group_id( $value->id ) ) . ' ' . esc_html__( 'add-ons', 'yith-woocommerce-product-add-ons' ); ?>
						</a>
					</td>
					<td>
					<?php

					if ( $value->products_id ) {

						$products_id = explode( ',', trim( $value->products_id, ',' ) );

						echo '<ul class="products_list">';
						foreach ( $products_id as $key_2 => $value_2 ) {
							$result = $wpdb->get_row( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE ID='$value_2'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
							if ( isset( $result->post_title ) ) {
								echo '<li><a href="post.php?post=' . esc_attr( $value_2 ) . '&action=edit">' . esc_html( $result->post_title ) . ' (#' . esc_html( $result->ID ) . ')</a></li>';
							} else {
								echo '<li>-</li>';
							}
						}
						echo '</ul>';

					} else {
						echo '<strong>' . esc_html__( 'All', 'yith-woocommerce-product-add-ons' ) . '</strong>'; }

					?>
					</td>
					<td>
					<?php

					if ( $value->categories_id ) {

						$categories_id = explode( ',', trim( $value->categories_id, ',' ) );

						echo '<ul class="categories_list">';
						foreach ( $categories_id as $key_2 => $value_2 ) {
							$result = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}terms WHERE term_id='$value_2'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
							echo is_object( $result ) ? '<li><span>' . esc_html( $result->name ) . '</span></li>' : '';
						}
						echo '</ul>';

					} else {
						echo '<strong>' . esc_html__( 'All', 'yith-woocommerce-product-add-ons' ) . '</strong>'; }

					?>
					</td>
					<?php if ( $show_vendor_column ) : ?>
						<td>
							<?php
							$vendor_id = intval( $value->vendor_id );
							if ( $vendor_id > 0 ) {
								$current_vendor = YITH_WAPO::get_multivendor_by_id( $vendor_id );
								if ( isset( $current_vendor ) && is_object( $current_vendor ) ) {
									echo esc_html( stripslashes( $current_vendor->name ) );
								}
							}
							?>
						</td>
					<?php endif; ?>
					<td><strong>
					<?php

					switch ( $value->visibility ) {
						case 0:
							echo '<span style="color: rgba(0,0,0,0.1);"><span class="dashicons dashicons-hidden" style="margin: 5px 5px 0px 0px;"></span> Hidden</span>';
							break;
						case 1:
							echo '<span class="dashicons dashicons-lock" style="margin: 5px 5px 0px 0px;"></span> Admin';
							break;
						case 9:
							echo '<span class="dashicons dashicons-visibility" style="margin: 5px 5px 0px 0px;"></span> Public';
							break;
						default:
							echo '<span style="color: rgba(0,0,0,0.1);"><span class="dashicons dashicons-hidden" style="margin: 5px 5px 0px 0px;"></span> Hidden</span>';
							break;
					}

					?>
					</strong></td>
					<td><?php echo esc_html( $value->priority ); ?></td>
					<td>
						<a href="edit.php?post_type=product&page=yith_wapo_group&id=<?php echo esc_attr( $value->id ); ?>" class="button" title="<?php echo esc_attr__( 'Edit', 'yith-woocommerce-product-add-ons' ); ?>">
							<span class="dashicons dashicons-edit" style="line-height: 27px;"></span>
						</a>
						<a href="edit.php?post_type=product&page=yith_wapo_group_addons&id=<?php echo esc_attr( $value->id ); ?>" class="button" title="<?php echo esc_attr__( 'Manage Add-ons', 'yith-woocommerce-product-add-ons' ); ?>">
							<span class="dashicons dashicons-admin-generic" style="line-height: 27px;"></span>
						</a>
						<a href="edit.php?post_type=product&page=yith_wapo_group&duplicate_group_id=<?php echo esc_attr( $value->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>" class="button" title="<?php echo esc_attr__( 'Duplicate', 'yith-woocommerce-product-add-ons' ); ?>">
							<span class="dashicons dashicons-admin-page" style="line-height: 27px;"></span>
						</a>
						<a href="edit.php?post_type=product&page=yith_wapo_group&delete_group_id=<?php echo esc_attr( $value->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>" class="button delete_group" title="<?php echo esc_attr__( 'Delete', 'yith-woocommerce-product-add-ons' ); ?>">
							<span class="dashicons dashicons-dismiss" style="line-height: 27px;"></span>
						</a>
					</td>
				</tr>

			<?php endforeach; ?>

		</table>

	<?php endfor; ?>

	<a href="admin.php?page=yith_wapo_panel&yith_wapo_v2=1&nonce=<?php echo esc_attr( wp_create_nonce( 'switch_version' ) ); ?>" class="button yith-update-button">
		<?php echo esc_html__( 'Switch to the new 2.0 version', 'yith-woocommerce-product-add-ons' ); ?>
	</a><br />
	<small>* <?php echo esc_html__( 'You will not lose the current configuration of your add-ons.', 'yith-woocommerce-product-add-ons' ); ?></small>

</div>

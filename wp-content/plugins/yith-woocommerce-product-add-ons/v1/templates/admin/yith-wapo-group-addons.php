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

/**
 *  Multi Vendor
 */
$vendor_user        = YITH_WAPO::get_current_multivendor();
$show_vendor_column = YITH_WAPO::$is_vendor_installed && ( ! isset( $vendor_user ) || ( isset( $vendor_user ) && is_object( $vendor_user ) && ! $vendor_user->has_limited_access() ) );

?>

<div id="group" class="wrap wapo-plugin">

	<?php if ( $group->id > 0 ) : ?>

		<h1>
			<?php echo esc_html__( 'Group', 'yith-woocommerce-product-add-ons' ) . ': ' . esc_html( $group->name ); ?>
			<a href="edit.php?post_type=product&page=yith_wapo_group&id=<?php echo esc_attr( $group->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>" class="page-title-action">
				<?php echo esc_html__( 'Edit group', 'yith-woocommerce-product-add-ons' ); ?> &raquo;
			</a>
		</h1>

		<?php

		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media(); } else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			}

			?>

		<!-- TYPES TABLE -->
		<div id="wapo-types" class="wrap">

			<div id="new-addon" class="type-row">
				<a href="#" class="button button-primary wapo-new-addon"><?php echo esc_html__( 'New Add-on', 'yith-woocommerce-product-add-ons' ); ?></a>
				<?php

					/**
					 *  Print Option Type Form
					 */
					echo esc_html( YITH_WAPO_Type::new_addon_form( $group ) );

				?>
			</div>

			<ul id="sortable-list" class="sortable">
				<?php
					$rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}yith_wapo_types WHERE group_id='$group->id' AND del='0' ORDER BY priority ASC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				foreach ( $rows as $key => $value ) :
					$type_id       = $value->id;
					$array_options = maybe_unserialize( $value->options );
					?>

					<li id="type-<?php echo esc_attr( $value->id ); ?>" class="type-row">

						<div href="#type-form-<?php echo esc_attr( $value->id ); ?>" class="wapo-type-edit" style="cursor: pointer;">
							<i class="dashicons dashicons-move" style="color: #aaa; line-height: 30px; margin-right: 5px;"></i>
							<span style="min-width: 200px; display: inline-block;">
								<?php echo esc_html__( 'Type', 'yith-woocommerce-product-add-ons' ); ?>
								<strong ><?php echo esc_html( str_replace( '_', ' ', $value->type ) ); ?></strong>
								<?php
								if ( isset( $array_options['label'] ) && count( $array_options['label'] ) > 0 ) {
									echo esc_html__( 'with', 'yith-woocommerce-product-add-ons' )
										. ' <strong>' . count( $array_options['label'] ) . '</strong> '
										. esc_html__( 'options', 'yith-woocommerce-product-add-ons' );
								} else {
									echo '<b class="ywapo_no_options_advice">[ ' . esc_html__(
										'In order to show the Add-On, ensure you set a title,
									created at least one option and you gave a name to it',
										'yith-woocommerce-product-add-ons'
									) . '
									]</b>';
								}
								?>
							</span>
							<span><b class="dashicons dashicons-arrow-right-alt2" style="height: 30px; line-height: 30px;"></b><strong style="text-transform: none;"><?php echo esc_html( stripslashes( $value->label ) ); ?></strong></span>
							<?php
							if ( $value->required ) :
								?>
								<span><b class="dashicons dashicons-yes" style="height: 30px; line-height: 30px;"></b> <?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?></span><?php endif; ?>
							<?php
							$rows_dep = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}yith_wapo_types WHERE id!='$value->id' AND group_id='$group->id' AND del='0' ORDER BY label ASC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
							?>
							<?php if ( $value->depend ) : ?>
							<span class="ywapo_admin_add_on_dependecies_container">
								<?php YITH_WAPO_Admin::printChosenDependencies( $rows_dep, $value ); ?>
							</span>
							<?php endif; ?>
							<?php if ( $value->depend_variations ) : ?>
							<span class="ywapo_admin_add_on_dependecies_variation_container">
								<?php YITH_WAPO_Admin::printChosenDependenciesVariations( $value->depend_variations ); ?>
							</span>
							<?php endif; ?>
							<div class="actions">
								<a href="edit.php?post_type=product&page=yith_wapo_group_addons&id=<?php echo esc_attr( $group->id ); ?>&duplicate_addon_id=<?php echo esc_attr( $value->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>"
									class="button duplicate-addon" title="<?php echo esc_attr__( 'Duplicate', 'yith-woocommerce-product-add-ons' ); ?>">
									<span class="dashicons dashicons-admin-page" style="line-height: 27px;"></span>
								</a>
								<a href="edit.php?post_type=product&page=yith_wapo_group_addons&id=<?php echo esc_attr( $group->id ); ?>&delete_addon_id=<?php echo esc_attr( $value->id ); ?>&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>"
									class="button delete-addon" title="<?php echo esc_attr__( 'Delete', 'yith-woocommerce-product-add-ons' ); ?>">
									<span class="dashicons dashicons-dismiss" style="line-height: 27px;"></span>
								</a>
							</div>
						</div>

						<?php

						/**
						 *  Print Option Type Form
						 */
						echo esc_html( YITH_WAPO_Type::printOptionTypeForm( $wpdb, $group, $value ) );

						?>

					</li>

				<?php endforeach; ?>

			</ul>

		</div>

		<div id="save-addons-order">
			<form action="edit.php?post_type=product" method="post">
				<input type="hidden" name="id" value="<?php echo esc_attr( $group->id ); ?>">
				<input type="hidden" name="page" value="yith_wapo_group_addons">
				<input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">
				<input type="hidden" name="act" value="update-order">
				<input type="hidden" name="class" value="YITH_WAPO_Group">
				<input type="hidden" name="types-order" value="">
				<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>">
				<input type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Save add-ons order', 'yith-woocommerce-product-add-ons' ); ?>">
			</form>
		</div>

	<?php endif; ?>

</div>

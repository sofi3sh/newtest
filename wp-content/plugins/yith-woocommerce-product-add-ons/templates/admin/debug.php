<?php
/**
 * Debug Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;
$nonce = wp_create_nonce( 'wapo_action' );

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith-wapo-panel-debug" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<div class="list-table-title">
				<h2><?php echo esc_html__( 'Debug panel', 'yith-woocommerce-product-add-ons' ); ?></h2>
			</div>

			<div class="fields">

				<!-- Option field -->
				<div class="field-wrap">
					<label for="option-characters-limit"><?php echo esc_html__( 'Database tables check', 'yith-woocommerce-product-add-ons' ); ?>:</label>
					<div class="field">
						<?php
						$blocks_table_exists = $wpdb->query("SELECT 1 FROM {$wpdb->prefix}yith_wapo_blocks"); // phpcs:ignore
						$addons_table_exists = $wpdb->query("SELECT 1 FROM {$wpdb->prefix}yith_wapo_addons"); // phpcs:ignore

						if ( false !== $blocks_table_exists && false !== $addons_table_exists ) {
							echo '<span style="color: #94aa09;"><span class="dashicons dashicons-yes"></span> '
								. esc_html__( 'Database tables created successfully.', 'yith-woocommerce-product-add-ons' ) . '</span>';
						} else {
							?>
							<a href="admin.php?page=yith_wapo_panel&tab=debug&wapo_action=db-check&nonce=<?php echo esc_attr( $nonce ); ?>" class="yith-update-button">
								Create database tables
							</a>
							<?php
						}
						?>
						<span class="description"><?php echo esc_html__( 'This feature checks the existence of the database tables.', 'yith-woocommerce-product-add-ons' ); ?></span>
					</div>
				</div>
				<!-- End option field -->

				<!-- Option field -->
				<div class="field-wrap">
					<label for="option-characters-limit"><?php echo esc_html__( 'Copy Add-ons from 1.x', 'yith-woocommerce-product-add-ons' ); ?>:</label>
					<div class="field">
						<a href="admin.php?page=yith_wapo_panel&tab=debug&wapo_action=reset-migration&nonce=<?php echo esc_attr( $nonce ); ?>" class="button-primary">
							<?php echo esc_html__( 'Copy all Add-ons', 'yith-woocommerce-product-add-ons' ); ?>
						</a>
						<span class="description"><?php echo esc_html__( 'This feature allows you to copy all the Add-ons here, which are currently present in version 1.x of the plugin.', 'yith-woocommerce-product-add-ons' ); ?></span>
					</div>
				</div>
				<!-- End option field -->

		</div>
	</div>
</div>

<?php
/**
 * Addon Editor Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var int $block_id Block ID.
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$addon_id      = isset( $_REQUEST['addon_id'] ) ? sanitize_key( $_REQUEST['addon_id'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$addon_type    = isset( $_REQUEST['addon_type'] ) ? sanitize_key( $_REQUEST['addon_type'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$template_file = YITH_WAPO_DIR . '/templates/admin/addons/' . $addon_type . '.php';

if ( yith_wapo_is_addon_type_available( $addon_type ) && ( file_exists( $template_file ) || 'new' === $addon_id ) ) : ?>

	<div id="yith-wapo-addon-overlay" class="yith-plugin-fw">
		<div id="addon-editor">

			<span href="#" id="close-popup">
				<img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/popup-close.png">
			</span>

			<?php if ( '' !== $addon_type ) : ?>

				<?php $addon = new YITH_WAPO_Addon( $addon_id ); ?>

				<form action="admin.php?page=yith_wapo_panel&tab=blocks" method="post" id="addon">
					<button type="submit" class="submit button-priprimary" style="display: none;"></button>

					<?php if ( 'new' === $addon_id ) : ?>
						<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo esc_attr( $block_id ); ?>&addon_id=new" style="margin-bottom: 20px; display: block;">
							< <?php echo esc_html__( 'back to the type choice', 'yith-woocommerce-product-add-ons' ); ?>
						</a>
					<?php endif; ?>

					<div id="addon-editor-type">

						<h3><?php echo esc_html( ucwords( str_replace( 'html', 'HTML', str_replace( '_', ' ', $addon_type ) ) ) ); ?></h3>

						<?php if ( strpos( $addon_type, 'html' ) === false ) : ?>
							<div id="addon-tabs">
								<a href="#" id="options-list" class="selected"><?php echo esc_html__( 'Populate options', 'yith-woocommerce-product-add-ons' ); ?></a>
								<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
									<a href="#" id="display-settings"><?php echo esc_html__( 'Display settings', 'yith-woocommerce-product-add-ons' ); ?></a>
									<?php if ( 'label' === $addon_type ) : ?>
										<a href="#" id="style-settings"><?php echo esc_html__( 'Style', 'yith-woocommerce-product-add-ons' ); ?></a>
									<?php endif; ?>
								<?php endif; ?>
								<a href="#" id="conditional-logic"><?php echo esc_html__( 'Conditional logic', 'yith-woocommerce-product-add-ons' ); ?></a>
								<?php if ( 'select' !== $addon_type ) : ?>
								<a href="#" id="advanced-settings"><?php echo esc_html__( 'Advanced settings', 'yith-woocommerce-product-add-ons' ); ?></a>
								<?php endif; ?>
							</div>
							<script type="text/javascript">
								jQuery('#addon-tabs a').click(function(){
									jQuery('#addon-tabs a').removeClass('selected');
									jQuery(this).addClass('selected');
									var tab = jQuery(this).attr('id');
									jQuery( '#addon-container > div' ).hide();
									jQuery( '#addon-container #tab-' + tab ).show();
								});
							</script>
						<?php endif; ?>

						<div id="addon-container">
							<!-- POPULATE OPTIONS -->
							<div id="tab-options-list">

								<?php
								$options_total = is_array( $addon->options ) && isset( array_values( $addon->options )[0] ) ? count( array_values( $addon->options )[0] ) : 1;
								if ( 'html_heading' === $addon_type || 'html_separator' === $addon_type || 'html_text' === $addon_type ) :
									include $template_file;
								else :
									?>

									<!-- Option field -->
									<div class="field-wrap" style="margin-top: 20px;">
										<label for="addon-title" style="width: 50px;"><?php echo esc_html__( 'Title', 'yith-woocommerce-product-add-ons' ); ?>:</label>
										<div class="field">
											<input type="text" name="addon_title" id="addon-title" value="<?php echo esc_attr( $addon->get_setting( 'title' ) ); ?>">
											<span class="description"><?php echo esc_html__( 'Enter a title to show before the options.', 'yith-woocommerce-product-add-ons' ); ?></span>
										</div>
									</div>
									<!-- End option field -->

									<!-- Option field -->
									<div class="field-wrap" style="margin-top: 20px;">
										<label for="addon-description" style="width: 50px;"><?php echo esc_html__( 'Description', 'yith-woocommerce-product-add-ons' ); ?>:</label>
										<div class="field">
											<textarea type="text" name="addon_description" id="addon-description"><?php echo esc_attr( $addon->get_setting( 'description' ) ); ?></textarea>
											<span class="description"><?php echo esc_html__( 'Enter a description to show before the options.', 'yith-woocommerce-product-add-ons' ); ?></span>
										</div>
									</div>
									<?php
										if ( 'select' === $addon_type ) {
									?>
									<!-- End option field -->
									<div class="field-wrap">
										<label for="addon-required" style="width: 50px;"><?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>:</label>
										<div class="field">
											<?php
											$required = $addon->get_setting( 'required', 'no' );

											yith_plugin_fw_get_field(
												array(
													'id'   => 'addon-required',
													'name' => 'addon_required',
													'class' => 'yith-wapo-required-select',
													'default' => 'no',
													'type' => 'onoff',
													'value' => $required,
												),
												true
											);
											?>
											<span class="description">
												<?php echo esc_html__( 'Enable to make this add-on required.', 'yith-woocommerce-product-add-ons' ); ?>
											</span>
										</div>
									</div>
									<?php } ?>
									<div id="addon_options">
									<?php for ( $x = 0; $x < $options_total; $x++ ) : ?>
										<div class="option <?php echo 1 === $options_total ? 'open' : ''; ?>">
											<div class="actions" style="<?php echo 1 === $options_total ? 'display: none;' : ''; ?>">
												<?php
													$actions = array(
														'delete'    => array(
															'title'        => __( 'Delete', 'yith-woocommerce-product-add-ons' ),
															'action'       => 'delete',
															'icon'         => 'trash',

															'confirm_data' => array(
																'title'               => __( 'Confirm delete', 'yith-plugin-fw' ),
																'message'             => __( 'Are you sure to delete this option?', 'yith-plugin-fw' ),
																'confirm-button'      => _x( 'Yes, delete', 'Delete confirmation action', 'yith-plugin-fw' ),
																'confirm-button-type' => 'delete',
															),
														),
													);
													yith_plugin_fw_get_action_buttons( $actions, true );
													?>
											</div>
											<?php include $template_file; ?>
										</div>
									<?php endfor; ?>
									</div>
									<div id="add-new-option">+ <?php echo esc_html__( 'Add a new option', 'yith-woocommerce-product-add-ons' ); ?></div>

									<!-- NEW OPTION TEMPLATE -->
									<?php for ( $temp = $x + 20; $x < $temp; $x++ ) : ?>
										<script type="text/html" id="tmpl-new-option-<?php echo esc_attr( $x ); ?>">
											<div class="option open">
												<div class="actions">
													<?php
														$actions = array(
															'delete'    => array(
																'title'        => __( 'Delete', 'yith-woocommerce-product-add-ons' ),
																'action'       => 'delete',
																'icon'         => 'trash',
																'confirm_data' => array(
																	'title'               => __( 'Confirm delete', 'yith-plugin-fw' ),
																	'message'             => __( 'Are you sure to delete this option?', 'yith-plugin-fw' ),
																	'confirm-button'      => _x( 'Yes, delete', 'Delete confirmation action', 'yith-plugin-fw' ),
																	'confirm-button-type' => 'delete',
																),
															),
														);
														yith_plugin_fw_get_action_buttons( $actions, true );
														?>
												</div>
												<?php
												$new_option = true;
												include $template_file;
												?>
											</div>
										</script>
									<?php endfor; ?>
									<!-- NEW OPTION TEMPLATE -->

								<?php endif; ?>
							</div>

							<?php
								include YITH_WAPO_DIR . '/templates/admin/addon-display-settings.php';
							if ( 'label' === $addon_type ) {
								include YITH_WAPO_DIR . '/templates/admin/addon-style-settings.php'; }
								include YITH_WAPO_DIR . '/templates/admin/addon-conditional-logic.php';
								include YITH_WAPO_DIR . '/templates/admin/addon-advanced-settings.php';
							?>
						</div><!-- #options-container -->
					</div><!-- #options-editor-radio -->

					<input type="hidden" name="wapo_action" value="save-addon">
					<input type="hidden" name="addon_id" value="<?php echo esc_attr( $addon_id ); ?>">
					<input type="hidden" name="addon_type" value="<?php echo esc_attr( $addon_type ); ?>">
					<input type="hidden" name="block_id" value="<?php echo esc_attr( $block_id ); ?>">
					<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>">

					<div id="addon-editor-buttons">
						<button type="reset" class="cancel button-secondary"><?php echo esc_html__( 'Cancel', 'yith-woocommerce-product-add-ons' ); ?></button>
						<button type="submit" class="submit button-primary"><?php echo esc_html__( 'Save', 'yith-woocommerce-product-add-ons' ); ?></button>
					</div>

				</form>

			<?php elseif ( 'new' === $addon_id ) : ?>

				<div id="types">

					<h3><?php echo esc_html__( 'Add HTML element', 'yith-woocommerce-product-add-ons' ); ?></h3>
					<div class="types">
						<?php foreach ( YITH_WAPO()->get_html_types() as $key => $html_type ) : ?>
							<a class="type" href="<?php echo esc_attr( admin_url( 'admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id . '&addon_id=new&addon_type=' . $html_type['slug'] ) ); ?>">
								<div class="icon <?php echo esc_attr( $html_type['slug'] ); ?>"><span class="wapo-icon wapo-icon-<?php echo esc_attr( $html_type['slug'] ); ?>"></span></div>
								<?php echo esc_html( $html_type['name'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>

					<h3><?php echo esc_html__( 'Add option for the user', 'yith-woocommerce-product-add-ons' ); ?></h3>
					<div class="types">
						<?php
							$available_addon_types = YITH_WAPO()->get_available_addon_types();
						foreach ( YITH_WAPO()->get_addon_types() as $key => $addon_type ) :
							$class = 'disabled';
							$url   = admin_url( 'admin.php?page=yith_wapo_panel' );
							if ( in_array( $addon_type['slug'], $available_addon_types, true ) ) {
								$class = 'enabled';
								$url   = admin_url( 'admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id . '&addon_id=new&addon_type=' . $addon_type['slug'] );
							}
							?>
							<a class="type <?php echo esc_attr( $class ); ?>" href="<?php echo esc_attr( $url ); ?>" <?php echo 'disabled' === $class ? 'onclick="return false;"' : ''; ?>>
								<img src="<?php echo esc_attr( YITH_WAPO_URL ) . 'assets/img/addons-icons/premium.svg'; ?>" class="premium-badge">
								<div class="icon <?php echo esc_attr( $addon_type['slug'] ); ?>"><span class="wapo-icon wapo-icon-<?php echo esc_attr( $addon_type['slug'] ); ?>"></span></div>
								<span><?php echo esc_html( $addon_type['name'] ); ?></span>
							</a>
						<?php endforeach; ?>
						<div class="clear"></div>
					</div>

				</div>

			<?php endif; ?>

		</div>
	</div>

	<script type="text/javascript">
		jQuery('#yith-wapo-addon-overlay').on('click', function(event) {
			if (event.target !== this) { return; }
			jQuery(this).fadeOut();
			var currentURL = window.location.href;
			currentURL = currentURL.split('&addon_id');
			window.history.pushState( '', '', currentURL[0] );
		});
		jQuery('#close-popup, button.cancel').on('click', function(event) {
			jQuery('#yith-wapo-addon-overlay').fadeOut();
			var currentURL = window.location.href;
			currentURL = currentURL.split('&addon_id');
			window.history.pushState( '', '', currentURL[0] );
		});
	</script>

<?php endif; ?>

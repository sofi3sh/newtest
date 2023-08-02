<?php
/**
 * Addon Advanced Options Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var YITH_WAPO_Addon $addon
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="tab-advanced-settings" style="display: none;">

	<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-first-options-selected"><?php echo esc_html__( 'Set the first selected options as free', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'    => 'addon-first-options-selected',
						'name'  => 'addon_first_options_selected',
						'class' => 'enabler',
						'type'  => 'onoff',
						'value' => $addon->get_setting( 'first_options_selected' ),
					),
					true
				);
				?>
				<span class="description">
					<?php echo esc_html__( 'Enable to set a specific number of options as free.', 'yith-woocommerce-product-add-ons' ); ?><br />
					<?php echo esc_html__( 'For example, the first three "pizza toppings" are free and included in the product price.', 'yith-woocommerce-product-add-ons' ); ?><br />
					<?php echo esc_html__( 'The user will pay for the fourth topping.', 'yith-woocommerce-product-add-ons' ); ?>
				</span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap enabled-by-addon-first-options-selected" style="display: none;">
			<label for="addon-first-free-options"><?php echo esc_html__( 'How many options the user can select for free', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'    => 'addon-first-free-options',
						'name'  => 'addon_first_free_options',
						'type'  => 'number',
						'value' => $addon->get_setting( 'first_free_options', 0 ),
					),
					true
				);
				?>
				<span class="description">
					<?php echo esc_html__( 'Set how many options the user can select for free.', 'yith-woocommerce-product-add-ons' ); ?>
				</span>
			</div>
		</div>
		<!-- End option field -->

	<?php endif; ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-selection-type"><?php echo esc_html__( 'Selection type', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'      => 'addon-selection-type',
					'name'    => 'addon_selection_type',
					'type'    => 'radio',
					'value'   => $addon->get_setting( 'selection_type', 'single' ),
					'options' => array(
						'single'   => __( 'Single - User can select only ONE of the options available', 'yith-woocommerce-product-add-ons' ),
						'multiple' => __( 'Multiple - User can select MULTIPLE options', 'yith-woocommerce-product-add-ons' ),
					),
				),
				true
			);
			?>
			<span class="description">
				<?php echo esc_html__( 'Choose to show these options in all products or only specific products or categories.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-enable-min-max"><?php echo esc_html__( 'Enable min/max selection rules', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'    => 'addon-enable-min-max',
						'name'  => 'addon_enable_min_max',
						'class' => 'enabler',
						'type'  => 'onoff',
						'value' => $addon->get_setting( 'enable_min_max' ),
					),
					true
				);
				?>
				<span class="description">
					<?php echo esc_html__( 'Enable if the user has to select a minimum, maximum, or the exact number of options to proceed with the purchase.', 'yith-woocommerce-product-add-ons' ); ?>
				</span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap enabled-by-addon-enable-min-max" style="display: none;">
			<label for="min-max-rules"><?php echo esc_html__( 'To proceed to buy, the user has to select:', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div id="min-max-rules">
				<?php
				$min_max_rule  = (array) $addon->get_setting( 'min_max_rule' );
				$min_max_value = (array) $addon->get_setting( 'min_max_value' );
				$min_max_count = count( $min_max_rule );
				for ( $y = 0; $y < $min_max_count; $y++ ) :
					?>
					<div class="field rule min-max-rule">
						<?php
						yith_plugin_fw_get_field(
							array(
								'id'      => 'addon-min-max-rule',
								'name'    => 'addon_min_max_rule[]',
								'type'    => 'select',
								'value'   => $min_max_rule[ $y ],
								'options' => array(
									'min' => __( 'A minimum of', 'yith-woocommerce-product-add-ons' ),
									'max' => __( 'A maximum of', 'yith-woocommerce-product-add-ons' ),
									'exa' => __( 'Exactly', 'yith-woocommerce-product-add-ons' ),
								),
							),
							true
						);
						yith_plugin_fw_get_field(
							array(
								'id'    => 'addon-min-max-value',
								'name'  => 'addon_min_max_value[]',
								'type'  => 'number',
								'min'   => '0',
								'value' => $min_max_value[ $y ],
							),
							true
						);
						?>
						<span class="description">
							<?php echo esc_html__( 'options', 'yith-woocommerce-product-add-ons' ); ?>
						</span>
						<img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/delete.png" class="delete-min-max-rule" alt="">
					</div>
				<?php endfor; ?>
				<div id="add-min-max-rule"><a href="#">+ <?php echo esc_html__( 'Add rule', 'yith-woocommerce-product-add-ons' ); ?></a></div>
			</div>
		</div>
		<!-- End option field -->

		<style>
			#min-max-rules .rule { position: relative; }
			#min-max-rules .rule .delete-min-max-rule { width: 8px; height: 10px; padding: 12px; cursor: pointer; position: absolute; top: 5px; left: 280px; }
			#min-max-rules .rule .delete-min-max-rule:hover { opacity: 0.5; }
		</style>

		<script type="text/javascript">
			jQuery('#add-min-max-rule a').click( function() {
				var ruleTemplate = jQuery('#min-max-rules .field.rule:first-child');
				var clonedOption = ruleTemplate.clone( true );
				clonedOption.find('input[type=number]').val('');
				clonedOption.insertBefore('#add-min-max-rule');
				return false;
			});
			jQuery('#min-max-rules').on( 'click' , '.delete-min-max-rule', function() {
				jQuery(this).parent().remove();
			});
		</script>

	<?php endif; ?>

</div>

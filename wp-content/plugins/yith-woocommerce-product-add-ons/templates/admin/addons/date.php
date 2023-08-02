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
	<?php echo esc_html__( 'DATE FIELD', 'yith-woocommerce-product-add-ons' ); ?> -
	<?php echo esc_html( $addon->get_option( 'label', $x ) ); ?>
</div>

<div class="fields">

	<?php require YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo esc_html__( 'Date format', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'      => 'option-date-format',
					'name'    => 'options[date_format][]',
					'type'    => 'select',
					'value'   => $addon->get_option( 'date_format', $x, 'dd/mm/yy' ),
					'options' => array(
						'd/m/Y' => esc_html__( 'Day / Month / Year', 'yith-woocommerce-product-add-ons' ),
						'm/d/Y' => esc_html__( 'Month / Day / Year', 'yith-woocommerce-product-add-ons' ),
						'd.m.Y' => esc_html__( 'Day . Month . Year', 'yith-woocommerce-product-add-ons' ),
					),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo esc_html__( 'Year', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<small><?php echo esc_html__( 'START YEAR', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[start_year][]" id="option-start-year" value="<?php echo esc_attr( $addon->get_option( 'start_year', $x ) ); ?>" class="mini">
		</div>
		<div class="field">
			<small><?php echo esc_html__( 'END YEAR', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[end_year][]" id="option-end-year" value="<?php echo esc_attr( $addon->get_option( 'end_year', $x ) ); ?>" class="mini">
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo esc_html__( 'Default date', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'      => 'option-date-default-' . $x,
					'class'   => 'option-date-default',
					'name'    => 'options[date_default][]',
					'type'    => 'select',
					'value'   => $addon->get_option( 'date_default', $x, '' ),
					'options' => array(
						''         => esc_html__( 'None', 'yith-woocommerce-product-add-ons' ),
						'today'    => esc_html__( 'Current day', 'yith-woocommerce-product-add-ons' ),
						'tomorrow' => esc_html__( 'Current day', 'yith-woocommerce-product-add-ons' ) . ' + 1',
						'specific' => esc_html__( 'Set a specific day', 'yith-woocommerce-product-add-ons' ),
						'interval' => esc_html__( 'Set a time interval from current day', 'yith-woocommerce-product-add-ons' ),
						'firstavl' => esc_html__( 'First available day', 'yith-woocommerce-product-add-ons' ),
					),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-date-default-day option-date-default-day-<?php echo esc_attr( $x ); ?>" style="<?php echo $addon->get_option( 'date_default', $x ) !== 'specific' ? 'display: none;' : ''; ?>">
		<label><?php echo esc_html__( 'Specific day', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-date-default-day-' . $x,
					'name'  => 'options[date_default_day][]',
					'type'  => 'datepicker',
					'value' => $addon->get_option( 'date_default_day', $x, '' ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-date-default-interval option-date-default-interval-<?php echo esc_attr( $x ); ?>" style="<?php echo $addon->get_option( 'date_default', $x ) !== 'interval' ? 'display: none;' : ''; ?>">
		<label><?php echo esc_html__( 'For default date, calculate', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php

			yith_plugin_fw_get_field(
				array(
					'id'      => 'option-date-default-interval-num-' . $x,
					'name'    => 'options[date_default_calculate_num][]',
					'class'   => 'micro',
					'type'    => 'select',
					'value'   => $addon->get_option( 'date_default_calculate_num', $x, '' ),
					'options' => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31 ),
				),
				true
			);
			?>
		</div>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'      => 'option-date-default-interval-type-' . $x,
					'name'    => 'options[date_default_calculate_type][]',
					'class'   => 'micro',
					'type'    => 'select',
					'value'   => $addon->get_option( 'date_default_calculate_type', $x, '' ),
					'options' => array(
						'days'   => esc_html__( 'Days', 'yith-woocommerce-product-add-ons' ),
						'months' => esc_html__( 'Months', 'yith-woocommerce-product-add-ons' ),
						'years'  => esc_html__( 'Years', 'yith-woocommerce-product-add-ons' ),
					),
				),
				true
			);
			?>
		</div>
		<span style="line-height: 35px;"><?php echo esc_html__( 'from current day', 'yith-woocommerce-product-add-ons' ); ?></span>
	</div>
	<!-- End option field -->

	<div class="field-wrap">
		<label><?php echo esc_html__( 'Selectable dates', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			$selectable_dates_option = $addon->get_option( 'selectable_dates', $x, '' );
			yith_plugin_fw_get_field(
				array(
					'id'      => 'option-selectable-dates-' . $x,
					'class'   => 'option-selectable-dates',
					'name'    => 'options[selectable_dates][]',
					'type'    => 'select',
					'value'   => $selectable_dates_option,
					'options' => array(
						''     => esc_html__( 'Set no limits', 'yith-woocommerce-product-add-ons' ),
						'days' => esc_html__( 'Set a range of days', 'yith-woocommerce-product-add-ons' ),
						'date' => esc_html__( 'Set a specific date range', 'yith-woocommerce-product-add-ons' ),
					),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-selectable-days-ranges" style="<?php echo 'days' === $selectable_dates_option ? '' : 'display: none;'; ?>">
		<label><?php echo esc_html__( 'Selectable days ranges', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field datepicker-micro">
			<small><?php echo esc_html__( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_min][]" id="option-days-min" value="<?php echo esc_attr( $addon->get_option( 'days_min', $x ) ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-days_min-' . $x,
					'name'  => 'options[days_min][]',
					'type'  => 'text',
					'value' => $addon->get_option( 'days_min', $x, '' ),
				),
				true
			);
			?>
		</div>
		<div class="field datepicker-micro">
			<small><?php echo esc_html__( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_max][]" id="option-days-max" value="<?php echo esc_attr( $addon->get_option( 'days_max', $x ) ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-days_max-' . $x,
					'name'  => 'options[days_max][]',
					'type'  => 'text',
					'value' => $addon->get_option( 'days_max', $x, '' ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-selectable-date-ranges" style="<?php echo 'date' === $selectable_dates_option ? '' : 'display: none;'; ?>">
		<label><?php echo esc_html__( 'Selectable date ranges', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field datepicker-micro">
			<small><?php echo esc_html__( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_min][]" id="option-days-min" value="<?php echo esc_attr( $addon->get_option( 'days_min', $x ) ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-date_min-' . $x,
					'name'  => 'options[date_min][]',
					'type'  => 'datepicker',
					'value' => $addon->get_option( 'date_min', $x, '' ),
				),
				true
			);
			?>
		</div>
		<div class="field datepicker-micro" style="margin-left: -23px;">
			<small><?php echo esc_html__( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_max][]" id="option-days-max" value="<?php echo esc_attr( $addon->get_option( 'days_max', $x ) ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-date_max-' . $x,
					'name'  => 'options[date_max][]',
					'type'  => 'datepicker',
					'value' => $addon->get_option( 'date_max', $x, '' ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="date-rule"><?php echo esc_html__( 'Enable / disable specific days', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-enable-disable-days-' . $x,
					'name'  => 'options[enable_disable_days][]',
					'class' => 'enabler',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'enable_disable_days', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-enable-disable-days-<?php echo esc_attr( $x ); ?>" style="display: none;">
		<label><?php echo esc_html__( 'Rule type', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div id="disable-date-rules-<?php echo esc_attr( $x ); ?>">
			<div class="field rules-type" style="margin-bottom: 10px;">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'option-enable-disable-days-type-' . $x,
						'name'    => 'options[enable_disable_date_rules][]',
						'class'   => 'micro',
						'type'    => 'select',
						'value'   => $addon->get_option( 'enable_disable_date_rules', $x, 'enable' ),
						'options' => array(
							'enable'  => esc_html__( 'Enable', 'yith-woocommerce-product-add-ons' ),
							'disable' => esc_html__( 'Disable', 'yith-woocommerce-product-add-ons' ),
						),
					),
					true
				);
				?>
			</div>
			<span style="line-height: 35px;"><?php echo esc_html__( 'these dates in calendar', 'yith-woocommerce-product-add-ons' ); ?></span>
			<div id="date-rules-<?php echo esc_attr( $x ); ?>" class="date-rules" style="clear: both;">

				<?php
					$date_rules_count = count( (array) $addon->get_option( 'date_rule_what', $x ) );
				for ( $y = 0; $y < $date_rules_count; $y++ ) :
					$date_rule_what = $addon->get_option( 'date_rule_what', $x, 'enable' )[ $y ];
					?>
					<div class="rule" style="margin-bottom: 10px;">
						<div class="field what">
						<?php
						yith_plugin_fw_get_field(
							array(
								'id'      => 'date-rule-what-' . $x . '-' . $y,
								'name'    => 'options[date_rule_what][' . $x . '][]',
								'class'   => 'micro select_what',
								'type'    => 'select',
								'value'   => $date_rule_what,
								'options' => array(
									'days'     => esc_html__( 'Days', 'yith-woocommerce-product-add-ons' ),
									'daysweek' => esc_html__( 'Days of the week', 'yith-woocommerce-product-add-ons' ),
									'months'   => esc_html__( 'Months', 'yith-woocommerce-product-add-ons' ),
									'years'    => esc_html__( 'Years', 'yith-woocommerce-product-add-ons' ),
								),
							),
							true
						);
						?>
						</div>

						<div class="field days" <?php echo 'daysweek' !== $date_rule_what && 'months' !== $date_rule_what && 'years' !== $date_rule_what ? '' : 'style="display: none;"'; ?>>
							<?php
							yith_plugin_fw_get_field(
								array(
									'id'    => 'date-rule-value-days-' . $x . '-' . $y,
									'name'  => 'options[date_rule_value_days][' . $x . '][' . $y . ']',
									'type'  => 'datepicker',
									'value' => isset( $addon->get_option( 'date_rule_value_days', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_days', $x, '' )[ $y ] : '',
									'data'  => array(
										'date-format' => 'yy-mm-dd',
									),
								),
								true
							);
							?>
						</div>

						<div class="field daysweek" <?php echo 'daysweek' === $date_rule_what ? '' : 'style="display: none;"'; ?>>
							<?php
							yith_plugin_fw_get_field(
								array(
									'id'       => 'date-rule-value-months-' . $x . '-' . $y,
									'name'     => 'options[date_rule_value_daysweek][' . $x . '][' . $y . ']',
									'type'     => 'select',
									'multiple' => true,
									'class'    => 'wc-enhanced-select',
									'options'  => array(
										'1' => esc_html__( 'Monday', 'yith-woocommerce-product-add-ons' ),
										'2' => esc_html__( 'Tuesday', 'yith-woocommerce-product-add-ons' ),
										'3' => esc_html__( 'Wednesday', 'yith-woocommerce-product-add-ons' ),
										'4' => esc_html__( 'Thursday', 'yith-woocommerce-product-add-ons' ),
										'5' => esc_html__( 'Friday', 'yith-woocommerce-product-add-ons' ),
										'6' => esc_html__( 'Saturday', 'yith-woocommerce-product-add-ons' ),
										'0' => esc_html__( 'Sunday', 'yith-woocommerce-product-add-ons' ),
									),
									'value'    => isset( $addon->get_option( 'date_rule_value_daysweek', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_daysweek', $x, '' )[ $y ] : '',
								),
								true
							);
							?>
						</div>

						<div class="field months" <?php echo 'months' === $date_rule_what ? '' : 'style="display: none;"'; ?>>
							<?php
							yith_plugin_fw_get_field(
								array(
									'id'       => 'date-rule-value-months-' . $x . '-' . $y,
									'name'     => 'options[date_rule_value_months][' . $x . '][' . $y . ']',
									'type'     => 'select',
									'multiple' => true,
									'class'    => 'wc-enhanced-select',
									'options'  => array(
										'1'  => esc_html__( 'January', 'yith-woocommerce-product-add-ons' ),
										'2'  => esc_html__( 'February', 'yith-woocommerce-product-add-ons' ),
										'3'  => esc_html__( 'March', 'yith-woocommerce-product-add-ons' ),
										'4'  => esc_html__( 'April', 'yith-woocommerce-product-add-ons' ),
										'5'  => esc_html__( 'May', 'yith-woocommerce-product-add-ons' ),
										'6'  => esc_html__( 'June', 'yith-woocommerce-product-add-ons' ),
										'7'  => esc_html__( 'July', 'yith-woocommerce-product-add-ons' ),
										'8'  => esc_html__( 'August', 'yith-woocommerce-product-add-ons' ),
										'9'  => esc_html__( 'September', 'yith-woocommerce-product-add-ons' ),
										'10' => esc_html__( 'October', 'yith-woocommerce-product-add-ons' ),
										'11' => esc_html__( 'November', 'yith-woocommerce-product-add-ons' ),
										'12' => esc_html__( 'December', 'yith-woocommerce-product-add-ons' ),
									),
									'value'    => isset( $addon->get_option( 'date_rule_value_months', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_months', $x, '' )[ $y ] : '',
								),
								true
							);
							?>
						</div>

						<div class="field years" <?php echo 'years' === $date_rule_what ? '' : 'style="display: none;"'; ?>>
							<?php
							$years = array();
							$datey = gmdate( 'Y' );
							for ( $yy = $datey; $yy < $datey + 10; $yy++ ) {
								$years[ $yy ] = $yy;
							}
							yith_plugin_fw_get_field(
								array(
									'id'       => 'date-rule-value-years' . $x . '-' . $y,
									'name'     => 'options[date_rule_value_years][' . $x . '][' . $y . ']',
									'type'     => 'select',
									'multiple' => true,
									'class'    => 'wc-enhanced-select',
									'options'  => $years,
									'value'    => isset( $addon->get_option( 'date_rule_value_years', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_years', $x, '' )[ $y ] : '',
								),
								true
							);
							?>
						</div>

						<img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/delete.png" class="delete-rule">

						<div class="clear"></div>
					</div>
				<?php endfor; ?>

				<style type="text/css">
					.date-rules .rule { position: relative; }
					.date-rules .rule .delete-rule { width: 8px; height: 10px; padding: 12px; cursor: pointer; position: absolute; left: 400px; }
					.date-rules .rule .delete-rule:hover { opacity: 0.5; }
					.date-rules .rule:first-child .delete-rule { display: none; }
					.date-rules span.select2.select2-container { border-radius: 0 !important; padding: 0px 5px !important }
					.date-rules span.selection span.select2-selection.select2-selection--multiple { min-height: 20px !important; }
				</style>

				<div id="add-date-rule" class="add-date-rule" style="clear: both;"><a href="#">+ <?php echo esc_html__( 'Add rule', 'yith-woocommerce-product-add-ons' ); ?></a></div>

			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="date-rule"><?php echo esc_html__( 'Show time selector', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-show-time-selector-' . $x,
					'name'  => 'options[show_time_selector][]',
					'class' => 'enabler',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'show_time_selector', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-show-time-selector-<?php echo esc_attr( $x ); ?>" style="display: none;">
		<label for="date-rule"><?php echo esc_html__( 'Enable / disable time slots', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-enable-time-slots-' . $x,
					'name'  => 'options[enable_time_slots][]',
					'class' => 'enabler',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'enable_time_slots', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-enable-time-slots-<?php echo esc_attr( $x ); ?>" style="display: none;">
		<label><?php echo esc_html__( 'Rule type', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div id="enable-disable-time-slots-<?php echo esc_attr( $x ); ?>">
			<div class="field rules-type" style="margin-bottom: 10px;">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'option-time-slots-type-' . $x,
						'name'    => 'options[time_slots_type][]',
						'class'   => 'micro',
						'type'    => 'select',
						'value'   => $addon->get_option( 'time_slots_type', $x, 'enable' ),
						'options' => array(
							'enable'  => __( 'Enable', 'yith-woocommerce-product-add-ons' ),
							'disable' => __( 'Disable', 'yith-woocommerce-product-add-ons' ),
						),
					),
					true
				);
				?>
			</div>
			<span style="line-height: 35px;"><?php echo esc_html__( 'these time slots:', 'yith-woocommerce-product-add-ons' ); ?></span>
			<div id="time-slots-<?php echo esc_attr( $x ); ?>" class="time-slots" style="clear: both;">

				<?php
					$time_slots_count = count( (array) $addon->get_option( 'time_slot_from', $x ) );
				for ( $y = 0; $y < $time_slots_count; $y++ ) :
					?>
					<div class="slot" style="margin-bottom: 10px;">

						<span style="line-height: 35px; margin-right: 10px; float: left;"><?php echo esc_html__( 'from', 'yith-woocommerce-product-add-ons' ); ?></span>

						<div class="field time-slot-from">
						<?php
						yith_plugin_fw_get_field(
							array(
								'id'      => 'time-slot-from-' . $x . '-' . $y,
								'name'    => 'options[time_slot_from][' . $x . '][]',
								'class'   => 'micro select_from',
								'type'    => 'select',
								'value'   => isset( $addon->get_option( 'time_slot_from', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_from', $x, '' )[ $y ] : '',
								'options' => array(
									'1'  => '01',
									'2'  => '02',
									'3'  => '03',
									'4'  => '04',
									'5'  => '05',
									'6'  => '06',
									'7'  => '07',
									'8'  => '08',
									'9'  => '09',
									'10' => '10',
									'11' => '11',
									'12' => '12',
								),
							),
							true
						);
						?>
						</div>

						<span style="line-height: 35px; margin-left: -6px; float: left;">:</span>

						<div class="field time-slot-from-min">
						<?php
						$minutes_array = array();
						for ( $mn = 0; $mn < 60; $mn++ ) {
							$minutes_array[ $mn ] = str_pad( $mn, 2, '0', STR_PAD_LEFT );
						}
						yith_plugin_fw_get_field(
							array(
								'id'      => 'time-slot-from-min-' . $x . '-' . $y,
								'name'    => 'options[time_slot_from_min][' . $x . '][]',
								'class'   => 'micro select_from_min',
								'type'    => 'select',
								'value'   => isset( $addon->get_option( 'time_slot_from_min', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_from_min', $x, '' )[ $y ] : '',
								'options' => $minutes_array,
							),
							true
						);
						?>
						</div>

						<div class="field time-slot-from-type">
						<?php
						yith_plugin_fw_get_field(
							array(
								'id'      => 'time-slot-from-type-' . $x . '-' . $y,
								'name'    => 'options[time_slot_from_type][' . $x . '][]',
								'class'   => 'micro',
								'type'    => 'select',
								'value'   => isset( $addon->get_option( 'time_slot_from_type', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_from_type', $x, '' )[ $y ] : '',
								'options' => array(
									'am' => 'am',
									'pm' => 'pm',
								),
							),
							true
						);
						?>
						</div>

						<span style="line-height: 35px; margin-right: 10px; float: left;"><?php echo esc_html__( 'to', 'yith-woocommerce-product-add-ons' ); ?></span>

						<div class="field time-slot-to">
							<?php
							yith_plugin_fw_get_field(
								array(
									'id'      => 'time-slot-to-' . $x . '-' . $y,
									'name'    => 'options[time_slot_to][' . $x . '][]',
									'class'   => 'micro select_to',
									'type'    => 'select',
									'value'   => isset( $addon->get_option( 'time_slot_to', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_to', $x, '' )[ $y ] : '',
									'options' => array(
										'1'  => '01',
										'2'  => '02',
										'3'  => '03',
										'4'  => '04',
										'5'  => '05',
										'6'  => '06',
										'7'  => '07',
										'8'  => '08',
										'9'  => '09',
										'10' => '10',
										'11' => '11',
										'12' => '12',
									),
								),
								true
							);
							?>
						</div>

						<span style="line-height: 35px; margin-left: -6px; float: left;">:</span>

						<div class="field time-slot-to-min">
						<?php
						$minutes_array = array();
						for ( $mn = 0; $mn < 60; $mn++ ) {
							$minutes_array[ $mn ] = str_pad( $mn, 2, '0', STR_PAD_LEFT );
						}
						yith_plugin_fw_get_field(
							array(
								'id'      => 'time-slot-to-min-' . $x . '-' . $y,
								'name'    => 'options[time_slot_to_min][' . $x . '][]',
								'class'   => 'micro select_to_min',
								'type'    => 'select',
								'value'   => isset( $addon->get_option( 'time_slot_to_min', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_to_min', $x, '' )[ $y ] : '',
								'options' => $minutes_array,
							),
							true
						);
						?>
						</div>

						<div class="field time-slot-to-type">
						<?php
						yith_plugin_fw_get_field(
							array(
								'id'      => 'time-slot-to-type-' . $x . '-' . $y,
								'name'    => 'options[time_slot_to_type][' . $x . '][]',
								'class'   => 'micro',
								'type'    => 'select',
								'value'   => isset( $addon->get_option( 'time_slot_to_type', $x, '' )[ $y ] ) ? $addon->get_option( 'time_slot_to_type', $x, '' )[ $y ] : '',
								'options' => array(
									'am' => 'am',
									'pm' => 'pm',
								),
							),
							true
						);
						?>
						</div>

						<img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/delete.png" class="delete-slot">

						<div class="clear"></div>
					</div>
				<?php endfor; ?>

				<style type="text/css">
					.time-slots .slot { position: relative; }
					.time-slots .slot select.micro { width: 60px !important; min-width: 60px !important; }
					.time-slots .slot .delete-slot { width: 8px; height: 10px; padding: 12px; cursor: pointer; position: absolute; left: 500px; }
					.time-slots .slot .delete-slot:hover { opacity: 0.5; }
					.time-slots .slot:first-child .delete-slot { display: none; }
				</style>

				<div id="add-time-slot" class="add-time-slot" style="clear: both;"><a href="#">+ <?php echo esc_html__( 'Add time slot', 'yith-woocommerce-product-add-ons' ); ?></a></div>

			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-show-time-selector-<?php echo esc_attr( $x ); ?>" style="display: none;">
		<label><?php echo esc_html__( 'Time interval', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div id="time-interval-<?php echo esc_attr( $x ); ?>">

			<div class="field time-interval">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'time-interval-' . $x,
						'name'    => 'options[time_interval][' . $x . ']',
						'class'   => 'micro select_interval',
						'type'    => 'select',
						'value'   => $addon->get_option( 'time_interval', $x, '10' ),
						'options' => array(
							'1'  => '1',
							'2'  => '2',
							'3'  => '3',
							'4'  => '4',
							'5'  => '5',
							'6'  => '6',
							'7'  => '7',
							'8'  => '8',
							'9'  => '9',
							'10' => '10',
							'11' => '11',
							'12' => '12',
							'13' => '13',
							'14' => '14',
							'15' => '15',
							'16' => '16',
							'17' => '17',
							'18' => '18',
							'19' => '19',
							'20' => '20',
							'21' => '21',
							'22' => '22',
							'23' => '23',
							'24' => '24',
							'25' => '25',
							'26' => '26',
							'27' => '27',
							'28' => '28',
							'29' => '29',
							'30' => '30',
						),
					),
					true
				);
				?>
			</div>

			<div class="field time-interval-type">
				<?php
				yith_plugin_fw_get_field(
					array(
						'id'      => 'time-interval-type-' . $x,
						'name'    => 'options[time_interval_type][' . $x . ']',
						'class'   => '',
						'type'    => 'select',
						'value'   => $addon->get_option( 'time_interval_type', $x, 'minutes' ),
						'options' => array(
							'seconds' => __( 'Seconds', 'yith-woocommerce-product-add-ons' ),
							'minutes' => __( 'Minutes', 'yith-woocommerce-product-add-ons' ),
							'hours'   => __( 'Hours', 'yith-woocommerce-product-add-ons' ),
						),
					),
					true
				);
				?>
			</div>

		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-required-' . $x,
					'name'  => 'options[required][' . $x . ']',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'required', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

</div>

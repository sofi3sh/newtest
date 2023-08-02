<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$date_format       = $addon->get_option( 'date_format', $x );
$date_format_js    = str_replace( 'd', 'dd', $date_format );
$date_format_js    = str_replace( 'm', 'mm', $date_format_js );
$date_format_js    = str_replace( 'Y', 'yy', $date_format_js );
$default_date      = '';
$default_date_type = $addon->get_option( 'date_default', $x );
// phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date
if ( 'today' === $default_date_type ) {
	$default_date = date( $date_format );
} elseif ( 'tomorrow' === $default_date_type ) {
	$default_date = date( $date_format, strtotime( '+1 day' ) );
} elseif ( 'specific' === $default_date_type ) {
	$default_specific_day = $addon->get_option( 'date_default_day', $x );
	$default_date         = date( $date_format, strtotime( $default_specific_day ) );
} elseif ( 'interval' === $default_date_type ) {
	$default_calculate_num  = $addon->get_option( 'date_default_calculate_num', $x );
	$default_calculate_type = $addon->get_option( 'date_default_calculate_type', $x );
	$default_date           = date( $date_format, strtotime( '+' . $default_calculate_num . ' ' . $default_calculate_type ) );
} elseif ( 'firstavl' === $default_date_type ) {
	$default_date = 'firstavl';
}

$required = $addon->get_option( 'required', $x ) === 'yes';

$start_year       = $addon->get_option( 'start_year', $x );
$end_year         = $addon->get_option( 'end_year', $x );
$selectable_dates = $addon->get_option( 'selectable_dates', $x );
$days_min         = $addon->get_option( 'days_min', $x );
$days_max         = $addon->get_option( 'days_max', $x );
$date_min         = $addon->get_option( 'date_min', $x );
$date_max         = $addon->get_option( 'date_max', $x );

$show_time_selector  = $addon->get_option( 'show_time_selector', $x );
$enable_time_slots   = $addon->get_option( 'enable_time_slots', $x );
$time_slots_type     = $addon->get_option( 'time_slots_type', $x );
$time_slot_from      = $addon->get_option( 'time_slot_from', $x );
$time_slot_from_min  = $addon->get_option( 'time_slot_from_min', $x );
$time_slot_from_type = $addon->get_option( 'time_slot_from_type', $x );
$time_slot_to        = $addon->get_option( 'time_slot_to', $x );
$time_slot_to_min    = $addon->get_option( 'time_slot_to_min', $x );
$time_slot_to_type   = $addon->get_option( 'time_slot_to_type', $x );
$time_interval       = $addon->get_option( 'time_interval', $x );
$time_interval_type  = $addon->get_option( 'time_interval_type', $x );

$enable_disable_days       = $addon->get_option( 'enable_disable_days', $x );
$enable_disable_date_rules = 'disable';

$selectable_days = '';
$selected_items            = array();

if ( 'days' === $selectable_dates && $days_min >= -365 && $days_max > $days_min ) {
	for ( $z = $days_min; $z < $days_max; $z++ ) {
		$selectable_days .= '"' . date( 'j-n-Y', strtotime( '+' . $z . ' day' ) ) . '", ';
		if ( 'firstavl' === $default_date && ( date( 'j-n-Y', strtotime( '+' . $z . ' day' ) ) >= date( 'j-n-Y' ) ) ) {
			$default_date = date( $date_format, strtotime( '+' . $z . ' day' ) );
		}
	}
} elseif ( 'date' === $selectable_dates ) {
	$z                   = 0;
	$selectable_date_min = date( 'j-n-Y', strtotime( $date_min ) );
	$selectable_date_max = date( 'j-n-Y', strtotime( $date_max ) );
	$selectable_days    .= '"' . $selectable_date_min . '", ';
	if ( 'firstavl' === $default_date && ( date( 'j-n-Y', strtotime( $date_min ) ) >= date( 'j-n-Y' ) ) ) {
		$default_date = date( $date_format, strtotime( $date_min ) );
	}
	while ( ++$z ) {
		if ( 'firstavl' === $default_date && ( date( 'j-n-Y', strtotime( $date_min . ' +' . $z . ' day' ) ) >= date( 'j-n-Y' ) ) ) {
			$default_date = date( $date_format, strtotime( $date_min . ' +' . $z . ' day' ) );
		}
		$calculated_date  = date( 'j-n-Y', strtotime( $date_min . ' +' . $z . ' day' ) );
		$selectable_days .= '"' . $calculated_date . '", ';
		if ( $calculated_date === $selectable_date_max ) {
			break;
		}
	}
}

if ( 'yes' === $enable_disable_days ) {
	// rules.
	$enable_disable_date_rules = $addon->get_option( 'enable_disable_date_rules', $x, 'enable' );
	$date_rules_count          = count( (array) $addon->get_option( 'date_rule_what', $x ) );
	for ( $y = 0; $y < $date_rules_count; $y++ ) {
		$date_rule_what     = isset( $addon->get_option( 'date_rule_what', $x )[ $y ] ) ? $addon->get_option( 'date_rule_what', $x )[ $y ] : '';
		$date_rule_days     = isset( $addon->get_option( 'date_rule_value_days', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_days', $x, '' )[ $y ] : '';
		$date_rule_daysweek = isset( $addon->get_option( 'date_rule_value_daysweek', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_daysweek', $x, '' )[ $y ] : '';
		$date_rule_months   = isset( $addon->get_option( 'date_rule_value_months', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_months', $x, '' )[ $y ] : '';
		$date_rule_years    = isset( $addon->get_option( 'date_rule_value_years', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_years', $x, '' )[ $y ] : '';
		if ( 'days' === $date_rule_what ) {
			$selected_items[ $date_rule_what ][] = $date_rule_days;

			if ( 'firstavl' === $default_date && ( date( 'j-n-Y', strtotime( $date_rule_days ) ) >= date( 'j-n-Y' ) ) ) {
				$default_date = date( $date_format, strtotime( $date_rule_days ) );
			}
		} elseif ( 'daysweek' === $date_rule_what ) {
			$selected_items[ $date_rule_what ][] = $date_rule_daysweek;

			$yyear = date( 'Y' );
			for ( $days = 0; $days < 100; $days++ ) {
				$day_time         = strtotime( '+' . $days . ' days' );
				$day_date         = date( 'j-n-Y', $day_time );
				$day_week         = date( 'N', $day_time ) - 1;
				$day_week_enabled = true;
				if ( 'firstavl' === $default_date &&
					(
						strtotime( $day_date ) >= strtotime( date( 'j-n-Y' ) ) &&
						(
							( in_array( $day_week + 1, $date_rule_daysweek, true ) && 'enable' === $enable_disable_date_rules ) ||
							( ! in_array( $day_week + 1, $date_rule_daysweek, true ) && 'disable' === $enable_disable_date_rules )
						)
					) ) {
					$default_date = date( $date_format, $day_time );
					break;
				}
			}
		} elseif ( 'months' === $date_rule_what ) {
			$selected_items[ $date_rule_what ][] = $date_rule_months;

			$yyear = date( 'Y' );
			foreach ( $date_rule_months as $key => $month ) {
				for ( $day = 1; $day < 32; $day++ ) {
					if ( 'firstavl' === $default_date && ( strtotime( date( 'j-n-Y', strtotime( $day . '-' . $month . '-' . $yyear ) ) ) >= strtotime( date( 'j-n-Y' ) ) ) ) {
						$default_date = date( $date_format, strtotime( $day . '-' . $month . '-' . $yyear ) );
					}
				}
			}
		} elseif ( 'years' === $date_rule_what ) {
			$selected_items[ $date_rule_what ][] = $date_rule_years;

			foreach ( $date_rule_years as $key => $yyear ) {
				for ( $month = 1; $month < 13; $month++ ) {
					for ( $day = 1; $day < 32; $day++ ) {
						if ( 'firstavl' === $default_date && ( strtotime( date( 'j-n-Y', strtotime( $day . '-' . $month . '-' . $yyear ) ) ) >= strtotime( date( 'j-n-Y' ) ) ) ) {
							$default_date = date( $date_format, strtotime( $day . '-' . $month . '-' . $yyear ) );
						}
					}
				}
			}
		}
	}
}

if ( ! empty( $selected_items ) ) {
	$selected_items = wp_json_encode( $selected_items );
}

?>

<div id="yith-wapo-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" class="yith-wapo-option">

	<?php if ( $addon->get_option( 'show_image', $x ) && $addon->get_option( 'image', $x ) !== '' && 'yes' !== $setting_hide_images ) : ?>
		<?php
			$post_id_image = attachment_url_to_postid( $addon->get_option( 'image', $x ) );
			$alt_text_image = get_post_meta( $post_id_image, '_wp_attachment_image_alt', true );
		?>
		<div class="image position-<?php echo esc_attr( $addon_options_images_position ); ?>">
			<img src="<?php echo esc_attr( $addon->get_option( 'image', $x ) ); ?>" alt="<?php echo esc_attr( $alt_text_image ) ?>">
		</div>
	<?php endif; ?>

	<div class="label">
		<label for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
			<?php echo ! $hide_option_label ? wp_kses_post( $addon->get_option( 'label', $x ) ) : ''; ?>
			<?php echo ! $hide_option_prices ? wp_kses_post( $addon->get_option_price_html( $x ) ) : ''; ?>
			<?php echo $required ? '<span class="required">*</span>' : ''; ?>
		</label>
	</div>

	<span id="temp-time" style="display: none;"></span>

	<input type="text"
		id="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
		class="yith_wapo_date datepicker"
		name="yith_wapo[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
		value="<?php echo esc_attr( $default_date ); ?>"
		data-price="<?php echo esc_attr( $price ); ?>"
		data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
		data-price-type="<?php echo esc_attr( $price_type ); ?>"
		data-price-method="<?php echo esc_attr( $price_method ); ?>"
		data-first-free-enabled="<?php echo esc_attr( $addon->get_setting( 'first_options_selected', 'no' ) ); ?>"
		data-first-free-options="<?php echo esc_attr( $addon->get_setting( 'first_free_options', 0 ) ); ?>"
		data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
		<?php echo $required ? 'required' : ''; ?>
		style="<?php echo esc_attr( $options_width_css ); ?>"
		readonly
	>

	<?php if ( $required ) : ?>
		<small class="required-error" style="color: #f00; padding: 5px 0px; display: none;"><?php echo esc_html__( 'This option is required.', 'yith-woocommerce-product-add-ons' ); ?></small>
	<?php endif; ?>

	<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
		<span class="tooltip position-<?php echo esc_attr( get_option( 'yith_wapo_tooltip_position' ) ); ?>" style="<?php echo esc_attr( $options_width_css ); ?>">
			<span><?php echo wp_kses_post( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>

	<?php if ( '' !== $option_description ) : ?>
		<p class="description">
			<?php echo wp_kses_post( $option_description ); ?>
		</p>
	<?php endif; ?>

	<script type="text/javascript">

		jQuery( function() {
			jQuery( '#yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>' ).datepicker({
				dateFormat: '<?php echo esc_attr( $date_format_js ); ?>',
				<?php
				if ( $start_year > 0 ) :
					?>
					minDate: new Date('<?php echo esc_attr( $start_year ); ?>-01-01'),
					<?php
				endif;
				if ( $end_year > 0 ) :
					?>
					maxDate: new Date('<?php echo esc_attr( $end_year ); ?>-12-31'),<?php endif; ?>

				beforeShowDay: function( date ) {
                var selectableDays = [<?php echo wp_kses_post( $selectable_days ); ?>];
				var selectedItems = <?php echo ! empty( $selected_items ) ? wp_kses_post( $selected_items ) : "''"; ?>;
				var enabled       = '<?php echo wp_kses_post( $enable_disable_date_rules ); ?>';
				enabled           = ( 'enable' === enabled ) ? 1 : 0;
				var returnValue   = true;

				if ( enabled ) {
					returnValue = false;
				}

                jQuery.each( selectableDays, function( i, item ) {
                    let currentDate = date.getDate() + '-' + ( date.getMonth() + 1 ) + '-' + date.getFullYear();
                    if ( -1 === jQuery.inArray( currentDate, selectableDays ) ) {
                        returnValue = false;
                        return false;
                    }
                });

				jQuery.each( selectedItems, function( i, items ) {

					if ( 'days' === i ) {
						  let currentDate = new Date( date );
						  jQuery.each( items, function ( i, item ) {
							  let selectedDay = new Date( item );
							  if (currentDate.toDateString() === selectedDay.toDateString()) {
								  returnValue = ( enabled ) ? true : false;
								  return false;
							  }
						  });
					} else if ( 'daysweek' === i ) {
						  let dayWeek = date.getDay();
						  jQuery.each( items, function ( i, item ) {
							  jQuery.each( item, function (e, day) {
								  if (dayWeek == day) {
									  returnValue = ( enabled ) ? true : false;
									  return false;
								  }
							  });
						  });
					} else if ( 'months' === i ) {
						  let dateMonth = date.getMonth();
						  jQuery.each( items, function( i, item ) {
							  jQuery.each( item, function( e, month ) {
								  if ( dateMonth == month -1 ) {
									  returnValue = ( enabled ) ? true : false;
									  return false;
								  }
							  } );
						  } );
					} else if ( 'years' === i ) {
						  let dateYear = date.getFullYear();
						  jQuery.each( items, function( i, item ) {
							  jQuery.each( item, function( e, year ) {
								  if ( dateYear == year ) {
									  returnValue = ( enabled ) ? true : false;
									  return false;
								  }
							  } );
						  } );
					  }
				  });

				  if ( returnValue ) { return [true]; }
				  return [false];
				  },

			<?php
			if ( 'yes' === $show_time_selector ) :
				?>
					onSelect: function() {

					var selectedDate = jQuery(this).val();
					// var selectedTime = jQuery('#wapo-datepicker-time-select').val();
					var selectedTime = jQuery('#temp-time').text();
					if ( selectedTime == '' ) { selectedTime = jQuery('#wapo-datepicker-time-select').val(); }
					jQuery(this).val( selectedDate + ' ' + selectedTime );

					jQuery(this).data('datepicker').inline = true;
				},<?php endif; ?>
				<?php
				if ( 'yes' === $show_time_selector ) :
					?>
					onClose: function() { jQuery(this).data('datepicker').inline = false; }<?php endif; ?>
			});
		});

		<?php if ( 'yes' === $show_time_selector ) : ?>

			function wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>( onFocusTime = false, defaultTime = false ) {
				// clearTimeout( wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>.timer );

				if ( jQuery( '#temp-time' ).text() != '' || <?php echo '' !== $default_date ? 'false' : 'true'; ?> ) {
					onFocusTime = false;
					defaultTime = false;
				}

				if ( jQuery( '#yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>' ).val() == '' ) {
					jQuery( '#temp-time' ).text('');
				}

				if ( ! jQuery('#wapo-datepicker-time').length ) {
					if ( true ) {

						var meridiem = 'am';
						var selectOptionsHTML = '';
						var selectOptionsAM = '';
						var selectOptionsPM = '';
						var selectOptions = '';

						var timeSlotsArray = [];
						var timeSlotsFromMin = [];
						var timeSlotsToMin = [];

						<?php
						if ( 'yes' === $enable_time_slots ) {

							$enabled_hours = array();
							$enabled       = 'enable' === $time_slots_type ? 0 : 1;
							for ( $hour = 0; $hour < 24; $hour++ ) {

								$time_slot_from_count = count( $time_slot_from );
								for ( $xx = 0; $xx < $time_slot_from_count; $xx++ ) {

									// array index from.
									$calculated_date_from = (int) $time_slot_from[ $xx ];
									if ( 'pm' === $time_slot_from_type[ $xx ] ) {
										$calculated_date_from += 12;
										if ( 24 === $calculated_date_from ) {
											$calculated_date_from = 12; }
									} else {
										if ( 12 === $calculated_date_from ) {
											$calculated_date_from = 0; }
									}
									if ( $calculated_date_from === $hour ) {
										$enabled = $enabled ? 0 : 1;
										?>
											timeSlotsFromMin[<?php echo esc_attr( $hour ); ?>] = <?php echo esc_attr( $time_slot_from_min[ $xx ] ); ?>;
											<?php
									}

									// array index to.
									$calculated_date_to = $time_slot_to[ $xx ];
									if ( 'pm' === $time_slot_to_type[ $xx ] ) {
										$calculated_date_to += 12;
										if ( 24 === $calculated_date_to ) {
											$calculated_date_to = 12; }
									} else {
										if ( 12 === $calculated_date_to ) {
											$calculated_date_to = 0; }
									}
									if ( $calculated_date_to === $hour - 1 ) {
										$enabled = $enabled ? 0 : 1;
										?>
											timeSlotsToMin[<?php echo esc_attr( $hour - 1 ); ?>] = <?php echo esc_attr( $time_slot_to_min[ $xx ] ); ?>;
											<?php
									}
								}

								?>
									timeSlotsArray[<?php echo esc_attr( $hour ); ?>] = <?php echo esc_attr( $enabled ); ?>;
									<?php
									$enabled_hours[ $hour ] = $enabled;

							}
						}
						?>

						// Hours
						for ( var timeH = 0; timeH < 24; timeH = timeH + <?php echo 'hours' === $time_interval_type ? esc_attr( $time_interval ) : 1; ?> ) {
							if ( '<?php echo esc_attr( $enable_time_slots ); ?>' != 'yes' || timeSlotsArray[ timeH ] == 1 ) {

								var selected = '';
								var timeHpad = String( timeH );
								if ( timeH == 0 ) { timeHpad = '12'; }
								if ( timeH > 11 ) { meridiem = 'pm'; }
								if ( timeH > 12 ) { timeHpad = String( timeH - 12 ); }

								// Minutes
								<?php if ( 'minutes' === $time_interval_type || 'seconds' === $time_interval_type ) : ?>

									var startMin = 0;
									var endMin = 59;

									if (  typeof timeSlotsFromMin[ timeH ] != 'undefined' ) {
										startMin = timeSlotsFromMin[ timeH ];
									}

									if ( typeof timeSlotsToMin[ timeH ] != 'undefined' ) {
										endMin = timeSlotsToMin[ timeH ];
									}

									for ( var timeM = startMin; timeM <= endMin; timeM = timeM + <?php echo 'minutes' === $time_interval_type ? esc_attr( $time_interval ) : 1; ?> ) {
										var timeMpad = String( timeM );

										// Seconds
										<?php if ( 'seconds' === $time_interval_type ) : ?>
											for ( var timeS = 0; timeS < 60; timeS = timeS + <?php echo 'seconds' === $time_interval_type ? esc_attr( $time_interval ) : 1; ?> ) {
												var timeSpad = String( timeS );
												var timeText = timeHpad.padStart(2,0) + ':' + timeMpad.padStart(2,0) + ':' + timeSpad.padStart(2,0) + ' ' + meridiem;
												if ( timeText == jQuery( '#temp-time' ).text() ) { selected = ' selected'; } else { selected = ''; }
												selectOptions += '<option' + selected + '>' + timeText + '</option>';

												if ( defaultTime ) {
													var inputField = jQuery( '#yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>' );
													inputField.val( inputField.val() + ' ' + timeText );
													defaultTime = false;
												} else if ( onFocusTime ) {
													jQuery('#temp-time').text( timeText );
													onFocusTime = false;
												}
											}

										<?php else : ?>
											var timeText = timeHpad.padStart(2,0) + ':' + timeMpad.padStart(2,0) + ' ' + meridiem;
											if ( timeText == jQuery( '#temp-time' ).text() ) { selected = ' selected'; } else { selected = ''; }
											selectOptions += '<option' + selected + '>' + timeText + '</option>';

											if ( defaultTime ) {
												var inputField = jQuery( '#yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>' );
												inputField.val( inputField.val() + ' ' + timeText );
												defaultTime = false;
											} else if ( onFocusTime ) {
												jQuery('#temp-time').text( timeText );
												onFocusTime = false;
											}

										<?php endif; ?>
									}
								<?php else : ?>
									var timeText = timeHpad.padStart(2,0) + ':00 ' + meridiem;
									if ( timeText == jQuery( '#temp-time' ).text() ) { selected = ' selected'; } else { selected = ''; }
									selectOptions += '<option' + selected + '>' + timeText + '</option>';

									if ( defaultTime ) {
										var inputField = jQuery( '#yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>' );
										inputField.val( inputField.val() + ' ' + timeText );
										defaultTime = false;
									} else if ( onFocusTime ) {
										jQuery('#temp-time').text( timeText );
										onFocusTime = false;
									}
								<?php endif; ?>

							}
						}
						selectOptionsHTML = selectOptions;

						var timeHTML = '<div id="wapo-datepicker-time"><label><?php echo esc_html__( 'Set time', 'yith-woocommerce-product-add-ons' ); ?></label><select id="wapo-datepicker-time-select">' + selectOptionsHTML + '</select></div>';
						timeHTML += '<div id="wapo-datepicker-save"><button><?php echo esc_html__( 'Save', 'yith-woocommerce-product-add-ons' ); ?></button></div>';
						jQuery('#ui-datepicker-div').append( timeHTML );
					} else {
						wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>.timer = setTimeout( wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?> , 10 );
					}
				}
			}

			jQuery('#yith-wapo-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?> .yith_wapo_date').focus(function() {
				wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>( true, false );
				setTimeout( function() {
					wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>( true, false );
				}, 1);
			});

			wapoDatepickerTime_<?php echo esc_attr( $addon->id ); ?>_<?php echo esc_attr( $x ); ?>( false, true );

		<?php endif; ?>

	</script>

</div>


/**
 * Admin JS
 */

( function ( $ ) {

  $( '#option-price-type' ).on('change', checkAddonConditions );

  function checkAddonConditions( ) {
    let selectedElement = $( this );
    let parentElement   = selectedElement.parents( '.option-cost' );
    let saleElement     = parentElement.find( 'div.option-price-sale' );

    if ( 'multiplied' === selectedElement.val() ) {
      saleElement.fadeOut();
    } else{
      saleElement.fadeIn();
    }
  }

	/*
	 *
	 *	enable/disable
	 *	blocks
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#sortable-blocks').on( 'change', '.active .yith-plugin-fw-onoff-container input', function() {

		var blockID = $(this).attr('id').replace( 'yith-wapo-active-block-', '' );
		var blockVisibility = 0;
		if ( $(this).is(':checked') ) { blockVisibility = 1; }
		else { blockVisibility = 0; }

		// Ajax method
		var data = {
			'action'		: 'enable_disable_block',
			'block_id'		: blockID,
			'block_vis'		: blockVisibility,
		};
		$.post( ajaxurl, data, function(response) {
			console.log( '[YITH.LOG] - Block visibility updated' );
		});

	});

	/*
	 *
	 *	enable/disable
	 *	addons
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#sortable-addons').on( 'change', '.addon-onoff input', function() {

		var addonID = $(this).attr('id').replace( 'yith-wapo-active-addon-', '' );
		var addonVisibility = 0;
		if ( $(this).is(':checked') ) { addonVisibility = 1; }
		else { addonVisibility = 0; }

		// Ajax method
		var data = {
			'action'		: 'enable_disable_addon',
			'addon_id'		: addonID,
			'addon_vis'		: addonVisibility,
		};
		$.post( ajaxurl, data, function(response) {
			console.log( '[YITH.LOG] - Addon visibility updated' );
		});

	});

	/*
	 *
	 *	sortable admin feature
	 *	blocks + addons
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$( '#sortable-blocks' ).sortable({
		helper: fixWidthHelper,
		revert: true,
		axis: 'y',
		update: function ( event, ui ) {
			var movedItem = ui.item.data('id');
			var prevItem = parseFloat( ui.item.prev().data('priority') );
			var nextItem = parseFloat( ui.item.next().data('priority') );
			// Ajax method
			var data = {
				'action'		: 'sortable_blocks',
				'moved_item'	: movedItem,
				'prev_item'		: prevItem,
				'next_item'		: nextItem,
			};
			$.post( ajaxurl, data, function(response) {
				var res = response.split('-');
				var itemID = res[0];
				var itemPR = parseFloat( res[1] );
				$( '#sortable-blocks #block-' + itemID ).attr( 'data-priority', itemPR );
			});
		}
	});
	$( '#sortable-addons' ).sortable({
		revert: true,
		axis: 'y',
		update: function ( event, ui ) {
			var movedItem = ui.item.data('id');
			var prevItem = parseFloat( ui.item.prev().data('priority') );
			var nextItem = parseFloat( ui.item.next().data('priority') );
			// Ajax method
			var data = {
				'action'		: 'sortable_addons',
				'moved_item'	: movedItem,
				'prev_item'		: prevItem,
				'next_item'		: nextItem,
			};
			$.post( ajaxurl, data, function(response) {
				var res = response.split('-');
				var itemID = res[0];
				var itemPR = parseFloat( res[1] );
				$( '#sortable-addons #addon-' + itemID ).attr( 'data-priority', itemPR );
				console.log( '[YITH.LOG] - New priority value: ' + itemPR );
			});
		}
	});

  $( '#addon_options' ).sortable({
    helper: fixWidthHelper,
    revert: true,
    axis: 'y',
  });

	$( 'ul, li, tbody, tr, td' ).disableSelection();
	function fixWidthHelper( e, ui ) {
		ui.children().each(function() { $(this).width( $(this).width() ); });
		return ui;
	}

	/*
	 *
	 *	block rules dependencies
	 *
	 * * * * * * * * * * * * * * * * * * * */

	var showInInput = $('#block-rules #yith-wapo-block-rule-show-in input');
	var showInProducts = $('.field-wrap.yith-wapo-block-rule-show-in-products');
	var showInCategories = $('.field-wrap.yith-wapo-block-rule-show-in-categories');
	var showInVal = showInInput.val();
	var excludeProducts = $('.field-wrap.yith-wapo-block-rule-exclude-products');
	var excludeProductsInput = $('#block-rules #yith-wapo-block-rule-exclude-products');
	var excludeProductsProducts = $('.field-wrap.yith-wapo-block-rule-exclude-products-products')
	var excludeProductsCategories = $('.field-wrap.yith-wapo-block-rule-exclude-products-categories')
	showInInput.change(function() {
		showInVal = $(this).val();
		if ( $(this).val() == 'products' ) {
			showInProducts.fadeIn();
			showInCategories.hide();
			/*
			excludeProducts.fadeOut();
			if ( excludeProductsInput.val() == 'yes' ) {
				excludeProducts.find('.yith-plugin-fw-onoff').click();
			}
			*/
		} else if ( $(this).val() == 'categories' ) {
			showInProducts.hide();
			showInCategories.fadeIn();
            /*
			excludeProducts.fadeIn();
			if ( excludeProductsInput.val() == 'yes' ) {
				excludeProductsCategories.fadeOut();
			}
             */
		} else {
			showInProducts.fadeOut();
			showInCategories.fadeOut();
            /*
			excludeProducts.fadeIn();
			if ( excludeProductsInput.val() == 'yes' ) {
				excludeProductsCategories.fadeIn();
			}
			*/
		}
	});
	excludeProductsInput.change(function() {
		if ( $(this).val() == 'yes' ) {
			excludeProductsProducts.fadeIn();
			if ( showInVal == 'all' ) {
				excludeProductsCategories.fadeIn();
			}
		} else {
			excludeProductsProducts.fadeOut();
			excludeProductsCategories.fadeOut();
		}
	});

	/*
	 *
	 *	options dependencies (enablers)
	 *	only for simple onoff options
	 *	function: check enablers
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('.yith-wapo').on('change', '.enabler input', function() { yith_wapo_check_enablers( $(this) ); });
	$('.yith-wapo .enabler input').each( function() { yith_wapo_check_enablers( $(this) ); });
	function yith_wapo_check_enablers( enabler ) {
		if ( enabler.is(':checked') ) { $( '.enabled-by-' + enabler.attr('id') ).fadeIn(); }
		else { $( '.enabled-by-' + enabler.attr('id') ).fadeOut(); }
	}
	// HTML Separator
	$('.yith-wapo').on('change', '#option-separator-style', function() {
		if ( $(this).val() == 'empty_space' ) {
			$('.field-wrap.option-separator-color').fadeOut();
		} else {
			$('.field-wrap.option-separator-color').fadeIn();
		}
	});
	// Label Style
	$('.yith-wapo').on('change', '#addon-custom-style', function() {
		if ( ! $(this).is(':checked') && $('#addon-image-equal-height').is(':checked') ) {
			$('.addon-image-equal-height .yith-plugin-fw-onoff').click();
		}
	});

	/*
	 *
	 *	option toggle
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#tab-options-list').on( 'click', '.option .title', function() {
		var fieldsContainer = $(this).parent().find('.fields');
		fieldsContainer.toggle();
		if ( fieldsContainer.is(':visible') ) {
			$(this).parent().removeClass('close').addClass('open');
		} else {
			$(this).parent().removeClass('open').addClass('close');
		}
	});

	/*
	 *
	 *	new option
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#add-new-option').click( function() {
		var newOptionID = $(this).parent().find('.option').length;
		$('.yith-plugin-fw-upload-container button').off();
		var template = wp.template( 'new-option-' + newOptionID );
		$('#add-new-option').before( template() );
		$('body').trigger( 'yith_fields_init' );
		// $('body').trigger( 'yith-framework-enhanced-select-init' );
	});

	$('#tab-options-list').on( 'change', '.option-price-method', function() {
		var parent = $(this).parent().parent().parent().parent();
		if ( $(this).val() != 'free' && $(this).val() != 'product' && $(this).val() != 'value_x_product' ) {
			parent.find('.option-cost').fadeIn();
			if ( $(this).val() == 'increase' ) {
				parent.find('.option-cost .option-price-method-increase').fadeIn();
				parent.find('.option-cost .option-price-method-decrease').fadeOut();
			} else {
				parent.find('.option-cost .option-price-method-increase').fadeOut();
				parent.find('.option-cost .option-price-method-decrease').fadeIn();
			}
		} else {
			parent.find('.option-cost').fadeOut();
		}
	});

	$('#tab-options-list').on( 'change', '.option-selectable-dates', function() {
		var parent = $(this).parent().parent().parent().parent();
		if ( $(this).val() == 'days' ) {
			parent.find('.option-selectable-days-ranges').fadeIn();
			parent.find('.option-selectable-date-ranges').hide();
		} else if ( $(this).val() == 'date' ) {
			parent.find('.option-selectable-days-ranges').hide();
			parent.find('.option-selectable-date-ranges').fadeIn();
		} else {
			parent.find('.option-selectable-days-ranges').hide();
			parent.find('.option-selectable-date-ranges').hide();
		}
	});

	/*
	 *
	 *	remove option
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#tab-options-list').on( 'click', '.yith-plugin-fw__action-button--delete-action', function() {
		$(this).parent().parent().remove();
	});

	/*
	 *
	 *	color swatch
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('#tab-options-list').on( 'change', '.color-show-as select', function() {
		var parent = $( this ).parent().parent().parent().parent().parent();
		if ( $( this ).val() == 'double' ) {
			parent.find('.color').fadeIn();
			parent.find('.color_b').fadeIn();
			parent.find('.color_image').hide();
		} else if ( $( this ).val() == 'image' ) {
			parent.find('.color').hide();
			parent.find('.color_b').hide();
			parent.find('.color_image').fadeIn();
		} else {
			parent.find('.color').fadeIn();
			parent.find('.color_b').hide();
			parent.find('.color_image').hide();
		}
	});
	$('#tab-options-list').on( 'click', '.option .title', function() {
		$(this).parent().find('.color-show-as select').change();
	});
	$('#tab-options-list').find('.color-show-as select').change();

	/*
	 *
	 *	calendar (date picker)
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$('.yith-wapo').on('change', '.option-date-default', function() {
		var parent = $(this).parent().parent().parent().parent();
		if ( $(this).val() == 'specific' ) {
			parent.find('.option-date-default-day').fadeIn();
			parent.find('.option-date-default-interval').hide();
		} else if ( $(this).val() == 'interval') {
			parent.find('.option-date-default-day').hide();
			parent.find('.option-date-default-interval').fadeIn();
		} else {
			parent.find('.option-date-default-day').fadeOut();
			parent.find('.option-date-default-interval').fadeOut();
		}
	});
	$( document ).on( 'click', '.add-date-rule a', function() {
		/*
		var template = wp.template( 'new-date-rule' );
		var parent = $(this).parent();
		parent.before( template() );
		$('.date-rules').trigger('yith-framework-enhanced-select-init');
		return false;
		*/
		let addDataRuleContainer = $(this).parent();
		let dateRulesContainer = $(this).parent().parent();
		let ruleTemplate = dateRulesContainer.find('.rule:first-child');
		let clonedOption = ruleTemplate.clone();
		let randomID = Math.floor( Math.random() * 100000 );
		clonedOption.find('select').val('days').attr('id', 'new-select-' + randomID );
		clonedOption.find('.option-date-default-day').fadeIn();
		clonedOption.find('input').val('').attr('id', 'new-input-' + randomID );
		clonedOption.find('ul li.select2-selection__choice').remove();
		clonedOption.find('.delete-rule').show();
		addDataRuleContainer.before( clonedOption );
		$('#new-input-' + randomID ).datepicker();
		return false;
	});
	$('.date-rules').on( 'change', '.select_what', function() {
		var rule = $(this).parent().parent().parent();
		rule.find('.field:not(.what)').hide();
		if ( $(this).val() == 'years' ) { rule.find('.field.years').fadeIn(); }
		else if ( $(this).val() == 'months' ) { rule.find('.field.months').fadeIn(); }
		else if ( $(this).val() == 'daysweek' ) { rule.find('.field.daysweek').fadeIn(); }
		else { rule.find('.field.days').fadeIn(); }
	});
	$('.date-rules').on( 'click' , '.delete-rule', function() {
		$(this).parent().remove();
	});

	/*
	 *
	 *	calendar (time slots)
	 *
	 * * * * * * * * * * * * * * * * * * * */

	$( document ).on( 'click', '.add-time-slot a', function() {
		let addDataRuleContainer = $(this).parent();
		let dateRulesContainer = $(this).parent().parent();
		let ruleTemplate = dateRulesContainer.find('.slot:first-child');
		let clonedOption = ruleTemplate.clone();
		let randomID = Math.floor( Math.random() * 100000 );
		clonedOption.find('.delete-slot').show();
		addDataRuleContainer.before( clonedOption );
		return false;
	});
	$('.time-slots').on( 'click' , '.delete-slot', function() {
		$(this).parent().remove();
	});
	/*
	 *
	 *	Conditional logic
	 *
	 * * * * * * * * * * * * * * * * * * * */
	$( document ).on( 'select2:open', function ( e ) {
		$( '.select2-results' ).closest( '.select2-container' ).addClass( 'yith-addons-select2-container' );
	} );
} )( jQuery );

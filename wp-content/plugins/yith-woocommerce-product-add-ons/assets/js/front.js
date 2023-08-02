
/**
 * Front JS
 */



// addon type (checkbox)

jQuery('body').on( 'change', '.yith-wapo-addon-type-checkbox input', function() {
	var optionWrapper = jQuery(this).parents('.yith-wapo-option');
	// Proteo check
	// if ( ! optionWrapper.hasClass('yith-wapo-option') ) { optionWrapper = optionWrapper.parent(); }
	if ( jQuery(this).is(':checked') ) {
		optionWrapper.addClass('selected');

		// Single selection
		if ( optionWrapper.hasClass('selection-single') ) {
			// Disable all
			optionWrapper.parent().find('input').prop('checked', false);
			optionWrapper.parent().find('.selected').removeClass('selected');
			// Enable only the current option
			optionWrapper.find('input').prop('checked', true);
			optionWrapper.addClass('selected');
		}

		// Replace image
		yith_wapo_replace_image( optionWrapper );

	} else {
		optionWrapper.removeClass('selected');
		yith_wapo_replace_image( optionWrapper, true );
	}
});

// addon type (color)

jQuery('body').on( 'change', '.yith-wapo-addon-type-color input', function() {
	var optionWrapper = jQuery(this).parent();
	// Proteo check
	if ( ! optionWrapper.hasClass('yith-wapo-option') ) { optionWrapper = optionWrapper.parent(); }
	if ( jQuery(this).is(':checked') ) {
		optionWrapper.addClass('selected');

		// Single selection
		if ( optionWrapper.hasClass('selection-single') ) {
			// Disable all
			optionWrapper.parent().find('input').prop('checked', false);
			optionWrapper.parent().find('.selected').removeClass('selected');
			// Enable only the current option
			optionWrapper.find('input').prop('checked', true);
			optionWrapper.addClass('selected');
		}

		// Replace image
		yith_wapo_replace_image( optionWrapper );

	} else {
		optionWrapper.removeClass('selected');
		yith_wapo_replace_image( optionWrapper, true );
	}
});

// addon type (label)

jQuery('body').on( 'change', '.yith-wapo-addon-type-label input', function() {
	var optionWrapper = jQuery(this).parent();
	// Proteo check
	if ( ! optionWrapper.hasClass('yith-wapo-option') ) { optionWrapper = optionWrapper.parent(); }
	if ( jQuery(this).is(':checked') ) {
		optionWrapper.addClass('selected');

		// Single selection
		if ( optionWrapper.hasClass('selection-single') ) {
			// Disable all
			optionWrapper.parent().find('input').prop('checked', false);
			optionWrapper.parent().find('.selected').removeClass('selected');
			// Enable only the current option
			optionWrapper.find('input').prop('checked', true);
			optionWrapper.addClass('selected');
		}

		// Replace image
		yith_wapo_replace_image( optionWrapper );

	} else {
		optionWrapper.removeClass('selected');
		yith_wapo_replace_image( optionWrapper, true );
	}
});

// addon type (product)

jQuery('body').on( 'change', '.yith-wapo-addon-type-product input', function() {
	var optionWrapper = jQuery(this).parent();// Proteo check
	// Proteo check
	if ( ! optionWrapper.hasClass('yith-wapo-option') ) { optionWrapper = optionWrapper.parent(); }
	if ( jQuery(this).is(':checked') ) {
		optionWrapper.addClass('selected');

		// Single selection
		if ( optionWrapper.hasClass('selection-single') ) {
			// Disable all
			optionWrapper.parent().find('input').prop('checked', false);
			optionWrapper.parent().find('.selected').removeClass('selected');
			// Enable only the current option
			optionWrapper.find('input').prop('checked', true);
			optionWrapper.addClass('selected');
		}

		// Replace image
		yith_wapo_replace_image( optionWrapper );

	} else {
		optionWrapper.removeClass('selected');
		yith_wapo_replace_image( optionWrapper, true );
	}
});

// addon type (radio)

jQuery('body').on( 'change', '.yith-wapo-addon-type-radio input', function() {
	var optionWrapper = jQuery(this).parent();
	// Proteo check
	if ( ! optionWrapper.hasClass('yith-wapo-option') ) { optionWrapper = optionWrapper.parent(); }
	if ( jQuery(this).is(':checked') ) {
		optionWrapper.addClass('selected');

		// Replace image
		yith_wapo_replace_image( optionWrapper );

		// Remove selected siblings
		optionWrapper.siblings().removeClass('selected');

	} else { optionWrapper.removeClass('selected'); }
});

// addon type (select)

jQuery('body').on( 'change', '.yith-wapo-addon-type-select select', function() {
	let optionWrapper    = jQuery( this ).parent();
	let selectedOption   = jQuery( this ).find('option:selected');
  let optionImageBlock = optionWrapper.find('div.option-image');
	// Proteo check
	if ( ! optionWrapper.hasClass('yith-wapo-option') ) {
    optionWrapper = optionWrapper.parent();
  }

	// Description & Image.
	var optionImage       = selectedOption.data( 'image' );
	var optionDescription = selectedOption.data( 'description' );
	var option_desc       = optionWrapper.find( 'p.option-description' );

	if ( typeof optionImage !== 'undefined' && optionImage ) {
		optionImage = '<img src="' + optionImage + '" style="max-width: 100%">';
    optionImageBlock.html( optionImage );
	}

  if ( 'default' === selectedOption.val() ){
    optionImageBlock.hide();
  } else {
    optionImageBlock.fadeIn();
  }

	if ( 'undefined' === typeof optionDescription ) {
		option_desc.empty();
	} else {
		option_desc.html( optionDescription );
	}

	// Replace image
	yith_wapo_replace_image( selectedOption );

});
jQuery('.yith-wapo-addon-type-select select').trigger('change');






// toggle feature

jQuery( document ).on( 'click', '.yith-wapo-addon.wapo-toggle .wapo-addon-title', function(){
  let addon_title = jQuery( this );
  let addon_el = addon_title.parents( '.yith-wapo-addon' );

  if ( addon_el.hasClass('toggle-open') ) {
    addon_el.removeClass('toggle-open').addClass('toggle-closed');
  } else {
    addon_el.removeClass('toggle-closed').addClass('toggle-open');
  }
	if ( addon_title.hasClass('toggle-open') ) {
    addon_title.removeClass('toggle-open').addClass('toggle-closed');
	} else {
    addon_title.removeClass('toggle-closed').addClass('toggle-open');
	}

  addon_title.parent().find('.options').toggle('fast');

  jQuery(document).trigger('yith_proteo_inizialize_html_elements');
});






// function: replace image

function yith_wapo_replace_image( optionWrapper, reset = false ) {

  var defaultPath = yith_wapo.replace_image_path;
	var zoomMagnifier = '.yith_magnifier_zoom_magnifier, .zoomWindowContainer .zoomWindow';

	if ( typeof optionWrapper.data('replace-image') !== 'undefined' && optionWrapper.data('replace-image') != '' ) {
		var replaceImageURL = optionWrapper.data('replace-image');

		// save original image for the reset
		if ( typeof( jQuery(defaultPath).attr('wapo-original-img') ) == 'undefined' ) {
			jQuery(defaultPath).attr( 'wapo-original-img', jQuery(defaultPath).attr('src') );
			if ( jQuery(zoomMagnifier).length ) {
				jQuery(zoomMagnifier).attr( 'wapo-original-img', jQuery(zoomMagnifier).css('background-image').slice(4, -1).replace(/"/g, "") );
			}
		}

		jQuery(defaultPath).attr( 'src', replaceImageURL );
		jQuery(defaultPath).attr( 'srcset', replaceImageURL );
		jQuery(zoomMagnifier).css('background-image', 'url(' + replaceImageURL + ')');
		jQuery('#yith_wapo_product_img').val( replaceImageURL );

	}
	if ( reset && typeof( jQuery(defaultPath).attr('wapo-original-img') ) != 'undefined' ) {

		var originalImage = jQuery(defaultPath).attr('wapo-original-img');
		var originalZoom = jQuery(zoomMagnifier).attr('wapo-original-img');

		jQuery(defaultPath).attr( 'src', originalImage );
		jQuery(defaultPath).attr( 'srcset', originalImage );
		jQuery(zoomMagnifier).css('background-image', 'url(' + originalZoom + ')');

	}
}

// function: check_required_fields

function yith_wapo_check_required_fields( action ) {
	var isRequired    = false;
	var hideButton    = false;
	var buttonClasses = yith_wapo.dom.single_add_to_cart_button;
	jQuery( 'form.cart .yith-wapo-addon:not(.hidden) input, form.cart .yith-wapo-addon:not(.hidden) select, form.cart .yith-wapo-addon:not(.hidden) textarea' ).each( function() {
		let element            = jQuery( this );
		let parent             = element.parent();
    let toggle_addon       = element.parents( 'div.yith-wapo-addon.wapo-toggle' );
    let toggle_addon_title = toggle_addon.find( 'h3.wapo-addon-title.toggle-closed' );
		let upload_el          = parent.find( '.yith-wapo-ajax-uploader' );

		if (
			element.attr( 'required' ) && ( 'checkbox' === element.attr('type') || 'radio' === element.attr('type') ) && ! element.parents( '.yith-wapo-option' ).hasClass( 'selected' )
			||
			element.attr('required') && ( element.val() == '' || element.val() == 'Required' )
		) {
			if ( action === 'highlight' ) {
				if ( 'checkbox' === element.attr('type') || 'radio' === element.attr('type') ) {
					parent = parent.parent();
				} else if ( upload_el ) {
					upload_el.css( 'border', '1px dashed #f00' );
					upload_el.css( 'background-color', '#fee' );
				} else {
					element.css( 'border', '1px solid #f00' );
				}

				parent.find( '.required-error' ).css( 'display', 'block' );

        if ( toggle_addon_title ) {
          toggle_addon_title.click();
        }
			}

			hideButton = true;
			isRequired = true;
		}
	});
	if ( action == 'hide' ) {
		if ( hideButton ) { jQuery( buttonClasses ).hide(); }
		else { jQuery( buttonClasses ).fadeIn(); }
	}
	return ! isRequired;
}


// conditional logic

window.setInterval( function() { yith_wapo_conditional_logic_check(); }, 1000 );
jQuery('form.cart .yith-wapo-addon').on( 'change', '*', function() { yith_wapo_conditional_logic_check(); });

function yith_wapo_conditional_logic_check() {
	jQuery('form.cart .yith-wapo-addon.conditional_logic').each(function() {

		var matchAll = true;
		var matchAny = false;

		var addonID = jQuery(this).data('addon_id');
		var logicDisplay = jQuery(this).data('conditional_logic_display');					// show / hide
		var logicDisplayIf = jQuery(this).data('conditional_logic_display_if');				// all / any
		var ruleAddon = String( jQuery(this).data('conditional_rule_addon') ).split('|');
		var ruleAddonIs = String( jQuery(this).data('conditional_rule_addon_is') ).split('|');

		for ( var x=0; x<ruleAddon.length; x++ ) {

			var ruleAddonSplit = ruleAddon[x].split('-');
			var anyEmpty = false;
			var anySelected = false;

			// variation check
			if ( ruleAddonSplit[0] == 'v' ) {

				if ( jQuery('.variation_id').val() == ruleAddonSplit[2] ) {
					anySelected = true;
				}

			// option check
			} else if ( typeof ruleAddonSplit[1] != 'undefined' ) {

				anySelected = (
					jQuery( '#yith-wapo-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).is(':checked')
					|| jQuery( 'select#yith-wapo-' + ruleAddonSplit[0] ).val() == ruleAddonSplit[1]
				) && ! jQuery( '#yith-wapo-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );

				var typeText = jQuery( 'input#yith-wapo-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();			// text
				var typeTextarea = jQuery( 'textarea#yith-wapo-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();		// textarea
				anyEmpty = (
					( typeof typeText != 'undefined' && typeText !== '' )
					|| ( typeof typeTextarea != 'undefined' && typeTextarea !== '' )
				);

			// addon check
			} else {
				anySelected = (
					jQuery( '#yith-wapo-addon-' + ruleAddon[x] + ' input:checkbox:checked').length > 0
					|| jQuery( '#yith-wapo-addon-' + ruleAddon[x] + ' input:radio:checked').length > 0
					|| jQuery( '#yith-wapo-addon-' + ruleAddon[x] + ' option:selected').length > 0
				);
			}

			if ( ruleAddonIs[x] == 'selected' ) {
				if ( anySelected )	{ matchAny = true; }
				else				{ matchAll = false; }
			} else if ( ruleAddonIs[x] == 'not-selected' ) {
				if ( anySelected )	{ matchAll = false; }
				else				{ matchAny = true; }
			} else if ( ruleAddonIs[x] == 'empty' ) {
				if ( anyEmpty )	{ matchAll = false; }
				else			{ matchAny = true; }
			} else if ( ruleAddonIs[x] == 'not-empty' ) {
				if ( anyEmpty )	{ matchAny = true; }
				else			{ matchAll = false; }
			}

		}

		if ( logicDisplayIf == 'all' && matchAll ) {
			if ( logicDisplay == 'show' ) { jQuery(this).fadeIn().removeClass('hidden'); }
			else { jQuery(this).hide().addClass('hidden'); }
		} else if ( logicDisplayIf == 'any' && matchAny ) {
			if ( logicDisplay == 'show' ) { jQuery(this).fadeIn().removeClass('hidden'); }
			else { jQuery(this).hide().addClass('hidden'); }
		} else {
			if ( logicDisplay == 'show' ) { jQuery(this).hide().addClass('hidden'); }
			else { jQuery(this).fadeIn().removeClass('hidden'); }
		}

	});
}

// ajax reload addons

jQuery( this ).on( 'yith-wapo-reload-addons', function( event, productPrice = '' ) {
	jQuery('#yith-wapo-container').css('opacity', '0.5');
	var addons = jQuery('form.cart').serializeArray();
	var data = {
		'action'	: 'live_print_blocks',
		'addons'	: addons,
	};
	if ( productPrice != '' ) {
		data.price = productPrice;
	}
	jQuery.ajax( {
		url : yith_wapo.ajaxurl,
		type : 'post',
		data : data,
		success : function( response ) {
			jQuery('#yith-wapo-container').html( response );
			yith_wapo_conditional_logic_check();
			jQuery('#yith-wapo-container').css('opacity', '1');
		}
	});
	return false;
});

function updateContainerProductPrice( variation ) {
  let container = jQuery( '#yith-wapo-container' );
  let new_product_price = 0;
  if ( typeof( variation.display_price ) !== 'undefined' ) {
    new_product_price = variation.display_price;
  }
  container.attr( 'data-product-price', new_product_price );
}

// reload after variation change
jQuery( this ).on('found_variation', function ( event, variation ) {
  updateContainerProductPrice( variation );
  jQuery( this ).trigger( 'yith-wapo-reload-addons');
});

// WooCommerce Measurement Price Calculator (compatibility)
jQuery('form.cart').on( 'change', '#price_calculator', function() {
	var price = jQuery('#price_calculator .product_price .amount').text().replace( ',', '.' );
	price = price.replace(/[^0-9\.-]+/g,'');
	jQuery('form.cart').trigger( 'yith-wapo-reload-addons', [ price ] );
});

/*
 *	ajax upload file
 */

// preventing page from redirecting
jQuery('html').on('dragover', function(e) {
	e.preventDefault();
	e.stopPropagation();
});
jQuery('html').on('drop', function(e) { e.preventDefault(); e.stopPropagation(); });

// drag enter
jQuery('.yith-wapo-ajax-uploader').on('dragenter', function (e) {
	e.stopPropagation();
	e.preventDefault();
	jQuery(this).css( 'opacity', '0.5' );
});

// drag over
jQuery('.yith-wapo-ajax-uploader').on('dragover', function (e) {
	e.stopPropagation();
	e.preventDefault();
});

// drag leave
jQuery('.yith-wapo-ajax-uploader').on('dragleave', function (e) {
	e.stopPropagation();
	e.preventDefault();
	jQuery(this).css( 'opacity', '1' );
});

// drop
jQuery('.yith-wapo-ajax-uploader').on('drop', function (e) {
	e.stopPropagation();
	e.preventDefault();

	var input = jQuery(this).parent().find('input.file');
	var file = e.originalEvent.dataTransfer.files[0];
	var data = new FormData();
	data.append( 'action', 'upload_file' );
	data.append( 'file', file );

	if ( yith_wapo.upload_allowed_file_types.includes( file.name.split('.').pop().toLowerCase() ) ) {
		if ( file.size <= yith_wapo.upload_max_file_size * 1024 * 1024 ) {
			yith_wapo_ajax_upload_file( data, file, input );
		} else { alert('Error: max file size ' + yith_wapo.upload_max_file_size + ' MB!') }
	} else { alert('Error: not supported extension!') }
});

// click
jQuery('#yith-wapo-container').on('click', '.yith-wapo-ajax-uploader .button, .yith-wapo-ajax-uploader .link', function() {
	jQuery(this).parent().parent().find('input.file').click();
});

// upload on click
jQuery('#yith-wapo-container').on('change', '.yith-wapo-addon-type-file input.file', function() {
	var input = jQuery(this);
	var file = jQuery(this)[0].files[0];
	var data = new FormData();
	data.append( 'action', 'upload_file' );
	data.append( 'file', file );

	if ( yith_wapo.upload_allowed_file_types.includes( file.name.split('.').pop().toLowerCase() ) ) {
		if ( file.size <= yith_wapo.upload_max_file_size * 1024 * 1024 ) {
			yith_wapo_ajax_upload_file( data, file, input );
		} else { alert('Error: max file size ' + yith_wapo.upload_max_file_size + ' MB!') }
	} else { alert('Error: not supported extension!') }

});

// remove
jQuery('#yith-wapo-container').on('click', '.yith-wapo-uploaded-file .remove', function() {
	jQuery(this).parent().hide();
	jQuery(this).parent().parent().find('.yith-wapo-ajax-uploader').fadeIn();
	jQuery(this).parent().parent().find('input').val('');
	jQuery(this).parent().parent().find('input.option').change();
});

function yith_wapo_ajax_upload_file( data, file, input ) {
  let exactSize = calculate_exact_file_size( file );
	input.parent().find('.yith-wapo-ajax-uploader').append('<div class="loader"></div>');

	jQuery.ajax( {
		url			: yith_wapo.ajaxurl,
		type		: 'POST',
		contentType	: false,
		processData	: false,
		data		: data,
		success : function( response ) {

			var wapo_option = input.parent();
			wapo_option.find('.yith-wapo-ajax-uploader .loader').remove();
			wapo_option.find('.yith-wapo-ajax-uploader').hide();
			//jQuery('.yith-wapo-ajax-uploader').html( 'Drop file to upload or <a href="' + response + '" target="_blank">browse</a>' );

			var file_name = response.replace(/^.*[\\\/]/, '');

			wapo_option.find('.yith-wapo-uploaded-file .info').html( file_name + '<br />' + exactSize );
			wapo_option.find('.yith-wapo-uploaded-file').fadeIn();
			wapo_option.find('input.option').val( response ).change();
		},
		error : function ( response ) {
			// jQuery('.yith-wapo-ajax-uploader').html( 'Error!<br /><br />' + response );
		}
	});
	return false;
}

function calculate_exact_file_size( file ) {
  let exactSize  = 0;
  let file_size  = file.size;
  let file_types = ['Bytes', 'KB', 'MB', 'GB'],
    i = 0;
  while( file_size > 900 ) {
    file_size /= 1024;
    i++;
  }
  exactSize = ( Math.round(file_size * 100 ) / 100 ) + ' ' + file_types[i];

  return exactSize;
}



jQuery('form.cart').on( 'click', 'span.checkboxbutton', function() {
	if ( jQuery(this).find( 'input' ).is( ':checked' ) ) {
		jQuery(this).addClass('checked');
	} else {
		jQuery(this).removeClass('checked');
	}
} );

jQuery('form.cart').on( 'click', 'span.radiobutton', function() {
	if ( jQuery(this).find( 'input' ).is( ':checked' ) ) {
		jQuery(this).parent().parent().parent().find('span.radiobutton.checked').removeClass('checked');
		jQuery(this).addClass('checked');
	}
} );






// min max rules

jQuery( '.yith-wapo-addon-type-checkbox, .yith-wapo-addon-type-color, .yith-wapo-addon-type-label, .yith-wapo-addon-type-product' ).each( function() {
	yith_wapo_check_min_max( jQuery( this ) );
});
jQuery( 'body' ).on( 'change', '.yith-wapo-addon-type-checkbox, .yith-wapo-addon-type-color, .yith-wapo-addon-type-label, .yith-wapo-addon-type-product', function() {
	yith_wapo_check_min_max( jQuery( this ) );
});

jQuery( 'body' ).on('click', 'form.cart button', function() {
	if ( yith_wapo_check_required_min_max() ) {
		jQuery('form.cart .yith-wapo-addon.conditional_logic.hidden').remove();
	} else {
		jQuery('html, body').animate( { scrollTop: jQuery('#yith-wapo-container').offset().top }, 500);
	}
	return yith_wapo_check_required_min_max();
});
jQuery(document).on( 'click', '.add-request-quote-button', function(e) {
    e.preventDefault();
	if ( ! yith_wapo_check_required_min_max() ) {
		yith_wapo_general.do_submit = false;
	}
});

function yith_wapo_check_required_min_max() {

  if ( ! checkRequiredSelect() ) {
    return false;
  }

	if ( ! yith_wapo_check_required_fields( 'highlight' ) ) {
		return false;
	}
	var requiredOptions = 0;
	var checkMinMax = '';
	jQuery('form.cart .yith-wapo-addon:not(.hidden)').each( function() {
		checkMinMax = yith_wapo_check_min_max( jQuery( this ), true );
		if ( checkMinMax > 0 ) {
			requiredOptions += checkMinMax;
		}
	});
	if ( requiredOptions > 0 ) {
		return false;
	}
	return true;
}

function yith_wapo_check_min_max( addon, submit = false ) {
	var minValue = addon.data('min');
	var maxValue = addon.data('max');
	var exaValue = addon.data('exa');
	var numberOfChecked = addon.find('input:checkbox:checked, input:radio:checked, option:not([value=""]):not([value="default"]):selected').length;

  let toggle_addon_title = addon.find( 'h3.wapo-addon-title.toggle-closed' );

  if ( exaValue > 0 ) {

		var optionsToSelect = 0;
		if ( exaValue == numberOfChecked ) {
			addon.removeClass( 'required-min' );
			addon.find( '.min-error, .min-error span' ).hide();
			// addon.find('input:checkbox').attr( 'required', false );
			addon.find('input:checkbox').not(':checked').attr( 'disabled', true );
		} else {
			if ( submit ) {
				optionsToSelect = exaValue - numberOfChecked;
				addon.addClass( 'required-min' );
				addon.find( '.min-error' ).show();
				if ( optionsToSelect == 1 ) {
					addon.find( '.min-error-an, .min-error-option' ).show();
					addon.find( '.min-error-qty' ).hide();
				} else {
					addon.find( '.min-error-an, .min-error-option' ).hide();
					addon.find( '.min-error-qty, .min-error-options' ).show();
					addon.find( '.min-error-qty' ).text( optionsToSelect );
				}
        if ( toggle_addon_title ) {
          toggle_addon_title.click();
        }
			}
			// addon.find('input:checkbox').attr( 'required', true );
			addon.find('input:checkbox').not(':checked').attr( 'disabled', false );
		}
		return optionsToSelect;

	} else {

		if ( maxValue > 0 ) {
			if ( maxValue > numberOfChecked ) {
				addon.find('input:checkbox').not(':checked').attr( 'disabled', false );
			} else {
				addon.find('input:checkbox').not(':checked').attr( 'disabled', true );
			}
		}

		if ( minValue > 0 ) {
			var optionsToSelect = 0;
			if ( minValue <= numberOfChecked ) {
				addon.removeClass( 'required-min' );
				addon.find( '.min-error, .min-error span' ).hide();
				// addon.find('input:checkbox').attr( 'required', false );
			} else {
				optionsToSelect = minValue - numberOfChecked;
				if ( submit ) {
					addon.addClass( 'required-min' );
					addon.find( '.min-error' ).show();
					if ( optionsToSelect == 1 ) {
						addon.find( '.min-error-an, .min-error-option' ).show();
						addon.find( '.min-error-qty, .min-error-options' ).hide();
					} else {
						addon.find( '.min-error-an, .min-error-option' ).hide();
						addon.find( '.min-error-qty, .min-error-options' ).show();
						addon.find( '.min-error-qty' ).text( optionsToSelect );
					}
          if ( toggle_addon_title ) {
            toggle_addon_title.click();
          }
				}
				// addon.find('input:checkbox').attr( 'required', true );
			}
			return optionsToSelect;
		}

	}
}

// Check if the Select add-ons are required.
function checkRequiredSelect() {

  let value = true;

  jQuery( '.yith-wapo-addon.yith-wapo-addon-type-select select' ).each(
    function () {
      let currentSelect = jQuery( this );
      if ( currentSelect.is( ':required' ) ) {
        let addon       = currentSelect.parents( '.yith-wapo-addon' );
        let selectValue = currentSelect.val();
        if ( 'default' === selectValue && ! addon.hasClass( 'hidden' ) ) {
          value = false;
          if ( ! value ) {
            let error_el           = addon.find( '.min-error, .min-error-an, .min-error-option' );
            let toggle_addon       = currentSelect.parents( 'div.yith-wapo-addon.wapo-toggle' );
            let toggle_addon_title = toggle_addon.find( 'h3.wapo-addon-title.toggle-closed' );
            addon.addClass( 'required-min' );

            if ( toggle_addon_title ) {
              toggle_addon_title.click();
            }

            addon.css( 'border', '1px solid #f00' );

            error_el.show();
          }
        }
      }
    }
  );

  return value;
}


// multiplied by value price

jQuery( 'body' ).on( 'change keyup', '.yith-wapo-addon-type-number input', function() {
	yith_wapo_check_multiplied_price( jQuery( this ) );
});

function yith_wapo_check_multiplied_price( addon ) {
	let price        = addon.data( 'price' );
	let sale_price   = addon.data( 'price-sale' );
	let defaultPrice = addon.data( 'default-price' );
	let priceType    = addon.data( 'price-type' );
	let priceMethod  = addon.data( 'price-method' );
	let default_attr = 'price';
  let final_price  = 0;
  let addon_value  = addon.val();

	if ( ! defaultPrice > 0 ) {
		if ( sale_price > 0 && ( 'number' !== addon.attr( 'type' ) && 'multiplied' === priceType ) ) {
			price        = sale_price;
			default_attr = 'price-sale';
		}
		defaultPrice = price;
		addon.data( 'default-price', defaultPrice );
	}
	if ( priceMethod == 'value_x_product' ) {
		var productPrice = parseFloat( jQuery( '#yith-wapo-container' ).data( 'product-price' ) );
    final_price = addon_value * productPrice;
	} else if ( priceType == 'multiplied' ) {
    final_price = addon_value * defaultPrice;
	}

  if ( final_price > 0 ) {
    addon.data( default_attr, final_price );
  }

}

// multiplied by length

jQuery( '.yith-wapo-addon-type-text, .yith-wapo-addon-type-textarea' ).on( 'change', 'input, textarea', function() {
	yith_wapo_check_multiplied_length( jQuery( this ) );
});

function yith_wapo_check_multiplied_length( addon ) {
	var price = addon.data('price');
	var defaultPrice = addon.data('default-price');
	if ( ! defaultPrice > 0 ) {
		defaultPrice = price;
		addon.data('default-price', defaultPrice);
	}
	var priceType = addon.data('price-type');
	var priceMethod = addon.data('price-method');
	if ( priceType == 'characters' ) {
		var remove_spaces = addon.data('remove-spaces');
		var length = addon.val().length;
		if( remove_spaces ) {
			length = addon.val().replace(/\s+/g, '').length;
		}
		addon.data( 'price', length * defaultPrice );
	}
}


// product qty

jQuery('.wapo-product-qty').keyup(function() {
	var productID = jQuery(this).data('product-id');
	var productQTY = jQuery(this).val();
	var productURL = '?add-to-cart=' + productID + '&quantity=' + productQTY;
	jQuery(this).parent().find('a').attr( 'href', productURL );
});

// calendar time selection

jQuery('.single-product').on('change', '#wapo-datepicker-time select', function() {
	var time = jQuery(this).val();
	jQuery('#temp-time').text( time );
	jQuery('.ui-state-active').click();
	// jQuery('#wapo-datepicker-time select').val( time );
	// var input = jQuery('.yith_wapo_date:focus');
	// var selectedDate = input.val();
	// input.val( selectedDate + ' ' + time );
});

// calendar time save

jQuery('.single-product').on('click', '#wapo-datepicker-save button', function() {
	jQuery('.hasDatepicker').datepicker('hide');
	// jQuery('#temp-time').text('');
});



